<?php
/**
 * It can extract lot numbers into three parts (Lot Num, Lot Prefix, Lot Extension)
 * and also it validate lot numbers. It uses for entering lot, editing lot and searching lot.
 * It is pure class
 *
 * Related tickets:
 * SAM-3023: Simplify entering lot number, item numbers and sale numbers in several places
 *
 * @author        Imran Rahman
 * Filename       LotNoSimpleParser.php
 * @version       SAM 2.0
 * @since         May 06, 2016
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.api
 */

namespace Sam\Core\AuctionLot\LotNo\Parse;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotNoSimpleParser
 * @package Sam\Core\AuctionLot\LotNo\Parse
 */
class LotNoPureParser extends CustomizableClass
{
    private const LOT_NUM_EMPTY = null;
    private const LOT_NUM_PREFIX_EMPTY = '';
    private const LOT_NUM_EXTENSION_EMPTY = '';

    /**
     * Class instantiation method
     * @return $this
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
     * Validate lot number by regular expression patterns.
     * @param string $lotFullNum
     * @param string $lotPrefixSeparator
     * @param string $lotExtensionSeparator
     * @return bool
     */
    public function validate(
        string $lotFullNum,
        string $lotPrefixSeparator = '',
        string $lotExtensionSeparator = ''
    ): bool {
        $patterns = $this->getPatterns($lotPrefixSeparator, $lotExtensionSeparator);
        if (preg_match($patterns['LotNum'], $lotFullNum)) {
            return true;
        }

        if (
            !$lotPrefixSeparator
            && !$lotExtensionSeparator
        ) {
            foreach ($patterns['NoSeparator'] as $pattern) {
                if (preg_match($pattern, $lotFullNum)) {
                    return true;
                }
            }
        } else {
            foreach ($patterns['Separator'] as $pattern) {
                if (preg_match($pattern, $lotFullNum)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Extract lot number, prefix, extension from lot full number
     * @param string $lotFullNum
     * @param string $lotPrefixSeparator
     * @param string $lotExtensionSeparator
     * @return LotNoParsed
     */
    public function parse(
        string $lotFullNum,
        string $lotPrefixSeparator = '',
        string $lotExtensionSeparator = ''
    ): LotNoParsed {
        $lotFullNum = trim($lotFullNum);
        // we dont need to parse empty lot full number (in case if we add new lot for auction)
        if ($lotFullNum === '') {
            return LotNoParsed::new()->construct();
        }

        $patterns = $this->getPatterns($lotPrefixSeparator, $lotExtensionSeparator);
        if (preg_match($patterns['LotNum'], $lotFullNum)) {
            return LotNoParsed::new()->construct((int)$lotFullNum);
        }

        $lotNumPrefix = self::LOT_NUM_PREFIX_EMPTY;
        $lotNum = self::LOT_NUM_EMPTY;
        $lotNumExt = self::LOT_NUM_EXTENSION_EMPTY;

        if (
            !$lotPrefixSeparator
            && !$lotExtensionSeparator
        ) {
            foreach ($patterns['NoSeparator'] as $patternType => $pattern) {
                if (preg_match($pattern, $lotFullNum)) {
                    switch ($patternType) {
                        case "NumPrefixExtension":
                            $lotNumPrefix = preg_replace($pattern, '$1', $lotFullNum);
                            $lotNum = (int)preg_replace($pattern, '$2', $lotFullNum);
                            $lotNumExt = preg_replace($pattern, '$3', $lotFullNum);
                            break;

                        case "NumPrefix":
                            $lotNumPrefix = preg_replace($pattern, '$1', $lotFullNum);
                            $lotNum = (int)preg_replace($pattern, '$2', $lotFullNum);
                            break;

                        case "NumExtension":
                            $lotNum = (int)preg_replace($pattern, '$1', $lotFullNum);
                            $lotNumExt = preg_replace($pattern, '$2', $lotFullNum);
                            break;

                        default:
                            break;
                    }
                }
            }
        } else {
            foreach ($patterns['Separator'] as $patternType => $pattern) {
                if (preg_match($pattern, $lotFullNum)) {
                    switch ($patternType) {
                        case "NumPrefixExtension":
                            $lotNumPrefix = (string)preg_replace($pattern, '$1', $lotFullNum);
                            $lotNum = (int)preg_replace($pattern, '$3', $lotFullNum);
                            $lotNumExt = (string)preg_replace($pattern, '$5', $lotFullNum);
                            break;

                        case "NumPrefix":
                            $lotNumPrefix = (string)preg_replace($pattern, '$1', $lotFullNum);
                            $lotNum = (int)preg_replace($pattern, '$3', $lotFullNum);
                            break;

                        case "NumExtension":
                            $lotNum = (int)preg_replace($pattern, '$1', $lotFullNum);
                            $lotNumExt = (string)preg_replace($pattern, '$3', $lotFullNum);
                            break;

                        default:
                            break;
                    }
                }
            }
        }

        return LotNoParsed::new()->construct($lotNum, $lotNumExt, $lotNumPrefix);
    }

    /**
     * Return Reg.Exp. patterns for parsing
     * @param string $lotPrefixSeparator
     * @param string $lotExtensionSeparator
     * @return array
     */
    protected function getPatterns(
        string $lotPrefixSeparator,
        string $lotExtensionSeparator
    ): array {
        $patterns = [
            'LotNum' => '/^(\d+)$/',
            'NoSeparator' => [
                'NumPrefixExtension' => '/^([a-zA-Z]{1,20})(\d+)([a-zA-Z]{1,20})$/',
                'NumPrefix' => '/^([a-zA-Z]{1,20})(\d+)$/',
                'NumExtension' => '/^(\d+)([a-zA-Z]{1,20})$/',
            ],
        ];

        $preSep = preg_quote($lotPrefixSeparator, '/');
        $extSep = preg_quote($lotExtensionSeparator, '/');
        if ($lotPrefixSeparator && $lotExtensionSeparator) {
            $patterns['Separator']['NumPrefixExtension'] = "/^([a-zA-Z0-9]{1,20})([{$preSep}]{1})(\d+)([{$extSep}]{1})([a-zA-Z0-9]{1,20})$/";
        }
        if ($lotPrefixSeparator) {
            $patterns['Separator']['NumPrefix'] = "/^([a-zA-Z0-9]{1,20})([{$preSep}]{1})(\d+)$/";
        }
        if ($lotExtensionSeparator) {
            $patterns['Separator']['NumExtension'] = "/^(\d+)([{$extSep}]{1})([a-zA-Z0-9]{1,20})$/";
        }
        return $patterns;
    }
}
