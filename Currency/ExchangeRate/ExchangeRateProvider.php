<?php
/**
 * SAM-7973: Refactor \Exrate_Sync_Ajax
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Currency\ExchangeRate;

use Sam\Core\Service\CustomizableClass;
use Sam\Currency\ExchangeRate\Internal\DataLoaderCreateTrait;

/**
 * Provides currency exchange rates for all currencies in the system
 *
 * Class ExchangeRateProvider
 * @package Sam\Currency\ExchangeRate
 */
class ExchangeRateProvider extends CustomizableClass
{
    use DataLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load currency exchange rates for all currencies in the system.
     * The first currency in the list is the default currency.
     *
     * @return ExchangeRate[]
     */
    public function get(): array
    {
        $exchangeRates = [$this->makeDefaultCurrencyExchangeRate()];
        $exchangeRates = array_merge($exchangeRates, $this->createDataLoader()->loadAllCurrencyExchangeRates());
        return $exchangeRates;
    }

    /**
     * @return ExchangeRate
     */
    protected function makeDefaultCurrencyExchangeRate(): ExchangeRate
    {
        $defaultCurrencySign = $this->createDataLoader()->loadDefaultCurrencySign(true);
        $exchangeRate = ExchangeRate::new()->construct(0, $defaultCurrencySign, $defaultCurrencySign, 1);
        return $exchangeRate;
    }
}
