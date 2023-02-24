<?php
/**
 *
 * General repository for AuctionLotItemCache entity
 *
 * SAM-3642: AuctionLotItemCache repository
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Pavel Mitkovskiy <pmitkovskiy@samauctionsoftware.com>
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           08 Mar, 2017
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
 * $auctionLotItemCacheRepository = \Sam\Storage\ReadRepository\Entity\AuctionLotItemCache\AuctionLotItemCacheReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterAuctionLotItemId($auctionLotItemIds);
 * $isFound = $auctionLotItemCacheRepository->exist();
 * $count = $auctionLotItemCacheRepository->count();
 * $item = $auctionLotItemCacheRepository->loadEntity();
 * $items = $auctionLotItemCacheRepository->loadEntities();
 *
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionLotItemCache;

/**
 * Class AuctionLotItemCacheReadRepository
 * @package Sam\Storage\ReadRepository\Entity\AuctionLotItemCache
 */
class AuctionLotItemCacheReadRepository extends AbstractAuctionLotItemCacheReadRepository
{
    protected array $joins = [
        'account' => 'JOIN account acc ON acc.id = ali.account_id',
        'auction' => 'JOIN auction a ON a.id = ali.auction_id',
        'auction_lot_item' => 'JOIN auction_lot_item ali ON alic.auction_lot_item_id = ali.id',
        'lot_item' => 'JOIN lot_item li ON li.id = ali.lot_item_id',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join 'account' table
     * @return static
     */
    public function joinAccount(): static
    {
        $this->joinAuctionLotItem();
        $this->join('account');
        return $this;
    }

    /**
     * Define filtering by account.active
     * @param bool|bool[] $active
     * @return static
     */
    public function joinAccountFilterActive(bool|array|null $active): static
    {
        $this->joinAccount();
        $this->filterArray('acc.active', $active);
        return $this;
    }

    /**
     * Left join auction table
     * @return static
     */
    public function joinAuction(): static
    {
        $this->joinAuctionLotItem();
        $this->join('auction');
        return $this;
    }

    /**
     * Define filtering by auction.auction_status_id
     * @param int|int[] $auctionStatusIds
     * @return static
     */
    public function joinAuctionFilterAuctionStatusId(int|array|null $auctionStatusIds): static
    {
        $this->joinAuction();
        $this->filterArray('a.auction_status_id', $auctionStatusIds);
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
     * @param int|int[] $auctionIds
     * @return static
     */
    public function joinAuctionLotItemFilterAuctionId(int|array|null $auctionIds): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('ali.auction_id', $auctionIds);
        return $this;
    }

    /**
     * Join auction_lot_item table
     * Define filtering by ali.lot_item_id
     * @param int|int[] $lotItemId
     * @return static
     */
    public function joinAuctionLotItemFilterLotItemId(int|array|null $lotItemId): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('ali.lot_item_id', $lotItemId);
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

    /**
     * Define filtering by ali.account_id
     * @param int|int[] $accountId
     * @return static
     */
    public function joinAuctionLotItemFilterAccountId(int|array|null $accountId): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('ali.account_id', $accountId);
        return $this;
    }

    /**
     * Left join lot_item table
     * @return static
     */
    public function joinLotItem(): static
    {
        $this->joinAuctionLotItem();
        $this->join('lot_item');
        return $this;
    }

    /**
     * Define filtering by li.active joined from `lot_item` table
     * @param bool|bool[] $status
     * @return static
     */
    public function joinLotItemFilterActive(bool|array|null $status): static
    {
        $this->joinLotItem();
        $this->filterArray('li.active', $status);
        return $this;
    }
}
