<?php
/**
 *
 * SAM-4563: Currency converter
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/7/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Currency\Convert;

use Currency;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;

/**
 * Class CurrencyConverter
 * @package Sam\Currency\Convert
 */
class CurrencyConverter extends CustomizableClass
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
     * Convert currency (using Currency objects)
     *
     * @param string $value value to convert
     * @param Currency $fromCurrency current currency of value
     * @param Currency $toCurrency new currency
     *
     * @return float converted to new currency value
     */
    public function convert(string $value, Currency $fromCurrency, Currency $toCurrency): float
    {
        return $this->convertByRates($value, $fromCurrency->ExRate, $toCurrency->ExRate);
    }

    /**
     * Convert currency (using exchange rates)
     *
     * @param string $input
     * @param float $fromCurrencyExRate
     * @param float $toCurrencyExRate
     * @return float
     */
    public function convertByRates(string $input, float $fromCurrencyExRate, float $toCurrencyExRate): float
    {
        $value = (float)$input;
        $dollarValue = Floating::gt($fromCurrencyExRate, 0)
            ? $value / $fromCurrencyExRate
            : $value;
        $value = $toCurrencyExRate * $dollarValue;
        return $value;
    }

}
