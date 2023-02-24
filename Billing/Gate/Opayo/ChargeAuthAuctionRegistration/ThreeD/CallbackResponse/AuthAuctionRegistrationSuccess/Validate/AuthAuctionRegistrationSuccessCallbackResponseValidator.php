<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\Validate;

use Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\AuthAuctionRegistrationSuccessCallbackResponseHandlingInput as Input;
use Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\Validate\AuthAuctionRegistrationSuccessCallbackResponseValidationResult as Result;
use Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Constants\BillingOpayo;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;


class AuthAuctionRegistrationSuccessCallbackResponseValidator extends CustomizableClass
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

        if (!$input->auctionId) {
            $result->addError(Result::ERR_INVALID_AUCTION_ID);
        }

        if (!$input->vtx) {
            $result->addError(Result::ERR_INVALID_VTX);
        }

        if (!in_array(
            $input->opayoAuthTransactionType,
            [
                BillingOpayo::OPAYO_AUTH_NONE,
                BillingOpayo::OPAYO_AUTH_DEFERRED,
                BillingOpayo::OPAYO_AUTH_AUTHENTICATE,
                BillingOpayo::OPAYO_AUTH_AUTHENTICATE_AUTHORISE
            ],
            true
        )) {
            $result->addError(Result::ERR_INVALID_OPAYO_AUTH_TYPE);
        }

        if (Floating::lteq($input->amount, 0)) {
            $result->addError(Result::ERR_INVALID_AMOUNT);
        }

        if (!$dataProvider->existAuctionById($input->auctionId, $input->isReadOnlyDb)) {
            $result->addError(Result::ERR_AUCTION_NOT_FOUND);
        }

        if (!$dataProvider->existUserById($input->userId, $input->isReadOnlyDb)) {
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
            log_error("Input validation failed for auth auction registration  " . composeSuffix($result->logData()));
        }
    }
}
