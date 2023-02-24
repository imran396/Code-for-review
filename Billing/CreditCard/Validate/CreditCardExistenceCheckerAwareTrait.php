<?php

namespace Sam\Billing\CreditCard\Validate;

/**
 * Trait CreditCardExistenceCheckerAwareTrait
 * @package Sam\Billing\CreditCard\Validate
 */
trait CreditCardExistenceCheckerAwareTrait
{
    /**
     * @var CreditCardExistenceChecker|null
     */
    protected ?CreditCardExistenceChecker $creditCardExistenceChecker = null;

    /**
     * @return CreditCardExistenceChecker
     */
    protected function getCreditCardExistenceChecker(): CreditCardExistenceChecker
    {
        if ($this->creditCardExistenceChecker === null) {
            $this->creditCardExistenceChecker = CreditCardExistenceChecker::new();
        }
        return $this->creditCardExistenceChecker;
    }

    /**
     * @param CreditCardExistenceChecker $creditCardExistenceChecker
     * @return static
     * @internal
     */
    public function setCreditCardExistenceChecker(CreditCardExistenceChecker $creditCardExistenceChecker): static
    {
        $this->creditCardExistenceChecker = $creditCardExistenceChecker;
        return $this;
    }

}
