<?php
/**
 * General repository for AuctionLotItemChanges entity
 *
 * SAM-3653: AuctionLotItem repository https://bidpath.atlassian.net/browse/SAM-3653
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           31 Mar, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * Usage example:
 * $auctionLotItemChangesRepository = \Sam\Storage\ReadRepository\Entity\AuctionLotItemChanges\AuctionLotItemChangesReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterAuctionId($auctionIds);
 * $isFound = $auctionLotItemChangesRepository->exist();
 * $count = $auctionLotItemChangesRepository->count();
 * $item = $auctionLotItemChangesRepository->loadEntity();
 * $items = $auctionLotItemChangesRepository->loadEntities();
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionLotItemChanges;

/**
 * Class AuctionLotItemChangesReadRepository
 */
class AuctionLotItemChangesReadRepository extends AbstractAuctionLotItemChangesReadRepository
{
    protected array $joins = [
        'auction_lot_item' => 'JOIN auction_lot_item ali ON ali.lot_item_id = alich.lot_item_id AND ali.auction_id = alich.auction_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join `auction_lot_item` table
     * @return static
     */
    public function joinAuctionLotItem(): static
    {
        $this->join('auction_lot_item');
        return $this;
    }

    /**
     * Left join auction_lot_item table
     * Define filtering by ali.id
     * @param int|int[] $auctionLotIds
     * @return static
     */
    public function joinAuctionLotItemFilterId(int|array|null $auctionLotIds): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('ali.id', $auctionLotIds);
        return $this;
    }
}
