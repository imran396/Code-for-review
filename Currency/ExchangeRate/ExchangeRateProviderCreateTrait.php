<?php
/**
 * SAM-7973: Refactor \Exrate_Sync_Ajax
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Currency\ExchangeRate;

/**
 * Trait ExchangeRateProviderCreateTrait
 * @package Sam\Currency\ExchangeRate
 */
trait ExchangeRateProviderCreateTrait
{
    /**
     * @var ExchangeRateProvider|null
     */
    protected ?ExchangeRateProvider $exchangeRateProvider = null;

    /**
     * @return ExchangeRateProvider
     */
    protected function createExchangeRateProvider(): ExchangeRateProvider
    {
        return $this->exchangeRateProvider ?: ExchangeRateProvider::new();
    }

    /**
     * @param ExchangeRateProvider $exchangeRateProvider
     * @return static
     * @internal
     */
    public function setExchangeRateProvider(ExchangeRateProvider $exchangeRateProvider): static
    {
        $this->exchangeRateProvider = $exchangeRateProvider;
        return $this;
    }
}
