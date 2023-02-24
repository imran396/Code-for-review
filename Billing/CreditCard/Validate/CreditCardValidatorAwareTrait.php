<?php

namespace Sam\Billing\CreditCard\Validate;

/**
 * Trait CreditCardValidatorAwareTrait
 * @package Sam\Billing\CreditCard\Validate
 */
trait CreditCardValidatorAwareTrait
{
    /**
     * @var CreditCardValidator|null
     */
    protected ?CreditCardValidator $creditCardValidator = null;

    /**
     * @return CreditCardValidator
     */
    protected function getCreditCardValidator(): CreditCardValidator
    {
        if ($this->creditCardValidator === null) {
            $this->creditCardValidator = CreditCardValidator::new();
        }
        return $this->creditCardValidator;
    }

    /**
     * @param CreditCardValidator $creditCardValidator
     * @return static
     * @internal
     */
    public function setCreditCardValidator(CreditCardValidator $creditCardValidator): static
    {
        $this->creditCardValidator = $creditCardValidator;
        return $this;
    }
}
