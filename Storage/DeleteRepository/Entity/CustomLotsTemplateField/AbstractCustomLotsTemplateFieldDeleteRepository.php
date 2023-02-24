<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\CustomLotsTemplateField;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractCustomLotsTemplateFieldDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_CUSTOM_LOTS_TEMPLATE_FIELD;
    protected string $alias = Db::A_CUSTOM_LOTS_TEMPLATE_FIELD;

    /**
     * Filter by custom_lots_template_field.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_field.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_field.config_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterConfigId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.config_id', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_field.config_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipConfigId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.config_id', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_field.index
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterIndex(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.index', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_field.index from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipIndex(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.index', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_field.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_field.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_field.order
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterOrder(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.order', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_field.order from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipOrder(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.order', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_field.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_field.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_field.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_field.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_field.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_field.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_field.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_field.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_field.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_field.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_field.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_field.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
