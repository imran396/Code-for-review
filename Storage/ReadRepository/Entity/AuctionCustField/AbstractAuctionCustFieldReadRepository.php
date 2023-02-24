<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionCustField;

use AuctionCustField;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractAuctionCustFieldReadRepository
 * @method AuctionCustField[] loadEntities()
 * @method AuctionCustField|null loadEntity()
 */
abstract class AbstractAuctionCustFieldReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_AUCTION_CUST_FIELD;
    protected string $alias = Db::A_AUCTION_CUST_FIELD;

    /**
     * Filter by auction_cust_field.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cust_field.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cust_field.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by auction_cust_field.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cust_field.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cust_field.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cust_field.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cust_field.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cust_field.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cust_field.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cust_field.name
     * @return static
     */
    public function groupByName(): static
    {
        $this->group($this->alias . '.name');
        return $this;
    }

    /**
     * Order by auction_cust_field.name
     * @param bool $ascending
     * @return static
     */
    public function orderByName(bool $ascending = true): static
    {
        $this->order($this->alias . '.name', $ascending);
        return $this;
    }

    /**
     * Filter auction_cust_field.name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeName(string $filterValue): static
    {
        $this->like($this->alias . '.name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_cust_field.order
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterOrder(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.order', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cust_field.order from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipOrder(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.order', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cust_field.order
     * @return static
     */
    public function groupByOrder(): static
    {
        $this->group($this->alias . '.order');
        return $this;
    }

    /**
     * Order by auction_cust_field.order
     * @param bool $ascending
     * @return static
     */
    public function orderByOrder(bool $ascending = true): static
    {
        $this->order($this->alias . '.order', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cust_field.order
     * @param float $filterValue
     * @return static
     */
    public function filterOrderGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cust_field.order
     * @param float $filterValue
     * @return static
     */
    public function filterOrderGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cust_field.order
     * @param float $filterValue
     * @return static
     */
    public function filterOrderLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cust_field.order
     * @param float $filterValue
     * @return static
     */
    public function filterOrderLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cust_field.type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.type', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cust_field.type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.type', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cust_field.type
     * @return static
     */
    public function groupByType(): static
    {
        $this->group($this->alias . '.type');
        return $this;
    }

    /**
     * Order by auction_cust_field.type
     * @param bool $ascending
     * @return static
     */
    public function orderByType(bool $ascending = true): static
    {
        $this->order($this->alias . '.type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cust_field.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cust_field.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cust_field.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cust_field.type
     * @param int $filterValue
     * @return static
     */
    public function filterTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cust_field.parameters
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterParameters(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.parameters', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cust_field.parameters from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipParameters(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.parameters', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cust_field.parameters
     * @return static
     */
    public function groupByParameters(): static
    {
        $this->group($this->alias . '.parameters');
        return $this;
    }

    /**
     * Order by auction_cust_field.parameters
     * @param bool $ascending
     * @return static
     */
    public function orderByParameters(bool $ascending = true): static
    {
        $this->order($this->alias . '.parameters', $ascending);
        return $this;
    }

    /**
     * Filter auction_cust_field.parameters by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeParameters(string $filterValue): static
    {
        $this->like($this->alias . '.parameters', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_cust_field.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cust_field.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cust_field.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by auction_cust_field.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cust_field.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cust_field.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cust_field.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cust_field.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cust_field.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cust_field.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cust_field.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by auction_cust_field.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cust_field.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cust_field.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cust_field.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cust_field.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cust_field.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cust_field.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cust_field.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by auction_cust_field.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cust_field.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cust_field.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cust_field.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cust_field.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cust_field.modified_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cust_field.modified_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cust_field.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by auction_cust_field.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cust_field.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cust_field.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cust_field.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cust_field.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cust_field.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cust_field.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cust_field.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by auction_cust_field.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cust_field.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cust_field.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cust_field.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cust_field.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cust_field.required
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRequired(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.required', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cust_field.required from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRequired(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.required', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cust_field.required
     * @return static
     */
    public function groupByRequired(): static
    {
        $this->group($this->alias . '.required');
        return $this;
    }

    /**
     * Order by auction_cust_field.required
     * @param bool $ascending
     * @return static
     */
    public function orderByRequired(bool $ascending = true): static
    {
        $this->order($this->alias . '.required', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cust_field.required
     * @param bool $filterValue
     * @return static
     */
    public function filterRequiredGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.required', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cust_field.required
     * @param bool $filterValue
     * @return static
     */
    public function filterRequiredGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.required', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cust_field.required
     * @param bool $filterValue
     * @return static
     */
    public function filterRequiredLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.required', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cust_field.required
     * @param bool $filterValue
     * @return static
     */
    public function filterRequiredLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.required', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cust_field.clone
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterClone(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.clone', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cust_field.clone from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipClone(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.clone', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cust_field.clone
     * @return static
     */
    public function groupByClone(): static
    {
        $this->group($this->alias . '.clone');
        return $this;
    }

    /**
     * Order by auction_cust_field.clone
     * @param bool $ascending
     * @return static
     */
    public function orderByClone(bool $ascending = true): static
    {
        $this->order($this->alias . '.clone', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cust_field.clone
     * @param bool $filterValue
     * @return static
     */
    public function filterCloneGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.clone', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cust_field.clone
     * @param bool $filterValue
     * @return static
     */
    public function filterCloneGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.clone', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cust_field.clone
     * @param bool $filterValue
     * @return static
     */
    public function filterCloneLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.clone', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cust_field.clone
     * @param bool $filterValue
     * @return static
     */
    public function filterCloneLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.clone', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cust_field.public_list
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterPublicList(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.public_list', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cust_field.public_list from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipPublicList(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.public_list', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cust_field.public_list
     * @return static
     */
    public function groupByPublicList(): static
    {
        $this->group($this->alias . '.public_list');
        return $this;
    }

    /**
     * Order by auction_cust_field.public_list
     * @param bool $ascending
     * @return static
     */
    public function orderByPublicList(bool $ascending = true): static
    {
        $this->order($this->alias . '.public_list', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cust_field.public_list
     * @param bool $filterValue
     * @return static
     */
    public function filterPublicListGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.public_list', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cust_field.public_list
     * @param bool $filterValue
     * @return static
     */
    public function filterPublicListGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.public_list', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cust_field.public_list
     * @param bool $filterValue
     * @return static
     */
    public function filterPublicListLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.public_list', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cust_field.public_list
     * @param bool $filterValue
     * @return static
     */
    public function filterPublicListLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.public_list', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cust_field.admin_list
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAdminList(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.admin_list', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cust_field.admin_list from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAdminList(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.admin_list', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cust_field.admin_list
     * @return static
     */
    public function groupByAdminList(): static
    {
        $this->group($this->alias . '.admin_list');
        return $this;
    }

    /**
     * Order by auction_cust_field.admin_list
     * @param bool $ascending
     * @return static
     */
    public function orderByAdminList(bool $ascending = true): static
    {
        $this->order($this->alias . '.admin_list', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cust_field.admin_list
     * @param bool $filterValue
     * @return static
     */
    public function filterAdminListGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.admin_list', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cust_field.admin_list
     * @param bool $filterValue
     * @return static
     */
    public function filterAdminListGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.admin_list', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cust_field.admin_list
     * @param bool $filterValue
     * @return static
     */
    public function filterAdminListLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.admin_list', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cust_field.admin_list
     * @param bool $filterValue
     * @return static
     */
    public function filterAdminListLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.admin_list', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_cust_field.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_cust_field.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by auction_cust_field.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by auction_cust_field.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_cust_field.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_cust_field.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_cust_field.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_cust_field.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
