<?php

namespace Sam\Billing\CreditCard\Load;

/**
 * Trait CreditCardSurchargeLoaderAwareTrait
 * @package Sam\Billing\CreditCard\Load
 */
trait CreditCardSurchargeLoaderAwareTrait
{
    /**
     * @var CreditCardSurchargeLoader|null
     */
    protected ?CreditCardSurchargeLoader $creditCardSurchargeLoader = null;

    /**
     * @return CreditCardSurchargeLoader
     */
    protected function getCreditCardSurchargeLoader(): CreditCardSurchargeLoader
    {
        if ($this->creditCardSurchargeLoader === null) {
            $this->creditCardSurchargeLoader = CreditCardSurchargeLoader::new();
        }
        return $this->creditCardSurchargeLoader;
    }

    /**
     * @param CreditCardSurchargeLoader $creditCardSurchargeLoader
     * @return static
     * @internal
     */
    public function setCreditCardSurchargeLoader(CreditCardSurchargeLoader $creditCardSurchargeLoader): static
    {
        $this->creditCardSurchargeLoader = $creditCardSurchargeLoader;
        return $this;
    }
}
