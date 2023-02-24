<?php
/**
 * General repository for AuctionLotItem entity
 *
 * SAM-3653: AuctionLotItem repository https://bidpath.atlassian.net/browse/SAM-3653
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           02 Mar, 2017
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
 * $auctionLotItemRepository = \Sam\Storage\Repository\AuctionLotItemReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterLotItemId($lotItemIds);
 * $isFound = $auctionLotItemRepository->exist();
 * $count = $auctionLotItemRepository->count();
 * $item = $auctionLotItemRepository->loadEntity();
 * $items = $auctionLotItemRepository->loadEntities();
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionLotItem;

use Sam\Core\Constants;
use Sam\Date\CurrentDateTrait;
use Sam\Lot\Search\Query\Build\Helper\LotSearchQueryBuilderHelperCreateTrait;

/**
 * Class AuctionLotItemReadRepository
 */
class AuctionLotItemReadRepository extends AbstractAuctionLotItemReadRepository
{
    use CurrentDateTrait;
    use LotSearchQueryBuilderHelperCreateTrait;

    private const JOIN_AUCTION_LOT_ITEM_BY_AUCTION = 'auction_lot_item_by_auction';
    private const JOIN_BID_TRANSACTION_BY_CURRENT_BID = 'bid_transaction_by_current_bid';

