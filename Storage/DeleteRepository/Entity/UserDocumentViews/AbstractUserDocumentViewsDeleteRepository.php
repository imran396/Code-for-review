<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserDocumentViews;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractUserDocumentViewsDeleteRepository extends DeleteRepositoryBase
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
}
