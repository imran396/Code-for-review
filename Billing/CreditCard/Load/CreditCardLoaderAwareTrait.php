<?php

namespace Sam\Billing\CreditCard\Load;

/**
 * Trait CreditCardLoaderAwareTrait
 * @package Sam\Billing\CreditCard\Load
 */
trait CreditCardLoaderAwareTrait
{
    /**
     * @var CreditCardLoader|null
     */
    protected ?CreditCardLoader $creditCardLoader = null;

    /**
     * @return CreditCardLoader
     */
    protected function getCreditCardLoader(): CreditCardLoader
    {
        if ($this->creditCardLoader === null) {
            $this->creditCardLoader = CreditCardLoader::new();
        }
        return $this->creditCardLoader;
    }

    /**
     * @param CreditCardLoader $creditCardLoader
     * @return static
     * @internal
     */
    public function setCreditCardLoader(CreditCardLoader $creditCardLoader): static
    {
        $this->creditCardLoader = $creditCardLoader;
        return $this;
    }


}
