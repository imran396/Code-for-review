<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\TaxDefinitionRange;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractTaxDefinitionRangeDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_TAX_DEFINITION_RANGE;
    protected string $alias = Db::A_TAX_DEFINITION_RANGE;

    /**
     * Filter by tax_definition_range.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition_range.tax_definition_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterTaxDefinitionId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_definition_id', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.tax_definition_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipTaxDefinitionId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_definition_id', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition_range.amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.amount', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.amount', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition_range.fixed
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterFixed(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fixed', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.fixed from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipFixed(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fixed', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition_range.percent
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterPercent(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.percent', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.percent from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipPercent(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.percent', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition_range.mode
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterMode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.mode', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.mode from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipMode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.mode', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition_range.active
     * @param bool|bool[]|null $filterValue
     * @return static
     */
    public function filterActive(bool|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.active from result
     * @param bool|bool[]|null $skipValue
     * @return static
     */
    public function skipActive(bool|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition_range.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition_range.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition_range.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition_range.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by tax_definition_range.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out tax_definition_range.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
