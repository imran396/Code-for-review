<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuditTrail;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractAuditTrailDeleteRepository extends DeleteRepositoryBase
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
}
