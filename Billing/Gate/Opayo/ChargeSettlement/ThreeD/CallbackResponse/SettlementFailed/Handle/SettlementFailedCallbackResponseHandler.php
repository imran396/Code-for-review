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

namespace Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementFailed\Handle;

use Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementFailed\Handle\Internal\Load\DataProviderCreateTrait;
use Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementFailed\Handle\SettlementFailedCallbackResponseHandleResult as Result;
use Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementFailed\SettlementFailedCallbackResponseHandlingInput as Input;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Load\Exception\CouldNotFindSettlement;
use Sam\User\Load\Exception\CouldNotFindUser;


class SettlementFailedCallbackResponseHandler extends CustomizableClass
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

        $settlement = $dataProvider->loadSettlement($input->settlementId, $input->isReadOnlyDb);
        if (!$settlement) {
            throw CouldNotFindSettlement::withId($input->settlementId);
        }

        $user = $dataProvider->loadUser($settlement->ConsignorId, $input->isReadOnlyDb);
        if (!$user) {
            throw CouldNotFindUser::withId($settlement->ConsignorId);
        }

        $message = sprintf(
            'Problem charging commission on settlement %s consignor email %s ; error: %s <br />',
            $settlement->SettlementNo,
            $user->Email,
            $input->threeDStatusResponse
        );

        return Result::new()->construct()
            ->addSuccess(Result::OK_SUCCESS, $message);
    }
}
