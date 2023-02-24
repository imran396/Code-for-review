<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Oct 2, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationFailed\Handle;

use Sam\Bidder\AuctionBidder\Register\Config\Opayo\OpayoThreeDSecureAuctionBidderRegistrationConfig;
use Sam\Bidder\AuctionBidder\Register\General\AuctionBidderRegistratorFactoryCreateTrait;
use Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationFailed\Handle\AuthAuctionRegistrationFailedCallbackResponseHandleResult as Result;
use Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationFailed\AuthAuctionRegistrationFailedCallbackResponseHandlingInput as Input;
use Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationFailed\Handle\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;


class AuthAuctionRegistrationFailedCallbackResponseHandler extends CustomizableClass
{
    use AuctionBidderRegistratorFactoryCreateTrait;
    use DataProviderCreateTrait;
    use TranslatorAwareTrait;

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

        $isResponsiveRoute = $dataProvider->isResponsiveRoute($input->url);
        $redirectUrl = $this->buildRedirectUrl($input);

        $opayoAuctionRegistrationConfig = OpayoThreeDSecureAuctionBidderRegistrationConfig::new()
            ->constructFail($input->threeDSecureErrorMessage);
        $registrator = $this->createAuctionBidderRegistratorFactory()->createBillingApiOpayoRegistrator(
            $input->userId,
            $input->auctionId,
            $input->editorUserId,
            $input->carrierMethod,
            $opayoAuctionRegistrationConfig
        );
        $auctionBidder = $registrator->register();
        if (!$auctionBidder) {
            return Result::new()->construct(
                $isResponsiveRoute,
                $registrator->getErrorMessage(),
                $redirectUrl,
                null
            )->addError(Result::ERR_REGISTRATION_ERROR, $registrator->getErrorMessage());
        }

        $message = $this->buildMessage($input);

        return Result::new()->construct(
            $isResponsiveRoute,
            $message,
            $redirectUrl,
            $auctionBidder
        )->addSuccess(Result::OK_SUCCESS);
    }

    protected function buildMessage(Input $input): string
    {
        $message = '';
        $dataProvider = $this->createDataProvider();
        if ($dataProvider->isResponsiveRoute($input->url)) {
            $message = $this->getTranslator()->translate(
                'SIGNUP_ERR_CC_BEEN_DECLINED',
                'user',
                $input->systemAccountId,
                $input->languageId
            );
        }
        return $message;
    }

    protected function buildRedirectUrl(Input $input): string
    {
        $redirectUrl = $input->url;
        $dataProvider = $this->createDataProvider();
        if ($dataProvider->isResponsiveRoute($input->url)) {
            $urlParams = $dataProvider->getUrlParams($input->url);
            $redirectUrl = $dataProvider->getReviseBillingUrl($input->auctionId, $urlParams);
        }
        return $redirectUrl;
    }
}
