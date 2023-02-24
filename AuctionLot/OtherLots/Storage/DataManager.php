<?php
/**
 * SAM-3506: Other lots on responsive lot detail page needs to be refactored
 * @see https://bidpath.atlassian.net/browse/SAM-3506
 *
 * @copyright   2018 Bidpath, Inc.
 * @author      Maxim Lyubetskiy
 * @package     com.swb.sam2
 * @version     SVN: $Id$
 * @since       Oct 20, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\OtherLots\Storage;

use AuctionLotItem;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class DataManager
 */
class DataManager extends CustomizableClass implements DataManagerInterface
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->enableReadOnlyDb(true);
        return $this;
    }

    /**
     * @param int $auctionId
     * @return AuctionLotItem[]
     */
    public function getAllOrderedAuctionLotIds(int $auctionId): iterable
    {
        $rows = $this->createAuctionLotItemReadRepository()
            ->considerOptionHideUnsoldLots()
            ->enableReadOnlyDb(true)
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->orderByOrderAndLotFullNumber()
            ->select(
                [
                    'ali.id',
                    'ali.lot_item_id'
                ]
            )
            ->loadRows();
        $order = 0;
        foreach ($rows as &$row) {
            $row['order'] = ++$order;
        }
        return $rows;
    }

    /**
     * @param int $auctionId
     * @param int $amount
     * @param int $offset
     * @return AuctionLotItem[]
     */
    public function getAuctionLots(int $auctionId, int $amount, int $offset = 0): iterable
    {
        $auctionLots = $this->createAuctionLotItemReadRepository()
            ->considerOptionHideUnsoldLots()
            ->enableReadOnlyDb(true)
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->limit($amount)
            ->offset($offset)
            ->orderByOrderAndLotFullNumber()
            ->loadEntities();
        return $auctionLots;
    }

    /**
     * @param array $ids
     * @return AuctionLotItem[]
     */
    public function getAuctionLotsByIds(array $ids): iterable
    {
        if (empty($ids)) {
            return [];
        }
        $auctionLots = $this->createAuctionLotItemReadRepository()
            ->considerOptionHideUnsoldLots()
            ->enableReadOnlyDb(true)
            ->filterId($ids)
            ->loadEntities();
        return $auctionLots;
    }

    /**
     * @param int $auctionId
     * @return int
     */
    public function countAllAuctionLots(int $auctionId): int
    {
        $count = $this->createAuctionLotItemReadRepository()
            ->considerOptionHideUnsoldLots()
            ->enableReadOnlyDb(true)
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->count();
        return $count;
    }
}
