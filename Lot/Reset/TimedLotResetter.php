<?php
/**
 * Timed lot resetter
 *
 * SAM-3918: Lot Resetter classes
 *
 * @copyright         2018 Bidpath, Inc.
 * @author            Igors Kotlevskis
 * @package           com.swb.sam2
 * @version           SVN: $Id$
 * @since             19 Oct, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package           com.swb.sam2.api
 *
 */

namespace Sam\Lot\Reset;

use AuctionLotItem;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\TimedOnlineOfferBid\TimedOnlineOfferBidReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\BidTransaction\BidTransactionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\TimedOnlineOfferBid\TimedOnlineOfferBidWriteRepositoryAwareTrait;

/**
 * Class TimedLotResetter
 * @package Sam\Lot\Reset
 */
class TimedLotResetter extends CustomizableClass
{
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use BidTransactionReadRepositoryCreateTrait;
    use BidTransactionWriteRepositoryAwareTrait;
    use TimedOnlineOfferBidReadRepositoryCreateTrait;
    use TimedOnlineOfferBidWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Reset auction_lot_item.current_bid_id and set lot_status_id=1
     * Mark all respective bid_transactions deleted
     * Mark all respective timed_online_offer_bid records deleted
     *
     * @param int $lotItemId
     * @param int $auctionId
     * @param int $editorUserId
     * @return bool true on success, false on failure
     */
    public function reset(int $lotItemId, int $auctionId, int $editorUserId): bool
    {
        $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auctionId);
        if (!$auctionLot) {
            return false;
        }

        $this->resetAuctionLot($auctionLot, $editorUserId);
        $btCount = $this->deleteBidTransactions($auctionLot, $editorUserId);
        $toobCount = $this->deleteTimedOnlineOfferBids($auctionLot, $editorUserId);
        $logData = [
            'li' => $auctionLot->LotItemId,
            'a' => $auctionLot->AuctionId,
            'bt cnt' => $btCount,
            'toob cnt' => $toobCount,
        ];
        log_info("Timed lot reset" . composeSuffix($logData));
        return true;
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     */
    protected function resetAuctionLot(AuctionLotItem $auctionLot, int $editorUserId): void
    {
        $auctionLot->unlinkCurrentBid();
        $auctionLot->toActive();
        $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
    }

    /**
     * Soft delete BidTransaction records and return their count
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     * @return int
     */
    protected function deleteBidTransactions(AuctionLotItem $auctionLot, int $editorUserId): int
    {
        $bitTransactions = $this->createBidTransactionReadRepository()
            ->enableReadOnlyDb(true)
            ->filterAuctionId($auctionLot->AuctionId)
            ->filterLotItemId($auctionLot->LotItemId)
            ->filterDeleted(false)
            ->loadEntities();
        foreach ($bitTransactions as $bitTransaction) {
            $bitTransaction->Deleted = true;
            $this->getBidTransactionWriteRepository()->saveWithModifier($bitTransaction, $editorUserId);
        }
        return count($bitTransactions);
    }

    /**
     * Soft delete TimedOnlineOfferBid records and return their count
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     * @return int
     */
    protected function deleteTimedOnlineOfferBids(AuctionLotItem $auctionLot, int $editorUserId): int
    {
        $timedOfferBids = $this->createTimedOnlineOfferBidReadRepository()
            ->enableReadOnlyDb(true)
            ->filterAuctionLotItemId($auctionLot->Id)
            ->filterDeleted([false, null])
            ->loadEntities();
        foreach ($timedOfferBids as $timedOfferBid) {
            $timedOfferBid->Deleted = true;
            $this->getTimedOnlineOfferBidWriteRepository()->saveWithModifier($timedOfferBid, $editorUserId);
        }
        return count($timedOfferBids);
    }
}
