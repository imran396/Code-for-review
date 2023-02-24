<?php
/**
 * SAM-4670: Payment record loader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/3/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Payment\Load;

/**
 * Trait PaymentLoaderAwareTrait
 * @package Sam\Billing\Payment\Load
 */
trait PaymentLoaderAwareTrait
{
    /**
     * @var PaymentLoader|null
     */
    protected ?PaymentLoader $paymentLoader = null;

    /**
     * @return PaymentLoader
     */
    protected function getPaymentLoader(): PaymentLoader
    {
        if ($this->paymentLoader === null) {
            $this->paymentLoader = PaymentLoader::new();
        }
        return $this->paymentLoader;
    }

    /**
     * @param PaymentLoader $paymentLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setPaymentLoader(PaymentLoader $paymentLoader): static
    {
        $this->paymentLoader = $paymentLoader;
        return $this;
    }
}
