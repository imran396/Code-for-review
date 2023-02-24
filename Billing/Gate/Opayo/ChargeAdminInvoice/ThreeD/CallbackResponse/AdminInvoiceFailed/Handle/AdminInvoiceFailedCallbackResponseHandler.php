<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Sept 09, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceFailed\Handle;

use Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceFailed\Handle\AdminInvoiceFailedCallbackResponseHandleResult as Result;
use Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceFailed\AdminInvoiceFailedCallbackResponseInput as Input;
use Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceFailed\Handle\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoice;
use Sam\User\Load\Exception\CouldNotFindUser;

class AdminInvoiceFailedCallbackResponseHandler extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function handle(Input $input): Result
    {
        $dataProvider = $this->createDataProvider();
        $invoice = $dataProvider->loadInvoice($input->invoiceId, $input->isReadOnlyDb);
        if (!$invoice) {
            throw CouldNotFindInvoice::withId($input->invoiceId);
        }

        $user = $dataProvider->loadUser($input->userId, $input->isReadOnlyDb);
        if (!$user) {
            throw CouldNotFindUser::withId($input->userId);
        }

        $failedPaymentMessage = sprintf(
            'Problem charging invoice %s bidder email %s; Error: %s<br />',
            $invoice->InvoiceNo,
            $user->Email,
            $input->threeDStatusResponse
        );

        return Result::new()->construct()->addSuccess(
            Result::OK_SUCCESS_HANDLE_FAILED_PAYMENT,
            $failedPaymentMessage
        );
    }
}
