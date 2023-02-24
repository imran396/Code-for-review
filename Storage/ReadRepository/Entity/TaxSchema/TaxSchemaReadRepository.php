<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\TaxSchema;

class TaxSchemaReadRepository extends AbstractTaxSchemaReadRepository
{
    protected array $joins = [
        'location_tax_schema' => 'JOIN location_tax_schema lts ON lts.tax_schema_id = ts.id',
        'tax_schema_lot_category' => 'JOIN tax_schema_lot_category tslc ON tslc.tax_schema_id = ts.id',
    ];

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function joinLocationTaxSchema(): static
    {
        $this->join('location_tax_schema');
        return $this;
    }

    public function joinLocationTaxSchemaFilterLocationId(int|array $filterValue): static
    {
        $this->joinLocationTaxSchema();
        $this->filterArray('lts.location_id', $filterValue);
        return $this;
    }

    public function joinLocationTaxSchemaFilterActive(bool|array $filterValue): static
    {
        $this->joinLocationTaxSchema();
        $this->filterArray('lts.active', $filterValue);
        return $this;
    }

    public function joinLocationTaxSchemaFilterSkipTaxSchemaIds(int|array $filterValue): static
    {
        $this->joinLocationTaxSchema();
        $this->skipArray('lts.tax_schema_id', $filterValue);
        return $this;
    }

    public function joinTaxSchemaLotCategory(): static
    {
        $this->join('tax_schema_lot_category');
        return $this;
    }

    public function joinTaxSchemaLotCategoryFilterLotCategoryId(int|array $lotCategoryId): static
    {
        $this->joinTaxSchemaLotCategory();
        $this->filterArray('tslc.lot_category_id', $lotCategoryId);
        return $this;
    }
}
