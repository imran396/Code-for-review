<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\TaxDefinition;

class TaxDefinitionReadRepository extends AbstractTaxDefinitionReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'tax_schema_tax_definition' => 'JOIN tax_schema_tax_definition tstd ON tstd.tax_definition_id = tdef.id',
    ];

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function joinTaxSchemaTaxDefinition(): static
    {
        $this->join('tax_schema_tax_definition');
        return $this;
    }

    public function joinTaxSchemaTaxDefinitionFilterTaxSchemaId(int|array $filterValue): static
    {
        $this->joinTaxSchemaTaxDefinition();
        $this->filterArray('tstd.tax_schema_id', $filterValue);
        return $this;
    }

    public function joinTaxSchemaTaxDefinitionFilterActive(bool|array $filterValue): static
    {
        $this->joinTaxSchemaTaxDefinition();
        $this->filterArray('tstd.active', $filterValue);
        return $this;
    }
}
