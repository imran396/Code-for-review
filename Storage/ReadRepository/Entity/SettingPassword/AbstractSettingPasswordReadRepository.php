<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingPassword;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingPassword;

/**
 * Abstract class AbstractSettingPasswordReadRepository
 * @method SettingPassword[] loadEntities()
 * @method SettingPassword|null loadEntity()
 */
abstract class AbstractSettingPasswordReadRepository extends ReadRepositoryBase
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
     * Group by setting_password.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_password.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by setting_password.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_password.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
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
     * Group by setting_password.pw_min_len
     * @return static
     */
    public function groupByPwMinLen(): static
    {
        $this->group($this->alias . '.pw_min_len');
        return $this;
    }

    /**
     * Order by setting_password.pw_min_len
     * @param bool $ascending
     * @return static
     */
    public function orderByPwMinLen(bool $ascending = true): static
    {
        $this->order($this->alias . '.pw_min_len', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.pw_min_len
     * @param int $filterValue
     * @return static
     */
    public function filterPwMinLenGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_min_len', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.pw_min_len
     * @param int $filterValue
     * @return static
     */
    public function filterPwMinLenGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_min_len', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.pw_min_len
     * @param int $filterValue
     * @return static
     */
    public function filterPwMinLenLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_min_len', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.pw_min_len
     * @param int $filterValue
     * @return static
     */
    public function filterPwMinLenLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_min_len', $filterValue, '<=');
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
     * Group by setting_password.pw_min_letter
     * @return static
     */
    public function groupByPwMinLetter(): static
    {
        $this->group($this->alias . '.pw_min_letter');
        return $this;
    }

    /**
     * Order by setting_password.pw_min_letter
     * @param bool $ascending
     * @return static
     */
    public function orderByPwMinLetter(bool $ascending = true): static
    {
        $this->order($this->alias . '.pw_min_letter', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.pw_min_letter
     * @param int $filterValue
     * @return static
     */
    public function filterPwMinLetterGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_min_letter', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.pw_min_letter
     * @param int $filterValue
     * @return static
     */
    public function filterPwMinLetterGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_min_letter', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.pw_min_letter
     * @param int $filterValue
     * @return static
     */
    public function filterPwMinLetterLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_min_letter', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.pw_min_letter
     * @param int $filterValue
     * @return static
     */
    public function filterPwMinLetterLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_min_letter', $filterValue, '<=');
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
     * Group by setting_password.pw_min_num
     * @return static
     */
    public function groupByPwMinNum(): static
    {
        $this->group($this->alias . '.pw_min_num');
        return $this;
    }

    /**
     * Order by setting_password.pw_min_num
     * @param bool $ascending
     * @return static
     */
    public function orderByPwMinNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.pw_min_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.pw_min_num
     * @param int $filterValue
     * @return static
     */
    public function filterPwMinNumGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_min_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.pw_min_num
     * @param int $filterValue
     * @return static
     */
    public function filterPwMinNumGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_min_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.pw_min_num
     * @param int $filterValue
     * @return static
     */
    public function filterPwMinNumLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_min_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.pw_min_num
     * @param int $filterValue
     * @return static
     */
    public function filterPwMinNumLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_min_num', $filterValue, '<=');
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
     * Group by setting_password.pw_min_special
     * @return static
     */
    public function groupByPwMinSpecial(): static
    {
        $this->group($this->alias . '.pw_min_special');
        return $this;
    }

    /**
     * Order by setting_password.pw_min_special
     * @param bool $ascending
     * @return static
     */
    public function orderByPwMinSpecial(bool $ascending = true): static
    {
        $this->order($this->alias . '.pw_min_special', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.pw_min_special
     * @param int $filterValue
     * @return static
     */
    public function filterPwMinSpecialGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_min_special', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.pw_min_special
     * @param int $filterValue
     * @return static
     */
    public function filterPwMinSpecialGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_min_special', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.pw_min_special
     * @param int $filterValue
     * @return static
     */
    public function filterPwMinSpecialLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_min_special', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.pw_min_special
     * @param int $filterValue
     * @return static
     */
    public function filterPwMinSpecialLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_min_special', $filterValue, '<=');
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
     * Group by setting_password.pw_req_mixed_case
     * @return static
     */
    public function groupByPwReqMixedCase(): static
    {
        $this->group($this->alias . '.pw_req_mixed_case');
        return $this;
    }

    /**
     * Order by setting_password.pw_req_mixed_case
     * @param bool $ascending
     * @return static
     */
    public function orderByPwReqMixedCase(bool $ascending = true): static
    {
        $this->order($this->alias . '.pw_req_mixed_case', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.pw_req_mixed_case
     * @param bool $filterValue
     * @return static
     */
    public function filterPwReqMixedCaseGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_req_mixed_case', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.pw_req_mixed_case
     * @param bool $filterValue
     * @return static
     */
    public function filterPwReqMixedCaseGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_req_mixed_case', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.pw_req_mixed_case
     * @param bool $filterValue
     * @return static
     */
    public function filterPwReqMixedCaseLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_req_mixed_case', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.pw_req_mixed_case
     * @param bool $filterValue
     * @return static
     */
    public function filterPwReqMixedCaseLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_req_mixed_case', $filterValue, '<=');
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
     * Group by setting_password.pw_max_seq_letter
     * @return static
     */
    public function groupByPwMaxSeqLetter(): static
    {
        $this->group($this->alias . '.pw_max_seq_letter');
        return $this;
    }

    /**
     * Order by setting_password.pw_max_seq_letter
     * @param bool $ascending
     * @return static
     */
    public function orderByPwMaxSeqLetter(bool $ascending = true): static
    {
        $this->order($this->alias . '.pw_max_seq_letter', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.pw_max_seq_letter
     * @param int $filterValue
     * @return static
     */
    public function filterPwMaxSeqLetterGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_max_seq_letter', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.pw_max_seq_letter
     * @param int $filterValue
     * @return static
     */
    public function filterPwMaxSeqLetterGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_max_seq_letter', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.pw_max_seq_letter
     * @param int $filterValue
     * @return static
     */
    public function filterPwMaxSeqLetterLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_max_seq_letter', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.pw_max_seq_letter
     * @param int $filterValue
     * @return static
     */
    public function filterPwMaxSeqLetterLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_max_seq_letter', $filterValue, '<=');
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
     * Group by setting_password.pw_max_conseq_letter
     * @return static
     */
    public function groupByPwMaxConseqLetter(): static
    {
        $this->group($this->alias . '.pw_max_conseq_letter');
        return $this;
    }

    /**
     * Order by setting_password.pw_max_conseq_letter
     * @param bool $ascending
     * @return static
     */
    public function orderByPwMaxConseqLetter(bool $ascending = true): static
    {
        $this->order($this->alias . '.pw_max_conseq_letter', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.pw_max_conseq_letter
     * @param int $filterValue
     * @return static
     */
    public function filterPwMaxConseqLetterGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_max_conseq_letter', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.pw_max_conseq_letter
     * @param int $filterValue
     * @return static
     */
    public function filterPwMaxConseqLetterGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_max_conseq_letter', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.pw_max_conseq_letter
     * @param int $filterValue
     * @return static
     */
    public function filterPwMaxConseqLetterLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_max_conseq_letter', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.pw_max_conseq_letter
     * @param int $filterValue
     * @return static
     */
    public function filterPwMaxConseqLetterLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_max_conseq_letter', $filterValue, '<=');
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
     * Group by setting_password.pw_max_seq_num
     * @return static
     */
    public function groupByPwMaxSeqNum(): static
    {
        $this->group($this->alias . '.pw_max_seq_num');
        return $this;
    }

    /**
     * Order by setting_password.pw_max_seq_num
     * @param bool $ascending
     * @return static
     */
    public function orderByPwMaxSeqNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.pw_max_seq_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.pw_max_seq_num
     * @param int $filterValue
     * @return static
     */
    public function filterPwMaxSeqNumGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_max_seq_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.pw_max_seq_num
     * @param int $filterValue
     * @return static
     */
    public function filterPwMaxSeqNumGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_max_seq_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.pw_max_seq_num
     * @param int $filterValue
     * @return static
     */
    public function filterPwMaxSeqNumLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_max_seq_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.pw_max_seq_num
     * @param int $filterValue
     * @return static
     */
    public function filterPwMaxSeqNumLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_max_seq_num', $filterValue, '<=');
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
     * Group by setting_password.pw_max_conseq_num
     * @return static
     */
    public function groupByPwMaxConseqNum(): static
    {
        $this->group($this->alias . '.pw_max_conseq_num');
        return $this;
    }

    /**
     * Order by setting_password.pw_max_conseq_num
     * @param bool $ascending
     * @return static
     */
    public function orderByPwMaxConseqNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.pw_max_conseq_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.pw_max_conseq_num
     * @param int $filterValue
     * @return static
     */
    public function filterPwMaxConseqNumGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_max_conseq_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.pw_max_conseq_num
     * @param int $filterValue
     * @return static
     */
    public function filterPwMaxConseqNumGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_max_conseq_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.pw_max_conseq_num
     * @param int $filterValue
     * @return static
     */
    public function filterPwMaxConseqNumLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_max_conseq_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.pw_max_conseq_num
     * @param int $filterValue
     * @return static
     */
    public function filterPwMaxConseqNumLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_max_conseq_num', $filterValue, '<=');
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
     * Group by setting_password.pw_renew
     * @return static
     */
    public function groupByPwRenew(): static
    {
        $this->group($this->alias . '.pw_renew');
        return $this;
    }

    /**
     * Order by setting_password.pw_renew
     * @param bool $ascending
     * @return static
     */
    public function orderByPwRenew(bool $ascending = true): static
    {
        $this->order($this->alias . '.pw_renew', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.pw_renew
     * @param int $filterValue
     * @return static
     */
    public function filterPwRenewGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_renew', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.pw_renew
     * @param int $filterValue
     * @return static
     */
    public function filterPwRenewGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_renew', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.pw_renew
     * @param int $filterValue
     * @return static
     */
    public function filterPwRenewLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_renew', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.pw_renew
     * @param int $filterValue
     * @return static
     */
    public function filterPwRenewLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_renew', $filterValue, '<=');
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
     * Group by setting_password.pw_history_repeat
     * @return static
     */
    public function groupByPwHistoryRepeat(): static
    {
        $this->group($this->alias . '.pw_history_repeat');
        return $this;
    }

    /**
     * Order by setting_password.pw_history_repeat
     * @param bool $ascending
     * @return static
     */
    public function orderByPwHistoryRepeat(bool $ascending = true): static
    {
        $this->order($this->alias . '.pw_history_repeat', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.pw_history_repeat
     * @param int $filterValue
     * @return static
     */
    public function filterPwHistoryRepeatGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_history_repeat', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.pw_history_repeat
     * @param int $filterValue
     * @return static
     */
    public function filterPwHistoryRepeatGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_history_repeat', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.pw_history_repeat
     * @param int $filterValue
     * @return static
     */
    public function filterPwHistoryRepeatLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_history_repeat', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.pw_history_repeat
     * @param int $filterValue
     * @return static
     */
    public function filterPwHistoryRepeatLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_history_repeat', $filterValue, '<=');
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
     * Group by setting_password.pw_tmp_timeout
     * @return static
     */
    public function groupByPwTmpTimeout(): static
    {
        $this->group($this->alias . '.pw_tmp_timeout');
        return $this;
    }

    /**
     * Order by setting_password.pw_tmp_timeout
     * @param bool $ascending
     * @return static
     */
    public function orderByPwTmpTimeout(bool $ascending = true): static
    {
        $this->order($this->alias . '.pw_tmp_timeout', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.pw_tmp_timeout
     * @param int $filterValue
     * @return static
     */
    public function filterPwTmpTimeoutGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_tmp_timeout', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.pw_tmp_timeout
     * @param int $filterValue
     * @return static
     */
    public function filterPwTmpTimeoutGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_tmp_timeout', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.pw_tmp_timeout
     * @param int $filterValue
     * @return static
     */
    public function filterPwTmpTimeoutLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_tmp_timeout', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.pw_tmp_timeout
     * @param int $filterValue
     * @return static
     */
    public function filterPwTmpTimeoutLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pw_tmp_timeout', $filterValue, '<=');
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
     * Group by setting_password.failed_login_attempt_lockout_timeout
     * @return static
     */
    public function groupByFailedLoginAttemptLockoutTimeout(): static
    {
        $this->group($this->alias . '.failed_login_attempt_lockout_timeout');
        return $this;
    }

    /**
     * Order by setting_password.failed_login_attempt_lockout_timeout
     * @param bool $ascending
     * @return static
     */
    public function orderByFailedLoginAttemptLockoutTimeout(bool $ascending = true): static
    {
        $this->order($this->alias . '.failed_login_attempt_lockout_timeout', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.failed_login_attempt_lockout_timeout
     * @param int $filterValue
     * @return static
     */
    public function filterFailedLoginAttemptLockoutTimeoutGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.failed_login_attempt_lockout_timeout', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.failed_login_attempt_lockout_timeout
     * @param int $filterValue
     * @return static
     */
    public function filterFailedLoginAttemptLockoutTimeoutGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.failed_login_attempt_lockout_timeout', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.failed_login_attempt_lockout_timeout
     * @param int $filterValue
     * @return static
     */
    public function filterFailedLoginAttemptLockoutTimeoutLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.failed_login_attempt_lockout_timeout', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.failed_login_attempt_lockout_timeout
     * @param int $filterValue
     * @return static
     */
    public function filterFailedLoginAttemptLockoutTimeoutLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.failed_login_attempt_lockout_timeout', $filterValue, '<=');
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
     * Group by setting_password.failed_login_attempt_time_increment
     * @return static
     */
    public function groupByFailedLoginAttemptTimeIncrement(): static
    {
        $this->group($this->alias . '.failed_login_attempt_time_increment');
        return $this;
    }

    /**
     * Order by setting_password.failed_login_attempt_time_increment
     * @param bool $ascending
     * @return static
     */
    public function orderByFailedLoginAttemptTimeIncrement(bool $ascending = true): static
    {
        $this->order($this->alias . '.failed_login_attempt_time_increment', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.failed_login_attempt_time_increment
     * @param int $filterValue
     * @return static
     */
    public function filterFailedLoginAttemptTimeIncrementGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.failed_login_attempt_time_increment', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.failed_login_attempt_time_increment
     * @param int $filterValue
     * @return static
     */
    public function filterFailedLoginAttemptTimeIncrementGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.failed_login_attempt_time_increment', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.failed_login_attempt_time_increment
     * @param int $filterValue
     * @return static
     */
    public function filterFailedLoginAttemptTimeIncrementLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.failed_login_attempt_time_increment', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.failed_login_attempt_time_increment
     * @param int $filterValue
     * @return static
     */
    public function filterFailedLoginAttemptTimeIncrementLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.failed_login_attempt_time_increment', $filterValue, '<=');
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
     * Group by setting_password.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_password.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by setting_password.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_password.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by setting_password.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_password.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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
     * Group by setting_password.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_password.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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

    /**
     * Group by setting_password.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_password.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_password.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_password.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_password.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_password.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
