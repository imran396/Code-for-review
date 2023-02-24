<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserAuthentication;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractUserAuthenticationDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_USER_AUTHENTICATION;
    protected string $alias = Db::A_USER_AUTHENTICATION;

    /**
     * Filter by user_authentication.user_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterUserId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_authentication.user_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipUserId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Filter by user_authentication.tmp_pword
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTmpPword(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tmp_pword', $filterValue);
        return $this;
    }

    /**
     * Filter out user_authentication.tmp_pword from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTmpPword(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tmp_pword', $skipValue);
        return $this;
    }

    /**
     * Filter by user_authentication.tmp_pword_ts
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterTmpPwordTs(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tmp_pword_ts', $filterValue);
        return $this;
    }

    /**
     * Filter out user_authentication.tmp_pword_ts from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipTmpPwordTs(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tmp_pword_ts', $skipValue);
        return $this;
    }

    /**
     * Filter by user_authentication.pword_history
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPwordHistory(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pword_history', $filterValue);
        return $this;
    }

    /**
     * Filter out user_authentication.pword_history from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPwordHistory(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pword_history', $skipValue);
        return $this;
    }

    /**
     * Filter by user_authentication.pword_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterPwordDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.pword_date', $filterValue);
        return $this;
    }

    /**
     * Filter out user_authentication.pword_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipPwordDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.pword_date', $skipValue);
        return $this;
    }

    /**
     * Filter by user_authentication.failed_login_attempt
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterFailedLoginAttempt(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.failed_login_attempt', $filterValue);
        return $this;
    }

    /**
     * Filter out user_authentication.failed_login_attempt from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipFailedLoginAttempt(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.failed_login_attempt', $skipValue);
        return $this;
    }

    /**
     * Filter by user_authentication.login_lockout
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterLoginLockout(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.login_lockout', $filterValue);
        return $this;
    }

    /**
     * Filter out user_authentication.login_lockout from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipLoginLockout(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.login_lockout', $skipValue);
        return $this;
    }

    /**
     * Filter by user_authentication.verification_code
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterVerificationCode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.verification_code', $filterValue);
        return $this;
    }

    /**
     * Filter out user_authentication.verification_code from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipVerificationCode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.verification_code', $skipValue);
        return $this;
    }

    /**
     * Filter by user_authentication.email_verified
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterEmailVerified(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.email_verified', $filterValue);
        return $this;
    }

    /**
     * Filter out user_authentication.email_verified from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipEmailVerified(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.email_verified', $skipValue);
        return $this;
    }

    /**
     * Filter by user_authentication.verification_code_generated_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterVerificationCodeGeneratedDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.verification_code_generated_date', $filterValue);
        return $this;
    }

    /**
     * Filter out user_authentication.verification_code_generated_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipVerificationCodeGeneratedDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.verification_code_generated_date', $skipValue);
        return $this;
    }

    /**
     * Filter by user_authentication.idp_uuid
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterIdpUuid(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.idp_uuid', $filterValue);
        return $this;
    }

    /**
     * Filter out user_authentication.idp_uuid from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipIdpUuid(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.idp_uuid', $skipValue);
        return $this;
    }

    /**
     * Filter by user_authentication.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out user_authentication.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by user_authentication.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_authentication.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by user_authentication.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_authentication.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by user_authentication.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_authentication.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by user_authentication.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_authentication.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
