<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LotCategoryTemplate;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractLotCategoryTemplateDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_LOT_CATEGORY_TEMPLATE;
    protected string $alias = Db::A_LOT_CATEGORY_TEMPLATE;

    /**
     * Filter by lot_category_template.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category_template.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category_template.lot_category_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotCategoryId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_category_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.lot_category_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotCategoryId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_category_id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category_template.lot_item_detail_template
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotItemDetailTemplate(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_detail_template', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.lot_item_detail_template from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotItemDetailTemplate(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_detail_template', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category_template.rtb_detail_template
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterRtbDetailTemplate(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.rtb_detail_template', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.rtb_detail_template from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipRtbDetailTemplate(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.rtb_detail_template', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category_template.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category_template.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category_template.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category_template.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category_template.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category_template.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
