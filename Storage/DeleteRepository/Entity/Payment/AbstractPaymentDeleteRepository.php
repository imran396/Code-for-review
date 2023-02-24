<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Payment;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractPaymentDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_PAYMENT;
    protected string $alias = Db::A_PAYMENT;

    /**
     * Filter by payment.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out payment.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by payment.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out payment.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by payment.tran_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterTranId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tran_id', $filterValue);
        return $this;
    }

    /**
     * Filter out payment.tran_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipTranId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tran_id', $skipValue);
        return $this;
    }

    /**
     * Filter by payment.tran_type
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterTranType(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tran_type', $filterValue);
        return $this;
    }

    /**
     * Filter out payment.tran_type from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipTranType(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tran_type', $skipValue);
        return $this;
    }

    /**
     * Filter by payment.tran_code
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTranCode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tran_code', $filterValue);
        return $this;
    }

    /**
     * Filter out payment.tran_code from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTranCode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tran_code', $skipValue);
        return $this;
    }

    /**
     * Filter by payment.tran_param
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTranParam(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.tran_param', $filterValue);
        return $this;
    }

    /**
     * Filter out payment.tran_param from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTranParam(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.tran_param', $skipValue);
        return $this;
    }

    /**
     * Filter by payment.payment_method_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterPaymentMethodId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.payment_method_id', $filterValue);
        return $this;
    }

    /**
     * Filter out payment.payment_method_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipPaymentMethodId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.payment_method_id', $skipValue);
        return $this;
    }

    /**
     * Filter by payment.credit_card_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreditCardId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.credit_card_id', $filterValue);
        return $this;
    }

    /**
     * Filter out payment.credit_card_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreditCardId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.credit_card_id', $skipValue);
        return $this;
    }

    /**
     * Filter by payment.other_payment_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterOtherPaymentName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.other_payment_name', $filterValue);
        return $this;
    }

    /**
     * Filter out payment.other_payment_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipOtherPaymentName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.other_payment_name', $skipValue);
        return $this;
    }

    /**
     * Filter by payment.amount
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterAmount(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.amount', $filterValue);
        return $this;
    }

    /**
     * Filter out payment.amount from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipAmount(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.amount', $skipValue);
        return $this;
    }

    /**
     * Filter by payment.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out payment.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by payment.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out payment.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by payment.note
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNote(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.note', $filterValue);
        return $this;
    }

    /**
     * Filter out payment.note from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNote(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.note', $skipValue);
        return $this;
    }

    /**
     * Filter by payment.paid_on
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterPaidOn(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.paid_on', $filterValue);
        return $this;
    }

    /**
     * Filter out payment.paid_on from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipPaidOn(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.paid_on', $skipValue);
        return $this;
    }

    /**
     * Filter by payment.txn_id
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTxnId(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.txn_id', $filterValue);
        return $this;
    }

    /**
     * Filter out payment.txn_id from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTxnId(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.txn_id', $skipValue);
        return $this;
    }

    /**
     * Filter by payment.active
     * @param bool|bool[]|null $filterValue
     * @return static
     */
    public function filterActive(bool|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out payment.active from result
     * @param bool|bool[]|null $skipValue
     * @return static
     */
    public function skipActive(bool|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Filter by payment.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out payment.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by payment.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out payment.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by payment.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out payment.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }
}
