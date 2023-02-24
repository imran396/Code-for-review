<?php

namespace Sam\Auction\Register\ConfirmShippingInfo;

use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Auction\Register\AuctionRegistrationManagerAwareTrait;
use Sam\User\Privilege\Validate\BidderPrivilegeChecker;
use Sam\User\Privilege\Validate\BidderPrivilegeCheckerAwareTrait;
use Sam\User\Validate\UserBillingCheckerAwareTrait;
use Sam\Core\Constants;

/**
 * Class RedirectUrlTypeDetector
 * @package Sam\Auction\Register\ConfirmShippingInfo
 */
class RedirectUrlTypeDetector extends CustomizableClass
{
    use AuctionRegistrationManagerAwareTrait;
    use BidderPrivilegeCheckerAwareTrait;
    use SettingsManagerAwareTrait;
    use UserBillingCheckerAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionAccountId
     * @param int|null $userId null for anonymous user
     * @return int url type
     */
    public function detect(int $auctionAccountId, ?int $userId): int
    {
        $auctionRegistrationManger = $this->getAuctionRegistrationManager();
        $auctionBidderOptions = $auctionRegistrationManger->getAuctionBidderOptions($auctionAccountId);
        if (!empty($auctionBidderOptions)) {
            $redirectUrlType = Constants\Url::P_REGISTER_CONFIRM_BIDDER_OPTIONS;
            return $redirectUrlType;
        }

        $sm = $this->getSettingsManager();
        $isRegistrationRequireCc = (bool)$sm->getForMain(Constants\Setting::REGISTRATION_REQUIRE_CC);
        $isPlaceBidRequireCc = (bool)$sm->get(Constants\Setting::PLACE_BID_REQUIRE_CC, $auctionAccountId);
        $isAutoPreferredCreditCard = (bool)$sm->getForMain(Constants\Setting::AUTO_PREFERRED_CREDIT_CARD);
        $isRequireReEnterCc = (bool)$sm->get(Constants\Setting::REQUIRE_REENTER_CC, $auctionAccountId);
        $isAllUserRequireCcAuth = (bool)$sm->get(Constants\Setting::ALL_USER_REQUIRE_CC_AUTH, $auctionAccountId);
        $hasReadyCcTransactionInfo = $this->getUserBillingChecker()->hasReadyCcTransactionInfo($userId, $auctionAccountId);

        $bidderPrivilegeChecker = $this->getBidderPrivilegeChecker()->initByUserId($userId);

        if (
            $isPlaceBidRequireCc
            && $isRequireReEnterCc
            && (
                $isAllUserRequireCcAuth
                || $this->isRegularBidder($bidderPrivilegeChecker)
            )
        ) {
            $redirectUrlType = Constants\Url::P_REGISTER_RENEW_BILLING;
        } elseif (
            (
                $isRegistrationRequireCc
                || $isPlaceBidRequireCc
            ) && !$hasReadyCcTransactionInfo
        ) {
            if (
                $bidderPrivilegeChecker->hasPrivilegeForPreferred()
                && !$isAllUserRequireCcAuth
            ) {
                $redirectUrlType = Constants\Url::P_REGISTER_TERMS_AND_CONDITIONS;
            } else {
                $redirectUrlType = Constants\Url::P_REGISTER_REVISE_BILLING;
            }
            /* => If he is NOT a preferred bidder
             * => AND if his cc info are already entered in the system and not expired
             * => AND is entered transaction required billing info
             * => then display the Auction registration pop up (the CC pop up that shows up
             *    when the user has no CC info entered or the entered CC's expiration date
             *    has passed) with a new title "Please confirm your CC information"
             * */
        } elseif (
            $isAutoPreferredCreditCard
            && $this->isRegularBidder($bidderPrivilegeChecker)
            && $hasReadyCcTransactionInfo
        ) {
            $redirectUrlType = Constants\Url::P_REGISTER_REVISE_BILLING;
        } else {
            $redirectUrlType = Constants\Url::P_REGISTER_TERMS_AND_CONDITIONS;
        }

        return $redirectUrlType;
    }

    /**
     * Bidder without "Preferred" privilege.
     * @param BidderPrivilegeChecker $bidderPrivilegeChecker
     * @return bool
     */
    protected function isRegularBidder(BidderPrivilegeChecker $bidderPrivilegeChecker): bool
    {
        $is = $bidderPrivilegeChecker->isBidder()
            && !$bidderPrivilegeChecker->hasPrivilegeForPreferred();
        return $is;
    }
}
