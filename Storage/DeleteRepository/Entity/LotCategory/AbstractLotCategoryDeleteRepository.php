<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LotCategory;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractLotCategoryDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_LOT_CATEGORY;
    protected string $alias = Db::A_LOT_CATEGORY;

    /**
     * Filter by lot_category.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category.parent_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterParentId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.parent_id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.parent_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipParentId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.parent_id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category.level
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLevel(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.level', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.level from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLevel(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.level', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category.sibling_order
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterSiblingOrder(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.sibling_order', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.sibling_order from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipSiblingOrder(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.sibling_order', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category.global_order
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterGlobalOrder(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.global_order', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.global_order from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipGlobalOrder(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.global_order', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category.buy_now_amount
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterBuyNowAmount(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.buy_now_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.buy_now_amount from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipBuyNowAmount(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.buy_now_amount', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category.starting_bid
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterStartingBid(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.starting_bid', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.starting_bid from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipStartingBid(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.starting_bid', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category.quantity_digits
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterQuantityDigits(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.quantity_digits', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.quantity_digits from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipQuantityDigits(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.quantity_digits', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category.image_link
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterImageLink(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.image_link', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.image_link from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipImageLink(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.image_link', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category.consignment_commission
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterConsignmentCommission(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.consignment_commission', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.consignment_commission from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipConsignmentCommission(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.consignment_commission', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category.hide_empty_fields
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterHideEmptyFields(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hide_empty_fields', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.hide_empty_fields from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipHideEmptyFields(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hide_empty_fields', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category.child_count
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterChildCount(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.child_count', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.child_count from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipChildCount(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.child_count', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_category.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_category.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