    protected array $joins = [
        'absentee_bid' => 'JOIN absentee_bid ab ON ab.auction_id = ali.auction_id AND ab.lot_item_id = ali.lot_item_id',
        'account' => 'JOIN account acc ON acc.id = ali.account_id',
        'auction' => 'JOIN auction a ON a.id = ali.auction_id',
        'auction_cache' => 'JOIN auction_cache ac ON ac.auction_id = ali.auction_id',
        self::JOIN_AUCTION_LOT_ITEM_BY_AUCTION => 'JOIN auction_lot_item AS ali2 ON ali2.auction_id = ali.auction_id',
        'auction_lot_item_cache' => 'JOIN auction_lot_item_cache AS alic ON alic.auction_lot_item_id = ali.id',
        'auction_lot_item_changes' => 'JOIN auction_lot_item_changes alich ON alich.lot_item_id = ali.lot_item_id AND alich.auction_id = ali.auction_id',
        'bid_transaction' => 'JOIN bid_transaction bt ON bt.auction_id = ali.auction_id AND bt.lot_item_id = ali.lot_item_id',
        self::JOIN_BID_TRANSACTION_BY_CURRENT_BID => 'JOIN bid_transaction AS bt_cb ON bt_cb.id = ali.current_bid_id',
        'consignor_user' => 'JOIN user uc ON uc.id = li.consignor_id',
        'consignor_user_info' => 'JOIN user_info uci ON uci.user_id = li.consignor_id',
        'currency' => 'JOIN currency curr ON curr.id = a.currency',
        'current_auction_bidder' => 'JOIN auction_bidder cab ON cab.user_id = alic.current_bidder_id AND cab.auction_id = ali.auction_id',
        'current_bidder_user' => 'JOIN user cbu ON cbu.id = alic.current_bidder_id',
        'current_bidder_user_info' => 'JOIN user_info cbui ON cbui.user_id = alic.current_bidder_id',
        'entity_sync' => 'JOIN entity_sync esync ON (ali.id = esync.entity_id AND esync.entity_type = ' . Constants\EntitySync::TYPE_AUCTION_LOT_ITEM . ')',
        'lot_item' => 'JOIN lot_item li ON li.id = ali.lot_item_id',
        'lot_item_category' => 'JOIN lot_item_category lic ON lic.lot_item_id = ali.lot_item_id',
        'lot_category' => 'JOIN lot_category lc ON lc.id = lic.lot_category_id',
        'rtb_current_group' => 'JOIN rtb_current_group AS rcg ON rcg.auction_id = ali.auction_id AND rcg.lot_item_id = ali.lot_item_id',
        'second_auction_bidder' => 'JOIN auction_bidder sab ON sab.user_id = alic.second_bidder_id AND sab.auction_id = ali.auction_id',
        'second_bidder_user' => 'JOIN user sbu ON sbu.id = alic.second_bidder_id',
        'second_bidder_user_info' => 'JOIN user_info sbui ON sbui.user_id = alic.second_bidder_id',
        'timed_online_item' => 'JOIN timed_online_item AS toi ON toi.auction_id = ali.auction_id AND toi.lot_item_id = ali.lot_item_id',
        'lot_item_cust_data' => 'JOIN lot_item_cust_data AS licd ON licd.lot_item_id = ali.lot_item_id',
        'lot_item_cust_field' => 'JOIN lot_item_cust_field AS licf ON licd.lot_item_cust_field_id = licf.id',
        'auction_dynamic' => 'JOIN auction_dynamic AS adyn ON ali.auction_id = adyn.auction_id',
        'consignor_commission' => 'JOIN consignor_commission_fee ccf_cc ON ccf_cc.id = ali.consignor_commission_id',
        'consignor_sold_fee' => 'JOIN consignor_commission_fee ccf_csf ON ccf_csf.id = ali.consignor_sold_fee_id',
        'consignor_unsold_fee' => 'JOIN consignor_commission_fee ccf_cuf ON ccf_cuf.id = ali.consignor_unsold_fee_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Consider auction's option "Hide Unsold Lots". When enabled, skip lots with "Unsold" status. (SAM-2877)
     * @return $this
     */
    public function considerOptionHideUnsoldLots(): static
    {
        $lsUnsold = Constants\Lot::LS_UNSOLD;
        $this->joinAuction();
        $this->inlineCondition("IF(a.hide_unsold_lots, ali.lot_status_id != {$lsUnsold}, true)");
        return $this;
    }

    /**
     * This is default joining to 'auction_lot_item' table by ali2.auction_id
     * no need to call this method
     * @return static
     */
    public function defineAuctionLotItemJoinByAuctionId(): static
    {
        $this->joins['auction_lot_item'] = $this->joins[self::JOIN_AUCTION_LOT_ITEM_BY_AUCTION];
        $this->setJoins($this->joins);
        return $this;
    }

    /**
     * Define filtering by lot start date (a.start_date, alic.start_date) in the past
     * @return static
     */
    public function filterTimedLotStartDateInPast(): static
    {
        $currentDateUtcIso = $this->getCurrentDateUtcIso();
        $queryBuilderHelper = $this->createLotSearchQueryBuilderHelper();
        $dateExpression = $queryBuilderHelper->getTimedLotStartDateExpr();
        $this->filterInequality($dateExpression, $currentDateUtcIso, '<');
        return $this;
    }

    /**
     * Define filtering by lot start date in the past and lot end date (a.end_date) in the future
     * @return static
     */
    public function filterTimedLotEndDateInFuture(): static
    {
        $currentDateUtcIso = $this->getCurrentDateUtcIso();
        $queryBuilderHelper = $this->createLotSearchQueryBuilderHelper();
        $startDateExpression = $queryBuilderHelper->getTimedLotStartDateExpr();
        $this->filterInequality($startDateExpression, $currentDateUtcIso, '<');
        $endDateExpression = $queryBuilderHelper->getTimedLotEndDateExpr();
        $this->filterInequality($endDateExpression, $currentDateUtcIso, '>');
        return $this;
    }

    /**
     * Group by li.id
     * @return static
     */
    public function joinLotItemGroupById(): static
    {
        $this->joinLotItem();
        $this->group('li.id');
        return $this;
    }

    public function innerJoinAuction(): static
    {
        $this->innerJoin('auction');
        return $this;
    }

    public function innerJoinAuctionFilterAuctionStatusId(int|array|null $auctionStatusIds): static
    {
        $this->innerJoinAuction();
        $this->filterArray('a.auction_status_id', $auctionStatusIds);
        return $this;
    }

    public function innerJoinAbsenteeBid(): static
    {
        $this->innerJoin('absentee_bid');
        return $this;
    }

    public function innerJoinBidTransaction(): static
    {
        $this->innerJoin('bid_transaction');
        return $this;
    }

    /**
     * inner join rtb_current_group table
     * @return static
     */
    public function innerJoinRtbCurrentGroup(): static
    {
        $this->innerJoin('rtb_current_group');
        return $this;
    }

    /**
     * inner join timed_online_item table
     * @return static
     */
    public function joinTimedOnlineItem(): static
    {
        $this->join('timed_online_item');
        return $this;
    }

    /**
     * Join `absentee_bid` table
     * @return static
     */
    public function joinAbsenteeBid(): static
    {
        $this->join('absentee_bid');
        return $this;
    }

    /**
     * @return static
     */
    public function joinAuctionLotItem(): static
    {
        if (empty($this->joins['auction_lot_item'])) {
            $this->defineAuctionLotItemJoinByAuctionId();
        }
        $this->join('auction_lot_item');
        return $this;
    }

    /**
     * Join `auction_lot_item` table by auction_id and filter results by lot_item_id
     * @param int|int[]|null $lotItemId
     * @return static
     */
    public function joinAuctionLotItemFilterLotItemId(int|array|null $lotItemId): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('ali2.lot_item_id', $lotItemId);
        return $this;
    }

