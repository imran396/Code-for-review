<?php
/**
 * Repository for TimedOnlineOfferBid entity
 *
 * SAM-3721: Timed item related repositories https://bidpath.atlassian.net/browse/SAM-3721
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           25 Apr, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\TimedOnlineOfferBid;

/**
 * Class TimedOnlineOfferBidReadRepository
 * @package Sam\Storage\ReadRepository\Entity\TimedOnlineOfferBid
 */
class TimedOnlineOfferBidReadRepository extends AbstractTimedOnlineOfferBidReadRepository
{
    protected array $joins = [
        'account' => 'JOIN account acc ON ali.account_id = acc.id',
        'auction' => 'JOIN auction a ON ali.auction_id = a.id',
        'auction_lot_item' => 'JOIN auction_lot_item ali ON toob.auction_lot_item_id = ali.id',
        'lot_item' => 'JOIN lot_item li ON ali.lot_item_id = li.id',
        'user' => 'JOIN user u ON toob.user_id = u.id',
    ];

    /**
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
     * Join auction table
     * @return static
     */
    public function joinAuction(): static
    {
        $this->join('auction');
        return $this;
    }

    /**
     * Join auction table and filter by a.auction_status_id
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
     * @param int|int[] $lotItemIds
     * @return static
     */
    public function joinAuctionLotItemFilterLotItemId(int|array|null $lotItemIds): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('ali.lot_item_id', $lotItemIds);
        return $this;
    }

    /**
     * Define filtering by ali.lot_status_id
     * @param int|int[] $lotStatusIds
     * @return static
     */
    public function joinAuctionLotItemFilterLotStatusId(int|array|null $lotStatusIds): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('ali.lot_status_id', $lotStatusIds);
        return $this;
    }

    /**
     * Join `lot_item` table
     * @return static
     */
    public function joinLotItem(): static
    {
        $this->joinAuctionLotItem();
        $this->join('lot_item');
        return $this;
    }

    /**
     * Define filtering by li.active
     * @param bool|bool[] $active
     * @return static
     */
    public function joinLotItemFilterActive(bool|array|null $active): static
    {
        $this->joinLotItem();
        $this->filterArray('li.active', $active);
        return $this;
    }
}
