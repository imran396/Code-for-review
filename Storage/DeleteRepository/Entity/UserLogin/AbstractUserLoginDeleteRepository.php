<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserLogin;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractUserLoginDeleteRepository extends DeleteRepositoryBase
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
}
