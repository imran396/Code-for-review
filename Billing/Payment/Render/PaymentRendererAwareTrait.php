<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           10/28/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Payment\Render;

/**
 * Trait PaymentRendererAwareTrait
 * @package Sam\Billing\Payment\Render
 */
trait PaymentRendererAwareTrait
{
    /**
     * @var PaymentRenderer|null
     */
    protected ?PaymentRenderer $paymentRenderer = null;

    /**
     * @return PaymentRenderer
     */
    protected function getPaymentRenderer(): PaymentRenderer
    {
        if ($this->paymentRenderer === null) {
            $this->paymentRenderer = PaymentRenderer::new();
        }
        return $this->paymentRenderer;
    }

    /**
     * @param PaymentRenderer $paymentRenderer
     * @return static
     * @internal
     */
    public function setPaymentRenderer(PaymentRenderer $paymentRenderer): static
    {
        $this->paymentRenderer = $paymentRenderer;
        return $this;
    }
}
