<?php
/**
 * It can extract item numbers into two parts (Item Num, Item Extension)
 * and also it validate item numbers.
 *
 * Related tickets:
 * SAM-3023: Simplify entering lot number, item numbers and sale numbers in several places
 *
 * @author        Imran Rahman
 * Filename       Parser.php
 * @version       SAM 2.0
 * @since         May 06, 2016
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.api
 */

namespace Sam\Core\LotItem\ItemNo\Parse;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotItemNoSimpleParser
 * @package Sam\Lot\ItemNo\Parse\Core
 */
class LotItemNoPureParser extends CustomizableClass
{
    /**
     * Extending class needs to be class or implement getInstance method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        return $this;
    }

    /**
     * @param string $itemFullNum
     * @param string $extensionSeparator
     * @return bool
     */
    public function validate(string $itemFullNum, string $extensionSeparator = ''): bool
    {
        $patterns = $this->getPatterns($extensionSeparator);
        $result = false;
        if (preg_match($patterns['Num'], $itemFullNum)) {
            $result = true;
        } elseif (preg_match($patterns['NumExtension'], $itemFullNum)) {
            $result = true;
        } elseif (preg_match($patterns['NumExtensionWithSeparator'], $itemFullNum)) {
            $result = true;
        }
        return $result;
    }

    /**
     * Parse lot# to parts
     * @param string $itemNo Concatenated full lot item#
     * @param string $extensionSeparator
     * @return LotItemNoParsed
     */
    public function parse(string $itemNo, string $extensionSeparator = ''): LotItemNoParsed
    {
        $itemNum = null;
        $itemNumExtension = '';
        $patterns = $this->getPatterns($extensionSeparator);

        if (preg_match($patterns['Num'], $itemNo)) {
            $itemNum = (int)$itemNo;
        } elseif (preg_match($patterns['NumExtension'], $itemNo)) {
            $itemNum = (int)preg_replace($patterns['NumExtension'], '$1', $itemNo);
            $itemNumExtension = (string)preg_replace($patterns['NumExtension'], '$2', $itemNo);
        } elseif (preg_match($patterns['NumExtensionWithSeparator'], $itemNo)) {
            $itemNum = (int)preg_replace($patterns['NumExtensionWithSeparator'], '$1', $itemNo);
            $itemNumExtension = (string)preg_replace($patterns['NumExtensionWithSeparator'], '$3', $itemNo);
        }

        return LotItemNoParsed::new()->construct($itemNum, $itemNumExtension);
    }

    /**
     * Return Reg.Exp. patterns for parsing
     * @param string $extensionSeparator
     * @return array
     */
    protected function getPatterns(string $extensionSeparator): array
    {
        $extSep = preg_quote($extensionSeparator, '/');
        $patterns = [
            'Num' => '/^(\d+)$/',
            'NumExtension' => '/^(\d+)([a-zA-Z]{1,20})$/',
            'NumExtensionWithSeparator' => "/^(\d+)([{$extSep}]{1})([a-zA-Z]{1,20})$/",
        ];

        return $patterns;
    }
}
