<?php
/**
 * Auction resetter.
 *
 * Related tickets:
 * SAM-3741: Auction Reset function adjustments
 * SAM-5012: Live/Hybrid auction state reset in rtbd process
 *
 * @copyright         2018 Bidpath, Inc.
 * @author            Oleg Kovalyov, Igors Kotlevskis
 * @package           com.swb.sam2
 * @version           SVN: $Id$
 * @since             8 June, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package           com.swb.sam2.api
 */

namespace Sam\Auction\Reset;

use Auction;
use AuctionLotItem;
use Sam\Auction\Cache\AuctionDbCacheManagerAwareTrait;
use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\AuctionLot\Cache\Save\AuctionLotCacheUpdaterCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Rtb\State\Reset\RtbStateResetterAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\TimedOnlineOfferBid\TimedOnlineOfferBidReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AbsenteeBid\AbsenteeBidWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\BidTransaction\BidTransactionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\TimedOnlineOfferBid\TimedOnlineOfferBidWriteRepositoryAwareTrait;

/**
 * Class Resetter
 * @package Sam\Auction
 * @method Auction getAuction() - Available auction existence checked in main method reset()
 */
class AuctionResetter extends CustomizableClass
{
    use AbsenteeBidReadRepositoryCreateTrait;
    use AbsenteeBidWriteRepositoryAwareTrait;
    use AuctionAwareTrait;
    use AuctionCacheLoaderAwareTrait;
    use AuctionDbCacheManagerAwareTrait;
    use AuctionLotCacheUpdaterCreateTrait;
    use AuctionLotItemReadRepositoryCreateTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionRendererAwareTrait;
    use AuctionWriteRepositoryAwareTrait;
    use BidTransactionReadRepositoryCreateTrait;
    use BidTransactionWriteRepositoryAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotItemWriteRepositoryAwareTrait;
    use RtbStateResetterAwareTrait;
    use TimedOnlineOfferBidReadRepositoryCreateTrait;
    use TimedOnlineOfferBidWriteRepositoryAwareTrait;

    /** bool $needWipeOutLotSoldInfo */
    private bool $needWipeOutLotSoldInfo = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $needWipeOutLotSoldInfo
     * @return static
     */
    public function enableWipeOutLotSoldInfo(bool $needWipeOutLotSoldInfo): static
    {
        $this->needWipeOutLotSoldInfo = $needWipeOutLotSoldInfo;
        return $this;
    }

    /**
     * @return bool
     */
    public function wipeOutLotSoldInfoEnabled(): bool
    {
        return $this->needWipeOutLotSoldInfo;
    }

    /**
     * Main function for auction resetting
     * @param int $editorUserId
     * @return void
     */
    public function reset(int $editorUserId): void
    {
        if (!$this->getAuction()) { // @phpstan-ignore-line
            log_error(
                "Available auction not found, when resetting auction"
                . composeSuffix(['a' => $this->getAuctionId()])
            );
            return;
        }
        $this->closeAuction($editorUserId);
        $this->deleteAbsenteeBids($editorUserId);
        $this->deleteBidTransactions($editorUserId);
        $this->deleteTimedOnlineOfferBids($editorUserId);
        $this->updateAuctionLots($editorUserId);
        $this->updateAuction($editorUserId);
    }

    /**
     * @return string
     */
    public function getSuccessMessage(): string
    {
        $saleNo = $this->getAuctionRenderer()->renderSaleNo($this->getAuction());
        $name = $this->getAuctionRenderer()->renderName($this->getAuction());
        $message = "Auction # {$saleNo} \"{$name}\" has been reset.";
        return $message;
    }

    /**
     * Temporary close auction to be prepared for reset actions.
     * We don't want Hybrid or Live auction would be running while resetting.
     * @param int $editorUserId
     * @return void
     */
    protected function closeAuction(int $editorUserId): void
    {
        $auction = $this->getAuction();
        $auction->toClosed();
        $this->getAuctionWriteRepository()->saveWithModifier($auction, $editorUserId);
    }

    /**
     * Delete Bid Transactions
     * @param int $editorUserId
     * @return void
     */
    protected function deleteBidTransactions(int $editorUserId): void
    {
        $repo = $this->createBidTransactionReadRepository()
            ->enableReadOnlyDb(true)
            ->filterAuctionId($this->getAuctionId())
            ->filterDeleted(false)
            // ->joinAccountFilterActive(true)
            // ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            // ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            // ->joinLotItemFilterActive(true)
            // ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->setChunkSize(300);

        while ($bidTransactions = $repo->loadEntities()) {
            foreach ($bidTransactions as $bidTransaction) {
                $bidTransaction->Deleted = true;
                $this->getBidTransactionWriteRepository()->saveWithModifier($bidTransaction, $editorUserId);
            }
        }
    }

