<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\ResultStorage\Session;

/**
 * @package Sam\Billing\Gate\ResultStorage\Session
 */
trait PaymentResultStorageCreateTrait
{
    /**
     * @var PaymentResultStorage|null
     */
    protected ?PaymentResultStorage $paymentResultStorage = null;

    /**
     * @return PaymentResultStorage
     */
    protected function createPaymentResultStorage(): PaymentResultStorage
    {
        return $this->paymentResultStorage ?: PaymentResultStorage::new();
    }

    /**
     * @param PaymentResultStorage $paymentResultStorage
     * @return $this
     * @internal
     */
    public function setPaymentResultStorage(PaymentResultStorage $paymentResultStorage): static
    {
        $this->paymentResultStorage = $paymentResultStorage;
        return $this;
    }
}
