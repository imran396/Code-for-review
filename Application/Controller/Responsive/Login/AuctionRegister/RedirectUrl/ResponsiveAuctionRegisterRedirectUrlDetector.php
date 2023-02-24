<?php
/**
 * Detect redirection URL for auction registration process.
 *
 * SAM-11674: Ability to adjust public page routing. Prepare existing routes
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 08, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Login\AuctionRegister\RedirectUrl;

use Sam\Application\Controller\Responsive\Login\AuctionRegister\RedirectUrl\Internal\Load\DataProviderCreateTrait;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class ResponsiveAuctionRegisterRedirectUrlDetector
 * @package Sam\Application\Controller\Responsive\Login\AuctionRegister\RedirectUrl
 */
class ResponsiveAuctionRegisterRedirectUrlDetector extends CustomizableClass
{
    use BackUrlParserAwareTrait;
    use DataProviderCreateTrait;
    use SettingsManagerAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Redirect to auction registration pages - confirm shipping, revise billing, terms and conditions
     *
     * @param int $auctionId auction.id
     * @param int $editorUserId user.id
     * @param string $redirectUrl default redirection url, it also acts as back-page url parameter in some cases
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function detect(int $auctionId, int $editorUserId, string $redirectUrl, bool $isReadOnlyDb = false): string
    {
        $dataProvider = $this->createDataProvider();
        $auction = $dataProvider->loadAuction($auctionId, $isReadOnlyDb); // $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            //STEP_AUCTION_ABSENT
            return $redirectUrl;
        }

        $sm = $this->getSettingsManager();
        $isPlaceBidRequireCc = (bool)$sm->get(Constants\Setting::PLACE_BID_REQUIRE_CC, $auction->AccountId);
        $isRegistrationRequireCc = (bool)$sm->getForMain(Constants\Setting::REGISTRATION_REQUIRE_CC);
        $isConfirmAddressSale = (bool)$sm->get(Constants\Setting::CONFIRM_ADDRESS_SALE, $auction->AccountId);
        $isConfirmTermsAndConditionsSale = (bool)$sm->get(Constants\Setting::CONFIRM_TERMS_AND_CONDITIONS_SALE, $auction->AccountId);
        $isAutoPreferredCreditCard = (bool)$sm->getForMain(Constants\Setting::AUTO_PREFERRED_CREDIT_CARD);
        $isRegistrationRequireCcForMain = (bool)$sm->getForMain(Constants\Setting::REGISTRATION_REQUIRE_CC);

        $isAuctionRegistered = $dataProvider->isAuctionRegistered($editorUserId, $auctionId, $isReadOnlyDb);

