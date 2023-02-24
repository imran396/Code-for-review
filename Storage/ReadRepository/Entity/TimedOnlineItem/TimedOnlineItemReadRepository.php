<?php
/**
 * Repository for TimedOnlineItem entity
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

namespace Sam\Storage\ReadRepository\Entity\TimedOnlineItem;

/**
 * Class TimedOnlineItemReadRepository
 * @package Sam\Storage\ReadRepository\Entity\TimedOnlineItem
 */
class TimedOnlineItemReadRepository extends AbstractTimedOnlineItemReadRepository
{
    protected array $joins = [
        'account' => 'JOIN account acc ON acc.id = a.account_id',
        'auction' => 'JOIN auction a ON a.id = toi.auction_id',
        // 'auction_lot_item' => 'JOIN auction_lot_item AS ali '
        //     . 'ON ali.auction_id = toi.auction_id AND ali.lot_item_id = toi.lot_item_id',
        'lot_item' => 'JOIN lot_item li ON li.id = toi.lot_item_id',
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
        $this->joinAuction();
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
     * Left join lot_item table
     * @return static
     */
    public function joinLotItem(): static
    {
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
