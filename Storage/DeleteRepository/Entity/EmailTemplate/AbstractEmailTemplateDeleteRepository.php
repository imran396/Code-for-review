<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\EmailTemplate;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractEmailTemplateDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_EMAIL_TEMPLATE;
    protected string $alias = Db::A_EMAIL_TEMPLATE;

    /**
     * Filter by email_template.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out email_template.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by email_template.account_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAccountId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out email_template.account_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAccountId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by email_template.key
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterKey(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.key', $filterValue);
        return $this;
    }

    /**
     * Filter out email_template.key from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipKey(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.key', $skipValue);
        return $this;
    }

    /**
     * Filter by email_template.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out email_template.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Filter by email_template.subject
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSubject(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.subject', $filterValue);
        return $this;
    }

    /**
     * Filter out email_template.subject from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSubject(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.subject', $skipValue);
        return $this;
    }

    /**
     * Filter by email_template.message
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterMessage(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.message', $filterValue);
        return $this;
    }

    /**
     * Filter out email_template.message from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipMessage(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.message', $skipValue);
        return $this;
    }

    /**
     * Filter by email_template.cc_support_email
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterCcSupportEmail(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_support_email', $filterValue);
        return $this;
    }

    /**
     * Filter out email_template.cc_support_email from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipCcSupportEmail(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_support_email', $skipValue);
        return $this;
    }

    /**
     * Filter by email_template.cc_auction_support_email
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterCcAuctionSupportEmail(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_auction_support_email', $filterValue);
        return $this;
    }

    /**
     * Filter out email_template.cc_auction_support_email from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipCcAuctionSupportEmail(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_auction_support_email', $skipValue);
        return $this;
    }

    /**
     * Filter by email_template.notify_consignor
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNotifyConsignor(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.notify_consignor', $filterValue);
        return $this;
    }

    /**
     * Filter out email_template.notify_consignor from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNotifyConsignor(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.notify_consignor', $skipValue);
        return $this;
    }

    /**
     * Filter by email_template.email_template_group_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterEmailTemplateGroupId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.email_template_group_id', $filterValue);
        return $this;
    }

    /**
     * Filter out email_template.email_template_group_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipEmailTemplateGroupId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.email_template_group_id', $skipValue);
        return $this;
    }

    /**
     * Filter by email_template.order
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterOrder(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.order', $filterValue);
        return $this;
    }

    /**
     * Filter out email_template.order from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipOrder(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.order', $skipValue);
        return $this;
    }

    /**
     * Filter by email_template.disabled
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterDisabled(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.disabled', $filterValue);
        return $this;
    }

    /**
     * Filter out email_template.disabled from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipDisabled(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.disabled', $skipValue);
        return $this;
    }

    /**
     * Filter by email_template.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out email_template.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by email_template.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out email_template.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by email_template.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out email_template.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by email_template.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out email_template.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by email_template.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out email_template.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
