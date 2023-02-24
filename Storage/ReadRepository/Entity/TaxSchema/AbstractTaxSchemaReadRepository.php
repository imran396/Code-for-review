<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\TaxSchema;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use TaxSchema;

/**
 * Abstract class AbstractTaxSchemaReadRepository
 * @method TaxSchema[] loadEntities()
 * @method TaxSchema|null loadEntity()
 */
abstract class AbstractTaxSchemaReadRepository extends ReadRepositoryBase
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
     * Group by tax_schema.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by tax_schema.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_schema.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_schema.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_schema.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_schema.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by tax_schema.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by tax_schema.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_schema.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_schema.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_schema.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_schema.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
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
     * Group by tax_schema.source_tax_schema_id
     * @return static
     */
    public function groupBySourceTaxSchemaId(): static
    {
        $this->group($this->alias . '.source_tax_schema_id');
        return $this;
    }

    /**
     * Order by tax_schema.source_tax_schema_id
     * @param bool $ascending
     * @return static
     */
    public function orderBySourceTaxSchemaId(bool $ascending = true): static
    {
        $this->order($this->alias . '.source_tax_schema_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_schema.source_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterSourceTaxSchemaIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.source_tax_schema_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_schema.source_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterSourceTaxSchemaIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.source_tax_schema_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_schema.source_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterSourceTaxSchemaIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.source_tax_schema_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_schema.source_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterSourceTaxSchemaIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.source_tax_schema_id', $filterValue, '<=');
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
     * Group by tax_schema.invoice_item_id
     * @return static
     */
    public function groupByInvoiceItemId(): static
    {
        $this->group($this->alias . '.invoice_item_id');
        return $this;
    }

    /**
     * Order by tax_schema.invoice_item_id
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceItemId(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_item_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_schema.invoice_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceItemIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_schema.invoice_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceItemIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_schema.invoice_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceItemIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_schema.invoice_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceItemIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_item_id', $filterValue, '<=');
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
     * Group by tax_schema.invoice_additional_id
     * @return static
     */
    public function groupByInvoiceAdditionalId(): static
    {
        $this->group($this->alias . '.invoice_additional_id');
        return $this;
    }

    /**
     * Order by tax_schema.invoice_additional_id
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceAdditionalId(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_additional_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_schema.invoice_additional_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceAdditionalIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_additional_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_schema.invoice_additional_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceAdditionalIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_additional_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_schema.invoice_additional_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceAdditionalIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_additional_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_schema.invoice_additional_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceAdditionalIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_additional_id', $filterValue, '<=');
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
     * Group by tax_schema.name
     * @return static
     */
    public function groupByName(): static
    {
        $this->group($this->alias . '.name');
        return $this;
    }

    /**
     * Order by tax_schema.name
     * @param bool $ascending
     * @return static
     */
    public function orderByName(bool $ascending = true): static
    {
        $this->order($this->alias . '.name', $ascending);
        return $this;
    }

    /**
     * Filter tax_schema.name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeName(string $filterValue): static
    {
        $this->like($this->alias . '.name', "%{$filterValue}%");
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
     * Group by tax_schema.geo_type
     * @return static
     */
    public function groupByGeoType(): static
    {
        $this->group($this->alias . '.geo_type');
        return $this;
    }

    /**
     * Order by tax_schema.geo_type
     * @param bool $ascending
     * @return static
     */
    public function orderByGeoType(bool $ascending = true): static
    {
        $this->order($this->alias . '.geo_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_schema.geo_type
     * @param int $filterValue
     * @return static
     */
    public function filterGeoTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.geo_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_schema.geo_type
     * @param int $filterValue
     * @return static
     */
    public function filterGeoTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.geo_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_schema.geo_type
     * @param int $filterValue
     * @return static
     */
    public function filterGeoTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.geo_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_schema.geo_type
     * @param int $filterValue
     * @return static
     */
    public function filterGeoTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.geo_type', $filterValue, '<=');
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
     * Group by tax_schema.amount_source
     * @return static
     */
    public function groupByAmountSource(): static
    {
        $this->group($this->alias . '.amount_source');
        return $this;
    }

    /**
     * Order by tax_schema.amount_source
     * @param bool $ascending
     * @return static
     */
    public function orderByAmountSource(bool $ascending = true): static
    {
        $this->order($this->alias . '.amount_source', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_schema.amount_source
     * @param int $filterValue
     * @return static
     */
    public function filterAmountSourceGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount_source', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_schema.amount_source
     * @param int $filterValue
     * @return static
     */
    public function filterAmountSourceGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount_source', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_schema.amount_source
     * @param int $filterValue
     * @return static
     */
    public function filterAmountSourceLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount_source', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_schema.amount_source
     * @param int $filterValue
     * @return static
     */
    public function filterAmountSourceLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount_source', $filterValue, '<=');
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
     * Group by tax_schema.country
     * @return static
     */
    public function groupByCountry(): static
    {
        $this->group($this->alias . '.country');
        return $this;
    }

    /**
     * Order by tax_schema.country
     * @param bool $ascending
     * @return static
     */
    public function orderByCountry(bool $ascending = true): static
    {
        $this->order($this->alias . '.country', $ascending);
        return $this;
    }

    /**
     * Filter tax_schema.country by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCountry(string $filterValue): static
    {
        $this->like($this->alias . '.country', "%{$filterValue}%");
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
     * Group by tax_schema.state
     * @return static
     */
    public function groupByState(): static
    {
        $this->group($this->alias . '.state');
        return $this;
    }

    /**
     * Order by tax_schema.state
     * @param bool $ascending
     * @return static
     */
    public function orderByState(bool $ascending = true): static
    {
        $this->order($this->alias . '.state', $ascending);
        return $this;
    }

    /**
     * Filter tax_schema.state by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeState(string $filterValue): static
    {
        $this->like($this->alias . '.state', "%{$filterValue}%");
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
     * Group by tax_schema.county
     * @return static
     */
    public function groupByCounty(): static
    {
        $this->group($this->alias . '.county');
        return $this;
    }

    /**
     * Order by tax_schema.county
     * @param bool $ascending
     * @return static
     */
    public function orderByCounty(bool $ascending = true): static
    {
        $this->order($this->alias . '.county', $ascending);
        return $this;
    }

    /**
     * Filter tax_schema.county by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCounty(string $filterValue): static
    {
        $this->like($this->alias . '.county', "%{$filterValue}%");
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
     * Group by tax_schema.city
     * @return static
     */
    public function groupByCity(): static
    {
        $this->group($this->alias . '.city');
        return $this;
    }

    /**
     * Order by tax_schema.city
     * @param bool $ascending
     * @return static
     */
    public function orderByCity(bool $ascending = true): static
    {
        $this->order($this->alias . '.city', $ascending);
        return $this;
    }

    /**
     * Filter tax_schema.city by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeCity(string $filterValue): static
    {
        $this->like($this->alias . '.city', "%{$filterValue}%");
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
     * Group by tax_schema.description
     * @return static
     */
    public function groupByDescription(): static
    {
        $this->group($this->alias . '.description');
        return $this;
    }

    /**
     * Order by tax_schema.description
     * @param bool $ascending
     * @return static
     */
    public function orderByDescription(bool $ascending = true): static
    {
        $this->order($this->alias . '.description', $ascending);
        return $this;
    }

    /**
     * Filter tax_schema.description by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeDescription(string $filterValue): static
    {
        $this->like($this->alias . '.description', "%{$filterValue}%");
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
     * Group by tax_schema.note
     * @return static
     */
    public function groupByNote(): static
    {
        $this->group($this->alias . '.note');
        return $this;
    }

    /**
     * Order by tax_schema.note
     * @param bool $ascending
     * @return static
     */
    public function orderByNote(bool $ascending = true): static
    {
        $this->order($this->alias . '.note', $ascending);
        return $this;
    }

    /**
     * Filter tax_schema.note by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeNote(string $filterValue): static
    {
        $this->like($this->alias . '.note', "%{$filterValue}%");
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
     * Group by tax_schema.for_invoice
     * @return static
     */
    public function groupByForInvoice(): static
    {
        $this->group($this->alias . '.for_invoice');
        return $this;
    }

    /**
     * Order by tax_schema.for_invoice
     * @param bool $ascending
     * @return static
     */
    public function orderByForInvoice(bool $ascending = true): static
    {
        $this->order($this->alias . '.for_invoice', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_schema.for_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterForInvoiceGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.for_invoice', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_schema.for_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterForInvoiceGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.for_invoice', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_schema.for_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterForInvoiceLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.for_invoice', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_schema.for_invoice
     * @param bool $filterValue
     * @return static
     */
    public function filterForInvoiceLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.for_invoice', $filterValue, '<=');
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
     * Group by tax_schema.for_settlement
     * @return static
     */
    public function groupByForSettlement(): static
    {
        $this->group($this->alias . '.for_settlement');
        return $this;
    }

    /**
     * Order by tax_schema.for_settlement
     * @param bool $ascending
     * @return static
     */
    public function orderByForSettlement(bool $ascending = true): static
    {
        $this->order($this->alias . '.for_settlement', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_schema.for_settlement
     * @param bool $filterValue
     * @return static
     */
    public function filterForSettlementGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.for_settlement', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_schema.for_settlement
     * @param bool $filterValue
     * @return static
     */
    public function filterForSettlementGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.for_settlement', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_schema.for_settlement
     * @param bool $filterValue
     * @return static
     */
    public function filterForSettlementLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.for_settlement', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_schema.for_settlement
     * @param bool $filterValue
     * @return static
     */
    public function filterForSettlementLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.for_settlement', $filterValue, '<=');
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
     * Group by tax_schema.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by tax_schema.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_schema.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_schema.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_schema.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_schema.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
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
     * Group by tax_schema.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by tax_schema.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_schema.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_schema.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_schema.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_schema.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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
     * Group by tax_schema.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by tax_schema.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_schema.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_schema.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_schema.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_schema.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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
     * Group by tax_schema.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by tax_schema.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_schema.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_schema.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_schema.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_schema.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by tax_schema.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by tax_schema.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_schema.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_schema.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_schema.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_schema.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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

    /**
     * Group by tax_schema.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by tax_schema.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than tax_schema.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than tax_schema.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than tax_schema.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than tax_schema.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
