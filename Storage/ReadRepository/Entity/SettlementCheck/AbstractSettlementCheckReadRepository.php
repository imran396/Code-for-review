<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettlementCheck;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettlementCheck;

/**
 * Abstract class AbstractSettlementCheckReadRepository
 * @method SettlementCheck[] loadEntities()
 * @method SettlementCheck|null loadEntity()
 */
abstract class AbstractSettlementCheckReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_SETTLEMENT_CHECK;
    protected string $alias = Db::A_SETTLEMENT_CHECK;

    /**
     * Filter by settlement_check.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_check.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_check.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by settlement_check.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_check.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_check.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_check.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_check.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_check.settlement_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterSettlementId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.settlement_id', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_check.settlement_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipSettlementId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.settlement_id', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_check.settlement_id
     * @return static
     */
    public function groupBySettlementId(): static
    {
        $this->group($this->alias . '.settlement_id');
        return $this;
    }

    /**
     * Order by settlement_check.settlement_id
     * @param bool $ascending
     * @return static
     */
    public function orderBySettlementId(bool $ascending = true): static
    {
        $this->order($this->alias . '.settlement_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_check.settlement_id
     * @param int $filterValue
     * @return static
     */
    public function filterSettlementIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_check.settlement_id
     * @param int $filterValue
     * @return static
     */
    public function filterSettlementIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_check.settlement_id
     * @param int $filterValue
     * @return static
     */
    public function filterSettlementIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_check.settlement_id
     * @param int $filterValue
     * @return static
     */
    public function filterSettlementIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.settlement_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_check.payment_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterPaymentId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.payment_id', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_check.payment_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipPaymentId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.payment_id', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_check.payment_id
     * @return static
     */
    public function groupByPaymentId(): static
    {
        $this->group($this->alias . '.payment_id');
        return $this;
    }

    /**
     * Order by settlement_check.payment_id
     * @param bool $ascending
     * @return static
     */
    public function orderByPaymentId(bool $ascending = true): static
    {
        $this->order($this->alias . '.payment_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_check.payment_id
     * @param int $filterValue
     * @return static
     */
    public function filterPaymentIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.payment_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_check.payment_id
     * @param int $filterValue
     * @return static
     */
    public function filterPaymentIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.payment_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_check.payment_id
     * @param int $filterValue
     * @return static
     */
    public function filterPaymentIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.payment_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_check.payment_id
     * @param int $filterValue
     * @return static
     */
    public function filterPaymentIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.payment_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_check.check_no
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCheckNo(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.check_no', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_check.check_no from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCheckNo(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.check_no', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_check.check_no
     * @return static
     */
    public function groupByCheckNo(): static
    {
        $this->group($this->alias . '.check_no');
        return $this;
    }

    /**
     * Order by settlement_check.check_no
     * @param bool $ascending
     * @return static
     */
    public function orderByCheckNo(bool $ascending = true): static
    {
        $this->order($this->alias . '.check_no', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_check.check_no
     * @param int $filterValue
     * @return static
     */
    public function filterCheckNoGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.check_no', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_check.check_no
     * @param int $filterValue
     * @return static
     */
    public function filterCheckNoGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.check_no', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_check.check_no
     * @param int $filterValue
     * @return static
     */
    public function filterCheckNoLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.check_no', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_check.check_no
     * @param int $filterValue
     * @return static
     */
    public function filterCheckNoLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.check_no', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_check.payee
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPayee(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.payee', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_check.payee from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPayee(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.payee', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_check.payee
     * @return static
     */
    public function groupByPayee(): static
    {
        $this->group($this->alias . '.payee');
        return $this;
    }

    /**
     * Order by settlement_check.payee
     * @param bool $ascending
     * @return static
     */
    public function orderByPayee(bool $ascending = true): static
    {
        $this->order($this->alias . '.payee', $ascending);
        return $this;
    }

    /**
     * Filter settlement_check.payee by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likePayee(string $filterValue): static
    {
        $this->like($this->alias . '.payee', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by settlement_check.amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.amount', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_check.amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.amount', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_check.amount
     * @return static
     */
    public function groupByAmount(): static
    {
        $this->group($this->alias . '.amount');
        return $this;
    }

    /**
     * Order by settlement_check.amount
     * @param bool $ascending
     * @return static
     */
    public function orderByAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_check.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_check.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_check.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_check.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_check.amount_spelling
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAmountSpelling(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.amount_spelling', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_check.amount_spelling from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAmountSpelling(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.amount_spelling', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_check.amount_spelling
     * @return static
     */
    public function groupByAmountSpelling(): static
    {
        $this->group($this->alias . '.amount_spelling');
        return $this;
    }

    /**
     * Order by settlement_check.amount_spelling
     * @param bool $ascending
     * @return static
     */
    public function orderByAmountSpelling(bool $ascending = true): static
    {
        $this->order($this->alias . '.amount_spelling', $ascending);
        return $this;
    }

    /**
     * Filter settlement_check.amount_spelling by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAmountSpelling(string $filterValue): static
    {
        $this->like($this->alias . '.amount_spelling', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by settlement_check.memo
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterMemo(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.memo', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_check.memo from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipMemo(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.memo', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_check.memo
     * @return static
     */
    public function groupByMemo(): static
    {
        $this->group($this->alias . '.memo');
        return $this;
    }

    /**
     * Order by settlement_check.memo
     * @param bool $ascending
     * @return static
     */
    public function orderByMemo(bool $ascending = true): static
    {
        $this->order($this->alias . '.memo', $ascending);
        return $this;
    }

    /**
     * Filter settlement_check.memo by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeMemo(string $filterValue): static
    {
        $this->like($this->alias . '.memo', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by settlement_check.note
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNote(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.note', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_check.note from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNote(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.note', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_check.note
     * @return static
     */
    public function groupByNote(): static
    {
        $this->group($this->alias . '.note');
        return $this;
    }

    /**
     * Order by settlement_check.note
     * @param bool $ascending
     * @return static
     */
    public function orderByNote(bool $ascending = true): static
    {
        $this->order($this->alias . '.note', $ascending);
        return $this;
    }

    /**
     * Filter settlement_check.note by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeNote(string $filterValue): static
    {
        $this->like($this->alias . '.note', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by settlement_check.address
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_check.address from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_check.address
     * @return static
     */
    public function groupByAddress(): static
    {
        $this->group($this->alias . '.address');
        return $this;
    }

    /**
     * Order by settlement_check.address
     * @param bool $ascending
     * @return static
     */
    public function orderByAddress(bool $ascending = true): static
    {
        $this->order($this->alias . '.address', $ascending);
        return $this;
    }

    /**
     * Filter settlement_check.address by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAddress(string $filterValue): static
    {
        $this->like($this->alias . '.address', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by settlement_check.printed_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterPrintedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.printed_on', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_check.printed_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipPrintedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.printed_on', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_check.printed_on
     * @return static
     */
    public function groupByPrintedOn(): static
    {
        $this->group($this->alias . '.printed_on');
        return $this;
    }

    /**
     * Order by settlement_check.printed_on
     * @param bool $ascending
     * @return static
     */
    public function orderByPrintedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.printed_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_check.printed_on
     * @param string $filterValue
     * @return static
     */
    public function filterPrintedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.printed_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_check.printed_on
     * @param string $filterValue
     * @return static
     */
    public function filterPrintedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.printed_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_check.printed_on
     * @param string $filterValue
     * @return static
     */
    public function filterPrintedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.printed_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_check.printed_on
     * @param string $filterValue
     * @return static
     */
    public function filterPrintedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.printed_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_check.posted_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterPostedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.posted_on', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_check.posted_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipPostedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.posted_on', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_check.posted_on
     * @return static
     */
    public function groupByPostedOn(): static
    {
        $this->group($this->alias . '.posted_on');
        return $this;
    }

    /**
     * Order by settlement_check.posted_on
     * @param bool $ascending
     * @return static
     */
    public function orderByPostedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.posted_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_check.posted_on
     * @param string $filterValue
     * @return static
     */
    public function filterPostedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.posted_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_check.posted_on
     * @param string $filterValue
     * @return static
     */
    public function filterPostedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.posted_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_check.posted_on
     * @param string $filterValue
     * @return static
     */
    public function filterPostedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.posted_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_check.posted_on
     * @param string $filterValue
     * @return static
     */
    public function filterPostedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.posted_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_check.cleared_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterClearedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.cleared_on', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_check.cleared_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipClearedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.cleared_on', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_check.cleared_on
     * @return static
     */
    public function groupByClearedOn(): static
    {
        $this->group($this->alias . '.cleared_on');
        return $this;
    }

    /**
     * Order by settlement_check.cleared_on
     * @param bool $ascending
     * @return static
     */
    public function orderByClearedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.cleared_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_check.cleared_on
     * @param string $filterValue
     * @return static
     */
    public function filterClearedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.cleared_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_check.cleared_on
     * @param string $filterValue
     * @return static
     */
    public function filterClearedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.cleared_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_check.cleared_on
     * @param string $filterValue
     * @return static
     */
    public function filterClearedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.cleared_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_check.cleared_on
     * @param string $filterValue
     * @return static
     */
    public function filterClearedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.cleared_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_check.voided_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterVoidedOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.voided_on', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_check.voided_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipVoidedOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.voided_on', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_check.voided_on
     * @return static
     */
    public function groupByVoidedOn(): static
    {
        $this->group($this->alias . '.voided_on');
        return $this;
    }

    /**
     * Order by settlement_check.voided_on
     * @param bool $ascending
     * @return static
     */
    public function orderByVoidedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.voided_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_check.voided_on
     * @param string $filterValue
     * @return static
     */
    public function filterVoidedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.voided_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_check.voided_on
     * @param string $filterValue
     * @return static
     */
    public function filterVoidedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.voided_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_check.voided_on
     * @param string $filterValue
     * @return static
     */
    public function filterVoidedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.voided_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_check.voided_on
     * @param string $filterValue
     * @return static
     */
    public function filterVoidedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.voided_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_check.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_check.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_check.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by settlement_check.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_check.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_check.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_check.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_check.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_check.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_check.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_check.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by settlement_check.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_check.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_check.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_check.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_check.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_check.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_check.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_check.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by settlement_check.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_check.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_check.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_check.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_check.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_check.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_check.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_check.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by settlement_check.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_check.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_check.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_check.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_check.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by settlement_check.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out settlement_check.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by settlement_check.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by settlement_check.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than settlement_check.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than settlement_check.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than settlement_check.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than settlement_check.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
