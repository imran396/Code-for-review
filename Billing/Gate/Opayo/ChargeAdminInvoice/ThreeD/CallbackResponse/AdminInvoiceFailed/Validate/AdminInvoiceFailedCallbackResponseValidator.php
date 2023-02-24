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

namespace Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceFailed\Validate;

use Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceFailed\AdminInvoiceFailedCallbackResponseInput as Input;
use Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceFailed\Validate\AdminInvoiceFailedCallbackResponseValidationResult as Result;
use Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceFailed\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Service\CustomizableClass;


class AdminInvoiceFailedCallbackResponseValidator extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(Input $input): Result
    {
        $result = Result::new()->construct();
        $dataProvider = $this->createDataProvider();

        if (!$input->userId) {
            $result->addError(Result::ERR_INVALID_USER_ID);
        }

        if (!$input->invoiceId) {
            $result->addError(Result::ERR_INVALID_INVOICE_ID);
        }

        if (
            $input->invoiceId
            && !$dataProvider->existInvoiceById($input->invoiceId, true)
        ) {
            $result->addError(Result::ERR_INVOICE_NOT_AVAILABLE);
        }

        if (!$dataProvider->existUserById($input->userId, true)) {
            $result->addError(Result::ERR_USER_NOT_FOUND);
        }

        if (!$result->hasError()) {
            $result->addSuccess(Result::OK_SUCCESS_VALIDATION);
        }

        $this->log($result);

        return $result;
    }

    protected function log(Result $result): void
    {
        if ($result->hasError()) {
            log_error("Input validation failed for invoice charging from Admin Invoice Edit page" . composeSuffix($result->logData()));
        }
    }
}
