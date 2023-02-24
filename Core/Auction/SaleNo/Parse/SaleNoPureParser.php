<?php
/**
 * It can extract sale numbers into two parts (Sale Num, Sale Extension)
 * and also it validate sale numbers.
 *
 * Related tickets:
 * SAM-3023: Simplify entering lot number, item numbers and sale numbers in several places
 *
 * @author        Imran Rahman
 * Filename       SaleNoSimpleParser.php
 * @version       SAM 2.0
 * @since         May 06, 2016
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.api
 */

namespace Sam\Core\Auction\SaleNo\Parse;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SaleNoSimpleParser
 * @package Sam\Core\Auction\SaleNo\Parse
 */
class SaleNoPureParser extends CustomizableClass
{
    /**
     * Extending class needs to be class or implement getInstance method
     *
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        return $this;
    }

    /**
     * Validate sale number by regular expression patterns.
     * @param string $saleFullNum
     * @param string $extensionSeparator
     * @return bool
     */
    public function validate(string $saleFullNum, string $extensionSeparator): bool
    {
        $patterns = $this->getPatterns($extensionSeparator);

        if (preg_match($patterns['Num'], $saleFullNum)) {
            return true;
        }

        if (preg_match($patterns['NumExtension'], $saleFullNum)) {
            return true;
        }

        if (preg_match($patterns['NumExtensionWithSeparator'], $saleFullNum)) {
            return true;
        }

        return false;
    }

    /**
     * Extract sale number and extension from sale full number.
     *
     * @param string $saleFullNo
     * @param string $extensionSeparator
     * @return SaleNoParsed
     */
    public function parse(string $saleFullNo, string $extensionSeparator): SaleNoParsed
    {
        $saleNum = '';
        $saleNumExtension = '';
        $patterns = $this->getPatterns($extensionSeparator);

        if (preg_match($patterns['Num'], $saleFullNo)) {
            $saleNum = $saleFullNo;
        } elseif (preg_match($patterns['NumExtension'], $saleFullNo)) {
            $saleNum = preg_replace($patterns['NumExtension'], '$1', $saleFullNo);
            $saleNumExtension = preg_replace($patterns['NumExtension'], '$2', $saleFullNo);
        } elseif (preg_match($patterns['NumExtensionWithSeparator'], $saleFullNo)) {
            $saleNum = preg_replace($patterns['NumExtensionWithSeparator'], '$1', $saleFullNo);
            $saleNumExtension = preg_replace($patterns['NumExtensionWithSeparator'], '$3', $saleFullNo);
        }

        $saleNum = Cast::toInt($saleNum, Constants\Type::F_DISABLED);
        $saleNum = $saleNum === 0 ? null : $saleNum;
        return SaleNoParsed::new()->construct($saleNum, $saleNumExtension);
    }

    /**
     * Return Reg.Exp. patterns for parsing
     * @param string $extensionSeparator
     * @return array
     */
    protected function getPatterns(string $extensionSeparator): array
    {
        $patterns = [
            'Num' => '/^(\d+)$/',
            'NumExtension' => '/^(\d+)([a-zA-Z]{1,20})$/',
            'NumExtensionWithSeparator' => '/^(\d+)([' . preg_quote($extensionSeparator, '/') . ']{1})([a-zA-Z]{1,20})$/',
        ];
        return $patterns;
    }

}
