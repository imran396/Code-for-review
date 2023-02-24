<?php
/**
 * SAM-6606: Refactoring classes in the \MySearch namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 06, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Search\Query\Build\Helper;

use LotItemCustField;
use Sam\Application\Access\Auction\AuctionAccessCheckerQueryBuilderHelperCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Model\LotItem\SellInfo\LotSellInfoPureChecker;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Search\Query\Build\Helper\Internal\UserAccessRoleProviderCreateTrait;
use Sam\Lot\Search\Query\LotSearchQuery;
use Sam\Lot\Search\Query\LotSearchQueryCriteria;
use Sam\SearchIndex\Engine\Entity\LotItem\LotItemEntitySearchQueryBuilderCreateTrait;
use Sam\SearchIndex\Engine\Fulltext\FulltextSearchQueryBuilderCreateTrait;
use Sam\SearchIndex\SearchIndexManagerCreateTrait;

/**
 * Class LotSearchQueryBuilderHelper
 * @package Sam\Lot\Search\Query
 */
class LotSearchQueryBuilderHelper extends CustomizableClass
{
    use AuctionAccessCheckerQueryBuilderHelperCreateTrait;
    use DbConnectionTrait;
    use FulltextSearchQueryBuilderCreateTrait;
    use LotCategoryLoaderAwareTrait;
    use LotItemEntitySearchQueryBuilderCreateTrait;
    use SearchIndexManagerCreateTrait;
    use UserAccessRoleProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Add select and from clauses required for ordering by recent auction
     * SAM-1566: Sort by recent auction
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @return LotSearchQuery
     */
    public function applyOrderByRecentAuction(LotSearchQuery $query, LotSearchQueryCriteria $criteria): LotSearchQuery
    {
        $isOrderByRecentAuction = $criteria->orderBy === LotSearchQueryCriteria::ORDER_BY_RECENT_AUCTION;
        $isAscending = $criteria->isAscendingOrder;
        if ($isOrderByRecentAuction) {
            $defaultLotDateForSortByRecentAuction = $isAscending ? '2100-01-01' : '1900-01-01';
            // @formatter:off
            $selectExpr = [];
            $selectExpr[] =
                 "IFNULL("
                    . "IF(a.auction_type = '" . Constants\Auction::TIMED . "', "
                        . "IFNULL(" . $this->getTimedLotEndDateExpr() . ", a.end_date), "
                        . "IFNULL(alic.start_date, a.start_closing_date)), "
                    . $this->escape($defaultLotDateForSortByRecentAuction)
                . ") AS orderby_recentauction";
            $selectExpr[] = "IFNULL(ali.`order`, CONCAT(li.item_num, li.item_num_ext)) AS orderby_recentauction_secondary";

            $join =
                'LEFT JOIN auction_lot_item ali '
                    . 'ON ali.lot_item_id = li.id '
                    . 'AND ali.lot_status_id IN (' . implode(', ', Constants\Lot::$availableLotStatuses) . ') '
                    . 'AND ((li.auction_id IS NOT NULL AND ali.auction_id = li.auction_id) '
                        . 'OR (li.auction_id IS NULL AND ali.id = '
                            . '(SELECT ali_ord_ra.id FROM auction_lot_item ali_ord_ra '
                            . 'WHERE ali_ord_ra.lot_item_id=li.id '
                            . 'AND ali_ord_ra.lot_status_id IN (' . implode(', ', Constants\Lot::$availableLotStatuses) . ') '
                            . 'ORDER BY ali_ord_ra.created_on DESC LIMIT 1))) '
                . 'LEFT JOIN auction a ON a.id = ali.auction_id '
                . 'LEFT JOIN auction_dynamic adyn ON adyn.auction_id = ali.auction_id '
                . 'LEFT JOIN auction_lot_item_cache alic ON alic.auction_lot_item_id = ali.id';
            // @formatter:on
            $query->addSelect($selectExpr);
            $query->addJoin($join);
        }
        return $query;
    }

