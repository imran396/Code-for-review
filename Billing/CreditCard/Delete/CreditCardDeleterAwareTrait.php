<?php
/**
 * SAM-4726: Credit Card Deleter
 *
 * @author        Vahagn Hovsepyan
 * @since         Dec 21, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Billing\CreditCard\Delete;

/**
 * Trait CreditCardDeleterAwareTrait
 */
trait CreditCardDeleterAwareTrait
{
    /**
     * @var CreditCardDeleter|null
     */
    protected ?CreditCardDeleter $creditCardDeleter = null;

    /**
     * @return CreditCardDeleter
     */
    protected function getCreditCardDeleter(): CreditCardDeleter
    {
        if ($this->creditCardDeleter === null) {
            $this->creditCardDeleter = CreditCardDeleter::new();
        }
        return $this->creditCardDeleter;
    }

    /**
     * @param CreditCardDeleter $creditCardDeleter
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setCreditCardDeleter(CreditCardDeleter $creditCardDeleter): static
    {
        $this->creditCardDeleter = $creditCardDeleter;
        return $this;
    }
}
