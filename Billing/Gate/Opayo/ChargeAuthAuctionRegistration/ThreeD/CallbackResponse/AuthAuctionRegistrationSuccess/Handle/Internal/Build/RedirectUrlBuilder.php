<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\Handle\Internal\Build;

use Sam\Auction\Load\Exception\CouldNotFindAuction;
use Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\Handle\Internal\Build\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;
use Sam\Lang\TranslatorAwareTrait;
use Sam\User\Load\Exception\CouldNotFindUser;


class RedirectUrlBuilder extends CustomizableClass
{
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


    public function build(
        string $url,
        int $auctionId,
        int $userId,
        bool $isReadOnlyDb
    ): string {
        $dataProvider = $this->createDataProvider();

        if ($dataProvider->isAdminRoute($url)) {
            return $url;
        }

        $auction = $dataProvider->loadAuction($auctionId, $isReadOnlyDb);
        if (!$auction) {
            throw CouldNotFindAuction::withId($auctionId);
        }

        if ($dataProvider->isRegConfirmPageEnabled($auction->AccountId)) {
            $redirectUrl = $dataProvider->getRegConfirmPageUrl($auctionId, $url);
            return $redirectUrl;
        }

        $urlParams = [];
        if ($dataProvider->isGoogleAnalyticsEnabled($auction->AccountId)) {
            $user = $dataProvider->loadUser($userId, $isReadOnlyDb);

            if (!$user) {
                throw CouldNotFindUser::withId($userId);
            }

            $urlParams[Constants\UrlParam::GA] = Constants\PageTracking::SALE_REGISTRATION
                . $auction->SaleNum
                . '_' . $user->Username;
        }

        if ($url !== '/') {
            $redirectUrl = UrlParser::new()->replaceParams($url, $urlParams);
            return $redirectUrl;
        }

        // Produce url to auction's landing page if no back-url provided
        $redirectUrl = $dataProvider->getAuctionsLandingUrl(
            $auction->Id,
            $auction->AccountId,
            $auction->AuctionInfoLink,
            $urlParams
        );

        return $redirectUrl;
    }
}
