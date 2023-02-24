<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LotCategoryCustData;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractLotCategoryCustDataDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_LOT_CATEGORY_CUST_DATA;
    protected string $alias = Db::A_LOT_CATEGORY_CUST_DATA;

    /**
     * Filter by lot_category_cust_data.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_cust_data.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category_cust_data.lot_category_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotCategoryId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_category_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_cust_data.lot_category_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotCategoryId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_category_id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category_cust_data.lot_item_cust_field_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotItemCustFieldId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_cust_field_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_cust_data.lot_item_cust_field_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotItemCustFieldId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_cust_field_id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category_cust_data.numeric
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterNumeric(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.numeric', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_cust_data.numeric from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipNumeric(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.numeric', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category_cust_data.text
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterText(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.text', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_cust_data.text from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipText(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.text', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category_cust_data.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_cust_data.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category_cust_data.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_cust_data.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category_cust_data.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_cust_data.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category_cust_data.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_cust_data.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category_cust_data.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_cust_data.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category_cust_data.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_cust_data.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
