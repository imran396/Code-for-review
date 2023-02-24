<?php

namespace Sam\Rtb;

use Auction;
use Sam\Billing\CreditCard\Validate\CreditCardValidatorAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class ConsoleHelper
 * @package Sam\Rtb
 */
class ConsoleHelper extends CustomizableClass
{
    use CreditCardValidatorAwareTrait;
    use EditorUserAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use SettingsManagerAwareTrait;
    use TranslatorAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionAccountId
     * @return bool
     */
    public function isRejectedToBidBecauseCcInfo(int $auctionAccountId): bool
    {
        /**
         * if place bid require cc is enabled in settings and bidder has no cc hide
         * the "place bid" button and display a warning for the user to update his profile in order to bid.
         */
        $hasCc = true;
        $isPreferred = false;
        $isPlaceBidRequireCc = $this->getSettingsManager()->get(Constants\Setting::PLACE_BID_REQUIRE_CC, $auctionAccountId);
        $isAllUserRequireCcAuth = $this->getSettingsManager()->get(Constants\Setting::ALL_USER_REQUIRE_CC_AUTH, $auctionAccountId);

        if ($this->getEditorUserId()) {
            $hasCc = $this->hasCc($auctionAccountId);
            $isPreferred = $this->getEditorUserBidderPrivilegeChecker()->hasPrivilegeForPreferred();
        }

        if ($isPlaceBidRequireCc) {
            if ($isAllUserRequireCcAuth) {
                $isRejectedToBid = !$hasCc;
            } else {
                $isRejectedToBid = !$hasCc && !$isPreferred;
            }
        } else {
            $isRejectedToBid = false;
        }
        return $isRejectedToBid;
    }

    /**
     * @param int $auctionAccountId
     * @return bool
     */
    public function hasCc(int $auctionAccountId): bool
    {
        $isPayTraceCim = $this->getSettingsManager()->get(Constants\Setting::PAY_TRACE_CIM, $auctionAccountId);
        $userBilling = $this->getEditorUserBillingOrCreate();
        if (
            $isPayTraceCim
            && $userBilling->PayTraceCustId !== ''
        ) {
            $hasCc = true;
        } else {
            $hasCc = $userBilling->CcNumber !== ''
                && $this->getCreditCardValidator()->validateExpiredDateFormatted($userBilling->CcExpDate);
        }
        return $hasCc;
    }

    /**
     * @param Auction $auction
     * @return string
     */
    public function portNotice(Auction $auction): string
    {
        $notice = '';
        $shouldShowPortNotice = $this->getSettingsManager()->get(Constants\Setting::SHOW_PORT_NOTICE, $auction->AccountId);
        $publicPort = $this->getRtbGeneralHelper()->getPublicPort();
        if (
            $shouldShowPortNotice
            && $publicPort !== 80
        ) {
            $langPortNotice = $this->getTranslator()->translateForRtb("BIDDERCLIENT_PORTNOTICE", $auction);
            $notice = sprintf($langPortNotice, $publicPort);
        }
        return $notice;
    }
}
