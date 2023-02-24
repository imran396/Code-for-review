<?php
/**
 * SAM-8683: Adjustments and fixes for number formatting
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Transform\Number\Format\Clear;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Number\Format\Common\NumberFormatSeparatorResolver;

/**
 * Class PureNumberFormatter
 * @package Sam\Core\Transform\Number\Format
 */
class NumberFormatRemover extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Remove number formatting and trim leading/heading spaces.
     *
     * @param string $numberFormatted
     * @param bool $isUsNumberFormatting
     * @return string $number
     */
    public function removeFormat(string $numberFormatted, bool $isUsNumberFormatting): string
    {
        $numberFormattedNormalized = trim($numberFormatted);
        if ($this->isNonAcceptableNumber($numberFormattedNormalized)) {
            return $numberFormatted;
        }

        $thousandAndDecimalSeparators = NumberFormatSeparatorResolver::new()
            ->thousandAndDecimalSeparators($isUsNumberFormatting);
        $numberClean = str_replace($thousandAndDecimalSeparators, ['', '.'], $numberFormattedNormalized);
        return $numberClean;
    }

    /**
     * Remove formatting from input and make float number result.
     * @param string $numberFormatted
     * @param bool $isUsNumberFormatting
     * @param int|null $precision
     * @return float
     */
    public function parse(string $numberFormatted, bool $isUsNumberFormatting, ?int $precision = 2): float
    {
        $numberFormatted = trim($numberFormatted);
        // should accept negative number
        if ($this->isNonAcceptableNumber($numberFormatted)) {
            return 0.;
        }

        $numberClean = $this->removeFormat($numberFormatted, $isUsNumberFormatting);
        $numberFloat = $precision === null
            ? (float)$numberClean
            : round((float)$numberClean, $precision);
        return $numberFloat;
    }

    /**
     * Remove formatting on percent expected input and make float number result.
     * @param string $numberFormatted
     * @param bool $isUsNumberFormatting
     * @return float
     */
    public function parsePercent(string $numberFormatted, bool $isUsNumberFormatting): float
    {
        return $this->parse($numberFormatted, $isUsNumberFormatting, 4);
    }

    /**
     * Validate input string as acceptable number. Returns true if:
     *   a. string is non numeric
     *   b. or is a number(include negative number) and contains
     *   any characters(whitespaces) except comma and dot.
     *
     * @param string $numberFormatted
     * @return bool
     */
    protected function isNonAcceptableNumber(string $numberFormatted): bool
    {
        return preg_match('/-[^\-0-9.,]+|[^\-0-9.,]+/', $numberFormatted);
    }
}
