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

namespace Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\Handle;

use Sam\Auction\Load\Exception\CouldNotFindAuction;
use Sam\Bidder\AuctionBidder\Register\General\AuctionBidderRegistratorFactoryCreateTrait;
use Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\Handle\AuthAuctionRegistrationSuccessCallbackResponseHandleResult as Result;
use Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\AuthAuctionRegistrationSuccessCallbackResponseHandlingInput as Input;
use Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\Handle\Internal\Build\MessageBuilderCreateTrait;
use Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\Handle\Internal\Build\RedirectUrlBuilderCreateTrait;
use Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\Handle\Internal\Produce\Transaction\TransactionProducerCreateTrait;
use Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\Handle\Internal\Load\DataProviderCreateTrait;
use Sam\Bidder\AuctionBidder\Register\Config\Opayo\OpayoThreeDSecureAuctionBidderRegistrationConfig;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;

/**
 * Class AuthAuctionRegistrationSuccessCallbackResponseHandler
 * @package Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistration
 */
class AuthAuctionRegistrationSuccessCallbackResponseHandler extends CustomizableClass
{
    use AuctionBidderRegistratorFactoryCreateTrait;
    use DataProviderCreateTrait;
    use MessageBuilderCreateTrait;
    use RedirectUrlBuilderCreateTrait;
    use TranslatorAwareTrait;
    use TransactionProducerCreateTrait;

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
        $transactionProducer = $this->createTransactionProducer();
        $transactionResult = $transactionProducer->produce(
            $input->userId,
            $input->auctionId,
            $input->accountId,
            $input->opayoAuthTransactionType,
            $input->transactionId,
            $input->threeDStatusResponse,
            $input->vtx,
            $input->securityKey,
            $input->amount
        );
        // If we have transaction error then we do not approve any bidder
        if ($transactionResult->hasError()) {
            $opayoAuctionRegistrationConfig = OpayoThreeDSecureAuctionBidderRegistrationConfig::new()
                ->constructFail($transactionResult->errorMessage());
            $registrator = $this->createAuctionBidderRegistratorFactory()->createBillingApiOpayoRegistrator(
                $input->userId,
                $input->auctionId,
                $input->editorUserId,
                $input->carrierMethod,
                $opayoAuctionRegistrationConfig
            );
            $auctionBidder = $registrator->register();
            return Result::new()->construct('', $input->url, $auctionBidder)
                ->addError(Result::ERR_TRANSACTION_ERROR, $transactionResult->errorMessage());
        }


        $dataProvider = $this->createDataProvider();
        $auction = $dataProvider->loadAuction($input->auctionId, $input->isReadOnlyDb);
        if (!$auction) {
            throw CouldNotFindAuction::withId($input->auctionId);
        }

        $registrator = $this->createAuctionBidderRegistratorFactory()->createBillingApiOpayoRegistrator(
            $input->userId,
            $input->auctionId,
            $input->editorUserId,
            $input->carrierMethod,
            OpayoThreeDSecureAuctionBidderRegistrationConfig::new()->constructSuccess()
        );
        $auctionBidder = $registrator->register();
        if (!$auctionBidder) {
            return Result::new()->construct('', $input->url, null)
                ->addError(Result::ERR_APPROVE_ERROR, $registrator->getErrorMessage());
        }

        $redirectUrl = $this->createRedirectUrlBuilder()->build(
            $input->url,
            $input->auctionId,
            $input->userId,
            $input->isReadOnlyDb
        );

        $message = $this->createMessageBuilder()->build(
            $input->url,
            $input->auctionId,
            $input->userId,
            $input->systemAccountId,
            $input->languageId,
            $input->isReadOnlyDb
        );

        return Result::new()->construct($message, $redirectUrl, $auctionBidder)
            ->addSuccess(Result::OK_SUCCESS);
    }
}
