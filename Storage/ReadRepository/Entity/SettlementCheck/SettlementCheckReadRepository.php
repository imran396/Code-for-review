<?php

namespace Sam\Storage\ReadRepository\Entity\SettlementCheck;

use Sam\Core\Constants;

class SettlementCheckReadRepository extends AbstractSettlementCheckReadRepository
{
    public const SELECT_STATUS =
        "IF(sch.voided_on IS NOT NULL, " . Constants\SettlementCheck::S_VOIDED . ","
        . " IF(sch.cleared_on IS NOT NULL, " . Constants\SettlementCheck::S_CLEARED . ","
        . " IF(sch.posted_on IS NOT NULL, " . Constants\SettlementCheck::S_POSTED . ","
        . " IF(sch.printed_on IS NOT NULL, " . Constants\SettlementCheck::S_PRINTED . ","
        . " IF(sch.created_on IS NOT NULL, " . Constants\SettlementCheck::S_CREATED . ","
        . " " . Constants\SettlementCheck::S_NONE . ")))))";

    protected array $joins = [
        'payment' => 'JOIN payment pmnt ON pmnt.id = sch.payment_id',
        'settlement' => 'JOIN settlement s ON s.id = sch.settlement_id',
    ];

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function filterStatus($filterValue): static
    {
        $this->filterArray(self::SELECT_STATUS, $filterValue);
        return $this;
    }

    public function joinPayment(): static
    {
        $this->join('payment');
        return $this;
    }

    public function joinPaymentOrderByAmount(bool $ascending = true): static
    {
        $this->joinPayment();
        $this->order('pmnt.amount', $ascending);
        return $this;
    }

    public function joinSettlement(): static
    {
        $this->join('settlement');
        return $this;
    }

    /**
     * @param int|int[] $accountId
     * @return static
     */
    public function joinSettlementFilterAccountId(int|array|null $accountId): static
    {
        $this->joinSettlement();
        $this->filterArray('s.account_id', $accountId);
        return $this;
    }

    /**
     * @param int|int[] $status
     * @return static
     */
    public function joinSettlementFilterSettlementStatusId(int|array|null $status): static
    {
        $this->joinSettlement();
        $this->filterArray('s.settlement_status_id', $status);
        return $this;
    }

    public function joinSettlementOrderBySettlementNo(bool $ascending = true): static
    {
        $this->joinSettlement();
        $this->order('s.settlement_no', $ascending);
        return $this;
    }

    /**
     * Order by status according check lifecycle.
     * @param bool $ascending
     * @return $this
     */
    public function orderByStatus(bool $ascending = true): static
    {
        $this->order(self::SELECT_STATUS, $ascending);
        return $this;
    }

    /**
     * Order by status according alphabetical arrangement of status names.
     * @param array $statusNames
     * @param bool $ascending
     * @return $this
     */
    public function orderByStatusAlphabetically(array $statusNames = [], bool $ascending = true): static
    {
        $statusNames = $statusNames ?: Constants\SettlementCheck::STATUS_NAMES;
        asort($statusNames);
        $caseExpr = $this->makeCase(self::SELECT_STATUS, $statusNames);
        $this->order($caseExpr, $ascending);
        return $this;
    }
}
