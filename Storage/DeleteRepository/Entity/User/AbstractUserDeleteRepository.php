<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\User;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractUserDeleteRepository extends DeleteRepositoryBase
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
}