        if (!$isAuctionRegistered) {
            $auctionBidderOptions = $dataProvider->loadAdditionalRegistrationOptionsByAccount($auction->AccountId, $isReadOnlyDb);
            $hasReadyCcTransactionInfo = $dataProvider->hasReadyCcTransactionInfo($editorUserId, $auction->AccountId);
            $urlBuilder = $this->getUrlBuilder();
            if ($isConfirmAddressSale) {
                //STEP_CONFIRM_SHIPPING
                $url = $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::P_REGISTER_CONFIRM_SHIPPING, $auctionId)
                );
                $url = $this->getBackUrlParser()->replace($url, $redirectUrl);
                return $url;
            }

            if (count($auctionBidderOptions)) {
                $bidderOptionSelection = $dataProvider->loadAdditionalRegistrationOptionsByUserAndAuction($editorUserId, $auctionId, $isReadOnlyDb);
                if (!$bidderOptionSelection) {
                    //STEP_CONFIRM_BIDDER_OPTIONS
                    $url = $urlBuilder->build(
                        AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::P_REGISTER_CONFIRM_BIDDER_OPTIONS, $auctionId)
                    );
                    $url = $this->getBackUrlParser()->replace($url, $redirectUrl);
                    return $url;
                }
            } elseif ($isConfirmTermsAndConditionsSale) {
                if (
                    (
                        $isRegistrationRequireCcForMain
                        || $isPlaceBidRequireCc
                    )
                    && !$hasReadyCcTransactionInfo
                ) {
                    //STEP_REVISE_BILLING
                    $url = $urlBuilder->build(
                        AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::P_REGISTER_REVISE_BILLING, $auctionId)
                    );
                    $url = $this->getBackUrlParser()->replace($url, $redirectUrl);
                    return $url;
                }

                if (
                    $isPlaceBidRequireCc
                    && $editorUserId
                ) {
                    // user has cc info, cc not expired and user is not preferred bidder
                    if (
                        $isAutoPreferredCreditCard
                        && $dataProvider->isBidder($editorUserId, $isReadOnlyDb)
                        && !$dataProvider->hasPrivilegeForPreferred($editorUserId, $isReadOnlyDb)
                        && $hasReadyCcTransactionInfo
                    ) {
                        //STEP_REVISE_BILLING
                        $url = $urlBuilder->build(
                            AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::P_REGISTER_REVISE_BILLING, $auctionId)
                        );
                        $url = $this->getBackUrlParser()->replace($url, $redirectUrl);
                        return $url;
                    }

                    if (!$hasReadyCcTransactionInfo) {
                        //STEP_REVISE_BILLING
                        $url = $urlBuilder->build(
                            AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::P_REGISTER_REVISE_BILLING, $auctionId)
                        );
                        $url = $this->getBackUrlParser()->replace($url, $redirectUrl);
                        return $url;
                    }

                    //STEP_TERMS_AND_CONDITIONS
                    $url = $urlBuilder->build(
                        AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::P_REGISTER_TERMS_AND_CONDITIONS, $auctionId)
                    );
                    $url = $this->getBackUrlParser()->replace($url, $redirectUrl);
                    return $url;
                }

                //STEP_TERMS_AND_CONDITIONS
                $url = $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::P_REGISTER_TERMS_AND_CONDITIONS, $auctionId)
                );
                $url = $this->getBackUrlParser()->replace($url, $redirectUrl);
                return $url;
            } else {
                if (
                    (
                        $isRegistrationRequireCc
                        || $isPlaceBidRequireCc
                    )
                    && !$hasReadyCcTransactionInfo
                ) {
                    //STEP_REVISE_BILLING
                    $url = $urlBuilder->build(
                        AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::P_REGISTER_REVISE_BILLING, $auctionId)
                    );
                    $url = $this->getBackUrlParser()->replace($url, $redirectUrl);
                    return $url;
                }

                if (
                    $isPlaceBidRequireCc
                    && $editorUserId
                ) {
                    // user has cc info, cc not expired and user is not preferred bidder
                    if (
                        $isAutoPreferredCreditCard
                        && $dataProvider->isBidder($editorUserId, $isReadOnlyDb)
                        && !$dataProvider->hasPrivilegeForPreferred($editorUserId, $isReadOnlyDb)
                        && $hasReadyCcTransactionInfo
                    ) {
                        //STEP_REVISE_BILLING
                        $url = $urlBuilder->build(
                            AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::P_REGISTER_REVISE_BILLING, $auctionId)
                        );
                        $url = $this->getBackUrlParser()->replace($url, $redirectUrl);
                        return $url;
                    }

                    if (!$hasReadyCcTransactionInfo) {
                        //STEP_REVISE_BILLING
                        $url = $urlBuilder->build(
                            AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::P_REGISTER_REVISE_BILLING, $auctionId)
                        );
                        $url = $this->getBackUrlParser()->replace($url, $redirectUrl);
                        return $url;
                    }

                    //STEP_TERMS_AND_CONDITIONS
                    $url = $urlBuilder->build(
                        AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::P_REGISTER_TERMS_AND_CONDITIONS, $auctionId)
                    );
                    $url = $this->getBackUrlParser()->replace($url, $redirectUrl);
                    return $url;
                }

                //STEP_TERMS_AND_CONDITIONS
                $url = $urlBuilder->build(
                    AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::P_REGISTER_TERMS_AND_CONDITIONS, $auctionId)
                );
                $url = $this->getBackUrlParser()->replace($url, $redirectUrl);
                return $url;
            }
        }

        //STEP_AUCTION_REGISTERED
        return $redirectUrl;
    }
}
