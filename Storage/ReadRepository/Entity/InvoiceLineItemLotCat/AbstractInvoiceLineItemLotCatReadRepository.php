<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\InvoiceLineItemLotCat;

use InvoiceLineItemLotCat;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractInvoiceLineItemLotCatReadRepository
 * @method InvoiceLineItemLotCat[] loadEntities()
 * @method InvoiceLineItemLotCat|null loadEntity()
 */
abstract class AbstractInvoiceLineItemLotCatReadRepository extends ReadRepositoryBase
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
     * Group by invoice_line_item_lot_cat.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by invoice_line_item_lot_cat.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item_lot_cat.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item_lot_cat.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item_lot_cat.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item_lot_cat.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by invoice_line_item_lot_cat.invoice_line_id
     * @return static
     */
    public function groupByInvoiceLineId(): static
    {
        $this->group($this->alias . '.invoice_line_id');
        return $this;
    }

    /**
     * Order by invoice_line_item_lot_cat.invoice_line_id
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceLineId(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_line_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item_lot_cat.invoice_line_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceLineIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_line_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item_lot_cat.invoice_line_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceLineIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_line_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item_lot_cat.invoice_line_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceLineIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_line_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item_lot_cat.invoice_line_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceLineIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_line_id', $filterValue, '<=');
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
     * Group by invoice_line_item_lot_cat.lot_cat_id
     * @return static
     */
    public function groupByLotCatId(): static
    {
        $this->group($this->alias . '.lot_cat_id');
        return $this;
    }

    /**
     * Order by invoice_line_item_lot_cat.lot_cat_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotCatId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_cat_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item_lot_cat.lot_cat_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotCatIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_cat_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item_lot_cat.lot_cat_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotCatIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_cat_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item_lot_cat.lot_cat_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotCatIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_cat_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item_lot_cat.lot_cat_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotCatIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_cat_id', $filterValue, '<=');
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
     * Group by invoice_line_item_lot_cat.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by invoice_line_item_lot_cat.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item_lot_cat.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item_lot_cat.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item_lot_cat.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item_lot_cat.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
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
     * Group by invoice_line_item_lot_cat.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by invoice_line_item_lot_cat.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item_lot_cat.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item_lot_cat.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item_lot_cat.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item_lot_cat.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
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
     * Group by invoice_line_item_lot_cat.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by invoice_line_item_lot_cat.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item_lot_cat.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item_lot_cat.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item_lot_cat.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item_lot_cat.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by invoice_line_item_lot_cat.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by invoice_line_item_lot_cat.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item_lot_cat.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item_lot_cat.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item_lot_cat.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item_lot_cat.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by invoice_line_item_lot_cat.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by invoice_line_item_lot_cat.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item_lot_cat.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item_lot_cat.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item_lot_cat.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item_lot_cat.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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

    /**
     * Group by invoice_line_item_lot_cat.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by invoice_line_item_lot_cat.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_line_item_lot_cat.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_line_item_lot_cat.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_line_item_lot_cat.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_line_item_lot_cat.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
