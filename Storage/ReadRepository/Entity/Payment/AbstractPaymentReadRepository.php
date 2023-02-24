<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Payment;

use Payment;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractPaymentReadRepository
 * @method Payment[] loadEntities()
 * @method Payment|null loadEntity()
 */
abstract class AbstractPaymentReadRepository extends ReadRepositoryBase
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
     * Group by payment.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by payment.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than payment.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than payment.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than payment.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than payment.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by payment.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by payment.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than payment.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than payment.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than payment.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than payment.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
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
     * Group by payment.tran_id
     * @return static
     */
    public function groupByTranId(): static
    {
        $this->group($this->alias . '.tran_id');
        return $this;
    }

    /**
     * Order by payment.tran_id
     * @param bool $ascending
     * @return static
     */
    public function orderByTranId(bool $ascending = true): static
    {
        $this->order($this->alias . '.tran_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than payment.tran_id
     * @param int $filterValue
     * @return static
     */
    public function filterTranIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tran_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than payment.tran_id
     * @param int $filterValue
     * @return static
     */
    public function filterTranIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tran_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than payment.tran_id
     * @param int $filterValue
     * @return static
     */
    public function filterTranIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tran_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than payment.tran_id
     * @param int $filterValue
     * @return static
     */
    public function filterTranIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tran_id', $filterValue, '<=');
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
     * Group by payment.tran_type
     * @return static
     */
    public function groupByTranType(): static
    {
        $this->group($this->alias . '.tran_type');
        return $this;
    }

    /**
     * Order by payment.tran_type
     * @param bool $ascending
     * @return static
     */
    public function orderByTranType(bool $ascending = true): static
    {
        $this->order($this->alias . '.tran_type', $ascending);
        return $this;
    }

    /**
     * Filter payment.tran_type by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTranType(string $filterValue): static
    {
        $this->like($this->alias . '.tran_type', "%{$filterValue}%");
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
     * Group by payment.tran_code
     * @return static
     */
    public function groupByTranCode(): static
    {
        $this->group($this->alias . '.tran_code');
        return $this;
    }

    /**
     * Order by payment.tran_code
     * @param bool $ascending
     * @return static
     */
    public function orderByTranCode(bool $ascending = true): static
    {
        $this->order($this->alias . '.tran_code', $ascending);
        return $this;
    }

    /**
     * Filter payment.tran_code by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTranCode(string $filterValue): static
    {
        $this->like($this->alias . '.tran_code', "%{$filterValue}%");
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
     * Group by payment.tran_param
     * @return static
     */
    public function groupByTranParam(): static
    {
        $this->group($this->alias . '.tran_param');
        return $this;
    }

    /**
     * Order by payment.tran_param
     * @param bool $ascending
     * @return static
     */
    public function orderByTranParam(bool $ascending = true): static
    {
        $this->order($this->alias . '.tran_param', $ascending);
        return $this;
    }

    /**
     * Filter payment.tran_param by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTranParam(string $filterValue): static
    {
        $this->like($this->alias . '.tran_param', "%{$filterValue}%");
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
     * Group by payment.payment_method_id
     * @return static
     */
    public function groupByPaymentMethodId(): static
    {
        $this->group($this->alias . '.payment_method_id');
        return $this;
    }

    /**
     * Order by payment.payment_method_id
     * @param bool $ascending
     * @return static
     */
    public function orderByPaymentMethodId(bool $ascending = true): static
    {
        $this->order($this->alias . '.payment_method_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than payment.payment_method_id
     * @param int $filterValue
     * @return static
     */
    public function filterPaymentMethodIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.payment_method_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than payment.payment_method_id
     * @param int $filterValue
     * @return static
     */
    public function filterPaymentMethodIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.payment_method_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than payment.payment_method_id
     * @param int $filterValue
     * @return static
     */
    public function filterPaymentMethodIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.payment_method_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than payment.payment_method_id
     * @param int $filterValue
     * @return static
     */
    public function filterPaymentMethodIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.payment_method_id', $filterValue, '<=');
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
     * Group by payment.credit_card_id
     * @return static
     */
    public function groupByCreditCardId(): static
    {
        $this->group($this->alias . '.credit_card_id');
        return $this;
    }

    /**
     * Order by payment.credit_card_id
     * @param bool $ascending
     * @return static
     */
    public function orderByCreditCardId(bool $ascending = true): static
    {
        $this->order($this->alias . '.credit_card_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than payment.credit_card_id
     * @param int $filterValue
     * @return static
     */
    public function filterCreditCardIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.credit_card_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than payment.credit_card_id
     * @param int $filterValue
     * @return static
     */
    public function filterCreditCardIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.credit_card_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than payment.credit_card_id
     * @param int $filterValue
     * @return static
     */
    public function filterCreditCardIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.credit_card_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than payment.credit_card_id
     * @param int $filterValue
     * @return static
     */
    public function filterCreditCardIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.credit_card_id', $filterValue, '<=');
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
     * Group by payment.other_payment_name
     * @return static
     */
    public function groupByOtherPaymentName(): static
    {
        $this->group($this->alias . '.other_payment_name');
        return $this;
    }

    /**
     * Order by payment.other_payment_name
     * @param bool $ascending
     * @return static
     */
    public function orderByOtherPaymentName(bool $ascending = true): static
    {
        $this->order($this->alias . '.other_payment_name', $ascending);
        return $this;
    }

    /**
     * Filter payment.other_payment_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeOtherPaymentName(string $filterValue): static
    {
        $this->like($this->alias . '.other_payment_name', "%{$filterValue}%");
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
     * Group by payment.amount
     * @return static
     */
    public function groupByAmount(): static
    {
        $this->group($this->alias . '.amount');
        return $this;
    }

    /**
     * Order by payment.amount
     * @param bool $ascending
     * @return static
     */
    public function orderByAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than payment.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than payment.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than payment.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than payment.amount
     * @param float $filterValue
     * @return static
     */
    public function filterAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.amount', $filterValue, '<=');
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
     * Group by payment.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by payment.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than payment.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than payment.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than payment.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than payment.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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
     * Group by payment.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by payment.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than payment.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than payment.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than payment.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than payment.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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
     * Group by payment.note
     * @return static
     */
    public function groupByNote(): static
    {
        $this->group($this->alias . '.note');
        return $this;
    }

    /**
     * Order by payment.note
     * @param bool $ascending
     * @return static
     */
    public function orderByNote(bool $ascending = true): static
    {
        $this->order($this->alias . '.note', $ascending);
        return $this;
    }

    /**
     * Filter payment.note by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeNote(string $filterValue): static
    {
        $this->like($this->alias . '.note', "%{$filterValue}%");
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
     * Group by payment.paid_on
     * @return static
     */
    public function groupByPaidOn(): static
    {
        $this->group($this->alias . '.paid_on');
        return $this;
    }

    /**
     * Order by payment.paid_on
     * @param bool $ascending
     * @return static
     */
    public function orderByPaidOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.paid_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than payment.paid_on
     * @param string $filterValue
     * @return static
     */
    public function filterPaidOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.paid_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than payment.paid_on
     * @param string $filterValue
     * @return static
     */
    public function filterPaidOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.paid_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than payment.paid_on
     * @param string $filterValue
     * @return static
     */
    public function filterPaidOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.paid_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than payment.paid_on
     * @param string $filterValue
     * @return static
     */
    public function filterPaidOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.paid_on', $filterValue, '<=');
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
     * Group by payment.txn_id
     * @return static
     */
    public function groupByTxnId(): static
    {
        $this->group($this->alias . '.txn_id');
        return $this;
    }

    /**
     * Order by payment.txn_id
     * @param bool $ascending
     * @return static
     */
    public function orderByTxnId(bool $ascending = true): static
    {
        $this->order($this->alias . '.txn_id', $ascending);
        return $this;
    }

    /**
     * Filter payment.txn_id by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTxnId(string $filterValue): static
    {
        $this->like($this->alias . '.txn_id', "%{$filterValue}%");
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
     * Group by payment.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by payment.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than payment.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than payment.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than payment.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than payment.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
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
     * Group by payment.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by payment.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than payment.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than payment.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than payment.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than payment.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
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
     * Group by payment.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by payment.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than payment.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than payment.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than payment.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than payment.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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

    /**
     * Group by payment.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by payment.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than payment.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than payment.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than payment.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than payment.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }
}
