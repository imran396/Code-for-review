<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingPassword;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingPasswordDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_SETTING_PASSWORD;
    protected string $alias = Db::A_SETTING_PASSWORD;

    /**
     * Filter by setting_password.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.pw_min_len
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterPwMinLen(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pw_min_len', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.pw_min_len from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipPwMinLen(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pw_min_len', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.pw_min_letter
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterPwMinLetter(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pw_min_letter', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.pw_min_letter from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipPwMinLetter(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pw_min_letter', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.pw_min_num
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterPwMinNum(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pw_min_num', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.pw_min_num from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipPwMinNum(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pw_min_num', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.pw_min_special
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterPwMinSpecial(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pw_min_special', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.pw_min_special from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipPwMinSpecial(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pw_min_special', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.pw_req_mixed_case
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterPwReqMixedCase(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pw_req_mixed_case', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.pw_req_mixed_case from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipPwReqMixedCase(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pw_req_mixed_case', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.pw_max_seq_letter
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterPwMaxSeqLetter(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pw_max_seq_letter', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.pw_max_seq_letter from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipPwMaxSeqLetter(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pw_max_seq_letter', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.pw_max_conseq_letter
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterPwMaxConseqLetter(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pw_max_conseq_letter', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.pw_max_conseq_letter from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipPwMaxConseqLetter(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pw_max_conseq_letter', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.pw_max_seq_num
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterPwMaxSeqNum(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pw_max_seq_num', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.pw_max_seq_num from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipPwMaxSeqNum(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pw_max_seq_num', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.pw_max_conseq_num
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterPwMaxConseqNum(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pw_max_conseq_num', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.pw_max_conseq_num from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipPwMaxConseqNum(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pw_max_conseq_num', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.pw_renew
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterPwRenew(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pw_renew', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.pw_renew from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipPwRenew(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pw_renew', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.pw_history_repeat
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterPwHistoryRepeat(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pw_history_repeat', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.pw_history_repeat from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipPwHistoryRepeat(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pw_history_repeat', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.pw_tmp_timeout
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterPwTmpTimeout(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pw_tmp_timeout', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.pw_tmp_timeout from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipPwTmpTimeout(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pw_tmp_timeout', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.failed_login_attempt_lockout_timeout
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterFailedLoginAttemptLockoutTimeout(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.failed_login_attempt_lockout_timeout', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.failed_login_attempt_lockout_timeout from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipFailedLoginAttemptLockoutTimeout(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.failed_login_attempt_lockout_timeout', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.failed_login_attempt_time_increment
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterFailedLoginAttemptTimeIncrement(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.failed_login_attempt_time_increment', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.failed_login_attempt_time_increment from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipFailedLoginAttemptTimeIncrement(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.failed_login_attempt_time_increment', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_password.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_password.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
