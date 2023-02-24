<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle;

trait PublicInvoiceViewSuccessCallbackResponseHandlerCreateTrait
{
    /**
     * @var PublicInvoiceViewSuccessCallbackResponseHandler|null
     */
    protected ?PublicInvoiceViewSuccessCallbackResponseHandler $publicInvoiceViewSuccessCallbackResponseHandler = null;

    /**
     * @return PublicInvoiceViewSuccessCallbackResponseHandler
     */
    protected function createPublicInvoiceViewSuccessCallbackResponseHandler(): PublicInvoiceViewSuccessCallbackResponseHandler
    {
        return $this->publicInvoiceViewSuccessCallbackResponseHandler ?: PublicInvoiceViewSuccessCallbackResponseHandler::new();
    }

    /**
     * @param PublicInvoiceViewSuccessCallbackResponseHandler $handler
     * @return static
     * @internal
     */
    public function setPublicInvoiceViewSuccessCallbackResponseHandler(PublicInvoiceViewSuccessCallbackResponseHandler $handler): static
    {
        $this->publicInvoiceViewSuccessCallbackResponseHandler = $handler;
        return $this;
    }
}
