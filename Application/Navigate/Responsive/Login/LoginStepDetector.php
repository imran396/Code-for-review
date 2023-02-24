<?php
/**
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

namespace Sam\Application\Navigate\Responsive\Login;

use Sam\Core\Service\CustomizableClass;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\AdditionalRegistrationOption\Load\AdditionalRegistrationOptionLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderCheckerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Validate\UserBillingCheckerAwareTrait;

/**
 * Class LoginStepDetector
 * @package Sam\Application\Navigate\Responsive\Login
 */
class LoginStepDetector extends CustomizableClass
{
    use AdditionalRegistrationOptionLoaderAwareTrait;
    use AuctionAwareTrait;
    use AuctionBidderCheckerAwareTrait;
    use AuctionLoaderAwareTrait;
    use EditorUserAwareTrait;
    use SettingsManagerAwareTrait;
    use UserBillingCheckerAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Store for log purpose, if subject available auction is missed
     * @var int|null
     */
    private ?int $wrongAuctionId = null;
    /** @var bool|null */
    private ?bool $hasReadyCcTransactionInfo = null;
    /** @var bool|null */
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
     * @return int|null
     */
    public function detect(): ?int
    {
        $this->init();
        if ($this->checkAuctionAbsent()) {
            return Constants\RegistrationStepStatuses::STEP_AUCTION_ABSENT;
        }
        if ($this->checkAuctionRegistered()) {
            return Constants\RegistrationStepStatuses::STEP_AUCTION_REGISTERED;
        }
        if ($this->checkConfirmShipping()) {
            return Constants\RegistrationStepStatuses::STEP_CONFIRM_SHIPPING;
        }
        if ($this->checkConfirmBidderOptions()) {
            return Constants\RegistrationStepStatuses::STEP_CONFIRM_BIDDER_OPTIONS;
        }
        if ($this->checkReviseBilling()) {
            return Constants\RegistrationStepStatuses::STEP_REVISE_BILLING;
        }
        if ($this->checkTermsAndConditions()) {
            return Constants\RegistrationStepStatuses::STEP_TERMS_AND_CONDITIONS;
        }
        return null;
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
     * Checks whether an auction has been loaded.
     * @return bool
     */
    protected function checkAuctionAbsent(): bool
    {
        if (!$this->getAuction()) {
            $message = 'Available auction not found for after login redirection! '
                . composeSuffix(['a' => $this->wrongAuctionId]);
            log_error($message);
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    private function checkInitialRequirements(): bool
    {
        $success = $this->getAuction() !== null;
        return $success;
    }

    /**
     * Check whether a user is registered for an auction.
     * @return bool
     */
    protected function checkAuctionRegistered(): bool
    {
        if (!$this->checkInitialRequirements()) {
            return false;
        }
        return $this->getAuctionBidderChecker()
            ->isAuctionRegistered($this->getEditorUserId(), $this->getAuctionId());
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
        $additionalRegistrationOptionLoader = $this->getAdditionalRegistrationOptionLoader();
        $auctionBidderOptions = $additionalRegistrationOptionLoader->loadByAccount($this->getAuction()->AccountId);
        if (count($auctionBidderOptions)) {
            $bidderOptionSelection = $additionalRegistrationOptionLoader
                ->setUserId($this->getEditorUserId())
                ->setAuctionId($this->getAuctionId())
                ->loadByUserAndAuction();
            if (!$bidderOptionSelection) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function checkReviseBilling(): bool
    {
        if (!$this->checkInitialRequirements()) {
            return false;
        }
        $success = $this->isCcRequiredGenerally()
            || $this->hasUserNotExpiredCcInfoAndNotPreferredBidder()
            || !$this->hasReadyCcTransactionInfo();
        return $success;
    }

    /**
     * Checks whether or not user has cc info, cc not expired and user is not preferred bidder
     * @return bool
     */
    protected function hasUserNotExpiredCcInfoAndNotPreferredBidder(): bool
    {
        $isAutoPreferredCreditCard = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::AUTO_PREFERRED_CREDIT_CARD);
        $bidderPrivilegeChecker = $this->getEditorUserBidderPrivilegeChecker();
        $success = $isAutoPreferredCreditCard
            && $bidderPrivilegeChecker->isBidder()
            && !$bidderPrivilegeChecker->hasPrivilegeForPreferred()
            && $this->isPlaceBidRequireCc()
            && $this->getEditorUserId()
            && $this->hasReadyCcTransactionInfo();
        return $success;
    }

    /**
     * @return bool
     */
    protected function checkTermsAndConditions(): bool
    {
        if (!$this->checkInitialRequirements()) {
            return false;
        }
        $stepConditionOne = !$this->isPlaceBidRequireCc() && !$this->getEditorUserId();
        $success = $stepConditionOne || !$this->hasReadyCcTransactionInfo();
        return $success;
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
     * Used in $this->checkReviseBilling()
     * @return bool
     */
    private function isCcRequiredGenerally(): bool
    {
        $sm = $this->getSettingsManager();
        $isRegistrationRequireCc = (bool)$sm->getForMain(Constants\Setting::REGISTRATION_REQUIRE_CC);
        $isRegistrationRequireCcForMain = (bool)$sm->getForMain(Constants\Setting::REGISTRATION_REQUIRE_CC);
        $isConfirmTermsAndConditionsSale = (bool)$sm->get(Constants\Setting::CONFIRM_TERMS_AND_CONDITIONS_SALE, $this->getAuction()->AccountId);
        $applyRegistrationRequireCc = $isConfirmTermsAndConditionsSale ? $isRegistrationRequireCcForMain : $isRegistrationRequireCc;

        $success = ($applyRegistrationRequireCc
                || $this->isPlaceBidRequireCc())
            && !$this->hasReadyCcTransactionInfo();
        return $success;
    }
}
