<?php
/**
 * "Buy Now" function availability checking.
 * This class is inherited from core class, that implements business logic.
 * We use there persistence layer.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Nov 04, 2015
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BuyNow;

use Auction;
use AuctionLotItem;
use AuctionLotItemCache;
use DateTime;
use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;
use Sam\Auction\Load\AuctionDynamicLoaderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotCacheLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\StaggerClosing\StaggerClosingHelperCreateTrait;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderCheckerAwareTrait;
use Sam\Bidding\CurrentBid\HighBidDetectorCreateTrait;
use Sam\Core\Bidding\BuyNow\BuyNowAvailabilityLiveCheckerCreateTrait;
use Sam\Core\Bidding\BuyNow\BuyNowAvailabilityTimedCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\User\Flag\UserFlaggingAwareTrait;

/**
 * Class Checker
 * @package Sam\Bidding\BuyNow
 */
class BuyNowValidator extends CustomizableClass
{
    use AuctionBidderCheckerAwareTrait;
    use AuctionCacheLoaderAwareTrait;
    use AuctionDynamicLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotCacheLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use BuyNowAvailabilityLiveCheckerCreateTrait;
    use BuyNowAvailabilityTimedCheckerCreateTrait;
    use HighBidDetectorCreateTrait;
    use RtbGeneralHelperAwareTrait;
    use SettingsManagerAwareTrait;
    use StaggerClosingHelperCreateTrait;
    use UserFlaggingAwareTrait;

    /**
     * @var string[]
     */
    protected array $notifications = [];
    /**
     * @var string[]
     */
    protected array $notificationLabels = [];

