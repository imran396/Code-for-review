<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserCustField;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use UserCustField;

/**
 * Abstract class AbstractUserCustFieldReadRepository
 * @method UserCustField[] loadEntities()
 * @method UserCustField|null loadEntity()
 */
abstract class AbstractUserCustFieldReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_USER_CUST_FIELD;
    protected string $alias = Db::A_USER_CUST_FIELD;

    /**
     * Filter by user_cust_field.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by user_cust_field.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_field.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_field.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_field.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_field.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_field.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.name
     * @return static
     */
    public function groupByName(): static
    {
        $this->group($this->alias . '.name');
        return $this;
    }

    /**
     * Order by user_cust_field.name
     * @param bool $ascending
     * @return static
     */
    public function orderByName(bool $ascending = true): static
    {
        $this->order($this->alias . '.name', $ascending);
        return $this;
    }

    /**
     * Filter user_cust_field.name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeName(string $filterValue): static
    {
        $this->like($this->alias . '.name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_cust_field.order
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterOrder(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.order', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.order from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipOrder(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.order', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.order
     * @return static
     */
    public function groupByOrder(): static
    {
        $this->group($this->alias . '.order');
        return $this;
    }

    /**
     * Order by user_cust_field.order
     * @param bool $ascending
     * @return static
     */
    public function orderByOrder(bool $ascending = true): static
    {
        $this->order($this->alias . '.order', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_field.order
     * @param float $filterValue
     * @return static
     */
    public function filterOrderGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_field.order
     * @param float $filterValue
     * @return static
     */
    public function filterOrderGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_field.order
     * @param float $filterValue
     * @return static
     */
    public function filterOrderLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_field.order
     * @param float $filterValue
     * @return static
     */
    public function filterOrderLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_field.type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.type', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.type', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.type
     * @return static
     */
    public function groupByType(): static
    {
        $this->group($this->alias . '.type');
        return $this;
    }

    /**
     * Order by user_cust_field.type
     * @param bool $ascending
     * @return static
     */
    public function orderByType(bool $ascending = true): static
    {
        $this->order($this->alias . '.type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_field.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_field.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_field.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_field.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_field.panel
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterPanel(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.panel', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.panel from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipPanel(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.panel', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.panel
     * @return static
     */
    public function groupByPanel(): static
    {
        $this->group($this->alias . '.panel');
        return $this;
    }

    /**
     * Order by user_cust_field.panel
     * @param bool $ascending
     * @return static
     */
    public function orderByPanel(bool $ascending = true): static
    {
        $this->order($this->alias . '.panel', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_field.panel
     * @param int $filterValue
     * @return static
     */
    public function filterPanelGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.panel', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_field.panel
     * @param int $filterValue
     * @return static
     */
    public function filterPanelGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.panel', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_field.panel
     * @param int $filterValue
     * @return static
     */
    public function filterPanelLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.panel', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_field.panel
     * @param int $filterValue
     * @return static
     */
    public function filterPanelLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.panel', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_field.parameters
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterParameters(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.parameters', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.parameters from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipParameters(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.parameters', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.parameters
     * @return static
     */
    public function groupByParameters(): static
    {
        $this->group($this->alias . '.parameters');
        return $this;
    }

    /**
     * Order by user_cust_field.parameters
     * @param bool $ascending
     * @return static
     */
    public function orderByParameters(bool $ascending = true): static
    {
        $this->order($this->alias . '.parameters', $ascending);
        return $this;
    }

    /**
     * Filter user_cust_field.parameters by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeParameters(string $filterValue): static
    {
        $this->like($this->alias . '.parameters', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_cust_field.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by user_cust_field.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_field.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_field.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_field.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_field.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_field.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by user_cust_field.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_field.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_field.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_field.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_field.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_field.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by user_cust_field.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_field.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_field.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_field.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_field.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_field.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by user_cust_field.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_field.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_field.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_field.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_field.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_field.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by user_cust_field.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_field.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_field.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_field.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_field.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_field.required
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRequired(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.required', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.required from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRequired(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.required', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.required
     * @return static
     */
    public function groupByRequired(): static
    {
        $this->group($this->alias . '.required');
        return $this;
    }

    /**
     * Order by user_cust_field.required
     * @param bool $ascending
     * @return static
     */
    public function orderByRequired(bool $ascending = true): static
    {
        $this->order($this->alias . '.required', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_field.required
     * @param bool $filterValue
     * @return static
     */
    public function filterRequiredGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.required', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_field.required
     * @param bool $filterValue
     * @return static
     */
    public function filterRequiredGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.required', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_field.required
     * @param bool $filterValue
     * @return static
     */
    public function filterRequiredLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.required', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_field.required
     * @param bool $filterValue
     * @return static
     */
    public function filterRequiredLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.required', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_field.on_registration
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterOnRegistration(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.on_registration', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.on_registration from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipOnRegistration(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.on_registration', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.on_registration
     * @return static
     */
    public function groupByOnRegistration(): static
    {
        $this->group($this->alias . '.on_registration');
        return $this;
    }

    /**
     * Order by user_cust_field.on_registration
     * @param bool $ascending
     * @return static
     */
    public function orderByOnRegistration(bool $ascending = true): static
    {
        $this->order($this->alias . '.on_registration', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_field.on_registration
     * @param bool $filterValue
     * @return static
     */
    public function filterOnRegistrationGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_registration', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_field.on_registration
     * @param bool $filterValue
     * @return static
     */
    public function filterOnRegistrationGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_registration', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_field.on_registration
     * @param bool $filterValue
     * @return static
     */
    public function filterOnRegistrationLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_registration', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_field.on_registration
     * @param bool $filterValue
     * @return static
     */
    public function filterOnRegistrationLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_registration', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_field.on_profile
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterOnProfile(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.on_profile', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.on_profile from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipOnProfile(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.on_profile', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.on_profile
     * @return static
     */
    public function groupByOnProfile(): static
    {
        $this->group($this->alias . '.on_profile');
        return $this;
    }

    /**
     * Order by user_cust_field.on_profile
     * @param bool $ascending
     * @return static
     */
    public function orderByOnProfile(bool $ascending = true): static
    {
        $this->order($this->alias . '.on_profile', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_field.on_profile
     * @param bool $filterValue
     * @return static
     */
    public function filterOnProfileGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_profile', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_field.on_profile
     * @param bool $filterValue
     * @return static
     */
    public function filterOnProfileGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_profile', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_field.on_profile
     * @param bool $filterValue
     * @return static
     */
    public function filterOnProfileLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_profile', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_field.on_profile
     * @param bool $filterValue
     * @return static
     */
    public function filterOnProfileLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_profile', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_field.encrypted
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterEncrypted(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.encrypted', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.encrypted from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipEncrypted(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.encrypted', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.encrypted
     * @return static
     */
    public function groupByEncrypted(): static
    {
        $this->group($this->alias . '.encrypted');
        return $this;
    }

    /**
     * Order by user_cust_field.encrypted
     * @param bool $ascending
     * @return static
     */
    public function orderByEncrypted(bool $ascending = true): static
    {
        $this->order($this->alias . '.encrypted', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_field.encrypted
     * @param bool $filterValue
     * @return static
     */
    public function filterEncryptedGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.encrypted', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_field.encrypted
     * @param bool $filterValue
     * @return static
     */
    public function filterEncryptedGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.encrypted', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_field.encrypted
     * @param bool $filterValue
     * @return static
     */
    public function filterEncryptedLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.encrypted', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_field.encrypted
     * @param bool $filterValue
     * @return static
     */
    public function filterEncryptedLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.encrypted', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_field.in_admin_search
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInAdminSearch(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.in_admin_search', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.in_admin_search from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInAdminSearch(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.in_admin_search', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.in_admin_search
     * @return static
     */
    public function groupByInAdminSearch(): static
    {
        $this->group($this->alias . '.in_admin_search');
        return $this;
    }

    /**
     * Order by user_cust_field.in_admin_search
     * @param bool $ascending
     * @return static
     */
    public function orderByInAdminSearch(bool $ascending = true): static
    {
        $this->order($this->alias . '.in_admin_search', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_field.in_admin_search
     * @param bool $filterValue
     * @return static
     */
    public function filterInAdminSearchGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_admin_search', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_field.in_admin_search
     * @param bool $filterValue
     * @return static
     */
    public function filterInAdminSearchGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_admin_search', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_field.in_admin_search
     * @param bool $filterValue
     * @return static
     */
    public function filterInAdminSearchLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_admin_search', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_field.in_admin_search
     * @param bool $filterValue
     * @return static
     */
    public function filterInAdminSearchLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_admin_search', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_field.in_admin_catalog
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInAdminCatalog(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.in_admin_catalog', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.in_admin_catalog from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInAdminCatalog(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.in_admin_catalog', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.in_admin_catalog
     * @return static
     */
    public function groupByInAdminCatalog(): static
    {
        $this->group($this->alias . '.in_admin_catalog');
        return $this;
    }

    /**
     * Order by user_cust_field.in_admin_catalog
     * @param bool $ascending
     * @return static
     */
    public function orderByInAdminCatalog(bool $ascending = true): static
    {
        $this->order($this->alias . '.in_admin_catalog', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_field.in_admin_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterInAdminCatalogGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_admin_catalog', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_field.in_admin_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterInAdminCatalogGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_admin_catalog', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_field.in_admin_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterInAdminCatalogLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_admin_catalog', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_field.in_admin_catalog
     * @param bool $filterValue
     * @return static
     */
    public function filterInAdminCatalogLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_admin_catalog', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_field.in_invoices
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInInvoices(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.in_invoices', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.in_invoices from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInInvoices(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.in_invoices', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.in_invoices
     * @return static
     */
    public function groupByInInvoices(): static
    {
        $this->group($this->alias . '.in_invoices');
        return $this;
    }

    /**
     * Order by user_cust_field.in_invoices
     * @param bool $ascending
     * @return static
     */
    public function orderByInInvoices(bool $ascending = true): static
    {
        $this->order($this->alias . '.in_invoices', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_field.in_invoices
     * @param bool $filterValue
     * @return static
     */
    public function filterInInvoicesGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_invoices', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_field.in_invoices
     * @param bool $filterValue
     * @return static
     */
    public function filterInInvoicesGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_invoices', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_field.in_invoices
     * @param bool $filterValue
     * @return static
     */
    public function filterInInvoicesLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_invoices', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_field.in_invoices
     * @param bool $filterValue
     * @return static
     */
    public function filterInInvoicesLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_invoices', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_field.in_settlements
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterInSettlements(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.in_settlements', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.in_settlements from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipInSettlements(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.in_settlements', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.in_settlements
     * @return static
     */
    public function groupByInSettlements(): static
    {
        $this->group($this->alias . '.in_settlements');
        return $this;
    }

    /**
     * Order by user_cust_field.in_settlements
     * @param bool $ascending
     * @return static
     */
    public function orderByInSettlements(bool $ascending = true): static
    {
        $this->order($this->alias . '.in_settlements', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_field.in_settlements
     * @param bool $filterValue
     * @return static
     */
    public function filterInSettlementsGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_settlements', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_field.in_settlements
     * @param bool $filterValue
     * @return static
     */
    public function filterInSettlementsGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_settlements', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_field.in_settlements
     * @param bool $filterValue
     * @return static
     */
    public function filterInSettlementsLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_settlements', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_field.in_settlements
     * @param bool $filterValue
     * @return static
     */
    public function filterInSettlementsLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.in_settlements', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_field.on_add_new_bidder
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterOnAddNewBidder(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.on_add_new_bidder', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.on_add_new_bidder from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipOnAddNewBidder(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.on_add_new_bidder', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.on_add_new_bidder
     * @return static
     */
    public function groupByOnAddNewBidder(): static
    {
        $this->group($this->alias . '.on_add_new_bidder');
        return $this;
    }

    /**
     * Order by user_cust_field.on_add_new_bidder
     * @param bool $ascending
     * @return static
     */
    public function orderByOnAddNewBidder(bool $ascending = true): static
    {
        $this->order($this->alias . '.on_add_new_bidder', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_field.on_add_new_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterOnAddNewBidderGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_add_new_bidder', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_field.on_add_new_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterOnAddNewBidderGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_add_new_bidder', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_field.on_add_new_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterOnAddNewBidderLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_add_new_bidder', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_field.on_add_new_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterOnAddNewBidderLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_add_new_bidder', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_cust_field.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out user_cust_field.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by user_cust_field.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by user_cust_field.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_cust_field.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_cust_field.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_cust_field.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_cust_field.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
