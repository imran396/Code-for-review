<?php
/**
 * SAM-10909: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. General adjustments
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Payment\Validate;

/**
 * Trait PaymentExistenceCheckerCreateTrait
 * @package Sam\Billing\Payment\Validate
 */
trait PaymentExistenceCheckerCreateTrait
{
    protected ?PaymentExistenceChecker $paymentExistenceChecker = null;

    /**
     * @return PaymentExistenceChecker
     */
    protected function createPaymentExistenceChecker(): PaymentExistenceChecker
    {
        return $this->paymentExistenceChecker ?: PaymentExistenceChecker::new();
    }

    /**
     * @param PaymentExistenceChecker $paymentExistenceChecker
     * @return $this
     * @internal
     */
    public function setPaymentExistenceChecker(PaymentExistenceChecker $paymentExistenceChecker): static
    {
        $this->paymentExistenceChecker = $paymentExistenceChecker;
        return $this;
    }
}
