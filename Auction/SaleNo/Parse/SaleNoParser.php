<?php
/**
 * It is a wrapper class of core  sale number parsing class.It gives sale number parsing result using
 * core sale number parsing method for parsing sale number.
 *
 * Related tickets:
 * SAM-3023: Simplify entering lot number, item numbers and sale numbers in several places
 *
 * @author        Imran Rahman
 * Filename       SaleNoParser.php
 * @version       SAM 2.0
 * @since         May 06, 2016
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.api
 *
 * @property Integer $saleNum            Parsed Sale Number
 * @property String $saleNumExtension    Parsed Sale Number Extension
 */

namespace Sam\Auction\SaleNo\Parse;

use Sam\Core\Auction\Render\AuctionPureRenderer;
use Sam\Core\Auction\SaleNo\Parse\SaleNoParsed;
use Sam\Core\Auction\SaleNo\Parse\SaleNoPureParser;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class SaleNoParser
 * @package Sam\Auction\SaleNo\Parse
 */
class SaleNoParser extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    public const OP_SALE_NO_EXTENSION_SEPARATOR = OptionalKeyConstants::KEY_SALE_NO_EXTENSION_SEPARATOR; // string

    /**
     * @var string
     */
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
        $this->extensionSeparator = (string)($optionals[self::OP_SALE_NO_EXTENSION_SEPARATOR]
            ?? $this->cfg()->get('core->auction->saleNo->extensionSeparator'));
        return $this;
    }

    /**
     * @param string $saleFullNum
     * @return bool
     */
    public function validate(string $saleFullNum): bool
    {
        $parser = SaleNoPureParser::new()->construct();
        $isValid = $parser->validate($saleFullNum, $this->extensionSeparator);
        return $isValid;
    }

    /**
     * @param string $saleFullNum
     * @return SaleNoParsed
     */
    public function parse(string $saleFullNum): SaleNoParsed
    {
        $parser = SaleNoPureParser::new()->construct();
        return $parser->parse($saleFullNum, $this->extensionSeparator);
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        $saleNoPattern = AuctionPureRenderer::new()->makeSaleNo(
            '(number)',
            '(extension)',
            $this->extensionSeparator
        );
        $message = "Sale# doesn't meet pattern: {$saleNoPattern}";
        return $message;
    }
}
