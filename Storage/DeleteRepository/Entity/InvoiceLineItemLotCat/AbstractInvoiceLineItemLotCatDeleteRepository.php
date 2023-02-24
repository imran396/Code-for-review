<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\InvoiceLineItemLotCat;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractInvoiceLineItemLotCatDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_INVOICE_LINE_ITEM_LOT_CAT;
    protected string $alias = Db::A_INVOICE_LINE_ITEM_LOT_CAT;

    /**
     * Filter by invoice_line_item_lot_cat.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item_lot_cat.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item_lot_cat.invoice_line_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterInvoiceLineId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_line_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item_lot_cat.invoice_line_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipInvoiceLineId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_line_id', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item_lot_cat.lot_cat_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotCatId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_cat_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item_lot_cat.lot_cat_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotCatId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_cat_id', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item_lot_cat.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item_lot_cat.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item_lot_cat.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item_lot_cat.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item_lot_cat.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item_lot_cat.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item_lot_cat.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item_lot_cat.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item_lot_cat.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item_lot_cat.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by invoice_line_item_lot_cat.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_line_item_lot_cat.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
