<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LotItemCustField;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractLotItemCustFieldDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_LOT_ITEM_CUST_FIELD;
    protected string $alias = Db::A_LOT_ITEM_CUST_FIELD;

    /**
     * Filter by lot_item_cust_field.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.order
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterOrder(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.order', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.order from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipOrder(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.order', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.type
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterType(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.type', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.type from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipType(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.type', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.in_catalog
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInCatalog(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.in_catalog', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.in_catalog from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInCatalog(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.in_catalog', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.search_field
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSearchField(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.search_field', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.search_field from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSearchField(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.search_field', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.parameters
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterParameters(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.parameters', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.parameters from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipParameters(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.parameters', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.access
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAccess(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.access', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.access from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAccess(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.access', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.unique
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterUnique(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.unique', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.unique from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipUnique(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.unique', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.barcode
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBarcode(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.barcode', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.barcode from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBarcode(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.barcode', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.barcode_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterBarcodeType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.barcode_type', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.barcode_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipBarcodeType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.barcode_type', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.barcode_auto_populate
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBarcodeAutoPopulate(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.barcode_auto_populate', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.barcode_auto_populate from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBarcodeAutoPopulate(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.barcode_auto_populate', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.in_invoices
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInInvoices(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.in_invoices', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.in_invoices from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInInvoices(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.in_invoices', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.in_settlements
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInSettlements(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.in_settlements', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.in_settlements from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInSettlements(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.in_settlements', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.in_admin_search
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInAdminSearch(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.in_admin_search', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.in_admin_search from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInAdminSearch(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.in_admin_search', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.search_index
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSearchIndex(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.search_index', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.search_index from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSearchIndex(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.search_index', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.in_admin_catalog
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInAdminCatalog(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.in_admin_catalog', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.in_admin_catalog from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInAdminCatalog(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.in_admin_catalog', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.fck_editor
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterFckEditor(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fck_editor', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.fck_editor from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipFckEditor(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fck_editor', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.auto_complete
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAutoComplete(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auto_complete', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.auto_complete from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAutoComplete(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auto_complete', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.lot_num_auto_fill
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLotNumAutoFill(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_num_auto_fill', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.lot_num_auto_fill from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLotNumAutoFill(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_num_auto_fill', $skipValue);
        return $this;
    }

    /**
     * Filter by lot_item_cust_field.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out lot_item_cust_field.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