    /**
     * Filter by permissions
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @param int $resType
     * @return LotSearchQuery
     */
    public function applyAccessFilter(
        LotSearchQuery $query,
        LotSearchQueryCriteria $criteria,
        int $resType = Constants\Auction::ACCESS_RESTYPE_AUCTION_CATALOG
    ): LotSearchQuery {
        $accessCond = $this->createAuctionAccessCheckerQueryBuilderHelper()->makeWhereClause($resType, $criteria->userId);
        if (!empty($accessCond)) {
            $query->addWhere($accessCond);
        }
        return $query;
    }

    /**
     * Filter by lot billing status related to invoicing
     *
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @return LotSearchQuery
     */
    public function applyLotBillingStatusFilter(LotSearchQuery $query, LotSearchQueryCriteria $criteria): LotSearchQuery
    {
        $filterLotBillingStatus = $criteria->lotBillingStatus;
        if ($filterLotBillingStatus === Constants\MySearch::LBSF_OPEN) {
            $whereCondition = "(SELECT COUNT(1) " .
                "FROM invoice_item ii " .
                "INNER JOIN invoice i ON i.id = ii.invoice_id " .
                "WHERE ii.lot_item_id = li.id " .
                "AND ii.active = true " .
                "AND i.invoice_status_id IN (" . implode(',', Constants\Invoice::$openInvoiceStatuses) . ")) = 0";
            $query->addWhere($whereCondition);
        } elseif ($filterLotBillingStatus === Constants\MySearch::LBSF_BILLED) {
            $whereCondition = "((SELECT COUNT(1) " .
                "FROM invoice_item ii " .
                "INNER JOIN invoice i ON i.id = ii.invoice_id " .
                "WHERE ii.lot_item_id = li.`id` " .
                "AND ii.active = TRUE " .
                "AND i.invoice_status_id IN (" . implode(',', Constants\Invoice::$openInvoiceStatuses) . ") ) > 0)";
            $query->addWhere($whereCondition);
        }
        return $query;
    }

    /**
     * Filter by auction id
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @return LotSearchQuery
     */
    public function applyAccountFilter(LotSearchQuery $query, LotSearchQueryCriteria $criteria): LotSearchQuery
    {
        $filterAccountId = $criteria->accountId;
        if ($filterAccountId) {
            $query->addWhere("li.account_id = " . $this->escape($filterAccountId));
        }
        return $query;
    }

    /**
     * Filter by a.auction_auctioneer_id
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @return LotSearchQuery
     */
    public function applyAuctioneerFilter(LotSearchQuery $query, LotSearchQueryCriteria $criteria): LotSearchQuery
    {
        if ($criteria->auctioneerId) {
            $query->addWhere("a.auction_auctioneer_id = " . $this->escape($criteria->auctioneerId));
        }
        return $query;
    }

    /**
     * Filter by featured
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @return LotSearchQuery
     */
    public function applyFeaturedFilter(LotSearchQuery $query, LotSearchQueryCriteria $criteria): LotSearchQuery
    {
        if ($criteria->isFeatured) {
            $query->addWhere("ali.sample_lot = true");
        }
        return $query;
    }

    /**
     * Skip lots by status
     * We skip won lots in AssignReady when BlockSoldLots is enabled
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @return LotSearchQuery
     */
    public function applySkipLotStatusFilter(LotSearchQuery $query, LotSearchQueryCriteria $criteria): LotSearchQuery
    {
        $skipLotStatuses = ArrayCast::makeIntArray($criteria->skipLotStatus, Constants\Lot::$lotStatuses);
        if ($skipLotStatuses) {
            $whereCondition = "(SELECT COUNT(1) " .
                "FROM auction_lot_item AS ali_skp_ls " .
                "WHERE ali_skp_ls.lot_item_id = li.`id` " .
                "AND ali_skp_ls.lot_status_id IN (" . implode(',', $skipLotStatuses) . ") ) = 0";
            $query->addWhere($whereCondition);
        }
        return $query;
    }

