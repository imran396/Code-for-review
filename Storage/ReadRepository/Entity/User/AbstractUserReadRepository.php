<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\User;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use User;

/**
 * Abstract class AbstractUserReadRepository
 * @method User[] loadEntities()
 * @method User|null loadEntity()
 */
abstract class AbstractUserReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_USER;
    protected string $alias = Db::A_USER;

    /**
     * Filter by user.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out user.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by user.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by user.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by user.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by user.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user.user_status_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserStatusId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_status_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user.user_status_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserStatusId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_status_id', $skipValue);
        return $this;
    }

    /**
     * Group by user.user_status_id
     * @return static
     */
    public function groupByUserStatusId(): static
    {
        $this->group($this->alias . '.user_status_id');
        return $this;
    }

    /**
     * Order by user.user_status_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserStatusId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_status_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user.user_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserStatusIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_status_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user.user_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserStatusIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_status_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user.user_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserStatusIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_status_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user.user_status_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserStatusIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_status_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user.customer_no
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCustomerNo(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.customer_no', $filterValue);
        return $this;
    }

    /**
     * Filter out user.customer_no from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCustomerNo(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.customer_no', $skipValue);
        return $this;
    }

    /**
     * Group by user.customer_no
     * @return static
     */
    public function groupByCustomerNo(): static
    {
        $this->group($this->alias . '.customer_no');
        return $this;
    }

    /**
     * Order by user.customer_no
     * @param bool $ascending
     * @return static
     */
    public function orderByCustomerNo(bool $ascending = true): static
    {
        $this->order($this->alias . '.customer_no', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user.customer_no
     * @param int $filterValue
     * @return static
     */
    public function filterCustomerNoGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.customer_no', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user.customer_no
     * @param int $filterValue
     * @return static
     */
    public function filterCustomerNoGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.customer_no', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user.customer_no
     * @param int $filterValue
     * @return static
     */
    public function filterCustomerNoLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.customer_no', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user.customer_no
     * @param int $filterValue
     * @return static
     */
    public function filterCustomerNoLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.customer_no', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user.use_permanent_bidderno
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterUsePermanentBidderno(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.use_permanent_bidderno', $filterValue);
        return $this;
    }

    /**
     * Filter out user.use_permanent_bidderno from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipUsePermanentBidderno(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.use_permanent_bidderno', $skipValue);
        return $this;
    }

    /**
     * Group by user.use_permanent_bidderno
     * @return static
     */
    public function groupByUsePermanentBidderno(): static
    {
        $this->group($this->alias . '.use_permanent_bidderno');
        return $this;
    }

    /**
     * Order by user.use_permanent_bidderno
     * @param bool $ascending
     * @return static
     */
    public function orderByUsePermanentBidderno(bool $ascending = true): static
    {
        $this->order($this->alias . '.use_permanent_bidderno', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user.use_permanent_bidderno
     * @param bool $filterValue
     * @return static
     */
    public function filterUsePermanentBiddernoGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.use_permanent_bidderno', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user.use_permanent_bidderno
     * @param bool $filterValue
     * @return static
     */
    public function filterUsePermanentBiddernoGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.use_permanent_bidderno', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user.use_permanent_bidderno
     * @param bool $filterValue
     * @return static
     */
    public function filterUsePermanentBiddernoLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.use_permanent_bidderno', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user.use_permanent_bidderno
     * @param bool $filterValue
     * @return static
     */
    public function filterUsePermanentBiddernoLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.use_permanent_bidderno', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user.username
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterUsername(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.username', $filterValue);
        return $this;
    }

    /**
     * Filter out user.username from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipUsername(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.username', $skipValue);
        return $this;
    }

    /**
     * Group by user.username
     * @return static
     */
    public function groupByUsername(): static
    {
        $this->group($this->alias . '.username');
        return $this;
    }

    /**
     * Order by user.username
     * @param bool $ascending
     * @return static
     */
    public function orderByUsername(bool $ascending = true): static
    {
        $this->order($this->alias . '.username', $ascending);
        return $this;
    }

    /**
     * Filter user.username by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeUsername(string $filterValue): static
    {
        $this->like($this->alias . '.username', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user.email
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEmail(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.email', $filterValue);
        return $this;
    }

    /**
     * Filter out user.email from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEmail(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.email', $skipValue);
        return $this;
    }

    /**
     * Group by user.email
     * @return static
     */
    public function groupByEmail(): static
    {
        $this->group($this->alias . '.email');
        return $this;
    }

    /**
     * Order by user.email
     * @param bool $ascending
     * @return static
     */
    public function orderByEmail(bool $ascending = true): static
    {
        $this->order($this->alias . '.email', $ascending);
        return $this;
    }

    /**
     * Filter user.email by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeEmail(string $filterValue): static
    {
        $this->like($this->alias . '.email', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user.pword
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPword(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pword', $filterValue);
        return $this;
    }

    /**
     * Filter out user.pword from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPword(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pword', $skipValue);
        return $this;
    }

    /**
     * Group by user.pword
     * @return static
     */
    public function groupByPword(): static
    {
        $this->group($this->alias . '.pword');
        return $this;
    }

    /**
     * Order by user.pword
     * @param bool $ascending
     * @return static
     */
    public function orderByPword(bool $ascending = true): static
    {
        $this->order($this->alias . '.pword', $ascending);
        return $this;
    }

    /**
     * Filter user.pword by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePword(string $filterValue): static
    {
        $this->like($this->alias . '.pword', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by user.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by user.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by user.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by user.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by user.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by user.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by user.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by user.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user.log_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLogDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.log_date', $filterValue);
        return $this;
    }

    /**
     * Filter out user.log_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLogDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.log_date', $skipValue);
        return $this;
    }

    /**
     * Group by user.log_date
     * @return static
     */
    public function groupByLogDate(): static
    {
        $this->group($this->alias . '.log_date');
        return $this;
    }

    /**
     * Order by user.log_date
     * @param bool $ascending
     * @return static
     */
    public function orderByLogDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.log_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user.log_date
     * @param string $filterValue
     * @return static
     */
    public function filterLogDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.log_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user.log_date
     * @param string $filterValue
     * @return static
     */
    public function filterLogDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.log_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user.log_date
     * @param string $filterValue
     * @return static
     */
    public function filterLogDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.log_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user.log_date
     * @param string $filterValue
     * @return static
     */
    public function filterLogDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.log_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user.added_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAddedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.added_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user.added_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAddedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.added_by', $skipValue);
        return $this;
    }

    /**
     * Group by user.added_by
     * @return static
     */
    public function groupByAddedBy(): static
    {
        $this->group($this->alias . '.added_by');
        return $this;
    }

    /**
     * Order by user.added_by
     * @param bool $ascending
     * @return static
     */
    public function orderByAddedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.added_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user.added_by
     * @param int $filterValue
     * @return static
     */
    public function filterAddedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.added_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user.added_by
     * @param int $filterValue
     * @return static
     */
    public function filterAddedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.added_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user.added_by
     * @param int $filterValue
     * @return static
     */
    public function filterAddedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.added_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user.added_by
     * @param int $filterValue
     * @return static
     */
    public function filterAddedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.added_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user.flag
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterFlag(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.flag', $filterValue);
        return $this;
    }

    /**
     * Filter out user.flag from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipFlag(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.flag', $skipValue);
        return $this;
    }

    /**
     * Group by user.flag
     * @return static
     */
    public function groupByFlag(): static
    {
        $this->group($this->alias . '.flag');
        return $this;
    }

    /**
     * Order by user.flag
     * @param bool $ascending
     * @return static
     */
    public function orderByFlag(bool $ascending = true): static
    {
        $this->order($this->alias . '.flag', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user.flag
     * @param int $filterValue
     * @return static
     */
    public function filterFlagGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.flag', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user.flag
     * @param int $filterValue
     * @return static
     */
    public function filterFlagGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.flag', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user.flag
     * @param int $filterValue
     * @return static
     */
    public function filterFlagLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.flag', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user.flag
     * @param int $filterValue
     * @return static
     */
    public function filterFlagLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.flag', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user.temp_added_by
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTempAddedBy(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.temp_added_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user.temp_added_by from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTempAddedBy(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.temp_added_by', $skipValue);
        return $this;
    }

    /**
     * Group by user.temp_added_by
     * @return static
     */
    public function groupByTempAddedBy(): static
    {
        $this->group($this->alias . '.temp_added_by');
        return $this;
    }

    /**
     * Order by user.temp_added_by
     * @param bool $ascending
     * @return static
     */
    public function orderByTempAddedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.temp_added_by', $ascending);
        return $this;
    }

    /**
     * Filter user.temp_added_by by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTempAddedBy(string $filterValue): static
    {
        $this->like($this->alias . '.temp_added_by', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out user.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by user.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by user.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
