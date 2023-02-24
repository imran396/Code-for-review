<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Sept 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementSuccess\Validate;

use Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementSuccess\SettlementSuccessCallbackResponseHandlingInput as Input;
use Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementSuccess\Validate\SettlementSuccessCallbackResponseValidationResult as Result;
use Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementSuccess\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;


class SettlementSuccessCallbackResponseValidator extends CustomizableClass
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

        if (!$input->settlementId) {
            $result->addError(Result::ERR_INVALID_SETTLEMENT_ID);
        }

        if (!$dataProvider->existSettlementById($input->settlementId)) {
            $result->addError(Result::ERR_SETTLEMENT_NOT_AVAILABLE);
            log_error(
                "Available settlement not found for Opayo ThreeD charging"
                . composeSuffix(['s' => $input->settlementId])
            );
        }

        if (!$input->userId) {
            $result->addError(Result::ERR_INVALID_USER_ID);
        }

        if (!$input->editorUserId) {
            $result->addError(Result::ERR_INVALID_EDITOR_USER_ID);
        }

        if (!$dataProvider->existUserById($input->userId, true)) {
            $result->addError(Result::ERR_USER_NOT_FOUND);
        }

        if (Floating::lteq($input->amount, 0)) {
            $result->addError(Result::ERR_INVALID_AMOUNT);
        }

        if (!$dataProvider->existEditorUserById($input->editorUserId, true)) {
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
            log_error("Input validation failed settlement charge  " . composeSuffix($result->logData()));
        }
    }
}
