<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\TaxSchema;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractTaxSchemaDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_TAX_SCHEMA;
    protected string $alias = Db::A_TAX_SCHEMA;

    /**
     * Filter by tax_schema.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.source_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterSourceTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.source_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.source_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipSourceTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.source_tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.invoice_item_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterInvoiceItemId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_item_id', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.invoice_item_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipInvoiceItemId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_item_id', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.invoice_additional_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterInvoiceAdditionalId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_additional_id', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.invoice_additional_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipInvoiceAdditionalId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_additional_id', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.geo_type
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterGeoType(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.geo_type', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.geo_type from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipGeoType(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.geo_type', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.amount_source
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAmountSource(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.amount_source', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.amount_source from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAmountSource(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.amount_source', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.country
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCountry(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.country', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.country from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCountry(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.country', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.state
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterState(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.state', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.state from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipState(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.state', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.county
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCounty(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.county', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.county from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCounty(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.county', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.city
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCity(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.city', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.city from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCity(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.city', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.description
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterDescription(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.description', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.description from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipDescription(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.description', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.note
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNote(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.note', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.note from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNote(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.note', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.for_invoice
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterForInvoice(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.for_invoice', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.for_invoice from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipForInvoice(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.for_invoice', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.for_settlement
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterForSettlement(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.for_settlement', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.for_settlement from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipForSettlement(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.for_settlement', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.active
     * @param bool|bool[]|null $filterValue
     * @return static
     */
    public function filterActive(bool|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.active from result
     * @param bool|bool[]|null $skipValue
     * @return static
     */
    public function skipActive(bool|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_schema.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_schema.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
