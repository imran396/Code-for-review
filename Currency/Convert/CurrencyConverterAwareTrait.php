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

/**
 * Trait CurrencyConverterAwareTrait
 * @package Sam\Currency\Convert
 */
trait CurrencyConverterAwareTrait
{
    /**
     * @var CurrencyConverter|null
     */
    protected ?CurrencyConverter $currencyConverter = null;

    /**
     * @return CurrencyConverter
     */
    protected function getCurrencyConverter(): CurrencyConverter
    {
        if ($this->currencyConverter === null) {
            $this->currencyConverter = CurrencyConverter::new();
        }
        return $this->currencyConverter;
    }

    /**
     * @param CurrencyConverter $currencyConverter
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setCurrencyConverter(CurrencyConverter $currencyConverter): static
    {
        $this->currencyConverter = $currencyConverter;
        return $this;
    }
}