    /**
     * Delete Timed Online Offer Bids
     * @param int $editorUserId
     * @return void
     */
    protected function deleteTimedOnlineOfferBids(int $editorUserId): void
    {
        if ($this->getAuction()->isTimed()) {
            $repo = $this->createTimedOnlineOfferBidReadRepository()
                ->enableReadOnlyDb(true)
                ->joinAuctionLotItemFilterAuctionId($this->getAuctionId())
                // ->joinAccountFilterActive(true)
                // ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
                // ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
                // ->joinLotItemFilterActive(true)
                // ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
                ->setChunkSize(300);

            while ($timedOfferBids = $repo->loadEntities()) {
                foreach ($timedOfferBids as $timedOfferBid) {
                    $timedOfferBid->Deleted = true;
                    $this->getTimedOnlineOfferBidWriteRepository()->saveWithModifier($timedOfferBid, $editorUserId);
                }
            }
        }
    }

    /**
     * Update Auction Lots
     * @param int $editorUserId
     * @return void
     */
    protected function updateAuctionLots(int $editorUserId): void
    {
        $repo = $this->createAuctionLotItemReadRepository()
            ->filterAuctionId($this->getAuctionId())
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->setChunkSize(300);
        while ($auctionLots = $repo->loadEntities()) {
            foreach ($auctionLots as $auctionLot) {
                $auctionLot->toActive();
                // Drop reference to current bid, because we soft-deleted its bids
                $auctionLot->unlinkCurrentBid();
                $auctionLot->BuyNow = false;
                if ($this->wipeOutLotSoldInfoEnabled()) {
                    $this->wipeOutLotSoldInfo($auctionLot, $editorUserId);
                }
                //if auction is reset, add a sale number to general notes where lot is sold.
                if ($auctionLot->GeneralNote) {
                    $saleNo = $this->getAuctionRenderer()->renderSaleNo($this->getAuction());
                    $auctionLot->GeneralNote = rtrim($auctionLot->GeneralNote, '.') . " in sale ($saleNo). ";
                }
                $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
                $this->createAuctionLotCacheUpdater()->refreshForAuctionLot($auctionLot, $editorUserId);
            }
        }
    }

    /**
     * Update Auction
     * @param int $editorUserId
     * @return void
     */
    protected function updateAuction(int $editorUserId): void
    {
        $auction = $this->getAuction();
        if ($auction->isTimed()) {
            $auction->toStarted();
        } elseif ($auction->isLive()) {
            $auction->toStarted();
            $this->getRtbStateResetter()->resetByAuction($auction->Id, $editorUserId);
            $this->getRtbStateResetter()->notifyRtbd($auction->Id);
        } elseif ($auction->isHybrid()) {
            $auction->toActive();
            $this->getRtbStateResetter()->resetByAuction($auction->Id, $editorUserId);
            $this->getRtbStateResetter()->notifyRtbd($auction->Id);
        }

        $this->getAuctionWriteRepository()->saveWithModifier($auction, $editorUserId);

        $auctionCache = $this->getAuctionCacheLoader()->load($auction->Id);
        if ($auctionCache) {
            $this->getAuctionDbCacheManager()->refresh($auctionCache, $editorUserId);
        } else {
            log_error("Available auction cache not found, when resetting auction state" . composeSuffix(['a' => $auction->Id]));
        }

        log_info("Auction has been reset" . composeSuffix(['a' => $auction->Id]));
    }

    /**
     * Drops hammer price, winning bidder/auction/date, if item was sold in this resetting auction.
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     * @return void
     */
    protected function wipeOutLotSoldInfo(AuctionLotItem $auctionLot, int $editorUserId): void
    {
        // Drop hammer price, winning bidder/auction/date from lot item
        $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId);
        if (!$lotItem) {
            log_error(
                "Available lot item not found for wiping out sold info in auction resetter"
                . composeSuffix(
                    [
                        'li' => $auctionLot->LotItemId,
                        'a' => $auctionLot->AuctionId,
                        'ali' => $auctionLot->Id,
                    ]
                )
            );
            return;
        }

        /**
         * Reset lot's sell info only if item was sold in this auction
         */
        if ($lotItem->isSaleSoldAuctionLinkedWith($this->getAuctionId())) {
            $lotItem->wipeOutSoldInfo();
            $this->getLotItemWriteRepository()->saveWithModifier($lotItem, $editorUserId);
        }
    }

    /**
     * Physically removed absentee_bid row from database
     * @param int $editorUserId
     * @return void
     */
    protected function deleteAbsenteeBids(int $editorUserId): void
    {
        $auction = $this->getAuction();
        if ($auction->isLiveOrHybrid()) {
            $repo = $this->createAbsenteeBidReadRepository()
                ->filterAuctionId($auction->Id)
                ->setChunkSize(300);
            while ($absenteeBids = $repo->loadEntities()) {
                foreach ($absenteeBids as $absenteeBid) {
                    $this->getAbsenteeBidWriteRepository()->deleteWithModifier($absenteeBid, $editorUserId);
                }
            }
        }
    }
}
