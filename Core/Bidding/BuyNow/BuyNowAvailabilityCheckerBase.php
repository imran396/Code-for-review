<?php
/**
 * "Buy Now" function availability checking.
 * This core class implements detection logic of Buy Now function availability for lot.
 * It isn't related to persistence layer.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Nov 04, 2015
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * More info:
 * https://bidpath.atlassian.net/browse/SAM-4458?focusedCommentId=116281&page=com.atlassian.jira.plugin.system.issuetabpanels%3Acomment-tabpanel#comment-116281
 */

namespace Sam\Core\Bidding\BuyNow;

use DateTime;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;

/**
 * Class BuyNowAvailabilityCheckerBase
 * @package Sam\Core\Bidding\BuyNow
 */
abstract class BuyNowAvailabilityCheckerBase extends CustomizableClass
{
    use CurrentDateTrait;

    /**
     * ali.lot_status_id
     */
    protected int $lotStatus = Constants\Lot::LS_UNASSIGNED;
    /**
     * ali.buy_now_amount. null and 0. behave equally.
     */
    protected ?float $buyNowAmount = null;
    /**
     * a.start_date
     */
    protected ?DateTime $startDateUtc = null;
    protected ?DateTime $endDateUtc = null;
    /**
     * ali.listing_only
     */
    protected bool $isAuctionListingOnly = false;
    /**
     * a.listing_only
     */
    protected bool $isAuctionLotListingOnly = false;
    /**
     * a.bidding_paused
     */
    protected bool $isBiddingPaused = false;
    /**
     * pass null to skip check
     */
    protected bool $isApprovedBidder = true;
    /**
     * u.flag - pass null to skip check
     */
    protected int $userFlag = Constants\User::FLAG_NONE;

    public const N_ENDED = 1;
    public const N_NOT_STARTED = 2;
    public const N_STATUS_NOT_ACTIVE = 3;
    public const N_STATUS_NOT_ACTIVE_NOT_UNSOLD = 4;
    public const N_BUY_NOW_NOT_SET = 5;
    public const N_NOT_MEET_CURRENT_BID = 6;
    public const N_RESTRICTION_AUCTION_STARTED = 7;    // Live
    public const N_RESTRICTION_LOT_STARTED = 8;        // Live
    public const N_AUCTION_LISTING_ONLY = 9;
    public const N_AUCTION_LOT_LISTING_ONLY = 10;
    public const N_BIDDING_PAUSED = 11;
    public const N_NOT_APPROVED_BIDDER = 12;
    public const N_USER_BLOCKED = 13;
    public const N_RESTRICTION_FIRST_BID = 14;         // Timed

    /** @var int[] */
    protected array $notifications = [];
    /** @var string[] */
    protected array $notificationLabels = [
        self::N_ENDED => 'lot ended',
        self::N_NOT_STARTED => 'lot not started',
        self::N_STATUS_NOT_ACTIVE => 'status not active',
        self::N_STATUS_NOT_ACTIVE_NOT_UNSOLD => 'status not active and not unsold',
        self::N_BUY_NOW_NOT_SET => 'buy now price not set',
        self::N_NOT_MEET_CURRENT_BID => 'not meet current bid',
        self::N_RESTRICTION_AUCTION_STARTED => 'auction started restriction failed',
        self::N_RESTRICTION_LOT_STARTED => 'lot started restriction failed',
        self::N_AUCTION_LISTING_ONLY => 'auction listing only',
        self::N_AUCTION_LOT_LISTING_ONLY => 'auction lot listing only',
        self::N_BIDDING_PAUSED => 'bidding paused',
        self::N_NOT_APPROVED_BIDDER => 'not approved bidder',
        self::N_USER_BLOCKED => 'user blocked',
        self::N_RESTRICTION_FIRST_BID => 'first bid restriction failed',
    ];

