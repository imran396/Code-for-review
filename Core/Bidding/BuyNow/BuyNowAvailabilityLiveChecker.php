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

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Math\Floating;

/**
 * Class BuyNowAvailabilityLiveChecker
 * @package Sam\Core\Bidding\BuyNow
 */
class BuyNowAvailabilityLiveChecker extends BuyNowAvailabilityCheckerBase
{
    /**
     * a.auction_status_id
     */
    protected int $auctionStatus = Constants\Auction::AS_NONE;
    /**
     * ab.max_bid
     */
    protected ?float $currentAbsenteeBid = null;
    /**
     * bt.bid (current live)
     */
    protected ?float $currentTransactionBid = null;
    protected bool $isAllowedForUnsold = false;
    /**
     * li.id
     */
    protected int $lotItemId = 0;
    /**
     * seta.buy_now
     */
    protected string $restriction = Constants\SettingRtb::BNLR_AUCTION_STARTED;
    /**
     * rtbc.lot_item_id
     */
    protected ?int $runningLotItemId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Detect current bid value according `absentee_bid` and `bid_transaction` records
     * @return float|null
     */
    public function detectCurrentBid(): ?float
    {
        $currentAbsenteeBid = $this->getCurrentAbsenteeBid();
        $currentTransactionBid = $this->getCurrentTransactionBid();
        $currentBid = max([$currentAbsenteeBid, $currentTransactionBid]);
        return $currentBid;
    }

    /**
     * Check if "Buy Now" action is available for live lot
     * @return bool
     */
    public function isAvailable(): bool
    {
        $this->notifications = [];
        $auctionStatus = $this->getAuctionStatus();
        $lotStatus = $this->getLotStatus();
        $currentDateUtc = $this->getCurrentDateUtc();
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();

        $isEnded = false;
        if ($currentDateUtc < $this->getStartDateUtc()) {
            if ($auctionStatusPureChecker->isClosed($auctionStatus)) {
                $isEnded = true;
            }
        } elseif (!$auctionStatusPureChecker->isStartedOrPaused($auctionStatus)) {
            $isEnded = true;
        }

        $this->checkMutualConditions($isEnded);

        if (
            Floating::gt($this->getBuyNowAmount(), 0)
            && Floating::lteq($this->getBuyNowAmount(), $this->detectCurrentBid())
        ) {
            $this->notifications[] = self::N_NOT_MEET_CURRENT_BID;
        }

        if (
            !$this->isAllowedForUnsold()
            && !$auctionLotStatusPureChecker->isActive($lotStatus)
        ) {
            $this->notifications[] = self::N_STATUS_NOT_ACTIVE;
        }

        if (
            $this->isAllowedForUnsold()
            && !$auctionLotStatusPureChecker->isActiveOrUnsold($lotStatus)
        ) {
            $this->notifications[] = self::N_STATUS_NOT_ACTIVE_NOT_UNSOLD;
        }

        if ($this->getRestriction() === Constants\SettingRtb::BNLR_LOT_STARTED) {
            if ($this->getRunningLotItemId() === $this->getLotItemId()) {
                $this->notifications[] = self::N_RESTRICTION_LOT_STARTED;
            }
        } elseif ($auctionStatusPureChecker->isStartedOrPaused($auctionStatus)) {
            $this->notifications[] = self::N_RESTRICTION_AUCTION_STARTED;
        }

        $isAvailable = count($this->notifications) === 0;
        return $isAvailable;
    }

    /**
     * @return int
     */
    public function getAuctionStatus(): int
    {
        return $this->auctionStatus;
    }

    /**
     * @param int $auctionStatus
     * @return static
     */
    public function setAuctionStatus(int $auctionStatus): static
    {
        $this->auctionStatus = $auctionStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function getRestriction(): string
    {
        return $this->restriction;
    }

    /**
     * @param string $restriction
     * @return static
     */
    public function setRestriction(string $restriction): static
    {
        $this->restriction = $restriction;
        return $this;
    }

    /**
     * @return int
     */
    public function getLotItemId(): int
    {
        return $this->lotItemId;
    }

    /**
     * @param int $lotItemId
     * @return static
     */
    public function setLotItemId(int $lotItemId): static
    {
        $this->lotItemId = $lotItemId;
        return $this;
    }

    /**
     * @return int
     */
    public function getRunningLotItemId(): int
    {
        return $this->runningLotItemId;
    }

    /**
     * @param int|null $lotItemId
     * @return static
     */
    public function setRunningLotItemId(?int $lotItemId): static
    {
        $this->runningLotItemId = $lotItemId;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getCurrentAbsenteeBid(): ?float
    {
        return $this->currentAbsenteeBid;
    }

    /**
     * @param float|null $currentAbsenteeBid
     * @return static
     */
    public function setCurrentAbsenteeBid(?float $currentAbsenteeBid): static
    {
        $this->currentAbsenteeBid = $currentAbsenteeBid;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getCurrentTransactionBid(): ?float
    {
        return $this->currentTransactionBid;
    }

    /**
     * @param float|null $currentTransactionBid
     * @return static
     */
    public function setCurrentTransactionBid(?float $currentTransactionBid): static
    {
        $this->currentTransactionBid = Cast::toFloat($currentTransactionBid);
        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowedForUnsold(): bool
    {
        return $this->isAllowedForUnsold;
    }

    /**
     * @param bool $isAllowedForUnsold
     * @return static
     */
    public function enableAllowedForUnsold(bool $isAllowedForUnsold): static
    {
        $this->isAllowedForUnsold = $isAllowedForUnsold;
        return $this;
    }
}
