<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserDocumentViews;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use UserDocumentViews;

/**
 * Abstract class AbstractUserDocumentViewsReadRepository
 * @method UserDocumentViews[] loadEntities()
 * @method UserDocumentViews|null loadEntity()
 */
abstract class AbstractUserDocumentViewsReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_USER_DOCUMENT_VIEWS;
    protected string $alias = Db::A_USER_DOCUMENT_VIEWS;

    /**
     * Filter by user_document_views.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_document_views.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by user_document_views.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by user_document_views.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_document_views.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_document_views.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_document_views.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_document_views.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_document_views.user_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_document_views.user_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_document_views.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by user_document_views.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_document_views.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_document_views.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_document_views.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_document_views.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_document_views.auction_lot_item_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionLotItemId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_lot_item_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_document_views.auction_lot_item_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionLotItemId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_lot_item_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_document_views.auction_lot_item_id
     * @return static
     */
    public function groupByAuctionLotItemId(): static
    {
        $this->group($this->alias . '.auction_lot_item_id');
        return $this;
    }

    /**
     * Order by user_document_views.auction_lot_item_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionLotItemId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_lot_item_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_document_views.auction_lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionLotItemIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_lot_item_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_document_views.auction_lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionLotItemIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_lot_item_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_document_views.auction_lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionLotItemIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_lot_item_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_document_views.auction_lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionLotItemIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_lot_item_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_document_views.lot_item_cust_data_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotItemCustDataId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_cust_data_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_document_views.lot_item_cust_data_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotItemCustDataId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_cust_data_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_document_views.lot_item_cust_data_id
     * @return static
     */
    public function groupByLotItemCustDataId(): static
    {
        $this->group($this->alias . '.lot_item_cust_data_id');
        return $this;
    }

    /**
     * Order by user_document_views.lot_item_cust_data_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotItemCustDataId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_item_cust_data_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_document_views.lot_item_cust_data_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemCustDataIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_cust_data_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_document_views.lot_item_cust_data_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemCustDataIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_cust_data_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_document_views.lot_item_cust_data_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemCustDataIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_cust_data_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_document_views.lot_item_cust_data_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemCustDataIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_cust_data_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_document_views.document_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterDocumentName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.document_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_document_views.document_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipDocumentName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.document_name', $skipValue);
        return $this;
    }

    /**
     * Group by user_document_views.document_name
     * @return static
     */
    public function groupByDocumentName(): static
    {
        $this->group($this->alias . '.document_name');
        return $this;
    }

    /**
     * Order by user_document_views.document_name
     * @param bool $ascending
     * @return static
     */
    public function orderByDocumentName(bool $ascending = true): static
    {
        $this->order($this->alias . '.document_name', $ascending);
        return $this;
    }

    /**
     * Filter user_document_views.document_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeDocumentName(string $filterValue): static
    {
        $this->like($this->alias . '.document_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_document_views.date_viewed
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterDateViewed(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.date_viewed', $filterValue);
        return $this;
    }

    /**
     * Filter out user_document_views.date_viewed from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipDateViewed(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.date_viewed', $skipValue);
        return $this;
    }

    /**
     * Group by user_document_views.date_viewed
     * @return static
     */
    public function groupByDateViewed(): static
    {
        $this->group($this->alias . '.date_viewed');
        return $this;
    }

    /**
     * Order by user_document_views.date_viewed
     * @param bool $ascending
     * @return static
     */
    public function orderByDateViewed(bool $ascending = true): static
    {
        $this->order($this->alias . '.date_viewed', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_document_views.date_viewed
     * @param string $filterValue
     * @return static
     */
    public function filterDateViewedGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.date_viewed', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_document_views.date_viewed
     * @param string $filterValue
     * @return static
     */
    public function filterDateViewedGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.date_viewed', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_document_views.date_viewed
     * @param string $filterValue
     * @return static
     */
    public function filterDateViewedLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.date_viewed', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_document_views.date_viewed
     * @param string $filterValue
     * @return static
     */
    public function filterDateViewedLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.date_viewed', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_document_views.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out user_document_views.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by user_document_views.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by user_document_views.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_document_views.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_document_views.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_document_views.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_document_views.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_document_views.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_document_views.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_document_views.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by user_document_views.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_document_views.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_document_views.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_document_views.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_document_views.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_document_views.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_document_views.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by user_document_views.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by user_document_views.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_document_views.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_document_views.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_document_views.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_document_views.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_document_views.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_document_views.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by user_document_views.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by user_document_views.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_document_views.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_document_views.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_document_views.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_document_views.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_document_views.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_document_views.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_document_views.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by user_document_views.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_document_views.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_document_views.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_document_views.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_document_views.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
