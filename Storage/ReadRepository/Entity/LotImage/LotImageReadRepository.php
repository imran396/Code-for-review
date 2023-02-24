<?php
/**
 * General repository for LotImage entity
 *
 * SAM-3685:Image related repositories https://bidpath.atlassian.net/browse/SAM-3685
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           24 Apr, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of users filtered by criteria
 * $lotImageRepository = \Sam\Storage\ReadRepository\Entity\LotImage\LotImageReadRepository::new()
 *     ->filterId($ids)          // single value passed as argument
 *     ->filterActive($active)   // array passed as argument
 *     ->skipId([$myId]);        // search avoiding these user ids
 * $isFound = $lotImageRepository->exist();
 * $count = $lotImageRepository->count();
 * $lotImages = $lotImageRepository->loadEntities();
 *
 * // Sample2. Load single user
 * $lotImageRepository = \Sam\Storage\ReadRepository\Entity\LotImage\LotImageReadRepository::new()
 *     ->filterId(1);
 * $lotImage = $lotImageRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\LotImage;

/**
 * Class LotImageReadRepository
 */
class LotImageReadRepository extends AbstractLotImageReadRepository
{
    protected array $joins = [
        'lot_item' => 'JOIN lot_item li ON li.id = limg.lot_item_id',
        'auction_lot_item' => 'JOIN auction_lot_item ali ON ali.lot_item_id = limg.lot_item_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join `lot_item` table
     * @return static
     */
    public function joinLotItem(): static
    {
        $this->join('lot_item');
        return $this;
    }

    /**
     * Join lot_item table
     * Define filtering by li.account_id
     * @param int|int[] $accountIds
     * @return static
     */
    public function joinLotItemFilterAccountId(int|array|null $accountIds): static
    {
        $this->joinLotItem();
        $this->filterArray('li.account_id', $accountIds);
        return $this;
    }

    /**
     * Join lot_item table
     * Define filtering by li.account_id
     * @param bool $active
     * @return static
     */
    public function joinLotItemFilterActive(bool|array|null $active): static
    {
        $this->joinLotItem();
        $this->filterArray('li.active', $active);
        return $this;
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
     * Join auction_lot_item table
     * Define filtering by ali.auction_id
     * @param int|int[] $auctionId
     * @return static
     */
    public function joinAuctionLotItemFilterAuctionId(int|array|null $auctionId): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('ali.auction_id', $auctionId);
        return $this;
    }

    /**
     * Join auction_lot_item table
     * Define filtering by ali.lot_status_id
     * @param int|int[] $lotStatusId
     * @return static
     */
    public function joinAuctionLotItemFilterLotStatusId(int|array|null $lotStatusId): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('ali.lot_status_id', $lotStatusId);
        return $this;
    }
}

