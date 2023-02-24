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

namespace Sam\MySearch\Load\Query;

use DateTime;
use LotItemCustField;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Search\Query\Build\Helper\LotSearchCustomFieldQueryBuilderHelperCreateTrait;
use Sam\Lot\Search\Query\Build\Helper\LotSearchQueryBuilderHelperCreateTrait;
use Sam\Lot\Search\Query\LotSearchQuery;

/**
 * Class LotSearchQueryBuilder
 * @package Sam\MySearch\Load
 */
class MySearchResultQueryBuilder extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use DbConnectionTrait;
    use LotSearchCustomFieldQueryBuilderHelperCreateTrait;
    use LotSearchQueryBuilderHelperCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param MySearchResultQueryCriteria $criteria
     * @param LotItemCustField[] $lotCustomFields Used custom fields
     * @return LotSearchQuery
     */
    public function build(MySearchResultQueryCriteria $criteria, array $lotCustomFields = []): LotSearchQuery
    {
        $searchEngine = $this->cfg()->get('core->search->index->type');
        $query = LotSearchQuery::new()
            ->construct('auction_lot_item', 'ali');

        $currentDateUtc = $this->getCurrentDateUtc();
        $query = $this->applySelect($query, $criteria, $currentDateUtc);
        $query = $this->applyJoins($query, $criteria, $currentDateUtc);
        $query = $this->applyOrderBy($query, $criteria, $lotCustomFields);

        $query = $this->applyGeneralWhereConditions($query, $currentDateUtc);
        $query = $this->applyAuctionTypeFilter($query, $criteria);
        $query = $this->applySkipAuctionLotsFilterExpression($query, $criteria);
        $query = $this->applyStatusFilterExpression($query, $criteria);
        $query = $this->applyTimedFilter($query, $criteria);

        $queryBuilderHelper = $this->createLotSearchQueryBuilderHelper();
        $query = $queryBuilderHelper->applyAccountFilter($query, $criteria);
        $query = $queryBuilderHelper->applyAccessFilter($query, $criteria);
        $query = $queryBuilderHelper->applyFeaturedFilter($query, $criteria);
        $query = $queryBuilderHelper->applyConsignorFilter($query, $criteria);

        $query = $queryBuilderHelper->applySearchTermFilter($query, $criteria, $searchEngine, $lotCustomFields);
        $query = $queryBuilderHelper->applyCategoryFilter($query, $criteria);
        $query = $queryBuilderHelper->applyPriceFilter($query, $criteria);
        $query = $this->createLotSearchCustomFieldQueryBuilderHelper()
            ->applyCustomFieldFilter($query, $criteria, $lotCustomFields);
        return $query;
    }

    /**
     * @param LotSearchQuery $query
     * @param MySearchResultQueryCriteria $criteria
     * @param DateTime $currentDateUtc
     * @return LotSearchQuery
     */
    protected function applySelect(
        LotSearchQuery $query,
        MySearchResultQueryCriteria $criteria,
        DateTime $currentDateUtc
    ): LotSearchQuery {
        $currentDateTimeIso = $currentDateUtc->format(Constants\Date::ISO);
        $currentDateTimeIsoEscaped = $this->escape($currentDateTimeIso);
        $currentDateIso = $currentDateUtc->format('Y-m-d') . ' 00:00:00';
        $userIdEscaped = $this->escape($criteria->userId);

        // @formatter:off
        $selectClauses = [
            "li.account_id AS account_id",
            "li.id AS id",
            "li.name AS name",
            "li.description AS lot_desc",
            "li.item_num AS item_num",
            "li.item_num_ext AS item_num_ext",
            "li.hammer_price AS hammer_price",
            "li.low_estimate AS low_est",
            "li.high_estimate AS high_est",
            "li.warranty AS warranty",

            "ali.id AS alid",
            "ali.lot_num_prefix AS num_prefix",
            "ali.lot_num AS num",
            "ali.lot_num_ext AS num_ext",
            "ali.quantity AS qty",
            "IF (ali.quantity_x_money = true,1,0) AS qty_x_money",
            "ali.lot_status_id AS lot_status",

            "alic.starting_bid_normalized AS starting_bid_normalized",
            "alic.current_bid AS current_bid",
            "alic.current_bidder_id AS current_bidder_id",
            "alic.current_max_bid AS current_max_bid",
            "alic.asking_bid AS asking_bid",
            "alic.bid_count AS bid_count",
            "UNIX_TIMESTAMP(" . $this->createLotSearchQueryBuilderHelper()->getLotStartDateExpr() . ")"
                . " - UNIX_TIMESTAMP({$currentDateTimeIsoEscaped}) AS seconds_before",
            "UNIX_TIMESTAMP(" . $this->createLotSearchQueryBuilderHelper()->getLotEndDateExpr() . ")"
                . " - UNIX_TIMESTAMP({$currentDateTimeIsoEscaped}) AS seconds_left",

            "a.id AS aid",
            "a.sale_num AS sale_num",
            "a.sale_num_ext AS sale_num_ext",
            "a.name AS auc_name",
            "a.auction_status_id AS auc_status",
            "a.auction_type AS auc_type",
            "a.event_type AS event_type",
            "a.start_date AS auc_st_dt",
            "a.end_date AS auc_en_dt",
            "IFNULL(adyn.extend_all_start_closing_date, a.start_closing_date) AS auction_start_closing_date",
            "a.timezone_id AS auc_tz",
            "a.stagger_closing AS stagger_closing",
            "IF(a.reverse, 1, 0) AS auc_reverse",
            "a.bidding_paused AS bidding_paused",

            "a.absentee_bids_display AS absentee_bids_display",
            "a.above_starting_bid AS above_starting_bid",
            //"a.above_reserve AS above_reserve",
            //"a.notify_absentee_bidders AS notify_absentee_bidders",
            "a.reserve_not_met_notice AS reserve_not_met_notice",
            "a.reserve_met_notice AS reserve_met_notice",
            //"a.no_lower_maxbid AS no_lower_maxbid",
            //"a.suggested_starting_bid AS suggested_starting_bid",
            "a.extend_all AS extend_all",

            "toi.id AS toiid",
            $this->createLotSearchQueryBuilderHelper()->getLotStartDateExpr() . " AS lot_st_dt",
            $this->createLotSearchQueryBuilderHelper()->getLotEndDateExpr() . " AS lot_en_dt",
            "ali.buy_now_amount AS buy_amount",
            "IF (toi.no_bidding, 1, 0) AS no_bidding",
            "IF (toi.best_offer, 1, 0) AS best_offer",

            "IF(a.auction_type = '" . Constants\Auction::TIMED . "', " .
            "a.end_date, " .
            "a.start_closing_date) AS auc_end_date",

            "ali.lot_status_id AS status_id",

            "(SELECT IF (a.auction_type = '" . Constants\Auction::TIMED . "', (IF (((SELECT max_bid FROM bid_transaction WHERE " .
                "auction_id = ali.auction_id AND lot_item_id = li.id " .
                "AND (deleted IS NULL OR deleted = false) " .
                "AND user_id = {$userIdEscaped} " .
                "AND user_id > 0 " .
                "ORDER BY id DESC LIMIT 1)) is null, NULL",
                "(SELECT max_bid FROM bid_transaction WHERE " .
                "auction_id = ali.auction_id AND lot_item_id = li.id " .
                "AND (deleted IS NULL OR deleted = false) " .
                "AND user_id = {$userIdEscaped} " .
                "AND user_id > 0 " .
                "ORDER BY id DESC LIMIT 1))), (IFNULL(ab.max_bid, 0)))) AS max_bid",

            "IF (a.auction_type = '" . Constants\Auction::TIMED . "', " .
                "IF (alic.start_date >= {$currentDateTimeIsoEscaped}, " .
                    Constants\Auction::STATUS_UPCOMING . ", " .
                    "IF (alic.end_date > {$currentDateTimeIsoEscaped}, " .
                        "IF (ali.lot_status_id = " . Constants\Lot::LS_ACTIVE . ", " .
                            Constants\Auction::STATUS_IN_PROGRESS . ", " .
                            Constants\Auction::STATUS_CLOSED .
                        "), " .
                        Constants\Auction::STATUS_CLOSED .
                    ") " .
                "), " .
                "IF (alic.start_date >= '{$currentDateIso}' AND a.auction_status_id = " . Constants\Auction::AS_ACTIVE . ", " .
                    Constants\Auction::STATUS_UPCOMING . ", " .
                    "IF (a.auction_status_id IN (" . Constants\Auction::AS_STARTED . ", " . Constants\Auction::AS_PAUSED . "), " .
                        "IF (ali.lot_status_id = " . Constants\Lot::LS_ACTIVE . ", " .
                            Constants\Auction::STATUS_IN_PROGRESS . "," .
                            Constants\Auction::STATUS_CLOSED  .
                        "), " .
                        Constants\Auction::STATUS_CLOSED .
                    ") " .
                ") " .
            ")  AS auc_status_order",

            "IF (li.hammer_price IS NOT NULL, li.hammer_price, " .
                "(SELECT IF (a.auction_type = '" . Constants\Auction::TIMED . "', " .
                    "(IF (ali.current_bid_id IS NULL," .
                        "li.starting_bid," .
                    "(SELECT bid FROM bid_transaction WHERE id = ali.current_bid_id)))," .
                "(li.low_estimate)))) as price",

            "acc.company_name AS acct_company_name",
            "acc.name AS acct_name"
        ];
        // @formatter:on
        $query->addSelect($selectClauses);
        return $query;
    }

    /**
     * @param LotSearchQuery $query
     * @param MySearchResultQueryCriteria $criteria
     * @param DateTime $currentDateUtc
     * @return LotSearchQuery
     */
    protected function applyJoins(
        LotSearchQuery $query,
        MySearchResultQueryCriteria $criteria,
        DateTime $currentDateUtc
    ): LotSearchQuery {
        $currentDateTimeIso = $currentDateUtc->format(Constants\Date::ISO);
        $currentDateTimeIsoEscaped = $this->escape($currentDateTimeIso);
        $userIdEscaped = $this->escape($criteria->userId);
        $auctioneerCond = '';
        if ($criteria->auctioneerId !== null) {
            $auctioneerCond = "AND a.auction_auctioneer_id = " . $this->escape($criteria->auctioneerId) . " ";
        }

        $joins = [
            "INNER JOIN lot_item AS li ON ali.lot_item_id = li.id",
            "INNER JOIN account AS acc ON acc.id = li.account_id AND acc.active",
            "INNER JOIN auction AS a ON ali.auction_id = a.id AND a.publish_date <= {$currentDateTimeIsoEscaped} AND a.auction_status_id in (" . implode(
                ',',
                Constants\Auction::$availableAuctionStatuses
            ) . ") {$auctioneerCond}",
            "LEFT JOIN auction_dynamic AS adyn ON adyn.auction_id = ali.auction_id",
            "LEFT JOIN bid_transaction AS bt ON bt.id = ali.current_bid_id",
            "LEFT JOIN timed_online_item AS toi ON toi.auction_id = ali.auction_id AND toi.lot_item_id = li.id",
            "LEFT JOIN absentee_bid AS ab ON ab.auction_id = ali.auction_id AND ab.user_id = $userIdEscaped AND ab.lot_item_id = li.id",
            "LEFT JOIN auction_lot_item_cache alic ON alic.auction_lot_item_id = ali.id"
        ];
        $query->addJoin($joins);
        $query->addJoinCount($joins);
        return $query;
    }

    /**
     * @param LotSearchQuery $query
     * @param DateTime $currentDateUtc
     * @return LotSearchQuery
     */
    protected function applyGeneralWhereConditions(LotSearchQuery $query, DateTime $currentDateUtc): LotSearchQuery
    {
        $currentDateTimeIso = $currentDateUtc->format(Constants\Date::ISO);
        $currentDateTimeIsoEscaped = $this->escape($currentDateTimeIso);

        $lotStatusActive = Constants\Lot::LS_ACTIVE;
        $lotStatusIds = implode(',', Constants\Lot::$availableLotStatuses);
        $where2 = <<<SQL
IF(a.`only_ongoing_lots`, ali.lot_status_id = '{$lotStatusActive}', ali.lot_status_id IN ({$lotStatusIds}))
SQL;

        $auctionTypeTimed = Constants\Auction::TIMED;
        $lotStartDateExpr = $this->createLotSearchQueryBuilderHelper()->getLotStartDateExpr();
        $where3 = <<<SQL
(IF (((a.not_show_upcoming_lots = 1 OR a.only_ongoing_lots = 1) AND a.auction_type ='{$auctionTypeTimed}'), {$lotStartDateExpr} < {$currentDateTimeIsoEscaped}, 1=1)) 
SQL;
        $whereConditions = [
            'li.active = true',
            $where2,
            $where3
        ];
        $query->addWhere($whereConditions);
        return $query;
    }

    /**
     * @param LotSearchQuery $query
     * @param MySearchResultQueryCriteria $criteria
     * @param LotItemCustField[] $lotCustomFields
     * @return LotSearchQuery
     */
    protected function applyOrderBy(
        LotSearchQuery $query,
        MySearchResultQueryCriteria $criteria,
        array $lotCustomFields = []
    ): LotSearchQuery {
        // Define ordering options
        $sort = $criteria->orderBy;
        $distanceAlias = '';
        if ($sort === 'distance') {
            $distanceAlias = $this->createLotSearchQueryBuilderHelper()->detectOrderByDistanceAlias($lotCustomFields);
            if (!$distanceAlias) {
                $sort = '';
            }
        }
        $orderBy = match ($sort) {
            'newest' => 'a.created_on desc, a.start_closing_date, ali.lot_item_id',
            'timeleft' => 'auc_status_order, '
                . 'CASE auc_status_order WHEN 1 THEN auc_end_date END ASC, '
                . 'CASE auc_status_order WHEN 2 THEN alic.start_date END ASC, '
                . 'CASE auc_status_order WHEN 3 THEN auc_end_date END DESC, '
                . 'ali.`order` ASC, a.start_closing_date desc, a.sale_num, ali.lot_num_prefix, ali.lot_num, ali.lot_num_ext',
            'highest' => 'price desc, a.start_closing_date, ali.lot_item_id',
            'lowest' => 'price, a.start_closing_date, ali.lot_item_id',
            'distance' => $distanceAlias,
            default => 'a.start_closing_date desc, ali.lot_item_id',
        };
        $query->addOrderBy($orderBy);
        return $query;
    }

    /**
     * @param LotSearchQuery $query
     * @param MySearchResultQueryCriteria $criteria
     * @return LotSearchQuery
     */
    protected function applyTimedFilter(LotSearchQuery $query, MySearchResultQueryCriteria $criteria): LotSearchQuery
    {
        if (
            $criteria->auctionType
            && !in_array(Constants\Auction::TIMED, $criteria->auctionType, false)
        ) {
            return $query;
        }
        $timedCond = '';
        if ($criteria->isRegularBidding) {
            $timedCond .= ' OR (toi.no_bidding IS NULL or toi.no_bidding = false) ';
        }
        if ($criteria->hasBuyNow) {
            $timedCond .= ' OR ali.buy_now_amount > 0 ';
        }
        if ($criteria->hasBestOffer) {
            $timedCond .= ' OR toi.best_offer = true ';
        }
        if ($timedCond) {
            $whereCondition = '(SELECT IF (' . $this->escape(Constants\Auction::TIMED) .
                ' = a.auction_type, (toi.id > 0 and (0 ' . $timedCond . ')), 1)) > 0 ';
            $query->addWhere($whereCondition);
        }
        return $query;
    }

    /**
     * Filtering by auction type
     *
     * @param LotSearchQuery $query
     * @param MySearchResultQueryCriteria $criteria
     * @return LotSearchQuery
     */
    private function applyAuctionTypeFilter(LotSearchQuery $query, MySearchResultQueryCriteria $criteria): LotSearchQuery
    {
        $auctionTypeCond = [];
        foreach ($criteria->auctionType as $auctionType) {
            $auctionTypeCond[] = "a.auction_type = " . $this->escape($auctionType);
        }
        if ($auctionTypeCond) {
            $whereCondition = '(' . implode(' OR ', $auctionTypeCond) . ') ';
            $query->addWhere($whereCondition);
        }
        return $query;
    }

    /**
     * @param LotSearchQuery $query
     * @param MySearchResultQueryCriteria $criteria
     * @return LotSearchQuery
     */
    private function applyStatusFilterExpression(LotSearchQuery $query, MySearchResultQueryCriteria $criteria): LotSearchQuery
    {
        if ($criteria->isExcludeClosed) {
            $whereConditions = [
                "ali.lot_status_id = '" . Constants\Lot::LS_ACTIVE . "'",
                "a.auction_status_id in (" . implode(',', Constants\Auction::$openAuctionStatuses) . ")"
            ];
        } else {
            $whereConditions = [
                "ali.lot_status_id in (" . implode(',', Constants\Lot::$availableLotStatuses) . ")",
                "a.auction_status_id in (" . implode(',', Constants\Auction::$availableAuctionStatuses) . ")"
            ];
        }
        $query->addWhere($whereConditions);
        return $query;
    }

    /**
     * @param LotSearchQuery $query
     * @param MySearchResultQueryCriteria $criteria
     * @return LotSearchQuery
     */
    private function applySkipAuctionLotsFilterExpression(LotSearchQuery $query, MySearchResultQueryCriteria $criteria): LotSearchQuery
    {
        if (!empty($criteria->skipAuctionLotIds)) {
            $csvExclude = implode(',', $criteria->skipAuctionLotIds);
            $query->addWhere("ali.id NOT IN({$csvExclude})");
        }
        return $query;
    }
}