    /**
     * Define filtering by ab.user_id joined from `absentee_bid` table
     * @param int|int[] $userId
     * @return static
     */
    public function joinAbsenteeBidFilterUserId(int|array|null $userId): static
    {
        $this->joinAbsenteeBid();
        $this->filterArray('ab.user_id', $userId);
        return $this;
    }

    /**
     * Join 'account' table
     * @return static
     */
    public function joinAccount(): static
    {
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
     * Left join auction_dynamic table
     * @return static
     */
    public function joinAuctionDynamic(): static
    {
        $this->join('auction_dynamic');
        return $this;
    }

    /**
     * Left join auction_cache table
     * @return static
     */
    public function joinAuctionCache(): static
    {
        $this->join('auction_cache');
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
     * Define filtering by auction.auction_type
     * @param string|string[] $auctionType
     * @return static
     */
    public function joinAuctionFilterAuctionType(string|array|null $auctionType): static
    {
        $this->joinAuction();
        $this->filterArray('a.auction_type', $auctionType);
        return $this;
    }

    /**
     * Left join auction_lot_item_cache table
     * @return static
     */
    public function joinAuctionLotItemCache(): static
    {
        $this->join('auction_lot_item_cache');
        return $this;
    }

    /**
     * Left join `auction_lot_item_changes` table
     * @return static
     */
    public function joinAuctionLotItemChanges(): static
    {
        $this->join('auction_lot_item_changes');
        return $this;
    }

    /**
     * Join auction_lot_item_changes table
     * Define filtering by alich.user_id
     * @param int|int[] $userIds
     * @return static
     */
    public function joinAuctionLotItemChangesFilterUserId(int|array|null $userIds): static
    {
        $this->joinAuctionLotItemChanges();
        $this->filterArray('alich.user_id', $userIds);
        return $this;
    }

    /**
     * Left join `entity_sync` table
     * @return static
     */
    public function joinAuctionLotItemSync(): static
    {
        $this->join('entity_sync');
        return $this;
    }

    /**
     * Define filtering by esync.sync_namespace_id
     * @param int|int[] $syncNamespaceId
     * @return static
     */
    public function joinAuctionLotItemSyncFilterSyncNamespaceId(int|array|null $syncNamespaceId): static
    {
        $this->joinAuctionLotItemSync();
        $this->filterArray('esync.sync_namespace_id', $syncNamespaceId);
        return $this;
    }

    /**
     * Define filtering by esync.key
     * @param string|string[] $key
     * @return static
     */
    public function joinAuctionLotItemSyncFilterKey(string|array|null $key): static
    {
        $this->joinAuctionLotItemSync();
        $this->filterArray('esync.`key`', $key);
        return $this;
    }

    /**
     * Left join `bid_transaction` table
     * @return static
     */
    public function joinBidTransaction(): static
    {
        $this->join('bid_transaction');
        return $this;
    }

    /**
     * Define filtering by bt.user_id joined from `bid_transaction` table
     * @param int|int[] $userId
     * @return static
     */
    public function joinBidTransactionFilterUserId(int|array|null $userId): static
    {
        $this->joinBidTransaction();
        $this->filterArray('bt.user_id', $userId);
        return $this;
    }

    /**
     * Define filtering by bt.deleted
     * @param bool|bool[] $deleted
     * @return static
     */
    public function joinBidTransactionFilterDeleted(bool|array|null $deleted): static
    {
        $this->joinBidTransaction();
        $this->filterArray('bt.deleted', $deleted);
        return $this;
    }

    /**
     * Join currency table
     * @return static
     */
    public function joinCurrency(): static
    {
        $this->joinAuction();
        $this->join('currency');
        return $this;
    }

    /**
     * Left join lot_category table
     * @return static
     */
    public function joinLotCategory(): static
    {
        $this->joinLotItemCategory();
        $this->join('lot_category');
        return $this;
    }

    /**
     * Define filtering by lc.active
     * @param bool|bool[] $status
     * @return static
     */
    public function joinLotCategoryFilterActive(bool|array|null $status): static
    {
        $this->joinLotCategory();
        $this->filterArray('lc.active', $status);
        return $this;
    }

    /**
     * Left join auction_bidder table
     * @return static
     */
    public function joinCurrentAuctionBidder(): static
    {
        $this->joinAuctionLotItemCache();
        $this->join('current_auction_bidder');
        return $this;
    }

    /**
     * Left join user table
     * @return static
     */
    public function joinCurrentBidderUser(): static
    {
        $this->joinAuctionLotItemCache();
        $this->join('current_bidder_user');
        return $this;
    }

    /**
     * Left join user_info table
     * @return static
     */
    public function joinCurrentBidderUserInfo(): static
    {
        $this->joinAuctionLotItemCache();
        $this->join('current_bidder_user_info');
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
     * Inner join lot_item table
     * Supersedes any left join of the lot_item table
     * @return static
     */
    public function innerJoinLotItem(): static
    {
        $this->innerJoin('lot_item');
        return $this;
    }

    /**
     * Define filtering by li.active joined from `lot_item` table
     * @param bool|bool[] $status
     * @return static
     */
    public function joinLotItemFilterActive(bool|array|null $status): static
    {
        $this->innerJoinLotItem();
        $this->filterArray('li.active', $status);
        return $this;
    }

    /**
     * Define filtering by lot_item.item_num with inner joined `lot_item` table
     * @param int|int[] $itemNum
     * @return $this
     */
    public function innerJoinLotItemFilterItemNum(int|array $itemNum): static
    {
        $this->innerJoin('lot_item');
        $this->filterArray('li.item_num', $itemNum);
        return $this;
    }

    /**
     * Define filtering by lot_item.item_num_ext with inner joined `lot_item` table
     * @param string|string[] $itemNumExt
     * @return $this
     */
    public function innerJoinLotItemFilterItemNumExt(string|array $itemNumExt): static
    {
        $this->innerJoin('lot_item');
        $this->filterArray('li.item_num_ext', $itemNumExt);
        return $this;
    }

    /**
     * Define filtering by li.consignor_id
     * @param int|int[] $consignorUserIds
     * @return static
     */
    public function joinLotItemFilterConsignorId(int|array|null $consignorUserIds): static
    {
        $this->joinLotItem();
        $this->filterArray('li.consignor_id', $consignorUserIds);
        return $this;
    }

    /**
     * Left join lot_item_category table
     * @return static
     */
    public function joinLotItemCategory(): static
    {
        $this->join('lot_item_category');
        return $this;
    }

    /**
     * join lot_item table
     * Define $changes, that should be skipped while checking
     * @param string|string[] $changes
     * @return static
     */
    public function joinLotItemSkipChanges(string|array|null $changes): static
    {
        $this->joinLotItem();
        $this->skipArray('li.changes', $changes);
        return $this;
    }

    /**
     * Left join auction_bidder table
     * @return static
     */
    public function joinSecondAuctionBidder(): static
    {
        $this->joinAuctionLotItemCache();
        $this->join('second_auction_bidder');
        return $this;
    }

    /**
     * Left join user table
     * @return static
     */
    public function joinSecondBidderUser(): static
    {
        $this->joinAuctionLotItemCache();
        $this->join('second_bidder_user');
        return $this;
    }

    /**
     * Left join user_info table
     * @return static
     */
    public function joinSecondBidderUserInfo(): static
    {
        $this->joinAuctionLotItemCache();
        $this->join('second_bidder_user_info');
        return $this;
    }

    /**
     * Left join user table
     * @return static
     */
    public function joinConsignorUser(): static
    {
        $this->joinLotItem();
        $this->join('consignor_user');
        return $this;
    }

    /**
     * @param int|int[] $statusId
     * @return static
     */
    public function joinConsignorUserFilterUserStatusId(int|array|null $statusId): static
    {
        $this->joinConsignorUser();
        $this->filterArray('uc.user_status_id', $statusId);
        return $this;
    }

    /**
     * Left join user_info table
     * @return static
     */
    public function joinConsignorUserInfo(): static
    {
        $this->joinConsignorUser();
        $this->join('consignor_user_info');
        return $this;
    }

    /**
     * Left join lot_item_cust_data table
     * @return $this
     */
    public function joinLotItemCustData(): static
    {
        $this->join('lot_item_cust_data');
        return $this;
    }

    /**
     * @param string|string[] $text
     * @return $this
     */
    public function joinLotItemCustDataFilterText(string|array|null $text): static
    {
        $this->joinLotItemCustData();
        $this->filterArray('licd.text', $text);
        return $this;
    }

    /**
     * Left join lot_item_cust_filed table
     * @return $this
     */
    public function joinLotItemCustField(): static
    {
        $this->join('lot_item_cust_field');
        return $this;
    }

    /**
     * @param int|int[] $id
     * @return $this
     */
    public function joinLotItemCustFieldFilterId(int|array|null $id): static
    {
        $this->joinLotItemCustField();
        $this->filterArray('licf.id', $id);
        return $this;
    }

    /**
     * Define ordering by li.item_num
     * @param bool $ascending
     * @return static
     */
    public function joinLotItemOrderByNum(bool $ascending = true): static
    {
        $this->joinLotItem();
        $this->order('li.item_num', $ascending);
        return $this;
    }

    /**
     * Define ordering by li.item_num_ext
     * @param bool $ascending
     * @return static
     */
    public function joinLotItemOrderByNumExt(bool $ascending = true): static
    {
        $this->joinLotItem();
        $this->order('li.item_num_ext', $ascending);
        return $this;
    }

    /**
     * Define ordering by alic.end_date
     * @param bool $ascending
     * @return static
     */
    public function joinAuctionLotItemCacheOrderByEndDate(bool $ascending = true): static
    {
        $this->joinAuctionLotItemCache();
        $this->order('alic.end_date', $ascending);
        return $this;
    }

    /**
     * Define ordering by ali.order, ali.lot_num_prefix, ali.lot_num, ali.lot_num_ext
     * @param bool $ascending
     * @return static
     */
    public function orderByOrderAndLotFullNumber(bool $ascending = true): static
    {
        return $this->orderByOrder($ascending)
            ->orderByLotNumPrefix($ascending)
            ->orderByLotNum($ascending)
            ->orderByLotNumExt($ascending);
    }

    /**
     * Join lot item and order by li.date_sold
     * @param bool $ascending
     * @return static
     */
    public function joinLotItemOrderByDateSold(bool $ascending = true): static
    {
        $this->joinLotItem();
        $this->order('li.date_sold', $ascending);
        return $this;
    }

    /**
     * Join lot item and order by li.id
     * @param bool $ascending
     * @return static
     */
    public function joinLotItemOrderById(bool $ascending = true): static
    {
        $this->joinLotItem();
        $this->order('li.id', $ascending);
        return $this;
    }

    /**
     * Join lot item and order by li.name
     * @param bool $ascending
     * @return static
     */
    public function joinLotItemOrderByName(bool $ascending = true): static
    {
        $this->joinLotItem();
        $this->order('li.name', $ascending);
        return $this;
    }

    /**
     * Apply filtering by Piecemeal Role (unite conditions that define this role status invariant)
     * SAM-6621: Enrich entity model for lot bulk group feature
     * @param int|array|null $bulkMasterIds
     * @return $this
     */
    public function filterByPiecemealRole(int|array|null $bulkMasterIds): static
    {
        $this->filterBulkMasterId($bulkMasterIds);
        $this->filterIsBulkMaster(false);
        return $this;
    }

    /**
     * Apply filtering by Master Role (unite conditions that define this role status invariant)
     * SAM-6621: Enrich entity model for lot bulk group feature
     * @return $this
     */
    public function filterByMasterRole(): static
    {
        $this->filterBulkMasterId(null);
        $this->filterIsBulkMaster(true);
        return $this;
    }

    /**
     * Define filtering by li.item_num joined from `lot_item` table
     * @param int|int[] $itemNumbers
     * @return static
     */
    public function joinLotItemFilterItemNum(int|array|null $itemNumbers): static
    {
        $this->joinLotItem();
        $this->filterArray('li.item_num', $itemNumbers);
        return $this;
    }

    /**
     * Define filtering by li.item_num_ext joined from `lot_item` table
     * @param string|string[] $itemNumberExtensions
     * @return static
     */
    public function joinLotItemFilterItemNumExt(string|array|null $itemNumberExtensions): static
    {
        $this->joinLotItem();
        $this->filterArray('li.item_num_ext', $itemNumberExtensions);
        return $this;
    }

    /**
     * Define ordering by alic.start_date
     * @param bool $ascending
     * @return static
     */
    public function joinAuctionLotItemCacheOrderByStartDate(bool $ascending = true): static
    {
        $this->joinAuctionLotItemCache();
        $this->order('alic.start_date', $ascending);
        return $this;
    }

    /**
     * Inner join `bid_transaction` table
     * @return static
     */
    public function joinBidTransactionByCurrentBid(): static
    {
        $this->join(self::JOIN_BID_TRANSACTION_BY_CURRENT_BID);
        return $this;
    }

    /**
     * @return static
     */
    public function joinBidTransactionByCurrentBidFilterBidGreaterOrEqualThanReservePrice(): static
    {
        $this->joinBidTransactionByCurrentBid();
        $this->joinLotItem();
        $this->inlineCondition('bt_cb.bid >= IFNULL(li.reserve_price, 0)');
        return $this;
    }

    /**
     * @param bool|bool[]|null[] $deleted
     * @return $this
     */
    public function joinBidTransactionByCurrentBidFilterDeleted(bool|array|null $deleted): static
    {
        $this->joinBidTransactionByCurrentBid();
        $this->filterArray('bt_cb.deleted', $deleted);
        return $this;
    }

    public function joinConsignorCommission(): static
    {
        $this->join('consignor_commission');
        return $this;
    }

    public function joinConsignorCommissionFilterActive(bool|array $active): static
    {
        $this->joinConsignorCommission();
        $this->filterArray('ccf_cc.active', $active);
        return $this;
    }

    public function joinConsignorSoldFee(): static
    {
        $this->join('consignor_sold_fee');
        return $this;
    }

    public function joinConsignorSoldFeeFilterActive(bool|array $active): static
    {
        $this->joinConsignorSoldFee();
        $this->filterArray('ccf_csf.active', $active);
        return $this;
    }

    public function joinConsignorUnsoldFee(): static
    {
        $this->join('consignor_unsold_fee');
        return $this;
    }

    public function joinConsignorUnsoldFeeFilterActive(bool|array $active): static
    {
        $this->joinConsignorUnsoldFee();
        $this->filterArray('ccf_cuf.active', $active);
        return $this;
    }
}
