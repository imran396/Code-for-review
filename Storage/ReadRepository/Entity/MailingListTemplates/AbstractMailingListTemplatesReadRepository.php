<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\MailingListTemplates;

use MailingListTemplates;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractMailingListTemplatesReadRepository
 * @method MailingListTemplates[] loadEntities()
 * @method MailingListTemplates|null loadEntity()
 */
abstract class AbstractMailingListTemplatesReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_MAILING_LIST_TEMPLATES;
    protected string $alias = Db::A_MAILING_LIST_TEMPLATES;

    /**
     * Filter by mailing_list_templates.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_templates.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by mailing_list_templates.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by mailing_list_templates.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_templates.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_templates.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_templates.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_templates.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by mailing_list_templates.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_templates.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by mailing_list_templates.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by mailing_list_templates.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_templates.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_templates.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_templates.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_templates.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by mailing_list_templates.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_templates.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Group by mailing_list_templates.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by mailing_list_templates.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_templates.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_templates.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_templates.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_templates.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by mailing_list_templates.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_templates.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Group by mailing_list_templates.name
     * @return static
     */
    public function groupByName(): static
    {
        $this->group($this->alias . '.name');
        return $this;
    }

    /**
     * Order by mailing_list_templates.name
     * @param bool $ascending
     * @return static
     */
    public function orderByName(bool $ascending = true): static
    {
        $this->order($this->alias . '.name', $ascending);
        return $this;
    }

    /**
     * Filter mailing_list_templates.name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeName(string $filterValue): static
    {
        $this->like($this->alias . '.name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by mailing_list_templates.user_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterUserType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.user_type', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_templates.user_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipUserType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.user_type', $skipValue);
        return $this;
    }

    /**
     * Group by mailing_list_templates.user_type
     * @return static
     */
    public function groupByUserType(): static
    {
        $this->group($this->alias . '.user_type');
        return $this;
    }

    /**
     * Order by mailing_list_templates.user_type
     * @param bool $ascending
     * @return static
     */
    public function orderByUserType(bool $ascending = true): static
    {
        $this->order($this->alias . '.user_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_templates.user_type
     * @param int $filterValue
     * @return static
     */
    public function filterUserTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_templates.user_type
     * @param int $filterValue
     * @return static
     */
    public function filterUserTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_templates.user_type
     * @param int $filterValue
     * @return static
     */
    public function filterUserTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_templates.user_type
     * @param int $filterValue
     * @return static
     */
    public function filterUserTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.user_type', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by mailing_list_templates.period_start
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterPeriodStart(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.period_start', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_templates.period_start from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipPeriodStart(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.period_start', $skipValue);
        return $this;
    }

    /**
     * Group by mailing_list_templates.period_start
     * @return static
     */
    public function groupByPeriodStart(): static
    {
        $this->group($this->alias . '.period_start');
        return $this;
    }

    /**
     * Order by mailing_list_templates.period_start
     * @param bool $ascending
     * @return static
     */
    public function orderByPeriodStart(bool $ascending = true): static
    {
        $this->order($this->alias . '.period_start', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_templates.period_start
     * @param string $filterValue
     * @return static
     */
    public function filterPeriodStartGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.period_start', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_templates.period_start
     * @param string $filterValue
     * @return static
     */
    public function filterPeriodStartGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.period_start', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_templates.period_start
     * @param string $filterValue
     * @return static
     */
    public function filterPeriodStartLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.period_start', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_templates.period_start
     * @param string $filterValue
     * @return static
     */
    public function filterPeriodStartLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.period_start', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by mailing_list_templates.period_end
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterPeriodEnd(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.period_end', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_templates.period_end from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipPeriodEnd(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.period_end', $skipValue);
        return $this;
    }

    /**
     * Group by mailing_list_templates.period_end
     * @return static
     */
    public function groupByPeriodEnd(): static
    {
        $this->group($this->alias . '.period_end');
        return $this;
    }

    /**
     * Order by mailing_list_templates.period_end
     * @param bool $ascending
     * @return static
     */
    public function orderByPeriodEnd(bool $ascending = true): static
    {
        $this->order($this->alias . '.period_end', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_templates.period_end
     * @param string $filterValue
     * @return static
     */
    public function filterPeriodEndGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.period_end', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_templates.period_end
     * @param string $filterValue
     * @return static
     */
    public function filterPeriodEndGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.period_end', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_templates.period_end
     * @param string $filterValue
     * @return static
     */
    public function filterPeriodEndLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.period_end', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_templates.period_end
     * @param string $filterValue
     * @return static
     */
    public function filterPeriodEndLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.period_end', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by mailing_list_templates.money_spent_from
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterMoneySpentFrom(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.money_spent_from', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_templates.money_spent_from from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipMoneySpentFrom(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.money_spent_from', $skipValue);
        return $this;
    }

    /**
     * Group by mailing_list_templates.money_spent_from
     * @return static
     */
    public function groupByMoneySpentFrom(): static
    {
        $this->group($this->alias . '.money_spent_from');
        return $this;
    }

    /**
     * Order by mailing_list_templates.money_spent_from
     * @param bool $ascending
     * @return static
     */
    public function orderByMoneySpentFrom(bool $ascending = true): static
    {
        $this->order($this->alias . '.money_spent_from', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_templates.money_spent_from
     * @param float $filterValue
     * @return static
     */
    public function filterMoneySpentFromGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.money_spent_from', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_templates.money_spent_from
     * @param float $filterValue
     * @return static
     */
    public function filterMoneySpentFromGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.money_spent_from', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_templates.money_spent_from
     * @param float $filterValue
     * @return static
     */
    public function filterMoneySpentFromLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.money_spent_from', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_templates.money_spent_from
     * @param float $filterValue
     * @return static
     */
    public function filterMoneySpentFromLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.money_spent_from', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by mailing_list_templates.money_spent_to
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterMoneySpentTo(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.money_spent_to', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_templates.money_spent_to from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipMoneySpentTo(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.money_spent_to', $skipValue);
        return $this;
    }

    /**
     * Group by mailing_list_templates.money_spent_to
     * @return static
     */
    public function groupByMoneySpentTo(): static
    {
        $this->group($this->alias . '.money_spent_to');
        return $this;
    }

    /**
     * Order by mailing_list_templates.money_spent_to
     * @param bool $ascending
     * @return static
     */
    public function orderByMoneySpentTo(bool $ascending = true): static
    {
        $this->order($this->alias . '.money_spent_to', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_templates.money_spent_to
     * @param float $filterValue
     * @return static
     */
    public function filterMoneySpentToGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.money_spent_to', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_templates.money_spent_to
     * @param float $filterValue
     * @return static
     */
    public function filterMoneySpentToGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.money_spent_to', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_templates.money_spent_to
     * @param float $filterValue
     * @return static
     */
    public function filterMoneySpentToLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.money_spent_to', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_templates.money_spent_to
     * @param float $filterValue
     * @return static
     */
    public function filterMoneySpentToLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.money_spent_to', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by mailing_list_templates.created_by
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_templates.created_by from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by mailing_list_templates.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by mailing_list_templates.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_templates.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_templates.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_templates.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_templates.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by mailing_list_templates.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_templates.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by mailing_list_templates.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by mailing_list_templates.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_templates.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_templates.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_templates.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_templates.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by mailing_list_templates.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_templates.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by mailing_list_templates.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by mailing_list_templates.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_templates.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_templates.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_templates.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_templates.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by mailing_list_templates.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_templates.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by mailing_list_templates.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by mailing_list_templates.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_templates.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_templates.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_templates.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_templates.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by mailing_list_templates.deleted
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterDeleted(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.deleted', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_templates.deleted from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipDeleted(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.deleted', $skipValue);
        return $this;
    }

    /**
     * Group by mailing_list_templates.deleted
     * @return static
     */
    public function groupByDeleted(): static
    {
        $this->group($this->alias . '.deleted');
        return $this;
    }

    /**
     * Order by mailing_list_templates.deleted
     * @param bool $ascending
     * @return static
     */
    public function orderByDeleted(bool $ascending = true): static
    {
        $this->order($this->alias . '.deleted', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_templates.deleted
     * @param bool $filterValue
     * @return static
     */
    public function filterDeletedGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.deleted', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_templates.deleted
     * @param bool $filterValue
     * @return static
     */
    public function filterDeletedGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.deleted', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_templates.deleted
     * @param bool $filterValue
     * @return static
     */
    public function filterDeletedLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.deleted', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_templates.deleted
     * @param bool $filterValue
     * @return static
     */
    public function filterDeletedLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.deleted', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by mailing_list_templates.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out mailing_list_templates.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by mailing_list_templates.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by mailing_list_templates.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than mailing_list_templates.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than mailing_list_templates.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than mailing_list_templates.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than mailing_list_templates.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
