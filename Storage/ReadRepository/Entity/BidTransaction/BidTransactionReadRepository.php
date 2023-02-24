<?php
/**
 * General repository for BidTransaction entity
 *
 * SAM-3690: Bidding related repositories https://bidpath.atlassian.net/browse/SAM-3690
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

namespace Sam\Storage\ReadRepository\Entity\BidTransaction;

use Sam\Bidder\AuctionBidder\Query\AuctionBidderQueryBuilderHelperCreateTrait;

/**
 * Class BidTransactionReadRepository
 * @package Sam\Storage\ReadRepository\Entity\BidTransaction
 */
class BidTransactionReadRepository extends AbstractBidTransactionReadRepository
{
    use AuctionBidderQueryBuilderHelperCreateTrait;

    private const JOIN_LOT_ITEM_BY_AUCTION_LOT_ITEM = 'lot_item_by_auction_lot_item';
    private const JOIN_LOT_ITEM_BY_ID = 'lot_item_by_id';
    private const JOIN_LOT_ITEM_BY_ID_AND_AUCTION_AND_WINNING_BIDDER = 'lot_item_by_auction_and_winning_bidder';

    protected array $joins = [
        'account' => 'JOIN account acc ON acc.id = a.account_id',
        'auction' => 'JOIN auction a ON a.id = bt.auction_id',
        'auction_by_auction_lot_item' => 'JOIN auction a ON a.id = ali.auction_id',
        'auction_bidder' => 'JOIN auction_bidder aub ON aub.auction_id = bt.auction_id AND aub.user_id = bt.user_id',
        'auction_lot_item' => 'JOIN auction_lot_item ali ON bt.auction_id = ali.auction_id AND bt.lot_item_id = ali.lot_item_id',
        'currency' => 'JOIN currency curr ON curr.id = a.currency',
        self::JOIN_LOT_ITEM_BY_ID => 'JOIN lot_item li ON bt.lot_item_id = li.id',
        self::JOIN_LOT_ITEM_BY_ID_AND_AUCTION_AND_WINNING_BIDDER
        => 'JOIN lot_item AS li ON li.id = bt.lot_item_id and li.auction_id = bt.auction_id and bt.user_id=li.winning_bidder_id',
        self::JOIN_LOT_ITEM_BY_AUCTION_LOT_ITEM => 'JOIN lot_item li ON li.id = ali.lot_item_id',
        'user' => 'JOIN `user` u ON bt.user_id = u.id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function defineLotItemJoinByAuctionLotItem(): static
    {
        $this->joins['lot_item'] = $this->joins[self::JOIN_LOT_ITEM_BY_AUCTION_LOT_ITEM];
        $this->setJoins($this->joins);
        return $this;
    }

    /**
     * This is default joining to 'lot_item' table without checking sold auction and winning bidder,
     * no need to call this method
     * @return static
     */
    public function defineLotItemJoinById(): static
    {
        $this->joins['lot_item'] = $this->joins[self::JOIN_LOT_ITEM_BY_ID];
        $this->setJoins($this->joins);
        return $this;
    }

    /**
     * This is additional joining option with binding by auction and winning bidder
     * @return static
     */
    public function defineLotItemJoinByIdAndAuctionAndWinningBidder(): static
    {
        $this->joins['lot_item'] = $this->joins[self::JOIN_LOT_ITEM_BY_ID_AND_AUCTION_AND_WINNING_BIDDER];
        $this->setJoins($this->joins);
        return $this;
    }

    public function groupByCurrency(): static
    {
        $this->group('a.currency');
        return $this;
    }

    public function innerJoinAuction(): static
    {
        $this->innerJoin('auction_by_auction_lot_item');
        return $this;
    }

    public function innerJoinAuctionFilterAuctionStatusId(int|array|null $auctionStatusIds): static
    {
        $this->innerJoinAuction();
        $this->filterArray('a.auction_status_id', $auctionStatusIds);
        return $this;
    }

    public function innerJoinAuctionByAuctionIdFilterAccountId(int|array|null $accountId): static
    {
        $this->innerJoin('auction');
        $this->filterArray('a.account_id', $accountId);
        return $this;
    }

    public function innerJoinAuctionByAuctionIdFilterAuctionStatusId(int|array|null $auctionStatusIds): static
    {
        $this->innerJoin('auction');
        $this->filterArray('a.auction_status_id', $auctionStatusIds);
        return $this;
    }

    public function innerJoinAuctionLotItem(): static
    {
        $this->innerJoin('auction_lot_item');
        return $this;
    }

    public function innerJoinAuctionLotItemFilterAccountId(int|array|null $accountId): static
    {
        $this->innerJoinAuctionLotItem();
        $this->filterArray('ali.account_id', $accountId);
        return $this;
    }

    public function innerJoinAuctionLotItemFilterLotStatusId(int|array|null $lotStatusIds): static
    {
        $this->innerJoinAuctionLotItem();
        $this->filterArray('ali.lot_status_id', $lotStatusIds);
        return $this;
    }

    public function innerJoinCurrency(): static
    {
        $this->innerJoin('currency');
        return $this;
    }

    public function innerJoinCurrencyFilterCurrencySign(string $currencySign): static
    {
        $this->innerJoinCurrency();
        $this->filterArray('curr.sign', $currencySign);
        return $this;
    }

    public function innerJoinLotItem(): static
    {
        if (empty($this->joins['lot_item'])) {
            $this->defineLotItemJoinByAuctionLotItem();
        }
        $this->innerJoin('lot_item');
        return $this;
    }

    public function innerJoinLotItemFilterActive(bool|array|null $active): static
    {
        $this->innerJoinLotItem();
        $this->filterArray('li.active', $active);
        return $this;
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
     * Define filtering by acc.active
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
     * Left join `auction_bidder` table
     * @return static
     */
    public function joinAuctionBidder(): static
    {
        $this->join('auction_bidder');
        return $this;
    }

    /**
     * Left join 'auction_bidder' table and filter approved aub
     * @param bool $isApproved
     * @return static
     */
    public function joinAuctionBidderFilterApproved(bool $isApproved): static
    {
        $this->joinAuctionBidder();
        $queryBuilderHelper = $this->createAuctionBidderQueryBuilderHelper();
        $this->inlineCondition(
            $isApproved
                ? $queryBuilderHelper->makeApprovedBidderWhereClause()
                : $queryBuilderHelper->makeUnApprovedBidderWhereClause()
        );
        return $this;
    }

    /**
     * Left join 'auction_bidder' table and skip aub.bidder_num values
     * @param string|string[] $bidderNum
     * @return static
     */
    public function joinAuctionBidderSkipBidderNum(string|array|null $bidderNum): static
    {
        $this->joinAuctionBidder();
        $this->skipArray('aub.bidder_num', $bidderNum);
        return $this;
    }

    public function joinAuctionFilterAccountId(int|array|null $accountId): static
    {
        $this->joinAuction();
        $this->filterArray('a.account_id', $accountId);
        return $this;
    }

    /**
     * Define filtering by a.auction_status_id
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

    public function joinCurrency(): static
    {
        $this->join('currency');
        return $this;
    }

    public function joinCurrencyFilterCurrencySign(string $currencySign): static
    {
        $this->join('currency');
        $this->filterArray('curr.sign', $currencySign);
        return $this;
    }

    /**
     * Join `lot_item` table
     * @return static
     */
    public function joinLotItem(): static
    {
        if (empty($this->joins['lot_item'])) {
            $this->defineLotItemJoinById();
        }
        $this->join('lot_item');
        return $this;
    }

    public function joinLotItemFilterAccountId(int|array|null $accountId): static
    {
        $this->joinLotItem();
        $this->filterArray('li.account_id', $accountId);
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

    /**
     * @param $internetBid
     * @return static
     */
    public function joinLotItemFilterInternetBidGreater($internetBid): static
    {
        $this->joinLotItem();
        $this->filterInequality('li.internet_bid', $internetBid, '>');
        return $this;
    }

    /**
     * @param float|float[]|null $hammerPrice
     * @return static
     */
    public function joinLotItemSkipHammerPrice(float|array|null $hammerPrice): static
    {
        $this->joinLotItem();
        $this->skipArray('li.hammer_price', $hammerPrice);
        return $this;
    }

    /**
     * Left join `user` table
     * @return static
     */
    public function joinUser(): static
    {
        $this->join('user');
        return $this;
    }

    /**
     * Left join user, filter u.user_status_id
     * @param int|int[] $userStatusId
     * @return static
     */
    public function joinUserFilterUserStatusId(int|array|null $userStatusId): static
    {
        $this->joinUser();
        $this->filterArray('u.user_status_id', $userStatusId);
        return $this;
    }

    /**
     * @param int|int[] $flag
     * @return static
     */
    public function joinUserSkipFlag(int|array|null $flag): static
    {
        $this->joinUser();
        $this->skipArray('u.flag', $flag);
        return $this;
    }
}
