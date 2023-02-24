<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettlementCheck;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettlementCheckDeleteRepository extends DeleteRepositoryBase
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
}
