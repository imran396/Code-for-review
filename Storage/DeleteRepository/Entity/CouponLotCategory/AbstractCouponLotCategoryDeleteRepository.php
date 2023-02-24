<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\CouponLotCategory;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractCouponLotCategoryDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_COUPON_LOT_CATEGORY;
    protected string $alias = Db::A_COUPON_LOT_CATEGORY;

    /**
     * Filter by coupon_lot_category.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon_lot_category.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon_lot_category.coupon_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCouponId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.coupon_id', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon_lot_category.coupon_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCouponId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.coupon_id', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon_lot_category.lot_category_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotCategoryId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_category_id', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon_lot_category.lot_category_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotCategoryId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_category_id', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon_lot_category.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon_lot_category.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon_lot_category.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon_lot_category.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon_lot_category.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon_lot_category.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon_lot_category.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon_lot_category.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by coupon_lot_category.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out coupon_lot_category.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