    /**
     * Check conditions, that are the same for timed and live lot
     * @param bool $isEnded
     */
    protected function checkMutualConditions(bool $isEnded): void
    {
        if ($isEnded) {
            $this->notifications[] = self::N_ENDED;
        }

        if (Floating::lteq($this->getBuyNowAmount(), 0)) {
            $this->notifications[] = self::N_BUY_NOW_NOT_SET;
        }

        if ($this->isAuctionListingOnly()) {
            $this->notifications[] = self::N_AUCTION_LISTING_ONLY;
        }

        if ($this->isAuctionLotListingOnly()) {
            $this->notifications[] = self::N_AUCTION_LOT_LISTING_ONLY;
        }

        if ($this->isBiddingPaused()) {
            $this->notifications[] = self::N_BIDDING_PAUSED;
        }

        if (!$this->isApprovedBidder()) {
            $this->notifications[] = self::N_NOT_APPROVED_BIDDER;
        }

        if ($this->getUserFlag() === Constants\User::FLAG_BLOCK) {
            $this->notifications[] = self::N_USER_BLOCKED;
        }
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
     * Return array of notification errors
     * @return array
     */
    public function getNotifications(): array
    {
        return $this->notifications;
    }

    /**
     * @return int
     */
    public function getLotStatus(): int
    {
        return $this->lotStatus;
    }

    /**
     * @param int $lotStatus
     * @return static
     */
    public function setLotStatus(int $lotStatus): static
    {
        $this->lotStatus = $lotStatus;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getBuyNowAmount(): ?float
    {
        return $this->buyNowAmount;
    }

    /**
     * @param float|null $buyNowAmount
     * @return static
     */
    public function setBuyNowAmount(?float $buyNowAmount): static
    {
        $this->buyNowAmount = $buyNowAmount;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getStartDateUtc(): ?DateTime
    {
        return $this->startDateUtc;
    }

    /**
     * @param DateTime|null $startDate
     * @return static
     */
    public function setStartDateUtc(?DateTime $startDate): static
    {
        $this->startDateUtc = $startDate;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getEndDateUtc(): ?DateTime
    {
        return $this->endDateUtc;
    }

    /**
     * @param DateTime|null $endDate
     * @return static
     */
    public function setEndDateUtc(?DateTime $endDate): static
    {
        $this->endDateUtc = $endDate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAuctionListingOnly(): bool
    {
        return $this->isAuctionListingOnly;
    }

    /**
     * @param bool $isAuctionListingOnly
     * @return static
     */
    public function enableAuctionListingOnly(bool $isAuctionListingOnly): static
    {
        $this->isAuctionListingOnly = $isAuctionListingOnly;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAuctionLotListingOnly(): bool
    {
        return $this->isAuctionLotListingOnly;
    }

    /**
     * @param bool $isAuctionLotListingOnly
     * @return static
     */
    public function enableAuctionLotListingOnly(bool $isAuctionLotListingOnly): static
    {
        $this->isAuctionLotListingOnly = $isAuctionLotListingOnly;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBiddingPaused(): bool
    {
        return $this->isBiddingPaused;
    }

    /**
     * @param bool $isBiddingPaused
     * @return static
     */
    public function enableBiddingPaused(bool $isBiddingPaused): static
    {
        $this->isBiddingPaused = $isBiddingPaused;
        return $this;
    }

    /**
     * @return bool
     */
    public function isApprovedBidder(): bool
    {
        return $this->isApprovedBidder;
    }

    /**
     * @param bool $isApprovedBidder
     * @return static
     */
    public function enableApprovedBidder(bool $isApprovedBidder): static
    {
        $this->isApprovedBidder = $isApprovedBidder;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserFlag(): int
    {
        return $this->userFlag;
    }

    /**
     * @param int $userFlag
     * @return static
     */
    public function setUserFlag(int $userFlag): static
    {
        $this->userFlag = $userFlag;
        return $this;
    }
}
