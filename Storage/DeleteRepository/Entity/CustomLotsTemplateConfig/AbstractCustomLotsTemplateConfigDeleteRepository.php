<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\CustomLotsTemplateConfig;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractCustomLotsTemplateConfigDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_CUSTOM_LOTS_TEMPLATE_CONFIG;
    protected string $alias = Db::A_CUSTOM_LOTS_TEMPLATE_CONFIG;

    /**
     * Filter by custom_lots_template_config.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_config.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_config.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_config.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_config.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_config.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_config.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_config.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_config.image_web_links
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterImageWebLinks(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.image_web_links', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_config.image_web_links from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipImageWebLinks(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.image_web_links', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_config.image_separate_columns
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterImageSeparateColumns(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.image_separate_columns', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_config.image_separate_columns from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipImageSeparateColumns(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.image_separate_columns', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_config.categories_separate_columns
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterCategoriesSeparateColumns(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.categories_separate_columns', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_config.categories_separate_columns from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipCategoriesSeparateColumns(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.categories_separate_columns', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_config.order_field_index
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterOrderFieldIndex(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.order_field_index', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_config.order_field_index from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipOrderFieldIndex(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.order_field_index', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_config.order_field_direction
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterOrderFieldDirection(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.order_field_direction', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_config.order_field_direction from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipOrderFieldDirection(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.order_field_direction', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_config.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_config.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_config.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_config.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_config.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_config.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_config.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_config.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by custom_lots_template_config.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out custom_lots_template_config.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
