<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\MailingListTemplateCategories;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractMailingListTemplateCategoriesDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_MAILING_LIST_TEMPLATE_CATEGORIES;
    protected string $alias = Db::A_MAILING_LIST_TEMPLATE_CATEGORIES;

    /**
     * Filter by mailing_list_template_categories.mailing_list_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterMailingListId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.mailing_list_id', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_template_categories.mailing_list_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipMailingListId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.mailing_list_id', $skipValue);
        return $this;
    }

    /**
     * Filter by mailing_list_template_categories.category_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterCategoryId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.category_id', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_template_categories.category_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipCategoryId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.category_id', $skipValue);
        return $this;
    }

    /**
     * Filter by mailing_list_template_categories.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_template_categories.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by mailing_list_template_categories.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_template_categories.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by mailing_list_template_categories.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_template_categories.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by mailing_list_template_categories.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_template_categories.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by mailing_list_template_categories.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_template_categories.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
