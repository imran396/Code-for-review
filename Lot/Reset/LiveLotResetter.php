<?php
/**
 * Live lot resetter
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
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\BidTransaction\BidTransactionWriteRepositoryAwareTrait;

/**
 * Class LiveLotResetter
 * @package Sam\Lot\Reset
 */
class LiveLotResetter extends CustomizableClass
{
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use BidTransactionReadRepositoryCreateTrait;
    use BidTransactionWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Reset auction_lot_item.current_bid_id
     * Mark all respective bid_transactions deleted
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

        log_info(
            "Live lot reset"
            . composeSuffix(
                [
                    'li' => $auctionLot->LotItemId,
                    'a' => $auctionLot->AuctionId,
                    'bt cnt' => $btCount,
                ]
            )
        );

        return true;
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     */
    protected function resetAuctionLot(AuctionLotItem $auctionLot, int $editorUserId): void
    {
        $auctionLot->unlinkCurrentBid();
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
        foreach ($bitTransactions as $transaction) {
            $transaction->Deleted = true;
            $this->getBidTransactionWriteRepository()->saveWithModifier($transaction, $editorUserId);
        }
        return count($bitTransactions);
    }
}
