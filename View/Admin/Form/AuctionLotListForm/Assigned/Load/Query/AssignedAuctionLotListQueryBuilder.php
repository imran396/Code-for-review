<?php
/**
 * SAM-6606: Refactoring classes in the \MySearch namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 08, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\Assigned\Load\Query;

use LotItemCustField;
use Sam\AuctionLot\Order\Query\AuctionLotOrderMysqlQueryBuilderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Search\Query\Build\Helper\LotSearchCustomFieldQueryBuilderHelperCreateTrait;
use Sam\Lot\Search\Query\Build\Helper\LotSearchQueryBuilderHelperCreateTrait;
use Sam\Lot\Search\Query\LotSearchQuery;

/**
 * Class AssignedAuctionLotListQueryBuilder
 * @package Sam\View\Admin\Form\AuctionLotListForm\Assigned\Load\Query
 */
class AssignedAuctionLotListQueryBuilder extends CustomizableClass
{
    use AuctionLotOrderMysqlQueryBuilderCreateTrait;
    use DbConnectionTrait;
    use LotSearchCustomFieldQueryBuilderHelperCreateTrait;
    use LotSearchQueryBuilderHelperCreateTrait;

    public const LOT_ITEM_KEYWORD_OPTION_INDEX = 0;
    public const LOT_ITEM_KEYWORD_OPTION_NAME = 1;
    public const LOT_ITEM_KEYWORD_OPTION_DESCRIPTION = 2;
    /** @var string[] */
    public const LOT_ITEM_KEYWORD_OPTION_NAMES = [
        self::LOT_ITEM_KEYWORD_OPTION_INDEX => 'Index',
        self::LOT_ITEM_KEYWORD_OPTION_NAME => 'Name',
        self::LOT_ITEM_KEYWORD_OPTION_DESCRIPTION => 'Description',
    ];

    /** @var string[] */
    protected array $resultFieldsMapping = [
        "account_id" => "ali.account_id",
        "bid_id" => "ali.current_bid_id",
        "auc_lot_id" => "ali.id",
        "lot_num" => "ali.lot_num",
        "lot_num_ext" => "ali.lot_num_ext",
        "lot_num_prefix" => "ali.lot_num_prefix",
        "lot_status_id" => "ali.lot_status_id",
        "order_num" => "ali.order",
        "group_id" => "ali.group_id",
        "lot_quantity" => "ali.quantity",
        "lot_quantity_scale" => "COALESCE(
            ali.quantity_digits, 
            li.quantity_digits, 
            (SELECT lc.quantity_digits
             FROM lot_category lc
               INNER JOIN lot_item_category lic ON lc.id = lic.lot_category_id
             WHERE lic.lot_item_id = li.id
               AND lc.active = 1
             ORDER BY lic.id
             LIMIT 1), 
            (SELECT seta.quantity_digits FROM setting_auction seta WHERE seta.account_id = li.account_id))",
        "bid_count" => "alic.bid_count",
        "current_bid" => "alic.current_bid",
        "current_max_bid" => "alic.current_max_bid",
        "current_bid_placed" => "alic.current_bid_placed",
        "lot_seo_url" => "alic.seo_url",
        "view_count" => "alic.view_count",
        "inv_id" => "ii.invoice_id",
        "auction_id_won" => "li.auction_id",
        "consignor_id" => "li.consignor_id",
        "hammer_price" => "li.hammer_price",
        "high_est" => "li.high_estimate",
        "lot_id" => "li.id",
        "internet_bid" => "li.internet_bid",
        "item_num" => "li.item_num",
        "item_num_ext" => "li.item_num_ext",
        "low_est" => "li.low_estimate",
        "lot_name" => "li.name",
        "reserve" => "li.reserve_price",
        "start_bid" => "li.starting_bid",
        "winning_bidder_id" => "li.winning_bidder_id",
        "consignor" => "uc.username",
        "consignor_company" => "uci.company_name",
        "consignor_email" => "uc.email",
        "created_username" => "ucr.username",
        "company_name" => "ui.company_name",
        "modified_username" => "umo.username",
        "winning_bidder" => "uw.username",
        "winning_bidder_company" => "uwi.company_name",
        "winning_bidder_email" => "uw.email",
        "high_bidder_company" => "uhbi.company_name",
        "high_bidder_email" => "uhb.email",
        "high_bidder_house" => "uhbp.house",
        "high_bidder_username" => "uhb.username",
        "high_bidder_user_id" => "uhb.id",
        "image_count" => "(SELECT COUNT(1) FROM lot_image WHERE lot_item_id = li.id)",  // SAM-3495
        "timezone_id" => "ali.timezone_id", //SAM-5873
        "lot_start_date" => "ali.start_date",
        "lot_end_date" => "ali.end_date",
        "rtb_current_lot_id" => "rtbc.lot_item_id",
        "rtb_current_lot_end_date" => "rtbc.lot_end_date",
        "rtb_current_pause_date" => "rtbc.pause_date",
        "rtb_current_lot_start_gap_time" => "rtbc.lot_start_gap_time",
        "rtb_current_extend_time" => "rtbc.lot_start_gap_time",
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
     * @param AssignedAuctionLotListQueryCriteria $criteria
     * @param LotItemCustField[] $lotCustomFields
     * @param LotItemCustField[] $selectCustomFields
     * @return LotSearchQuery
     */
    public function build(AssignedAuctionLotListQueryCriteria $criteria, array $lotCustomFields = [], array $selectCustomFields = []): LotSearchQuery
    {
        $query = LotSearchQuery::new()
            ->construct('auction_lot_item', 'ali');
        $query = $this->applySelect($query);
        $query = $this->applyJoins($query);
        $query = $this->applyGeneralWhereConditions($query, $criteria);
        $query = $this->applyKeywordFilter($query, $criteria);
        $query = $this->applyBiddingFilter($query, $criteria);

        $queryBuilderHelper = $this->createLotSearchQueryBuilderHelper();
        $query = $queryBuilderHelper->applyBidCountFilter($query, $criteria);
        $query = $queryBuilderHelper->applyCategoryFilter($query, $criteria);
        $query = $queryBuilderHelper->applyConsignorFilter($query, $criteria);
        $query = $queryBuilderHelper->applyItemNumFilter($query, $criteria);
        $query = $queryBuilderHelper->applyLotStatusFilter($query, $criteria);
        $query = $queryBuilderHelper->applyReserveMeetFilter($query, $criteria);
        $query = $queryBuilderHelper->applyWinningBidderFilter($query, $criteria);

        $customFieldQueryBuilderHelper = $this->createLotSearchCustomFieldQueryBuilderHelper();
        $query = $customFieldQueryBuilderHelper->applyCustomFieldFilter($query, $criteria, $lotCustomFields);
        $query = $customFieldQueryBuilderHelper->applyCustomFieldSelect($query, $selectCustomFields);

        $orderBy = $this->buildOrderByExpression($criteria);
        $query = $query->addOrderBy($orderBy);

        return $query;
    }

