<?php
/**
 * It is a wrapper class of core  item parsing class.It gives item number parsing result using
 * core item parsing method for parsing item number.
 *
 * Related tickets:
 * SAM-3023: Simplify entering lot number, item numbers and sale numbers in several places
 *
 * @author        Imran Rahman
 * Filename       LotItemNoParser.php
 * @version       SAM 2.0
 * @since         May 06, 2016
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.api
 *
 * @property Integer $itemNum            Parsed  Item Number
 * @property String $itemNumExtension    Parsed  Item Number Extension
 */

namespace Sam\Lot\ItemNo\Parse;

use Sam\Core\LotItem\ItemNo\Parse\LotItemNoParsed;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\LotItem\ItemNo\Parse\LotItemNoPureParser;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Render\LotRenderer;
use Sam\Lot\Render\LotRendererAwareTrait;

/**
 * Class LotItemNoParser
 * @package Sam\Lot\ItemNo\Parse
 */
class LotItemNoParser extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use LotRendererAwareTrait;

    public const OP_ITEM_NO_EXTENSION_SEPARATOR = OptionalKeyConstants::KEY_ITEM_NO_EXTENSION_SEPARATOR; // string

    /** @var string */
    private string $extensionSeparator;

    /**
     * Extending class needs to be class or implement getInstance method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->extensionSeparator = (string)($optionals[self::OP_ITEM_NO_EXTENSION_SEPARATOR]
            ?? $this->cfg()->get('core->lot->itemNo->extensionSeparator'));
        return $this;
    }

    /**
     * @param string $itemFullNum
     * @return bool
     */
    public function validate(string $itemFullNum): bool
    {
        $parser = LotItemNoPureParser::new()->construct();
        $isValid = $parser->validate($itemFullNum, $this->extensionSeparator);
        return $isValid;
    }

    /**
     * Parse lot# to parts
     * @param string $itemNo Concatenated full lot item#
     * @return LotItemNoParsed Parsed item number
     */
    public function parse(string $itemNo): LotItemNoParsed
    {
        $parser = LotItemNoPureParser::new()->construct();
        return $parser->parse($itemNo, $this->extensionSeparator);
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        $itemNoPattern = $this->getLotRenderer()->makeItemNo(
            '(number)',
            '(extension)',
            [LotRenderer::OP_ITEM_NO_EXTENSION_SEPARATOR => $this->extensionSeparator]
        );
        $message = "Item# doesn't meet pattern: {$itemNoPattern}";
        return $message;
    }
}
