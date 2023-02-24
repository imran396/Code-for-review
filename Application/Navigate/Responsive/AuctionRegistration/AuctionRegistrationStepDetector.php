<?php
/**
 * Detects required processing step for user during his Auction Registration use-case
 *
 * SAM-5546: Auction registration step detection and redirect
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           12-12, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Navigate\Responsive\AuctionRegistration;

use Sam\Core\Service\CustomizableClass;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Register\AuctionRegistrationManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;
use Sam\User\Validate\UserBillingCheckerAwareTrait;

/**
 * Class AuctionRegistrationStepDetector
 * @package Sam\Application\Navigate\Responsive\AuctionRegistration
 */
class AuctionRegistrationStepDetector extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionRegistrationManagerAwareTrait;
    use AuthIdentityManagerCreateTrait;
    use EditorUserAwareTrait;
    use SettingsManagerAwareTrait;
    use UserBillingCheckerAwareTrait;

    /**
     * Store for log purpose, if subject available auction is missed
     */
    private ?int $wrongAuctionId = null;
    private ?bool $hasReadyCcTransactionInfo = null;
    private ?bool $isPlaceBidRequireCc = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int
     */
    public function detect(): int
    {
        $this->init();
        if ($this->checkAnonymousUser()) {
            return Constants\RegistrationStepStatuses::STEP_ANONYMOUS_USER;
        }
        if ($this->checkAuctionAbsent()) {
            return Constants\RegistrationStepStatuses::STEP_AUCTION_ABSENT;
        }
        if ($this->checkNoBidderPrivileges()) {
            return Constants\RegistrationStepStatuses::STEP_NO_BIDDER_PRIVILEGES;
        }
        if ($this->checkConfirmShipping()) {
            return Constants\RegistrationStepStatuses::STEP_CONFIRM_SHIPPING;
        }
        if ($this->checkConfirmBidderOptions()) {
            return Constants\RegistrationStepStatuses::STEP_CONFIRM_BIDDER_OPTIONS;
        }
        if ($this->checkRenewBilling()) {
            return Constants\RegistrationStepStatuses::STEP_RENEW_BILLING;
        }
        if ($this->checkReviseBilling()) {
            return Constants\RegistrationStepStatuses::STEP_REVISE_BILLING;
        }
        return Constants\RegistrationStepStatuses::STEP_TERMS_AND_CONDITIONS;
    }

    protected function init(): void
    {
        $auction = $this->getAuctionLoader()->load($this->getAuctionId());
        if (!$auction) {
            $this->wrongAuctionId = $this->getAuctionId();
        }
        // Initialize with null, if not found, we will refer to this object in other checkers
        $this->setAuction($auction);
    }

    /**
     * @return bool
     */
    protected function checkAuctionAbsent(): bool
    {
        if (!$this->getAuction()) {
            $message = 'Available auction not found for user registration in auction'
                . composeSuffix(['a' => $this->wrongAuctionId]);
            log_error($message);
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function checkAnonymousUser(): bool
    {
        return !$this->createAuthIdentityManager()->isAuthorized();
    }

    /**
     * Checks whether a user has bidder privileges.
     * @return bool
     */
    protected function checkNoBidderPrivileges(): bool
    {
        if (!$this->hasEditorUserBidderRole()) {
            log_warning(
                'User does not have bidder privileges, thus cannot register to auction'
                . composeSuffix(['u' => $this->getEditorUserId()])
            );
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    private function checkInitialRequirements(): bool
    {
        $success =
            !$this->checkAnonymousUser()
            && $this->getAuction()
            && $this->hasEditorUserBidderRole();
        return $success;
    }

    /**
     * @return bool
     */
    protected function checkConfirmShipping(): bool
    {
        if (!$this->checkInitialRequirements()) {
            return false;
        }
        $isConfirmAddressSale = (bool)$this->getSettingsManager()->get(Constants\Setting::CONFIRM_ADDRESS_SALE, $this->getAuction()->AccountId);
        return $isConfirmAddressSale;
    }

    /**
     * @return bool
     */
    protected function checkConfirmBidderOptions(): bool
    {
        if (!$this->checkInitialRequirements()) {
            return false;
        }
        $auction = $this->getAuction();
        if (!$auction) {
            log_error("Available auction not found, when checking confirm bidder options" . composeSuffix(['a' => $this->getAuctionId()]));
            return false;
        }
        $auctionBidderOptions = $this->getAuctionRegistrationManager()->getAuctionBidderOptions($auction->AccountId);
        $success = !empty($auctionBidderOptions);
        return $success;
    }

    /**
     * @return bool
     */
    protected function checkRenewBilling(): bool
    {
        if (!$this->checkInitialRequirements()) {
            return false;
        }
        $auctionAccountId = $this->getAuction()->AccountId;
        $sm = $this->getSettingsManager();
        $isRequireReenterCc = (bool)$sm->get(Constants\Setting::REQUIRE_REENTER_CC, $auctionAccountId);
        $isAllUserRequireCcAuth = (bool)$sm->get(Constants\Setting::ALL_USER_REQUIRE_CC_AUTH, $auctionAccountId);
        $bidderPrivilegeChecker = $this->getEditorUserBidderPrivilegeChecker();
        $success = $this->isPlaceBidRequireCc()
            && $isRequireReenterCc
            && (
                $isAllUserRequireCcAuth
                || !$bidderPrivilegeChecker->hasPrivilegeForPreferred()
            );
        return $success;
    }

    /**
     * @return bool
     */
    protected function checkReviseBilling(): bool
    {
        if (!$this->checkInitialRequirements()) {
            return false;
        }

        $isCcRequiredGenerally = $this->isCcRequiredGenerally();
        $conditionOne = $isCcRequiredGenerally && !$this->isBidderRelievedOfCcCheck();
        $success = $conditionOne
            || (!$isCcRequiredGenerally
                && $this->isAutoAssignPreferredBidderPrivilegeUponCreditCardUpdate());

        return $success;
    }

    /**
     * Used in checkReviseBilling()
     * @return bool
     */
    private function isCcRequiredGenerally(): bool
    {
        $isRegistrationRequireCcForMain = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::REGISTRATION_REQUIRE_CC);
        $success = ($isRegistrationRequireCcForMain
                || $this->isPlaceBidRequireCc())
            && !$this->hasReadyCcTransactionInfo();
        return $success;
    }

    /**
     * Used in checkReviseBilling()
     * @return bool
     */
    private function isBidderRelievedOfCcCheck(): bool
    {
        $isAllUserRequireCcAuth = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::ALL_USER_REQUIRE_CC_AUTH, $this->getAuction()->AccountId);
        $bidderPrivilegeChecker = $this->getEditorUserBidderPrivilegeChecker();
        $success = $bidderPrivilegeChecker->hasPrivilegeForPreferred()
            && !$isAllUserRequireCcAuth;
        return $success;
    }

    /**
     * Used in checkReviseBilling()
     * SAM-655: "Auto assign Preferred bidder privileges upon credit card update" improvements
     * => If he is NOT a preferred bidder
     * => AND if his cc info are already entered in the system and not expired
     * => AND is entered transaction required billing info
     * => then display the Auction registration pop up (the CC pop up that shows up
     *    when the user has no CC info entered or the entered CC's expiration date
     *    has passed) with a new title "Please confirm your CC information"
     * @return bool
     */
    private function isAutoAssignPreferredBidderPrivilegeUponCreditCardUpdate(): bool
    {
        // Setting option "Auto assign Preferred bidder privileges upon credit card update"
        $isAutoPreferredCreditCard = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::AUTO_PREFERRED_CREDIT_CARD);
        $bidderPrivilegeChecker = $this->getEditorUserBidderPrivilegeChecker();
        $success = $isAutoPreferredCreditCard
            && $bidderPrivilegeChecker->isBidder()
            && !$bidderPrivilegeChecker->hasPrivilegeForPreferred()
            && $this->hasReadyCcTransactionInfo();
        return $success;
    }

    /**
     * @return bool
     */
    private function isPlaceBidRequireCc(): bool
    {
        if ($this->isPlaceBidRequireCc === null) {
            $this->isPlaceBidRequireCc = (bool)$this->getSettingsManager()
                ->get(Constants\Setting::PLACE_BID_REQUIRE_CC, $this->getAuction()->AccountId);
        }
        return $this->isPlaceBidRequireCc;
    }

    /**
     * @return bool
     */
    private function hasReadyCcTransactionInfo(): bool
    {
        if ($this->hasReadyCcTransactionInfo === null) {
            $this->hasReadyCcTransactionInfo = $this->getUserBillingChecker()
                ->hasReadyCcTransactionInfo($this->getEditorUserId(), $this->getAuction()->AccountId);
        }
        return $this->hasReadyCcTransactionInfo;
    }
}
