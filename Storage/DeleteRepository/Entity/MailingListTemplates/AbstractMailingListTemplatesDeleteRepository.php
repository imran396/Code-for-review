<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\MailingListTemplates;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractMailingListTemplatesDeleteRepository extends DeleteRepositoryBase
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
}
