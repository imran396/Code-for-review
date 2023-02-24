<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\MailingListTemplateCategories;

use MailingListTemplateCategories;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractMailingListTemplateCategoriesReadRepository
 * @method MailingListTemplateCategories[] loadEntities()
 * @method MailingListTemplateCategories|null loadEntity()
 */
abstract class AbstractMailingListTemplateCategoriesReadRepository extends ReadRepositoryBase
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
     * Group by mailing_list_template_categories.mailing_list_id
     * @return static
     */
    public function groupByMailingListId(): static
    {
        $this->group($this->alias . '.mailing_list_id');
        return $this;
    }

    /**
     * Order by mailing_list_template_categories.mailing_list_id
     * @param bool $ascending
     * @return static
     */
    public function orderByMailingListId(bool $ascending = true): static
    {
        $this->order($this->alias . '.mailing_list_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_template_categories.mailing_list_id
     * @param int $filterValue
     * @return static
     */
    public function filterMailingListIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.mailing_list_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_template_categories.mailing_list_id
     * @param int $filterValue
     * @return static
     */
    public function filterMailingListIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.mailing_list_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_template_categories.mailing_list_id
     * @param int $filterValue
     * @return static
     */
    public function filterMailingListIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.mailing_list_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_template_categories.mailing_list_id
     * @param int $filterValue
     * @return static
     */
    public function filterMailingListIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.mailing_list_id', $filterValue, '<=');
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
     * Group by mailing_list_template_categories.category_id
     * @return static
     */
    public function groupByCategoryId(): static
    {
        $this->group($this->alias . '.category_id');
        return $this;
    }

    /**
     * Order by mailing_list_template_categories.category_id
     * @param bool $ascending
     * @return static
     */
    public function orderByCategoryId(bool $ascending = true): static
    {
        $this->order($this->alias . '.category_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_template_categories.category_id
     * @param int $filterValue
     * @return static
     */
    public function filterCategoryIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.category_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_template_categories.category_id
     * @param int $filterValue
     * @return static
     */
    public function filterCategoryIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.category_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_template_categories.category_id
     * @param int $filterValue
     * @return static
     */
    public function filterCategoryIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.category_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_template_categories.category_id
     * @param int $filterValue
     * @return static
     */
    public function filterCategoryIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.category_id', $filterValue, '<=');
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
     * Group by mailing_list_template_categories.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by mailing_list_template_categories.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_template_categories.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_template_categories.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_template_categories.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_template_categories.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
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
     * Group by mailing_list_template_categories.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by mailing_list_template_categories.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_template_categories.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_template_categories.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_template_categories.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_template_categories.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by mailing_list_template_categories.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by mailing_list_template_categories.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_template_categories.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_template_categories.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_template_categories.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_template_categories.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by mailing_list_template_categories.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by mailing_list_template_categories.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_template_categories.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_template_categories.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_template_categories.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_template_categories.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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

    /**
     * Group by mailing_list_template_categories.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by mailing_list_template_categories.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_template_categories.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_template_categories.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_template_categories.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_template_categories.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
