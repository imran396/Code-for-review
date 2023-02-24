<?php
/**
 * Move all lot bids from one auction to another auction.
 * Bids moving is actual, when we move lot from one auction to another, hence this namespace was chosen
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           05 Dec, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Move;

use Auction;
use AuctionLotItem;
use InvalidArgumentException;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Cache\Save\AuctionLotCacheUpdaterCreateTrait;
use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Bidder\AuctionBidder\Register\General\AuctionBidderRegistratorFactoryCreateTrait;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderCheckerAwareTrait;
use Sam\Bidding\AbsenteeBid\Load\AbsenteeBidLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AbsenteeBid\AbsenteeBidWriteRepositoryAwareTrait;

/**
 * Class BidMover
 * @package Sam\Lot\Move
 */
class BidMover extends CustomizableClass
{
    use AbsenteeBidLoaderAwareTrait;
    use AbsenteeBidReadRepositoryCreateTrait;
    use AbsenteeBidWriteRepositoryAwareTrait;
    use AuctionBidderCheckerAwareTrait;
    use AuctionBidderHelperAwareTrait;
    use AuctionBidderRegistratorFactoryCreateTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotCacheUpdaterCreateTrait;
    use CurrentDateTrait;
    use EditorUserAwareTrait;

    protected ?AuctionLotItem $sourceAuctionLot = null;
    protected ?AuctionLotItem $targetAuctionLot = null;
    protected ?Auction $sourceAuction = null;
    protected ?Auction $targetAuction = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     */
    public function move(): void
    {
        $sourceAuction = $this->getSourceAuction();
        $targetAuction = $this->getTargetAuction();
        if (!$sourceAuction || !$targetAuction) {
            log_error('Source or target auction missed');
            return;
        }
        if ($sourceAuction->isTimed()) {
            if ($targetAuction->isTimed()) {
                $this->moveTimedToTimed();
            } else {
                $this->moveTimedToLive();
            }
        } else {
            if ($targetAuction->isTimed()) {
                $this->moveLiveToTimed();
            } else {
                $this->moveLiveToLive();
            }
        }
    }

    /**
     */
    protected function moveLiveToLive(): void
    {
        $absenteeBids = $this->getAbsenteeBidLoader()
            ->loadForAuctionLot($this->sourceAuctionLot->LotItemId, $this->sourceAuctionLot->AuctionId, true);
        foreach ($absenteeBids as $absenteeBid) {
            $isAuctionRegistered = $this->getAuctionBidderChecker()
                ->isAuctionRegistered($absenteeBid->UserId, $this->targetAuctionLot->AuctionId);
            if (!$isAuctionRegistered) {
                // On the admin side there should not be any restriction for an admin to add a user to an auction as bidder
                $this->createAuctionBidderRegistratorFactory()
                    ->createWebBidMoveRegistrator(
                        $absenteeBid->UserId,
                        $this->targetAuctionLot->AuctionId,
                        $this->getEditorUserId()
                    )
                    ->register();
                // if (!$this->getAuctionBidderHelper()->isApproved($auctionBidder)) {
                //     $bidderNum = $this->getAuctionBidderHelper()->suggestBidderNum($auctionBidder);
                //     $auctionBidder = $this->getAuctionBidderHelper()->approve($auctionBidder, $bidderNum);
                //     $auctionBidder->Save();
                // }
            }
        }
        $this->reAssignLotBid($this->sourceAuctionLot, $this->targetAuctionLot);
    }

    protected function moveLiveToTimed(): void
    {
        // TODO
    }

    protected function moveTimedToTimed(): void
    {
        // TODO
    }

    protected function moveTimedToLive(): void
    {
        // TODO
    }

    /**
     * @return int
     */
    public function countMovedBids(): int
    {
        $count = $this->createAbsenteeBidReadRepository()
            ->filterAuctionId($this->targetAuctionLot->AuctionId)
            ->filterLotItemId($this->targetAuctionLot->LotItemId)
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->count();
        return $count;
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @return static
     */
    public function setSourceAuctionLot(AuctionLotItem $auctionLot): static
    {
        $this->sourceAuctionLot = $auctionLot;
        return $this;
    }

    /**
     * @param Auction $auction
     * @return static
     */
    public function setSourceAuction(Auction $auction): static
    {
        $this->sourceAuction = $auction;
        return $this;
    }

    /**
     * @return Auction
     */
    protected function getSourceAuction(): ?Auction
    {
        if ($this->sourceAuction === null) {
            $this->sourceAuction = $this->getAuctionLoader()->load($this->sourceAuctionLot->AuctionId);
        }
        if (!$this->sourceAuction) {
            throw new InvalidArgumentException(
                "Source auction not found"
                . composeSuffix(['a' => $this->sourceAuctionLot->AuctionId])
            );
        }
        return $this->sourceAuction;
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @return static
     */
    public function setTargetAuctionLot(AuctionLotItem $auctionLot): static
    {
        $this->targetAuctionLot = $auctionLot;
        return $this;
    }

    /**
     * @param Auction $auction
     * @return static
     */
    public function setTargetAuction(Auction $auction): static
    {
        $this->targetAuction = $auction;
        return $this;
    }

    /**
     * Reassign lot absentee bid to another sale
     * @param AuctionLotItem $oldAuctionLot auction lot item from previous sale
     * @param AuctionLotItem $newAuctionLot auction lot item for new sale
     */
    public function reAssignLotBid(AuctionLotItem $oldAuctionLot, AuctionLotItem $newAuctionLot): void
    {
        $absenteeBids = $this->createAbsenteeBidReadRepository()
            ->enableReadOnlyDb(true)
            ->filterAuctionId($oldAuctionLot->AuctionId)
            ->filterLotItemId($oldAuctionLot->LotItemId)
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            // ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)  // lot may be already deleted
            ->joinLotItemFilterActive(true)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->loadEntities();
        foreach ($absenteeBids as $absenteeBid) {
            $absenteeBid->AuctionId = $newAuctionLot->AuctionId;
            $this->getAbsenteeBidWriteRepository()->saveWithModifier($absenteeBid, $this->getEditorUserId());
        }

        $this->createAuctionLotCacheUpdater()->refreshForAuctionLot($newAuctionLot, $this->getEditorUserId());
    }

    /**
     * @return Auction
     */
    protected function getTargetAuction(): ?Auction
    {
        if ($this->targetAuction === null) {
            $this->targetAuction = $this->getAuctionLoader()->load($this->targetAuctionLot->AuctionId);
        }
        if (!$this->targetAuction) {
            throw new InvalidArgumentException(
                "Target auction not found"
                . composeSuffix(['a' => $this->targetAuctionLot->AuctionId])
            );
        }
        return $this->targetAuction;
    }
}
