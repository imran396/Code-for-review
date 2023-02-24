<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuditTrail;

use AuditTrail;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractAuditTrailReadRepository
 * @method AuditTrail[] loadEntities()
 * @method AuditTrail|null loadEntity()
 */
abstract class AbstractAuditTrailReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_AUDIT_TRAIL;
    protected string $alias = Db::A_AUDIT_TRAIL;

    /**
     * Filter by audit_trail.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out audit_trail.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by audit_trail.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by audit_trail.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than audit_trail.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than audit_trail.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than audit_trail.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than audit_trail.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by audit_trail.timestamp
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTimestamp(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.timestamp', $filterValue);
        return $this;
    }

    /**
     * Filter out audit_trail.timestamp from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTimestamp(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.timestamp', $skipValue);
        return $this;
    }

    /**
     * Group by audit_trail.timestamp
     * @return static
     */
    public function groupByTimestamp(): static
    {
        $this->group($this->alias . '.timestamp');
        return $this;
    }

    /**
     * Order by audit_trail.timestamp
     * @param bool $ascending
     * @return static
     */
    public function orderByTimestamp(bool $ascending = true): static
    {
        $this->order($this->alias . '.timestamp', $ascending);
        return $this;
    }

    /**
     * Filter by greater than audit_trail.timestamp
     * @param string $filterValue
     * @return static
     */
    public function filterTimestampGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.timestamp', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than audit_trail.timestamp
     * @param string $filterValue
     * @return static
     */
    public function filterTimestampGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.timestamp', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than audit_trail.timestamp
     * @param string $filterValue
     * @return static
     */
    public function filterTimestampLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.timestamp', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than audit_trail.timestamp
     * @param string $filterValue
     * @return static
     */
    public function filterTimestampLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.timestamp', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by audit_trail.ms
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterMs(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.ms', $filterValue);
        return $this;
    }

    /**
     * Filter out audit_trail.ms from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipMs(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.ms', $skipValue);
        return $this;
    }

    /**
     * Group by audit_trail.ms
     * @return static
     */
    public function groupByMs(): static
    {
        $this->group($this->alias . '.ms');
        return $this;
    }

    /**
     * Order by audit_trail.ms
     * @param bool $ascending
     * @return static
     */
    public function orderByMs(bool $ascending = true): static
    {
        $this->order($this->alias . '.ms', $ascending);
        return $this;
    }

    /**
     * Filter by greater than audit_trail.ms
     * @param int $filterValue
     * @return static
     */
    public function filterMsGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.ms', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than audit_trail.ms
     * @param int $filterValue
     * @return static
     */
    public function filterMsGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.ms', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than audit_trail.ms
     * @param int $filterValue
     * @return static
     */
    public function filterMsLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.ms', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than audit_trail.ms
     * @param int $filterValue
     * @return static
     */
    public function filterMsLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.ms', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by audit_trail.ip
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterIp(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.ip', $filterValue);
        return $this;
    }

    /**
     * Filter out audit_trail.ip from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipIp(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.ip', $skipValue);
        return $this;
    }

    /**
     * Group by audit_trail.ip
     * @return static
     */
    public function groupByIp(): static
    {
        $this->group($this->alias . '.ip');
        return $this;
    }

    /**
     * Order by audit_trail.ip
     * @param bool $ascending
     * @return static
     */
    public function orderByIp(bool $ascending = true): static
    {
        $this->order($this->alias . '.ip', $ascending);
        return $this;
    }

    /**
     * Filter by greater than audit_trail.ip
     * @param int $filterValue
     * @return static
     */
    public function filterIpGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.ip', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than audit_trail.ip
     * @param int $filterValue
     * @return static
     */
    public function filterIpGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.ip', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than audit_trail.ip
     * @param int $filterValue
     * @return static
     */
    public function filterIpLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.ip', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than audit_trail.ip
     * @param int $filterValue
     * @return static
     */
    public function filterIpLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.ip', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by audit_trail.port
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterPort(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.port', $filterValue);
        return $this;
    }

    /**
     * Filter out audit_trail.port from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipPort(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.port', $skipValue);
        return $this;
    }

    /**
     * Group by audit_trail.port
     * @return static
     */
    public function groupByPort(): static
    {
        $this->group($this->alias . '.port');
        return $this;
    }

    /**
     * Order by audit_trail.port
     * @param bool $ascending
     * @return static
     */
    public function orderByPort(bool $ascending = true): static
    {
        $this->order($this->alias . '.port', $ascending);
        return $this;
    }

    /**
     * Filter by greater than audit_trail.port
     * @param int $filterValue
     * @return static
     */
    public function filterPortGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.port', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than audit_trail.port
     * @param int $filterValue
     * @return static
     */
    public function filterPortGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.port', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than audit_trail.port
     * @param int $filterValue
     * @return static
     */
    public function filterPortLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.port', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than audit_trail.port
     * @param int $filterValue
     * @return static
     */
    public function filterPortLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.port', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by audit_trail.user_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out audit_trail.user_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Group by audit_trail.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by audit_trail.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than audit_trail.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than audit_trail.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than audit_trail.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than audit_trail.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by audit_trail.proxy_user_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterProxyUserId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.proxy_user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out audit_trail.proxy_user_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipProxyUserId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.proxy_user_id', $skipValue);
        return $this;
    }

    /**
     * Group by audit_trail.proxy_user_id
     * @return static
     */
    public function groupByProxyUserId(): static
    {
        $this->group($this->alias . '.proxy_user_id');
        return $this;
    }

    /**
     * Order by audit_trail.proxy_user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByProxyUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.proxy_user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than audit_trail.proxy_user_id
     * @param int $filterValue
     * @return static
     */
    public function filterProxyUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.proxy_user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than audit_trail.proxy_user_id
     * @param int $filterValue
     * @return static
     */
    public function filterProxyUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.proxy_user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than audit_trail.proxy_user_id
     * @param int $filterValue
     * @return static
     */
    public function filterProxyUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.proxy_user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than audit_trail.proxy_user_id
     * @param int $filterValue
     * @return static
     */
    public function filterProxyUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.proxy_user_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by audit_trail.section
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSection(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.section', $filterValue);
        return $this;
    }

    /**
     * Filter out audit_trail.section from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSection(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.section', $skipValue);
        return $this;
    }

    /**
     * Group by audit_trail.section
     * @return static
     */
    public function groupBySection(): static
    {
        $this->group($this->alias . '.section');
        return $this;
    }

    /**
     * Order by audit_trail.section
     * @param bool $ascending
     * @return static
     */
    public function orderBySection(bool $ascending = true): static
    {
        $this->order($this->alias . '.section', $ascending);
        return $this;
    }

    /**
     * Filter audit_trail.section by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSection(string $filterValue): static
    {
        $this->like($this->alias . '.section', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by audit_trail.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out audit_trail.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by audit_trail.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by audit_trail.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than audit_trail.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than audit_trail.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than audit_trail.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than audit_trail.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by audit_trail.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out audit_trail.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by audit_trail.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by audit_trail.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than audit_trail.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than audit_trail.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than audit_trail.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than audit_trail.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by audit_trail.event
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEvent(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.event', $filterValue);
        return $this;
    }

    /**
     * Filter out audit_trail.event from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEvent(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.event', $skipValue);
        return $this;
    }

    /**
     * Group by audit_trail.event
     * @return static
     */
    public function groupByEvent(): static
    {
        $this->group($this->alias . '.event');
        return $this;
    }

    /**
     * Order by audit_trail.event
     * @param bool $ascending
     * @return static
     */
    public function orderByEvent(bool $ascending = true): static
    {
        $this->order($this->alias . '.event', $ascending);
        return $this;
    }

    /**
     * Filter audit_trail.event by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeEvent(string $filterValue): static
    {
        $this->like($this->alias . '.event', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by audit_trail.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out audit_trail.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by audit_trail.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by audit_trail.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than audit_trail.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than audit_trail.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than audit_trail.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than audit_trail.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by audit_trail.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out audit_trail.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by audit_trail.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by audit_trail.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than audit_trail.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than audit_trail.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than audit_trail.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than audit_trail.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by audit_trail.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out audit_trail.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by audit_trail.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by audit_trail.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than audit_trail.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than audit_trail.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than audit_trail.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than audit_trail.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by audit_trail.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out audit_trail.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by audit_trail.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by audit_trail.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than audit_trail.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than audit_trail.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than audit_trail.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than audit_trail.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