    /**
     * @var bool
     */
    protected bool $enabledUserChecking = true;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if "Buy Now" action is available
     * @param int|null $userId
     * @param int $lotItemId
     * @param int $auctionId
     * @return bool
     */
    public function isAvailable(?int $userId, int $lotItemId, int $auctionId): bool
    {
        $logInfo = composeSuffix(['li' => $lotItemId, 'a' => $auctionId, 'u' => $userId]);
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction instanceof Auction) {
            log_error("Auction not found {$logInfo}");
            return false;
        }
        $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auctionId);
        if (!$auctionLot instanceof AuctionLotItem) {
            log_error("AuctionLotItem not found for {$logInfo}");
            return false;
        }
        $auctionLotCache = $this->getAuctionLotCacheLoader()->loadById($auctionLot->Id);
        if (!$auctionLotCache instanceof AuctionLotItemCache) {
            log_error("Available auction lot cache not found" . composeSuffix(['ali' => $auctionLot->Id]));
            return false;
        }
        $isBuyNowUnsold = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::BUY_NOW_UNSOLD, $auction->AccountId);
        $isApprovedBidder = $this->getAuctionBidderChecker()->isAuctionApproved($userId, $auctionId);
        $userFlag = $this->getUserFlagging()->detectFlag($userId, $auction->AccountId);
        if ($auction->isTimed()) {
            $checker = $this->createBuyNowAvailabilityTimedChecker();
            if ($this->enabledUserChecking) {
                $checker
                    ->enableApprovedBidder($isApprovedBidder)
                    ->setUserFlag($userFlag);
            }
            $endDate = $this->getEndDate($auction, $auctionLot, $auctionLotCache);
            $isAvailable = $checker
                ->enableAuctionListingOnly($auction->ListingOnly)
                ->enableAuctionLotListingOnly($auctionLot->ListingOnly)
                ->enableBiddingPaused($auction->BiddingPaused)
                ->setBuyNowAmount($auctionLot->BuyNowAmount)
                ->setCurrentBid($auctionLotCache->CurrentBid)
                ->setEndDateUtc($endDate)
                ->setLotStatus((int)$auctionLot->LotStatusId)
                ->setStartDateUtc($auctionLotCache->StartDate)
                ->isAvailable();
            $this->notifications = $this->createBuyNowAvailabilityTimedChecker()->getNotifications();
            $this->notificationLabels = $this->createBuyNowAvailabilityTimedChecker()->getNotificationLabels();
        } else {
            $checker = $this->createBuyNowAvailabilityLiveChecker();
            if ($this->enabledUserChecking) {
                $checker
                    ->enableApprovedBidder($isApprovedBidder)
                    ->setUserFlag($userFlag);
            }
            $currentBidAmount = $this->createHighBidDetector()->detectAmount($lotItemId, $auctionId);
            $rtbCurrent = $this->getRtbGeneralHelper()->loadRtbCurrentOrCreate($auction);
            $buyNowRestriction = $this->getSettingsManager()
                ->get(Constants\Setting::BUY_NOW_RESTRICTION, $auction->AccountId);
            $isAvailable = $checker
                ->enableAllowedForUnsold($isBuyNowUnsold)
                ->enableAuctionListingOnly($auction->ListingOnly)
                ->enableAuctionLotListingOnly($auctionLot->ListingOnly)
                ->enableBiddingPaused($auction->BiddingPaused)
                ->setAuctionStatus((int)$auction->AuctionStatusId)
                ->setBuyNowAmount($auctionLot->BuyNowAmount)
                ->setCurrentAbsenteeBid($auctionLotCache->CurrentBid)
                ->setCurrentTransactionBid($currentBidAmount)
                ->setLotItemId($lotItemId)
                ->setLotStatus((int)$auctionLot->LotStatusId)
                ->setRestriction($buyNowRestriction)
                ->setRunningLotItemId((int)$rtbCurrent->LotItemId)
                ->setStartDateUtc($auction->StartClosingDate)
                ->isAvailable();
            $this->notifications = $this->createBuyNowAvailabilityLiveChecker()->getNotifications();
            $this->notificationLabels = $this->createBuyNowAvailabilityLiveChecker()->getNotificationLabels();
        }
        return $isAvailable;
    }

    /**
     * @param Auction $auction
     * @param AuctionLotItem $auctionLot
     * @param AuctionLotItemCache $auctionLotCache
     * @return DateTime|null
     */
    protected function getEndDate(Auction $auction, AuctionLotItem $auctionLot, AuctionLotItemCache $auctionLotCache): ?DateTime
    {
        if (
            $auction->ExtendAll
            && $auctionLot->isActive()
        ) {
            $auctionDynamic = $this->getAuctionDynamicLoader()->loadOrCreate($auction->Id);
            if ($auction->StaggerClosing) {
                $endDate = $this->createStaggerClosingHelper()
                    ->calcEndDate(
                        $auctionDynamic->ExtendAllStartClosingDate,
                        $auction->LotsPerInterval,
                        $auction->StaggerClosing,
                        $auctionLot->Order
                    );
            } else {
                $endDate = $auctionDynamic->ExtendAllStartClosingDate;
            }
        } else {
            $endDate = $auctionLotCache->EndDate;
        }
        return $endDate;
    }

    /**
     * Check Buy Now action availability passing fields array fetched by LotList component
     * @param BuyNowValidationInput $input
     * @return bool
     */
    public function isAvailableByDataArray(BuyNowValidationInput $input): bool
    {
        $isAvailable = false;
        $endDate = new DateTime($input->lotEndDate);
        $startDate = new DateTime($input->lotStartDate);
        // $userFlag = (int)$input->userFlag;
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isTimed($input->auctionType)) {
            $checker = $this->createBuyNowAvailabilityTimedChecker();
            if ($this->enabledUserChecking) {
                $checker
                    ->enableApprovedBidder($input->approvedBidder)
                    ->setUserFlag($input->userFlag);
            }
            $isAvailable = $checker
                ->enableAuctionListingOnly($input->isAuctionListing)
                ->enableAuctionLotListingOnly($input->isLotListing)
                ->enableBiddingPaused($input->isBiddingPaused)
                ->setBuyNowAmount($input->buyAmount)
                ->setCurrentBid($input->currentBid)
                ->setEndDateUtc($endDate)
                ->setLotStatus($input->lotStatusId)
                ->setStartDateUtc($startDate)
                ->isAvailable();
            $this->notifications = $this->createBuyNowAvailabilityTimedChecker()->getNotifications();
            $this->notificationLabels = $this->createBuyNowAvailabilityTimedChecker()->getNotificationLabels();
        } elseif ($auctionStatusPureChecker->isLiveOrHybrid($input->auctionType)) {
            $checker = $this->createBuyNowAvailabilityLiveChecker();
            if ($this->enabledUserChecking) {
                $checker
                    ->enableApprovedBidder($input->approvedBidder)
                    ->setUserFlag($input->userFlag);
            }
            $isAvailable = $checker
                ->enableAllowedForUnsold($input->isBuyNowUnsold)
                ->enableAuctionListingOnly($input->isAuctionListing)
                ->enableAuctionLotListingOnly($input->isLotListing)
                ->enableBiddingPaused($input->isBiddingPaused)
                ->setAuctionStatus($input->auctionStatusId)
                ->setBuyNowAmount($input->buyAmount)
                ->setCurrentAbsenteeBid($input->currentBid)
                ->setCurrentTransactionBid($input->currentTransactionBid)
                ->setLotItemId($input->lotItemId)
                ->setLotStatus($input->lotStatusId)
                ->setRestriction($input->buyNowRestriction)
                ->setRunningLotItemId($input->rtbCurrentLotId)
                ->setStartDateUtc($startDate)
                ->isAvailable();
            $this->notifications = $this->createBuyNowAvailabilityLiveChecker()->getNotifications();
            $this->notificationLabels = $this->createBuyNowAvailabilityLiveChecker()->getNotificationLabels();
        }
        return $isAvailable;
    }

    /**
     * Check, if notification occurred
     * @param int $notification
     * @return bool
     */
    public function hasNotification(int $notification): bool
    {
        $hasNotification = in_array($notification, $this->notifications, true);
        return $hasNotification;
    }

    /**
     * Return array of notification labels
     * @return array
     */
    public function getNotificationLabels(): array
    {
        $labels = array_intersect_key($this->notificationLabels, array_flip($this->notifications));
        return $labels;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableUserChecking(bool $enable): static
    {
        $this->enabledUserChecking = $enable;
        return $this;
    }
}
