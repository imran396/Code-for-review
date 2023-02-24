<?php
/**
 * Additional methods for Undo feature
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           13 Okt, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\State\History\Base;

use RtbCurrent;
use RtbCurrentSnapshot;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\DeleteRepository\Entity\RtbCurrentSnapshot\RtbCurrentSnapshotDeleteRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\RtbCurrentSnapshot\RtbCurrentSnapshotReadRepositoryCreateTrait;

/**
 * Class Helper
 * @package Sam\Rtb\State\History\Base
 */
class Helper extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use RtbCurrentSnapshotDeleteRepositoryCreateTrait;
    use RtbCurrentSnapshotReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return last snapshot for $rtbCurrent session
     * @param RtbCurrent $rtbCurrent
     * @return RtbCurrentSnapshot|null
     */
    public function loadLast(RtbCurrent $rtbCurrent): ?RtbCurrentSnapshot
    {
        return $this->createRtbCurrentSnapshotReadRepository()
            ->filterRtbCurrentId($rtbCurrent->Id)
            ->orderByCreatedOn(false)
            ->orderById(false)
            ->limit(1)
            ->loadEntity();
    }

    /**
     * Return data for Undo button update
     * @param RtbCurrent $rtbCurrent
     * @return array
     */
    public function getUndoButtonData(RtbCurrent $rtbCurrent): array
    {
        $count = $this->countForLot($rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $data['Cnt'] = $count;
        if ($count) {
            $auction = $this->getAuctionLoader()->load($rtbCurrent->AuctionId, true);
            if (!$auction) {
                log_error(
                    "Available auction not found for undo button data"
                    . composeSuffix(['a' => $rtbCurrent->AuctionId])
                );
                return $data;
            }
            if ($auction->isSimpleClerking()) {
                // we show command to undone only in Simple Console
                $snapshot = $this->loadLast($rtbCurrent);
                $data[Constants\Rtb::RES_UNDO_COMMAND] = $snapshot->Command ?? null;
            }
        }
        return $data;
    }

    /**
     * Delete snapshots of auction except snapshots for running lot
     * @param RtbCurrent $rtbCurrent
     */
    public function clean(RtbCurrent $rtbCurrent): void
    {
        $count = $this->createRtbCurrentSnapshotReadRepository()
            ->filterAuctionId($rtbCurrent->AuctionId)
            ->skipLotItemId($rtbCurrent->LotItemId)
            ->count();

        if ($count > 0) {
            $this->createRtbCurrentSnapshotDeleteRepository()
                ->filterAuctionId($rtbCurrent->AuctionId)
                ->skipLotItemId($rtbCurrent->LotItemId)
                ->delete();
            log_debug(
                $count . ' snapshot(s) deleted for cleanup'
                . composeSuffix(['a' => $rtbCurrent->AuctionId])
            );
        }
    }

    /**
     * Delete snapshots for auction
     * @param int $auctionId
     */
    public function cleanForAuctionId(int $auctionId): void
    {
        $this->createRtbCurrentSnapshotDeleteRepository()
            ->filterAuctionId($auctionId)
            ->delete();
    }

    /**
     * Return count of active snapshots for lot in auction
     * @param int|null $auctionId
     * @param int|null $lotItemId
     * @return int
     */
    protected function countForLot(?int $lotItemId, ?int $auctionId): int
    {
        if (!$lotItemId || !$auctionId) {
            return 0;
        }
        $count = $this->createRtbCurrentSnapshotReadRepository()
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->count();
        return $count;
    }
}
