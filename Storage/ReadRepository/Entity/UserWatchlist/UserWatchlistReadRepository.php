<?php
/**
 * General repository for UserWatchlist entity
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           14 Feb, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\UserWatchlist;

/**
 * Class UserWatchlistReadRepository
 * @package Sam\Storage\ReadRepository\Entity\UserWatchlist
 */
class UserWatchlistReadRepository extends AbstractUserWatchlistReadRepository
{
    protected array $joins = [
        'auction' => 'JOIN auction a ON a.id = uw.auction_id',
        'currency' => 'JOIN currency curr ON curr.id = a.currency',
        'lot_item' => 'JOIN lot_item li ON li.id = uw.lot_item_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function groupByCurrencySign(): static
    {
        $this->join('currency');
        $this->group('curr.sign');
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
     * Left join lot_item table
     * @return static
     */
    public function joinLotItem(): static
    {
        $this->join('lot_item');
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
     * join auction table and filter a.account_id
     * @param int|int[] $accountId
     * @return static
     */
    public function joinAuctionFilterAccountId(int|array|null $accountId): static
    {
        $this->joinAuction();
        $this->filterArray('a.account_id', $accountId);
        return $this;
    }

    public function joinCurrency(): static
    {
        $this->join('currency');
        return $this;
    }

    public function joinCurrencyFilterCurrencySign(string $currencySign): static
    {
        $this->joinCurrency();
        $this->filterArray('curr.sign', $currencySign);
        return $this;
    }

    public function joinLotItemFilterAccountId(int|array|null $accountId): static
    {
        $this->joinLotItem();
        $this->filterArray('li.account_id', $accountId);
        return $this;
    }

    /**
     * Join lot item table
     * @param bool|bool[] $active
     * @return static
     */
    public function joinLotItemFilterActive(bool|array|null $active): static
    {
        $this->joinLotItem();
        $this->filterArray('li.active', $active);
        return $this;
    }

    /**
     * Define filtering by ii.winning_bidder_id
     * @param int|int[] $winningBidderIds
     * @return static
     */
    public function joinLotItemFilterWinningBidderId(int|array|null $winningBidderIds): static
    {
        $this->joinLotItem();
        $this->filterArray('li.winning_bidder_id', $winningBidderIds);
        return $this;
    }
}
