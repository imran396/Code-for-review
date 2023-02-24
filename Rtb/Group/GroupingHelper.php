<?php
/**
 * Grouping helper
 */

namespace Sam\Rtb\Group;

use AuctionLotItem;
use RtbCurrent;
use RtbCurrentGroup;
use Sam\AuctionLot\Order\Query\AuctionLotOrderMysqlQueryBuilderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\DeleteRepository\Entity\RtbCurrentGroup\RtbCurrentGroupDeleteRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\RtbCurrentGroup\RtbCurrentGroupReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrentGroup\RtbCurrentGroupWriteRepositoryAwareTrait;

/**
 * Class GroupingHelper
 * @package Sam\Rtb\Group
 */
class GroupingHelper extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use AuctionLotOrderMysqlQueryBuilderCreateTrait;
    use DbConnectionTrait;
    use EntityFactoryCreateTrait;
    use RtbCurrentGroupDeleteRepositoryCreateTrait;
    use RtbCurrentGroupReadRepositoryCreateTrait;
    use RtbCurrentGroupWriteRepositoryAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * For Choice-, Qty- Grouping: remove sold lots
     * For other groupings: remove all lots from group
     *
     * @param RtbCurrent $rtbCurrent
     * @param int $editorUserId
     * @return void
     */
    public function refreshGroupForRtbCurrent(RtbCurrent $rtbCurrent, int $editorUserId): void
    {
        if (in_array($rtbCurrent->LotGroup, [Constants\Rtb::GROUP_CHOICE, Constants\Rtb::GROUP_QUANTITY], true)) {
            $this->clearGroupFromSold($rtbCurrent->AuctionId);
            if ($this->countGroup($rtbCurrent->AuctionId) === 1) { // if only one lot left in group, it is not group anymore
                $this->removeFromGroup($rtbCurrent->AuctionId, $rtbCurrent->LotItemId);
            }
            if ($this->countGroup($rtbCurrent->AuctionId) === 0) {
                $rtbCurrent->LotGroup = '';
                $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $editorUserId);
            }
        } else {
            $this->clearGroup($rtbCurrent->AuctionId);
            $rtbCurrent->LotGroup = '';
            $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $editorUserId);
        }
    }

    /**
     * Remove all grouped lots for auction
     *
     * @param int $auctionId auction.id
     * @return void
     */
    public function clearGroup(int $auctionId): void
    {
        $this->createRtbCurrentGroupDeleteRepository()
            ->filterAuctionId($auctionId)
            ->delete();
    }

    /**
     * Remove sold lots from group of auction
     *
     * @param int $auctionId auction.id
     * @return void
     */
    public function clearGroupFromSold(int $auctionId): void
    {
        $rows = $this->createAuctionLotItemReadRepository()
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::$wonLotStatuses)
            ->select(['lot_item_id'])
            ->loadRows();
        $lotItemIds = ArrayCast::arrayColumnInt($rows, 'lot_item_id');
        if ($lotItemIds) {
            $this->createRtbCurrentGroupDeleteRepository()
                ->filterAuctionId($auctionId)
                ->filterLotItemId($lotItemIds)
                ->delete();
        }
    }

    /**
     * Add lot to group for auction
     *
     * @param int $auctionId auction.id
     * @param int $lotItemId lot_item.id
     * @param int $editorUserId
     * @return void
     */
    public function addToGroup(int $auctionId, int $lotItemId, int $editorUserId): void
    {
        $rtbGroup = $this->createEntityFactory()->rtbCurrentGroup();
        $rtbGroup->AuctionId = $auctionId;
        $rtbGroup->LotItemId = $lotItemId;
        $this->getRtbCurrentGroupWriteRepository()->saveWithModifier($rtbGroup, $editorUserId);
    }

    /**
     * Remove lot from group of auction
     *
     * @param int $auctionId auction.id
     * @param int $lotItemId lot_item.id
     * @return void
     */
    public function removeFromGroup(int $auctionId, int $lotItemId): void
    {
        $this->createRtbCurrentGroupDeleteRepository()
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->delete();
    }

    /**
     * Get grouped lots
     *
     * @param int $auctionId auction.id
     * @param int|null $limit max count of lots must be returned
     * @param int[] $skipLotItemIds
     * @return RtbCurrentGroup[]
     */
    public function loadGroups(int $auctionId, ?int $limit = null, array $skipLotItemIds = []): array
    {
        return $this->createRtbCurrentGroupReadRepository()
            ->filterAuctionId($auctionId)
            ->limit($limit)
            ->orderById()
            ->skipLotItemId($skipLotItemIds)
            ->loadEntities();
    }

    /**
     * @param int $auctionId
     * @return int
     */
    public function countGroup(int $auctionId): int
    {
        return $this->createRtbCurrentGroupReadRepository()
            ->filterAuctionId($auctionId)
            ->count();
    }

    /**
     * Get the next lot in the group of auction
     *
     * @param int $auctionId auction.id
     * @param int $lotItemId lot_item.id
     * @return AuctionLotItem
     */
    public function loadGroupNextLot(int $auctionId, int $lotItemId): ?AuctionLotItem
    {
        $availableLotStatusList = implode(',', Constants\Lot::$availableLotStatuses);
        $nextLotsWhereClause = $this->createAuctionLotOrderMysqlQueryBuilder()->buildNextLotsWhereClause();
        $query = <<<SQL
SELECT ali.*
FROM auction_lot_item ali2, auction_lot_item ali
INNER JOIN rtb_current_group AS rcg
    ON ali.auction_id = rcg.auction_id
    AND ali.lot_item_id = rcg.lot_item_id
    AND ali.lot_status_id in ({$availableLotStatusList})
WHERE
    ali.auction_id = {$this->escape($auctionId)} 
    AND ali2.lot_status_id IN ({$availableLotStatusList})
    AND ali2.lot_item_id = {$this->escape($lotItemId)} 
    AND ali2.auction_id = ali.auction_id
    AND {$nextLotsWhereClause}
ORDER BY rcg.id
LIMIT 1
SQL;
        $dbResult = $this->query($query);
        $auctionLots = AuctionLotItem::InstantiateDbResult($dbResult);
        $auctionLot = $auctionLots ? $auctionLots[0] : null;
        return $auctionLot;
    }

    /**
     * Get the previous lot in the group of auction
     *
     * @param int $auctionId auction.id
     * @param int $lotItemId lot_item.id
     * @return AuctionLotItem
     */
    public function loadGroupPrevLot(int $auctionId, int $lotItemId): ?AuctionLotItem
    {
        $availableLotStatusList = implode(',', Constants\Lot::$availableLotStatuses);
        $prevLotsWhereClause = $this->createAuctionLotOrderMysqlQueryBuilder()->buildPrevLotsWhereClause();
        $query = <<<SQL
SELECT ali.*
FROM auction_lot_item ali2, auction_lot_item ali
INNER JOIN rtb_current_group AS rcg ON
    ali.auction_id = rcg.auction_id
    AND ali.lot_item_id = rcg.lot_item_id
    AND ali.lot_status_id in ({$availableLotStatusList})
WHERE
    ali.auction_id = {$this->escape($auctionId)} 
    AND ali2.lot_status_id IN ({$availableLotStatusList})
    AND ali2.lot_item_id = {$this->escape($lotItemId)} 
    AND ali2.auction_id = ali.auction_id
    AND {$prevLotsWhereClause} 
ORDER BY rcg.id DESC
LIMIT 1;
SQL;
        $dbResult = $this->query($query);
        $auctionLots = AuctionLotItem::InstantiateDbResult($dbResult);
        $auctionLot = $auctionLots ? $auctionLots[0] : null;
        return $auctionLot;
    }

    /**
     * Order and return passed lot ids
     *
     * @param int[] $lotItemIds
     * @param int $auctionId
     * @return int[]
     */
    public function loadOrderedIds(array $lotItemIds, int $auctionId): array
    {
        $rows = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb(true)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemIds)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->orderByOrderAndLotFullNumber()
            ->select(['lot_item_id'])
            ->loadRows();
        $orderedLotItemIds = ArrayCast::arrayColumnInt($rows, 'lot_item_id');
        return $orderedLotItemIds;
    }
}
