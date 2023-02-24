<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Oct 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementFailed\Validate;

use Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementFailed\SettlementFailedCallbackResponseHandlingInput as Input;
use Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementFailed\Validate\SettlementFailedCallbackResponseValidationResult as Result;
use Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementFailed\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Service\CustomizableClass;


class SettlementFailedCallbackResponseValidator extends CustomizableClass
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

        if (!$dataProvider->existSettlementById($input->settlementId, $input->isReadOnlyDb)) {
            $result->addError(Result::ERR_SETTLEMENT_NOT_AVAILABLE);
            log_error(
                "Available settlement not found for Opayo ThreeD charging"
                . composeSuffix(['s' => $input->settlementId])
            );
        }
        return $result;
    }
}
