<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @author        Oleh Kovalov
 * @since         May 10, 2022
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>*
 */

namespace Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceFailed\Handle;

trait AdminInvoiceFailedCallbackResponseHandlerCreateTrait
{
    /**
     * @var AdminInvoiceFailedCallbackResponseHandler|null
     */
    protected ?AdminInvoiceFailedCallbackResponseHandler $adminInvoiceFailedCallbackResponseHandler = null;

    /**
     * @return AdminInvoiceFailedCallbackResponseHandler
     */
    protected function createAdminInvoiceFailedCallbackResponseHandler(): AdminInvoiceFailedCallbackResponseHandler
    {
        return $this->adminInvoiceFailedCallbackResponseHandler ?: AdminInvoiceFailedCallbackResponseHandler::new();
    }

    /**
     * @param AdminInvoiceFailedCallbackResponseHandler $handler
     * @return static
     * @internal
     */
    public function setAdminInvoiceFailedCallbackResponseHandler(AdminInvoiceFailedCallbackResponseHandler $handler): static
    {
        $this->adminInvoiceFailedCallbackResponseHandler = $handler;
        return $this;
    }
}