    /**
     * @return array
     */
    public function getResultFieldsMapping(): array
    {
        return $this->resultFieldsMapping;
    }

    /**
     * @param AssignedAuctionLotListQueryCriteria $criteria
     * @return string
     */
    public function buildOrderByExpression(AssignedAuctionLotListQueryCriteria $criteria): string
    {
        $expression = $criteria->orderBy ?? $this->createAuctionLotOrderMysqlQueryBuilder()->buildLotOrderClause();
        return $expression;
    }

    /**
     * @param LotSearchQuery $query
     * @return LotSearchQuery
     */
    protected function applySelect(LotSearchQuery $query): LotSearchQuery
    {
        $selectExpressions = [];
        foreach ($this->resultFieldsMapping as $alias => $selectExpr) {
            $selectExpressions[] = "{$selectExpr} AS {$alias}";
        }
        $query->addSelect($selectExpressions);
        return $query;
    }

    /**
     * @param LotSearchQuery $query
     * @return LotSearchQuery
     */
    protected function applyJoins(LotSearchQuery $query): LotSearchQuery
    {
        $joins = [
            // @formatter:off
            "INNER JOIN lot_item AS li ON ali.lot_item_id = li.id",
            "INNER JOIN account AS ac_lot ON li.account_id = ac_lot.id AND ac_lot.active",
            "LEFT JOIN auction AS auc ON ali.auction_id = auc.id",
            "LEFT JOIN auction_lot_item_cache alic ON ali.id = alic.auction_lot_item_id",
            "LEFT JOIN user_info AS ui ON li.winning_bidder_id = ui.user_id",
            "LEFT JOIN user AS uw ON li.winning_bidder_id = uw.id AND uw.user_status_id = " . Constants\User::US_ACTIVE,
            "LEFT JOIN user AS uc ON li.consignor_id = uc.id AND uc.user_status_id = " . Constants\User::US_ACTIVE,
            "LEFT JOIN `invoice_item` ii ON ii.lot_item_id = li.id AND ii.auction_id = li.auction_id AND ii.active AND ii.release = false" .
                " AND (SELECT invoice_status_id FROM invoice WHERE id = ii.invoice_id) IN (" . implode(',', Constants\Invoice::$openInvoiceStatuses) . ")",
            "LEFT JOIN `user` AS ucr ON ucr.id = li.created_by",
            "LEFT JOIN `user` AS umo ON umo.id = li.modified_by",
            "LEFT JOIN `user` AS uhb ON uhb.id = alic.current_bidder_id",
            "LEFT JOIN user_info AS uwi ON li.winning_bidder_id = uwi.user_id",
            "LEFT JOIN user_info AS uci ON li.consignor_id = uci.user_id",
            "LEFT JOIN user_info AS uhbi ON alic.current_bidder_id = uhbi.user_id",
            "LEFT JOIN bidder AS uhbp ON alic.current_bidder_id = uhbp.user_id",
            "LEFT JOIN rtb_current AS rtbc ON rtbc.auction_id = ali.auction_id AND rtbc.lot_item_id = li.id",
            // @formatter:on
        ];
        $query->addJoin($joins);
        $query->addJoinCount($joins);
        return $query;
    }

