<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserAuthentication;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use UserAuthentication;

/**
 * Abstract class AbstractUserAuthenticationReadRepository
 * @method UserAuthentication[] loadEntities()
 * @method UserAuthentication|null loadEntity()
 */
abstract class AbstractUserAuthenticationReadRepository extends ReadRepositoryBase
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
     * Group by user_authentication.user_id
     * @return static
     */
    public function groupByUserId(): static
    {
        $this->group($this->alias . '.user_id');
        return $this;
    }

    /**
     * Order by user_authentication.user_id
     * @param bool $ascending
     * @return static
     */
    public function orderByUserId(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_authentication.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_authentication.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_authentication.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_authentication.user_id
     * @param int $filterValue
     * @return static
     */
    public function filterUserIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_id', $filterValue, '<=');
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
     * Group by user_authentication.tmp_pword
     * @return static
     */
    public function groupByTmpPword(): static
    {
        $this->group($this->alias . '.tmp_pword');
        return $this;
    }

    /**
     * Order by user_authentication.tmp_pword
     * @param bool $ascending
     * @return static
     */
    public function orderByTmpPword(bool $ascending = true): static
    {
        $this->order($this->alias . '.tmp_pword', $ascending);
        return $this;
    }

    /**
     * Filter user_authentication.tmp_pword by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTmpPword(string $filterValue): static
    {
        $this->like($this->alias . '.tmp_pword', "%{$filterValue}%");
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
     * Group by user_authentication.tmp_pword_ts
     * @return static
     */
    public function groupByTmpPwordTs(): static
    {
        $this->group($this->alias . '.tmp_pword_ts');
        return $this;
    }

    /**
     * Order by user_authentication.tmp_pword_ts
     * @param bool $ascending
     * @return static
     */
    public function orderByTmpPwordTs(bool $ascending = true): static
    {
        $this->order($this->alias . '.tmp_pword_ts', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_authentication.tmp_pword_ts
     * @param string $filterValue
     * @return static
     */
    public function filterTmpPwordTsGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.tmp_pword_ts', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_authentication.tmp_pword_ts
     * @param string $filterValue
     * @return static
     */
    public function filterTmpPwordTsGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.tmp_pword_ts', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_authentication.tmp_pword_ts
     * @param string $filterValue
     * @return static
     */
    public function filterTmpPwordTsLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.tmp_pword_ts', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_authentication.tmp_pword_ts
     * @param string $filterValue
     * @return static
     */
    public function filterTmpPwordTsLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.tmp_pword_ts', $filterValue, '<=');
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
     * Group by user_authentication.pword_history
     * @return static
     */
    public function groupByPwordHistory(): static
    {
        $this->group($this->alias . '.pword_history');
        return $this;
    }

    /**
     * Order by user_authentication.pword_history
     * @param bool $ascending
     * @return static
     */
    public function orderByPwordHistory(bool $ascending = true): static
    {
        $this->order($this->alias . '.pword_history', $ascending);
        return $this;
    }

    /**
     * Filter user_authentication.pword_history by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePwordHistory(string $filterValue): static
    {
        $this->like($this->alias . '.pword_history', "%{$filterValue}%");
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
     * Group by user_authentication.pword_date
     * @return static
     */
    public function groupByPwordDate(): static
    {
        $this->group($this->alias . '.pword_date');
        return $this;
    }

    /**
     * Order by user_authentication.pword_date
     * @param bool $ascending
     * @return static
     */
    public function orderByPwordDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.pword_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_authentication.pword_date
     * @param string $filterValue
     * @return static
     */
    public function filterPwordDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.pword_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_authentication.pword_date
     * @param string $filterValue
     * @return static
     */
    public function filterPwordDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.pword_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_authentication.pword_date
     * @param string $filterValue
     * @return static
     */
    public function filterPwordDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.pword_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_authentication.pword_date
     * @param string $filterValue
     * @return static
     */
    public function filterPwordDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.pword_date', $filterValue, '<=');
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
     * Group by user_authentication.failed_login_attempt
     * @return static
     */
    public function groupByFailedLoginAttempt(): static
    {
        $this->group($this->alias . '.failed_login_attempt');
        return $this;
    }

    /**
     * Order by user_authentication.failed_login_attempt
     * @param bool $ascending
     * @return static
     */
    public function orderByFailedLoginAttempt(bool $ascending = true): static
    {
        $this->order($this->alias . '.failed_login_attempt', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_authentication.failed_login_attempt
     * @param int $filterValue
     * @return static
     */
    public function filterFailedLoginAttemptGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.failed_login_attempt', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_authentication.failed_login_attempt
     * @param int $filterValue
     * @return static
     */
    public function filterFailedLoginAttemptGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.failed_login_attempt', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_authentication.failed_login_attempt
     * @param int $filterValue
     * @return static
     */
    public function filterFailedLoginAttemptLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.failed_login_attempt', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_authentication.failed_login_attempt
     * @param int $filterValue
     * @return static
     */
    public function filterFailedLoginAttemptLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.failed_login_attempt', $filterValue, '<=');
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
     * Group by user_authentication.login_lockout
     * @return static
     */
    public function groupByLoginLockout(): static
    {
        $this->group($this->alias . '.login_lockout');
        return $this;
    }

    /**
     * Order by user_authentication.login_lockout
     * @param bool $ascending
     * @return static
     */
    public function orderByLoginLockout(bool $ascending = true): static
    {
        $this->order($this->alias . '.login_lockout', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_authentication.login_lockout
     * @param string $filterValue
     * @return static
     */
    public function filterLoginLockoutGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.login_lockout', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_authentication.login_lockout
     * @param string $filterValue
     * @return static
     */
    public function filterLoginLockoutGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.login_lockout', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_authentication.login_lockout
     * @param string $filterValue
     * @return static
     */
    public function filterLoginLockoutLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.login_lockout', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_authentication.login_lockout
     * @param string $filterValue
     * @return static
     */
    public function filterLoginLockoutLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.login_lockout', $filterValue, '<=');
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
     * Group by user_authentication.verification_code
     * @return static
     */
    public function groupByVerificationCode(): static
    {
        $this->group($this->alias . '.verification_code');
        return $this;
    }

    /**
     * Order by user_authentication.verification_code
     * @param bool $ascending
     * @return static
     */
    public function orderByVerificationCode(bool $ascending = true): static
    {
        $this->order($this->alias . '.verification_code', $ascending);
        return $this;
    }

    /**
     * Filter user_authentication.verification_code by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeVerificationCode(string $filterValue): static
    {
        $this->like($this->alias . '.verification_code', "%{$filterValue}%");
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
     * Group by user_authentication.email_verified
     * @return static
     */
    public function groupByEmailVerified(): static
    {
        $this->group($this->alias . '.email_verified');
        return $this;
    }

    /**
     * Order by user_authentication.email_verified
     * @param bool $ascending
     * @return static
     */
    public function orderByEmailVerified(bool $ascending = true): static
    {
        $this->order($this->alias . '.email_verified', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_authentication.email_verified
     * @param bool $filterValue
     * @return static
     */
    public function filterEmailVerifiedGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.email_verified', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_authentication.email_verified
     * @param bool $filterValue
     * @return static
     */
    public function filterEmailVerifiedGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.email_verified', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_authentication.email_verified
     * @param bool $filterValue
     * @return static
     */
    public function filterEmailVerifiedLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.email_verified', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_authentication.email_verified
     * @param bool $filterValue
     * @return static
     */
    public function filterEmailVerifiedLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.email_verified', $filterValue, '<=');
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
     * Group by user_authentication.verification_code_generated_date
     * @return static
     */
    public function groupByVerificationCodeGeneratedDate(): static
    {
        $this->group($this->alias . '.verification_code_generated_date');
        return $this;
    }

    /**
     * Order by user_authentication.verification_code_generated_date
     * @param bool $ascending
     * @return static
     */
    public function orderByVerificationCodeGeneratedDate(bool $ascending = true): static
    {
        $this->order($this->alias . '.verification_code_generated_date', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_authentication.verification_code_generated_date
     * @param string $filterValue
     * @return static
     */
    public function filterVerificationCodeGeneratedDateGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.verification_code_generated_date', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_authentication.verification_code_generated_date
     * @param string $filterValue
     * @return static
     */
    public function filterVerificationCodeGeneratedDateGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.verification_code_generated_date', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_authentication.verification_code_generated_date
     * @param string $filterValue
     * @return static
     */
    public function filterVerificationCodeGeneratedDateLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.verification_code_generated_date', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_authentication.verification_code_generated_date
     * @param string $filterValue
     * @return static
     */
    public function filterVerificationCodeGeneratedDateLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.verification_code_generated_date', $filterValue, '<=');
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
     * Group by user_authentication.idp_uuid
     * @return static
     */
    public function groupByIdpUuid(): static
    {
        $this->group($this->alias . '.idp_uuid');
        return $this;
    }

    /**
     * Order by user_authentication.idp_uuid
     * @param bool $ascending
     * @return static
     */
    public function orderByIdpUuid(bool $ascending = true): static
    {
        $this->order($this->alias . '.idp_uuid', $ascending);
        return $this;
    }

    /**
     * Filter user_authentication.idp_uuid by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeIdpUuid(string $filterValue): static
    {
        $this->like($this->alias . '.idp_uuid', "%{$filterValue}%");
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
     * Group by user_authentication.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by user_authentication.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_authentication.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_authentication.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_authentication.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_authentication.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
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
     * Group by user_authentication.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by user_authentication.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_authentication.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_authentication.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_authentication.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_authentication.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by user_authentication.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by user_authentication.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_authentication.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_authentication.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_authentication.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_authentication.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by user_authentication.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by user_authentication.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_authentication.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_authentication.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_authentication.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_authentication.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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

    /**
     * Group by user_authentication.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by user_authentication.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than user_authentication.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than user_authentication.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than user_authentication.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than user_authentication.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