    /**
     * Skip lots by auction id.
     * We use sub-query instead join, because it should consider unassigned items.
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @return LotSearchQuery
     */
    public function applySkipAuctionFiler(LotSearchQuery $query, LotSearchQueryCriteria $criteria): LotSearchQuery
    {
        $skipAuctionId = $criteria->skipAuctionId;
        if ($skipAuctionId) {
            $whereCondition = "(SELECT COUNT(1) FROM auction_lot_item AS ali_skp_a " .
                "WHERE ali_skp_a.lot_item_id = li.id " .
                "AND ali_skp_a.auction_id = " . $this->escape($skipAuctionId) . " " .
                "AND ali_skp_a.lot_status_id in (" . implode(',', Constants\Lot::$availableLotStatuses) . ")) = 0";
            $query->addWhere($whereCondition);
        }
        return $query;
    }

    /**
     * Filter by auction id.
     * We can use join instead of sub-query, because we don't care about un-assigned items, when this filtering required and we filter by status
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @return LotSearchQuery
     */
    public function applyAuctionFilter(LotSearchQuery $query, LotSearchQueryCriteria $criteria): LotSearchQuery
    {
        $filterAuctionId = $criteria->auctionId;
        if ($filterAuctionId) {
            $join = "LEFT JOIN auction_lot_item ali_auc_flt ON ali_auc_flt.lot_item_id = li.id";
            $query->addJoin($join);
            $query->addJoinCount($join);

            $whereCondition = 'ali_auc_flt.auction_id = ' . $this->escape($filterAuctionId) .
                " AND ali_auc_flt.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ")";
            $query->addWhere($whereCondition);
        }
        return $query;
    }

    /**
     * Filter by overall lot status, it considers ali.lot_status_id.
     * Attention! This filtering logic may be confusing. Before fixing it search for clarifications in SAM-5603, SAM-6247.
     * Especially for cases, where we perform filtering "from the contrary" way, i.e. for unassigned, unsold statuses, where condition = 0.
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @return LotSearchQuery
     */
    public function applyOverallLotStatusFilter(LotSearchQuery $query, LotSearchQueryCriteria $criteria): LotSearchQuery
    {
        $overallLotStatus = $criteria->overallLotStatus;
        $filterAuctionId = $criteria->auctionId;
        $auctionCondition = $filterAuctionId > 0 ? ' AND ali_flt.auction_id = ' . $this->escape($filterAuctionId) . ' ' : '';
        if ($overallLotStatus === Constants\MySearch::OLSF_ASSIGNED) {
            // Items that are assigned as a lot to at least one auction
            $whereCondition = "(SELECT COUNT(1) FROM auction_lot_item ali_flt " .
                "INNER JOIN auction a_flt ON a_flt.id = ali_flt.auction_id " .
                "AND a_flt.auction_status_id IN (" . implode(',', Constants\Auction::$notDeletedAuctionStatuses) . ") " .
                "WHERE ali_flt.lot_item_id = li.id AND ali_flt.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ") " .
                "{$auctionCondition}) > 0";
            $query->addWhere($whereCondition);
        } elseif ($overallLotStatus === Constants\MySearch::OLSF_UNASSIGNED) {
            if ($filterAuctionId) {
                // Unassigned lot status filter doesn't work together with auction filter
                $query->addWhere('false');
            } else {
                // Items that are not assigned as a lot to any single auction (no auction_lot_item record for lot item)
                $whereCondition = "(SELECT COUNT(1) FROM auction_lot_item ali_flt " .
                    "INNER JOIN auction a_flt ON a_flt.id = ali_flt.auction_id " .
                    "WHERE ali_flt.lot_item_id = li.id " .
                    "AND a_flt.auction_status_id IN (" . implode(', ', Constants\Auction::$notDeletedAuctionStatuses) . ") " .
                    "AND ali_flt.lot_status_id IN (" . implode(', ', Constants\Lot::$availableLotStatuses) . ")) = 0";
                $query->addWhere($whereCondition);
            }
        } elseif ($overallLotStatus === Constants\MySearch::OLSF_SOLD) {
            // Lots with status sold in at least one auction. Only looks at the lot status
            $whereCondition = "(SELECT COUNT(1) FROM auction_lot_item ali_flt " .
                "INNER JOIN auction a_flt ON a_flt.id = ali_flt.auction_id " .
                "AND a_flt.auction_status_id IN (" . implode(',', Constants\Auction::$notDeletedAuctionStatuses) . ") " .
                "WHERE ali_flt.lot_item_id = li.id AND lot_status_id = '" . Constants\Lot::LS_SOLD . "'" .
                "{$auctionCondition}) > 0";
            $query->addWhere($whereCondition);
        } elseif ($overallLotStatus === Constants\MySearch::OLSF_UNSOLD) {
            if ($filterAuctionId > 0) {
                // Active and Unsold lots assigned to filtering auction
                $whereCondition = "(SELECT COUNT(1) FROM auction_lot_item ali_flt " .
                    "INNER JOIN auction a_flt ON a_flt.id = ali_flt.auction_id " .
                    "AND a_flt.auction_status_id IN (" . implode(', ', Constants\Auction::$notDeletedAuctionStatuses) . ") " .
                    "WHERE ali_flt.lot_item_id = li.id AND ali_flt.lot_status_id IN (" . implode(',', [Constants\Lot::LS_ACTIVE, Constants\Lot::LS_UNSOLD]) . ") " .
                    "{$auctionCondition}) > 0";
                $query->addWhere($whereCondition);
            } else {
                // Unassigned items and lots that are not of status sold or received in any single auction.
                $whereCondition = "(SELECT COUNT(1) FROM auction_lot_item ali_flt " .
                    "INNER JOIN auction a_flt ON a_flt.id = ali_flt.auction_id " .
                    "AND a_flt.auction_status_id IN (" . implode(', ', Constants\Auction::$notDeletedAuctionStatuses) . ") " .
                    "WHERE ali_flt.lot_item_id = li.id AND ali_flt.lot_status_id IN (" . implode(',', Constants\Lot::$wonLotStatuses) . ") " .
                    ") = 0";
                $query->addWhere($whereCondition);
            }
        } elseif ($overallLotStatus === Constants\MySearch::OLSF_RECEIVED) {
            // Lots with status received in at least one auction. Only looks at the lot status
            $whereCondition = "(SELECT COUNT(1) FROM auction_lot_item ali_flt " .
                "INNER JOIN auction a_flt ON a_flt.id = ali_flt.auction_id " .
                "AND a_flt.auction_status_id IN (" . implode(',', Constants\Auction::$notDeletedAuctionStatuses) . ") " .
                "WHERE ali_flt.lot_item_id = li.id AND ali_flt.lot_status_id = '" . Constants\Lot::LS_RECEIVED . "'" .
                "{$auctionCondition}) > 0";
            $query->addWhere($whereCondition);
        }
        return $query;
    }

    /**
     * Apply filtering by reserve meet strategy.
     * We compare current bid to reserve price (SAM-6384).
     *
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @return LotSearchQuery
     */
    public function applyReserveMeetFilter(LotSearchQuery $query, LotSearchQueryCriteria $criteria): LotSearchQuery
    {
        $isAuctionReverse = $criteria->isAuctionReverse;
        $reserveMeetStrategy = $criteria->reserveMeetStrategy;

        if ($reserveMeetStrategy === Constants\Lot::FILTER_RESERVE_MEET) {
            if ($isAuctionReverse) {
                $whereCondition = 'CASE WHEN (alic.current_max_bid IS NOT NULL AND li.reserve_price > 0) THEN alic.current_max_bid <= li.reserve_price';
            } else {
                $whereCondition = 'CASE WHEN (alic.current_max_bid IS NOT NULL AND li.reserve_price > 0) THEN alic.current_max_bid >= li.reserve_price';
            }
            $whereCondition .= '
                        WHEN (alic.current_max_bid IS NOT NULL AND (li.reserve_price = 0 OR li.reserve_price IS NULL)) THEN TRUE
                        WHEN (alic.current_max_bid IS  NULL AND (li.reserve_price = 0 OR li.reserve_price IS NULL)) THEN FALSE
                        WHEN (alic.current_max_bid IS NULL AND li.reserve_price > 0) THEN FALSE
                    END';
            $query->addWhere("({$whereCondition})");
        } elseif ($reserveMeetStrategy === Constants\Lot::FILTER_RESERVE_NOT_MEET) {
            if ($isAuctionReverse) {
                $whereCondition = 'CASE WHEN (alic.current_max_bid IS NOT NULL AND li.reserve_price > 0) THEN alic.current_max_bid > li.reserve_price';
            } else {
                $whereCondition = 'CASE WHEN (alic.current_max_bid IS NOT NULL AND li.reserve_price > 0) THEN alic.current_max_bid < li.reserve_price';
            }
            $whereCondition .= '
                       WHEN (alic.current_max_bid IS NOT NULL AND (li.reserve_price = 0 OR li.reserve_price IS NULL)) THEN FALSE
                       WHEN (alic.current_max_bid IS  NULL AND (li.reserve_price = 0 OR li.reserve_price IS NULL)) THEN TRUE
                       WHEN (alic.current_max_bid IS NULL AND li.reserve_price > 0) THEN TRUE
                    END';
            $query->addWhere("({$whereCondition})");
        }
        return $query;
    }

    /**
     * Apply filtering by bid count strategy.
     *
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @return LotSearchQuery
     */
    public function applyBidCountFilter(LotSearchQuery $query, LotSearchQueryCriteria $criteria): LotSearchQuery
    {
        $bidCountStrategy = $criteria->bidCountStrategy;
        if ($bidCountStrategy === Constants\Lot::FILTER_BID_COUNT_HAS_BIDS) {
            $query->addWhere("alic.bid_count > 0");
        } elseif ($bidCountStrategy === Constants\Lot::FILTER_BID_COUNT_HAS_NO_BIDS) {
            $query->addWhere("alic.bid_count = 0");
        }
        return $query;
    }

    /**
     * Apply filtering by lot status
     *
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @return LotSearchQuery
     */
    public function applyLotStatusFilter(LotSearchQuery $query, LotSearchQueryCriteria $criteria): LotSearchQuery
    {
        $lotStatusId = $criteria->lotStatusId;
        if ($lotStatusId !== null) {
            $query->addWhere("ali.lot_status_id = " . $this->escape($lotStatusId));
        }
        return $query;
    }

    /**
     * Apply filtering by item num
     *
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @return LotSearchQuery
     */
    public function applyItemNumFilter(LotSearchQuery $query, LotSearchQueryCriteria $criteria): LotSearchQuery
    {
        $lotItemNum = $criteria->lotItemNum;
        if (is_numeric($lotItemNum)) {
            $query->addWhere("li.item_num LIKE " . $this->escape('%' . $lotItemNum . '%'));
        }
        return $query;
    }

    /**
     * Apply filtering by consignor
     *
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @return LotSearchQuery
     */
    public function applyConsignorFilter(LotSearchQuery $query, LotSearchQueryCriteria $criteria): LotSearchQuery
    {
        $consignorUserId = $criteria->consignorId;
        if ($consignorUserId !== null) {
            $query->addWhere("li.consignor_id = " . $this->escape($consignorUserId));
        }
        return $query;
    }

    /**
     * Apply filtering by categories
     *
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @return LotSearchQuery
     */
    public function applyCategoryFilter(LotSearchQuery $query, LotSearchQueryCriteria $criteria): LotSearchQuery
    {
        $headLotCategoryIds = $criteria->categoryIds;
        if ($headLotCategoryIds) {
            if ($criteria->categoryMatch === Constants\MySearch::CATEGORY_MATCH_ALL) {
                foreach ($headLotCategoryIds as $headLotCategoryId) {
                    $lotCategoryIds = $this->getLotCategoryLoader()
                        ->loadCategoryWithDescendantIds([$headLotCategoryId], true);
                    $lotCategoryIdList = implode(',', $lotCategoryIds);
                    $query->addWhere(
                        "(SELECT COUNT(1) FROM lot_item_category AS lic " .
                        "WHERE lic.lot_item_id = li.`id` AND lic.lot_category_id IN(" . $lotCategoryIdList . ")) > 0"
                    );
                }
            } else {
                $lotCategoryIds = $this->getLotCategoryLoader()
                    ->loadCategoryWithDescendantIds($headLotCategoryIds, true);
                $lotCategoryIdList = implode(',', $lotCategoryIds);
                $query->addWhere(
                    "(SELECT COUNT(1) FROM lot_item_category AS lic " .
                    "WHERE lic.lot_item_id = li.`id` AND lic.lot_category_id IN(" . $lotCategoryIdList . ")) > 0"
                );
            }
        }
        return $query;
    }

    /**
     * Filter by Price
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @return LotSearchQuery
     */
    public function applyPriceFilter(LotSearchQuery $query, LotSearchQueryCriteria $criteria): LotSearchQuery
    {
        $minPrice = $criteria->priceMin;
        $maxPrice = $criteria->priceMax;
        $minPriceEscaped = $this->escape($minPrice);
        $maxPriceEscaped = $this->escape($maxPrice);

        // @formatter:off
        if (Floating::gt($minPrice, 0)) {
            $whereCondition = "(if (li.hammer_price IS NOT NULL, " .
                                 "(if (li.hammer_price >= {$minPriceEscaped},true,false)), " .
                                 "(if (bt.bid > 0, " .
                                     "(if(bt.bid >= {$minPriceEscaped}, true, false)), " .
                                     "(if(li.starting_bid > 0, " .
                                         "(if (li.starting_bid >= {$minPriceEscaped}, true, false)), " .
                                         "false " .
                                     ")) " .
                                  ")) " .
                               "))";
            $query->addWhere($whereCondition);
        }
       if (Floating::gt($maxPrice, 0)) {
           $whereCondition = "AND (if (li.hammer_price IS NOT NULL, " .
                                 "(if (li.hammer_price <= {$maxPriceEscaped},true,false)), " .
                                 "(if (bt.bid > 0, " .
                                     "(if(bt.bid <= {$maxPriceEscaped}, true, false)), " .
                                     "(if(li.starting_bid > 0, " .
                                         "(if (li.starting_bid <= {$maxPriceEscaped}, true, false)), " .
                                         "false " .
                                     ")) " .
                                  ")) " .
                               "))";
           $query->addWhere($whereCondition);
        }
        // @formatter:on
        return $query;
    }

    /**
     * Apply filtering by winning bidder
     *
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @return LotSearchQuery
     */
    public function applyWinningBidderFilter(LotSearchQuery $query, LotSearchQueryCriteria $criteria): LotSearchQuery
    {
        if (LotSellInfoPureChecker::new()->isWinningBidder($criteria->winningBidderId)) {
            $query->addWhere("li.winning_bidder_id = " . $this->escape($criteria->winningBidderId));
        }
        return $query;
    }

    /**
     * Apply filtering by search term
     *
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @param int $searchEngine
     * @param array $lotCustomFields
     * @return LotSearchQuery
     */
    public function applySearchTermFilter(
        LotSearchQuery $query,
        LotSearchQueryCriteria $criteria,
        int $searchEngine,
        array $lotCustomFields = []
    ): LotSearchQuery {
        $accountId = $criteria->accountId;
        $userId = $criteria->userId;
        $searchKey = $criteria->searchKey;
        $isPublic = !$criteria->isPrivateSearch;
        $baseTable = $query->getBaseTable();
        $userAccesses = $this->createUserAccessRoleProvider()->get($userId);

        if ($this->createSearchIndexManager()->checkSearchKey($searchKey)) {
            $searchConds = [];

            $whereClauseForNumber = $this->createLotItemEntitySearchQueryBuilder()
                ->getWhereClauseForItemNumber($searchKey);
            $searchConds[] = $whereClauseForNumber;

            if ($baseTable === 'auction_lot_item') {
                $whereClauseForNumber = $this->createLotItemEntitySearchQueryBuilder()
                    ->getWhereClauseForLotNumber($searchKey);
                $searchConds[] = $whereClauseForNumber;
            }

            switch ($searchEngine) {
                case Constants\Search::INDEX_FULLTEXT:
                    $searchConds[] = $this->createFulltextSearchQueryBuilder()->getWhereClause(
                        $searchKey,
                        Constants\Search::ENTITY_LOT_ITEM,
                        $isPublic,
                        $accountId
                    );
                    $searchJoinClause = $this->createFulltextSearchQueryBuilder()
                        ->getJoinClause(Constants\Search::ENTITY_LOT_ITEM, 'li.id', $accountId);
                    $query->addJoin("INNER JOIN {$searchJoinClause}");
                    $query->addJoinCount("INNER JOIN {$searchJoinClause}");

                    break;

                default:    // SearchIndexManager::INDEX_NONE
                    $searchConds[] = $this->createLotItemEntitySearchQueryBuilder()->getWhereClause($searchKey);
                    $whereForCategory = $this->createLotItemEntitySearchQueryBuilder()->getWhereClauseForCategory($searchKey);
                    $searchConds[] = $whereForCategory;
                    if ($baseTable === 'lot_item') {
                        $whereForCustomField = $this->createLotItemEntitySearchQueryBuilder()
                            ->getWhereClauseForCustomFieldsOptimized($searchKey);
                    } else {
                        $whereForCustomField = $this->createLotItemEntitySearchQueryBuilder()->getWhereClauseForCustomFields(
                            $searchKey,
                            'li',
                            'ali',
                            $userId,
                            $lotCustomFields,
                            $userAccesses
                        );
                    }
                    $searchConds[] = $whereForCustomField;
                    break;
            }

            $searchConds = array_filter($searchConds);
            if (!empty($searchConds)) {
                $query->addWhere("(" . implode(' OR ', $searchConds) . ")");
            }
        }
        return $query;
    }


    /**
     * @param LotItemCustField[] $lotCustomFields
     * @return string|null
     */
    public function detectOrderByDistanceAlias(array $lotCustomFields): ?string
    {
        $distanceAlias = null;
        foreach ($lotCustomFields as $lotCustomField) {
            if ($lotCustomField->Type === Constants\CustomField::TYPE_POSTALCODE) {
                $distanceAlias = $this->makeOrderByDistanceAlias($lotCustomField);
            }
        }
        return $distanceAlias;
    }


    /**
     * @param LotItemCustField $lotCustomField
     * @return string
     * @internal
     */
    public function makeOrderByDistanceAlias(LotItemCustField $lotCustomField): string
    {
        return 'distance' . $lotCustomField->Id;
    }

    /**
     * Return expression for fetching lot start date in GTM
     * @return string
     */
    public function getLotStartDateExpr(): string
    {
        $timed = Constants\Auction::TIMED;
        $timedLotStartDateExpr = $this->getTimedLotStartDateExpr();
        $expression = <<<SQL
IF(a.auction_type = '{$timed}',
    {$timedLotStartDateExpr},
    a.start_closing_date
)
SQL;
        return $expression;
    }

    /**
     * Return expression for fetching timed lot start date in GTM
     * We use auction start date, when "extend all" is on
     * @return string
     */
    public function getTimedLotStartDateExpr(): string
    {
        $expression = "IF(a.extend_all, a.start_bidding_date, IFNULL(ali.start_bidding_date, a.start_bidding_date))";
        return $expression;
    }

    /**
     * Return expression for fetching lot end date in UTC.
     * We want to use auction start date for live/hybrid auctions.
     * TODO: implement virtual end date approximate calculation for hybrid, like we do for timed with staggered closing
     * @return string
     */
    public function getLotEndDateExpr(): string
    {
        $timedLotEndDateExpr = $this->getTimedLotEndDateExpr();
        $timed = Constants\Auction::TIMED;
        $expression = <<<SQL
IF(a.auction_type = '{$timed}',
    {$timedLotEndDateExpr},
    a.start_closing_date
)
SQL;
        return $expression;
    }

    /**
     * Return expression for fetching timed lot end date in GTM
     * It may be dependent on "extend all" turned on option and "staggered closing" settings.
     * @return string
     */
    public function getTimedLotEndDateExpr(): string
    {
        $lsActive = Constants\Lot::LS_ACTIVE;
        $expression = <<<SQL
IF(a.extend_all AND ali.lot_status_id = {$lsActive},
    IF(IFNULL(a.stagger_closing, 0) AND ali.order > 0,
        DATE_ADD(
            IFNULL(adyn.extend_all_start_closing_date, a.start_closing_date),
            INTERVAL FLOOR((ali.order - 1) / a.lots_per_interval) * a.stagger_closing MINUTE
        ),
        IFNULL(adyn.extend_all_start_closing_date, a.start_closing_date)
    ),
    alic.end_date
)
SQL;
        return $expression;
    }
}