    /**
     * @param LotSearchQuery $query
     * @param AssignedAuctionLotListQueryCriteria $criteria
     * @return LotSearchQuery
     */
    protected function applyGeneralWhereConditions(LotSearchQuery $query, AssignedAuctionLotListQueryCriteria $criteria): LotSearchQuery
    {
        $whereConditions = [
            'li.active = true',
            "ali.auction_id = " . $this->escape($criteria->auctionId),
            "ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ")",

        ];
        $query->addWhere($whereConditions);
        return $query;
    }

    /**
     * Apply filtering by bidder who placed bids
     *
     * @param LotSearchQuery $query
     * @param AssignedAuctionLotListQueryCriteria $criteria
     * @return LotSearchQuery
     */
    protected function applyBiddingFilter(LotSearchQuery $query, AssignedAuctionLotListQueryCriteria $criteria): LotSearchQuery
    {
        $bidderUserId = $criteria->bidderId;
        $bidderUserIdEscaped = $this->escape($bidderUserId);
        if ($bidderUserId !== null) {
            // @formatter:off
           $whereCondition =
                "((SELECT COUNT(1) FROM absentee_bid " .
                    "WHERE user_id = {$bidderUserIdEscaped} " .
                    "AND lot_item_id = ali.lot_item_id " .
                    "AND auction_id = ali.auction_id " .
                    "AND max_bid > 0) > 0 " .
                "OR (SELECT COUNT(1) FROM bid_transaction " .
                    "WHERE auction_id = ali.auction_id " .
                    "AND lot_item_id = ali.lot_item_id  " .
                    "AND (deleted = false OR deleted IS NULL) " .
                    "AND user_id = {$bidderUserIdEscaped} ) > 0)";
            // @formatter:on
            $query->addWhere($whereCondition);
        }
        return $query;
    }

    /**
     * Apply filter by searchKey (default), lot_item.name, lot_item.description. Depends on keyword options select list
     * Possible Options:
     * 0 - Index
     * 1 - Lot Name (lot_item.name)
     * 2 - Lot Description (lot_item.description)
     *
     * @param LotSearchQuery $query
     * @param AssignedAuctionLotListQueryCriteria $criteria
     * @return LotSearchQuery
     */
    protected function applyKeywordFilter(LotSearchQuery $query, AssignedAuctionLotListQueryCriteria $criteria): LotSearchQuery
    {
        switch ($criteria->keywordSearchOption) {
            case self::LOT_ITEM_KEYWORD_OPTION_NAME;
                $query = $this->applyItemNameFilter($query, $criteria);
                break;
            case self::LOT_ITEM_KEYWORD_OPTION_DESCRIPTION:
                $query = $this->applyItemDescriptionFilter($query, $criteria);
                break;
            default:
                $criteria->isPrivateSearch = true;
                $query = $this->createLotSearchQueryBuilderHelper()
                    ->applySearchTermFilter($query, $criteria, Constants\Search::INDEX_FULLTEXT);
        }
        return $query;
    }

    /**
     * Apply filtering by item name
     *
     * @param LotSearchQuery $query
     * @param AssignedAuctionLotListQueryCriteria $criteria
     * @return LotSearchQuery
     */
    protected function applyItemNameFilter(LotSearchQuery $query, AssignedAuctionLotListQueryCriteria $criteria): LotSearchQuery
    {
        $lotItemName = $criteria->searchKey;
        if ($lotItemName) {
            $query->addWhere("li.name LIKE " . $this->escape('%' . $lotItemName . '%'));
        }
        return $query;
    }

    /**
     * Apply filtering by item description
     *
     * @param LotSearchQuery $query
     * @param AssignedAuctionLotListQueryCriteria $criteria
     * @return LotSearchQuery
     */
    protected function applyItemDescriptionFilter(LotSearchQuery $query, AssignedAuctionLotListQueryCriteria $criteria): LotSearchQuery
    {
        $lotItemDescription = $criteria->searchKey;
        if ($lotItemDescription) {
            $query->addWhere("li.description LIKE " . $this->escape('%' . $lotItemDescription . '%'));
        }
        return $query;
    }
}
