<?php
/**
 * SAM-3690: Bidding related repositories https://bidpath.atlassian.net/browse/SAM-3690
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\AbsenteeBid;

use Sam\Bidder\AuctionBidder\Query\AuctionBidderQueryBuilderHelperCreateTrait;

/**
 * General repository for AbsenteeBid entity
 *
 * Class AbsenteeBidReadRepository
 * @package Sam\Storage\ReadRepository\Entity\AbsenteeBid
 */
class AbsenteeBidReadRepository extends AbstractAbsenteeBidReadRepository
{
    use AuctionBidderQueryBuilderHelperCreateTrait;

    protected array $joins = [
        'account' => 'JOIN account acc ON acc.id = a.account_id',
        'auction' => 'JOIN auction a ON a.id = ab.auction_id',
        'auction_bidder' => 'JOIN auction_bidder aub ON aub.auction_id = ab.auction_id AND aub.user_id = ab.user_id',
        'auction_lot_item' => 'JOIN auction_lot_item ali ON ali.auction_id = ab.auction_id AND ab.lot_item_id = ali.lot_item_id',
        'auction_lot_item_cache' => 'JOIN auction_lot_item_cache alic ON alic.auction_lot_item_id = ali.id',
        'lot_item' => 'JOIN lot_item li ON li.id = ab.lot_item_id',
        'user' => 'JOIN user u ON u.id = ab.user_id',
    ];

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
     * Join auction table
     * @return static
     */
    public function joinAuction(): static
    {
        $this->join('auction');
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
     * Join auction_lot_item table
     * Define filtering by CONCAT(ali.lot_num_prefix,ali.lot_num,ali.lot_num_ext)
     * @param string|string[] $lotPL
     * @return static
     */
    public function joinAuctionLotItemFilterLotPl(string|array|null $lotPL): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('CONCAT(ali.lot_num_prefix,ali.lot_num,ali.lot_num_ext)', $lotPL);
        return $this;
    }

    /**
     * Join `auction_lot_item_cache` table
     * @return static
     */
    public function joinAuctionLotItemCache(): static
    {
        $this->joinAuctionLotItem();
        $this->join('auction_lot_item_cache');
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

    public function joinLotItemFilterAccountId(int|array|null $accountId): static
    {
        $this->joinLotItem();
        $this->filterArray('li.account_id', $accountId);
        return $this;
    }

    /**
     * Left join lot_item table and filter by li.active
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
     * Left join `user` table
     * @return static
     */
    public function joinUser(): static
    {
        $this->join('user');
        return $this;
    }

    /**
     * Left join `user` table and define filtering by u.user_status_id
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
     * Join `auction_bidder` table
     * @return static
     */
    public function joinAuctionBidder(): static
    {
        $this->join('auction_bidder');
        return $this;
    }

    /**
     * Left join 'auction_bidder' table and filter by approved aub
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

    public function innerJoinAuctionFilterAuctionStatusId(int|array|null $auctionStatusIds): static
    {
        $this->innerJoin('auction');
        $this->filterArray('a.auction_status_id', $auctionStatusIds);
        return $this;
    }

    public function innerJoinAuctionFilterAccountId(int|array|null $accountId): static
    {
        $this->innerJoin('auction');
        $this->filterArray('a.account_id', $accountId);
        return $this;
    }

    /**
     * @param bool $ascending
     * @return static
     */
    public function innerJoinAuctionLotItemOrderByLotFullNumber(bool $ascending = true): static
    {
        $this->innerJoin('auction_lot_item');
        $this->order('ali.lot_num_prefix', $ascending);
        $this->order('ali.lot_num', $ascending);
        $this->order('ali.lot_num_ext', $ascending);
        return $this;
    }

    public function innerJoinAuctionLotItemFilterLotStatusId(int|array|null $lotStatusIds): static
    {
        $this->innerJoin('auction_lot_item');
        $this->filterArray('ali.lot_status_id', $lotStatusIds);
        return $this;
    }
}
