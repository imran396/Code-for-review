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

namespace Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceSuccess\Validate;

use Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceSuccess\AdminInvoiceSuccessCallbackResponseInput as Input;
use Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceSuccess\Validate\AdminInvoiceSuccessCallbackResponseValidationResult as Result;
use Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceSuccess\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;


class AdminInvoiceSuccessCallbackResponseValidator extends CustomizableClass
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

        if (!$input->accountId) {
            $result->addError(Result::ERR_INVALID_ACCOUNT_ID);
        }

        if (!$input->userId) {
            $result->addError(Result::ERR_INVALID_USER_ID);
        }

        if (!$input->invoiceId) {
            $result->addError(Result::ERR_INVALID_INVOICE_ID);
        }

        $creditCard = $dataProvider->loadCreditCard($input->creditCardId, $input->isReadOnlyDb);
        if (
            !$creditCard
            || !$dataProvider->isValidCreditCard($input->ccNumber, $creditCard->Name)
        ) {
            $result->addError(Result::ERR_INVALID_CC_NUMBER);
        }

        if (Floating::lteq($input->amount, 0)) {
            $result->addError(Result::ERR_INVALID_AMOUNT);
        }

        if (
            $input->invoiceId
            && !$dataProvider->existInvoiceById($input->invoiceId, $input->isReadOnlyDb)
        ) {
            $result->addError(Result::ERR_INVOICE_NOT_AVAILABLE);
        }

        if (!$dataProvider->existUserById($input->userId, $input->isReadOnlyDb)) {
            $result->addError(Result::ERR_USER_NOT_FOUND);
        }

        if (!$dataProvider->existEditorUserById($input->editorUserId, $input->isReadOnlyDb)) {
            $result->addError(Result::ERR_EDITOR_USER_NOT_FOUND);
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
