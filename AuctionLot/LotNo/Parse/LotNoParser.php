<?php
/**
 * It is a wrapper class of core lot parsing class.It gives account based Lot number parsing result using
 * core lot parsing method for parsing Lot number.
 *
 *
 * Related tickets:
 * SAM-3023: Simplify entering lot number, item numbers and sale numbers in several places
 *
 * @author        Imran Rahman
 * Filename       LotNoParser.php
 * @version       SAM 2.0
 * @since         May 06, 2016
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.api
 *
 * @property int $lotNum                      Parsed  Lot Number
 * @property string $lotNumPrefix                 Parsed  Lot Number Prefix
 * @property string $lotNumExtension              Parsed  Lot Number Extension
 * @property string $lotPrefixSeparator           Prefix  separator form Account Id
 * @property string $lotExtensionSeparator        Extension separator from Account Id
 * @property string $fullLotNum                   Accept from client side.
 * @property int $accountId                   Accept from client side.
 *
 */

namespace Sam\AuctionLot\LotNo\Parse;

use Sam\Core\AuctionLot\LotNo\Parse\LotNoParsed;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\AuctionLot\LotNo\Parse\LotNoPureParser;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Render\LotRenderer;
use Sam\Lot\Render\LotRendererAwareTrait;

/**
 * Class LotNoParser
 * @package Sam\AuctionLot\LotNo\Parse
 */
class LotNoParser extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use LotRendererAwareTrait;

    public const OP_LOT_NO_PREFIX_SEPARATOR = OptionalKeyConstants::KEY_LOT_NO_PREFIX_SEPARATOR; // string
    public const OP_LOT_NO_EXTENSION_SEPARATOR = OptionalKeyConstants::KEY_LOT_NO_EXTENSION_SEPARATOR; // string

    protected string $lotPrefixSeparator = '';
    protected string $lotExtensionSeparator = '';

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
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->lotPrefixSeparator = $optionals[self::OP_LOT_NO_PREFIX_SEPARATOR]
            ?? $this->cfg()->get('core->lot->lotNo->prefixSeparator');
        $this->lotExtensionSeparator = $optionals[self::OP_LOT_NO_EXTENSION_SEPARATOR]
            ?? $this->cfg()->get('core->lot->lotNo->extensionSeparator');
        return $this;
    }

    /**
     * @param string $lotFullNum
     * @return bool
     */
    public function validate(string $lotFullNum): bool
    {
        $parser = LotNoPureParser::new()->construct();
        $isValid = $parser->validate($lotFullNum, $this->lotPrefixSeparator, $this->lotExtensionSeparator);
        return $isValid;
    }

    /**
     * @param string $lotFullNum
     * @return LotNoParsed
     */
    public function parse(string $lotFullNum): LotNoParsed
    {
        $parser = LotNoPureParser::new()->construct();
        return $parser->parse($lotFullNum, $this->lotPrefixSeparator, $this->lotExtensionSeparator);
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        $lotNoPattern = $this->getLotRenderer()->makeLotNo(
            "(number)",
            "(extension)",
            "(prefix)",
            [
                LotRenderer::OP_LOT_NO_PREFIX_SEPARATOR => $this->lotPrefixSeparator,
                LotRenderer::OP_LOT_NO_EXTENSION_SEPARATOR => $this->lotExtensionSeparator,
            ]
        );
        $message = "Lot# doesn't meet pattern: {$lotNoPattern}";
        return $message;
    }
}
