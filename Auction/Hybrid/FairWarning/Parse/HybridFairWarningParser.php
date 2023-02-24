<?php
/**
 * SAM-6315: Unit tests for hybrid fair warning manager
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 17, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Hybrid\FairWarning\Parse;


use Sam\Core\Service\CustomizableClass;

/**
 * Class HybridFairWarningParser
 * @package Sam\Auction\Hybrid\FairWarning\Parse
 */
class HybridFairWarningParser extends CustomizableClass
{

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Parse fair warning texts to array[<seconds left> => <warning message>].
     * It doesn't validate input, just parses multi-line text to associative array.
     * @param string $warningTexts
     * @return array
     */
    public function parse(string $warningTexts): array
    {
        $lines = explode("\n", $warningTexts);
        $warnings = [];
        foreach ($lines as $line) {
            if (preg_match('/([^:]+):(.+)/', $line, $matches)) {
                $warnings[trim($matches[1])] = trim($matches[2]);
            }
        }
        return $warnings;
    }
}
