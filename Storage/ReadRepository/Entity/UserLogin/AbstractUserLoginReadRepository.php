<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserLogin;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use UserLogin;

/**
 * Abstract class AbstractUserLoginReadRepository
 * @method UserLogin[] loadEntities()
 * @method UserLogin|null loadEntity()
 */
abstract class AbstractUserLoginReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_USER_LOGIN;
    protected string $alias = Db::A_USER_LOGIN;

    /**
     * Filter by user_login.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_login.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by user_login.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by user_login.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_login.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_login.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_login.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_login.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_login.user_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_login.user_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_login.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by user_login.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_login.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_login.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_login.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_login.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_login.ip_address
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterIpAddress(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.ip_address', $filterValue);
        return $this;
    }

    /**
     * Filter out user_login.ip_address from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipIpAddress(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.ip_address', $skipValue);
        return $this;
    }

    /**
     * Group by user_login.ip_address
     * @return static
     */
    public function groupByIpAddress(): static
    {
        $this->group($this->alias . '.ip_address');
        return $this;
    }

    /**
     * Order by user_login.ip_address
     * @param bool $ascending
     * @return static
     */
    public function orderByIpAddress(bool $ascending = true): static
    {
        $this->order($this->alias . '.ip_address', $ascending);
        return $this;
    }

    /**
     * Filter user_login.ip_address by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeIpAddress(string $filterValue): static
    {
        $this->like($this->alias . '.ip_address', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_login.logged_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLoggedDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.logged_date', $filterValue);
        return $this;
    }

    /**
     * Filter out user_login.logged_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLoggedDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.logged_date', $skipValue);
        return $this;
    }

    /**
     * Group by user_login.logged_date
     * @return static
     */
    public function groupByLoggedDate(): static
    {
        $this->group($this->alias . '.logged_date');
        return $this;
    }

    /**
     * Order by user_login.logged_date
     * @param bool $ascending
     * @return static
     */
    public function orderByLoggedDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.logged_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_login.logged_date
     * @param string $filterValue
     * @return static
     */
    public function filterLoggedDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.logged_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_login.logged_date
     * @param string $filterValue
     * @return static
     */
    public function filterLoggedDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.logged_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_login.logged_date
     * @param string $filterValue
     * @return static
     */
    public function filterLoggedDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.logged_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_login.logged_date
     * @param string $filterValue
     * @return static
     */
    public function filterLoggedDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.logged_date', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_login.blocked
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterBlocked(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.blocked', $filterValue);
        return $this;
    }

    /**
     * Filter out user_login.blocked from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipBlocked(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.blocked', $skipValue);
        return $this;
    }

    /**
     * Group by user_login.blocked
     * @return static
     */
    public function groupByBlocked(): static
    {
        $this->group($this->alias . '.blocked');
        return $this;
    }

    /**
     * Order by user_login.blocked
     * @param bool $ascending
     * @return static
     */
    public function orderByBlocked(bool $ascending = true): static
    {
        $this->order($this->alias . '.blocked', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_login.blocked
     * @param bool $filterValue
     * @return static
     */
    public function filterBlockedGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.blocked', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_login.blocked
     * @param bool $filterValue
     * @return static
     */
    public function filterBlockedGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.blocked', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_login.blocked
     * @param bool $filterValue
     * @return static
     */
    public function filterBlockedLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.blocked', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_login.blocked
     * @param bool $filterValue
     * @return static
     */
    public function filterBlockedLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.blocked', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_login.sess_id
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSessId(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.sess_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_login.sess_id from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSessId(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.sess_id', $skipValue);
        return $this;
    }

    /**
     * Group by user_login.sess_id
     * @return static
     */
    public function groupBySessId(): static
    {
        $this->group($this->alias . '.sess_id');
        return $this;
    }

    /**
     * Order by user_login.sess_id
     * @param bool $ascending
     * @return static
     */
    public function orderBySessId(bool $ascending = true): static
    {
        $this->order($this->alias . '.sess_id', $ascending);
        return $this;
    }

    /**
     * Filter user_login.sess_id by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSessId(string $filterValue): static
    {
        $this->like($this->alias . '.sess_id', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by user_login.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out user_login.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by user_login.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by user_login.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_login.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_login.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_login.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_login.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_login.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_login.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_login.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by user_login.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_login.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_login.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_login.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_login.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_login.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_login.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by user_login.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by user_login.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_login.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_login.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_login.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_login.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_login.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_login.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by user_login.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by user_login.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_login.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_login.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_login.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_login.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by user_login.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_login.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by user_login.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by user_login.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_login.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_login.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_login.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_login.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
