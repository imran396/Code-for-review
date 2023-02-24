<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionEmailTemplate;

use AuctionEmailTemplate;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractAuctionEmailTemplateReadRepository
 * @method AuctionEmailTemplate[] loadEntities()
 * @method AuctionEmailTemplate|null loadEntity()
 */
abstract class AbstractAuctionEmailTemplateReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_AUCTION_EMAIL_TEMPLATE;
    protected string $alias = Db::A_AUCTION_EMAIL_TEMPLATE;

    /**
     * Filter by auction_email_template.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_email_template.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_email_template.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by auction_email_template.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_email_template.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_email_template.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_email_template.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_email_template.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_email_template.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_email_template.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_email_template.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by auction_email_template.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_email_template.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_email_template.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_email_template.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_email_template.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_email_template.key
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterKey(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.key', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_email_template.key from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipKey(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.key', $skipValue);
        return $this;
    }

    /**
     * Group by auction_email_template.key
     * @return static
     */
    public function groupByKey(): static
    {
        $this->group($this->alias . '.key');
        return $this;
    }

    /**
     * Order by auction_email_template.key
     * @param bool $ascending
     * @return static
     */
    public function orderByKey(bool $ascending = true): static
    {
        $this->order($this->alias . '.key', $ascending);
        return $this;
    }

    /**
     * Filter auction_email_template.key by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeKey(string $filterValue): static
    {
        $this->like($this->alias . '.key', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_email_template.name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.name', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_email_template.name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.name', $skipValue);
        return $this;
    }

    /**
     * Group by auction_email_template.name
     * @return static
     */
    public function groupByName(): static
    {
        $this->group($this->alias . '.name');
        return $this;
    }

    /**
     * Order by auction_email_template.name
     * @param bool $ascending
     * @return static
     */
    public function orderByName(bool $ascending = true): static
    {
        $this->order($this->alias . '.name', $ascending);
        return $this;
    }

    /**
     * Filter auction_email_template.name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeName(string $filterValue): static
    {
        $this->like($this->alias . '.name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_email_template.subject
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSubject(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.subject', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_email_template.subject from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSubject(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.subject', $skipValue);
        return $this;
    }

    /**
     * Group by auction_email_template.subject
     * @return static
     */
    public function groupBySubject(): static
    {
        $this->group($this->alias . '.subject');
        return $this;
    }

    /**
     * Order by auction_email_template.subject
     * @param bool $ascending
     * @return static
     */
    public function orderBySubject(bool $ascending = true): static
    {
        $this->order($this->alias . '.subject', $ascending);
        return $this;
    }

    /**
     * Filter auction_email_template.subject by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSubject(string $filterValue): static
    {
        $this->like($this->alias . '.subject', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_email_template.message
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterMessage(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.message', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_email_template.message from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipMessage(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.message', $skipValue);
        return $this;
    }

    /**
     * Group by auction_email_template.message
     * @return static
     */
    public function groupByMessage(): static
    {
        $this->group($this->alias . '.message');
        return $this;
    }

    /**
     * Order by auction_email_template.message
     * @param bool $ascending
     * @return static
     */
    public function orderByMessage(bool $ascending = true): static
    {
        $this->order($this->alias . '.message', $ascending);
        return $this;
    }

    /**
     * Filter auction_email_template.message by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeMessage(string $filterValue): static
    {
        $this->like($this->alias . '.message', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by auction_email_template.cc_support_email
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterCcSupportEmail(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_support_email', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_email_template.cc_support_email from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipCcSupportEmail(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_support_email', $skipValue);
        return $this;
    }

    /**
     * Group by auction_email_template.cc_support_email
     * @return static
     */
    public function groupByCcSupportEmail(): static
    {
        $this->group($this->alias . '.cc_support_email');
        return $this;
    }

    /**
     * Order by auction_email_template.cc_support_email
     * @param bool $ascending
     * @return static
     */
    public function orderByCcSupportEmail(bool $ascending = true): static
    {
        $this->order($this->alias . '.cc_support_email', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_email_template.cc_support_email
     * @param bool $filterValue
     * @return static
     */
    public function filterCcSupportEmailGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_support_email', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_email_template.cc_support_email
     * @param bool $filterValue
     * @return static
     */
    public function filterCcSupportEmailGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_support_email', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_email_template.cc_support_email
     * @param bool $filterValue
     * @return static
     */
    public function filterCcSupportEmailLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_support_email', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_email_template.cc_support_email
     * @param bool $filterValue
     * @return static
     */
    public function filterCcSupportEmailLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_support_email', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_email_template.cc_auction_support_email
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterCcAuctionSupportEmail(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_auction_support_email', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_email_template.cc_auction_support_email from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipCcAuctionSupportEmail(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_auction_support_email', $skipValue);
        return $this;
    }

    /**
     * Group by auction_email_template.cc_auction_support_email
     * @return static
     */
    public function groupByCcAuctionSupportEmail(): static
    {
        $this->group($this->alias . '.cc_auction_support_email');
        return $this;
    }

    /**
     * Order by auction_email_template.cc_auction_support_email
     * @param bool $ascending
     * @return static
     */
    public function orderByCcAuctionSupportEmail(bool $ascending = true): static
    {
        $this->order($this->alias . '.cc_auction_support_email', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_email_template.cc_auction_support_email
     * @param bool $filterValue
     * @return static
     */
    public function filterCcAuctionSupportEmailGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_auction_support_email', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_email_template.cc_auction_support_email
     * @param bool $filterValue
     * @return static
     */
    public function filterCcAuctionSupportEmailGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_auction_support_email', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_email_template.cc_auction_support_email
     * @param bool $filterValue
     * @return static
     */
    public function filterCcAuctionSupportEmailLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_auction_support_email', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_email_template.cc_auction_support_email
     * @param bool $filterValue
     * @return static
     */
    public function filterCcAuctionSupportEmailLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.cc_auction_support_email', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_email_template.notify_consignor
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNotifyConsignor(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.notify_consignor', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_email_template.notify_consignor from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNotifyConsignor(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.notify_consignor', $skipValue);
        return $this;
    }

    /**
     * Group by auction_email_template.notify_consignor
     * @return static
     */
    public function groupByNotifyConsignor(): static
    {
        $this->group($this->alias . '.notify_consignor');
        return $this;
    }

    /**
     * Order by auction_email_template.notify_consignor
     * @param bool $ascending
     * @return static
     */
    public function orderByNotifyConsignor(bool $ascending = true): static
    {
        $this->order($this->alias . '.notify_consignor', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_email_template.notify_consignor
     * @param bool $filterValue
     * @return static
     */
    public function filterNotifyConsignorGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_consignor', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_email_template.notify_consignor
     * @param bool $filterValue
     * @return static
     */
    public function filterNotifyConsignorGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_consignor', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_email_template.notify_consignor
     * @param bool $filterValue
     * @return static
     */
    public function filterNotifyConsignorLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_consignor', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_email_template.notify_consignor
     * @param bool $filterValue
     * @return static
     */
    public function filterNotifyConsignorLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.notify_consignor', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_email_template.email_template_group_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterEmailTemplateGroupId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.email_template_group_id', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_email_template.email_template_group_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipEmailTemplateGroupId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.email_template_group_id', $skipValue);
        return $this;
    }

    /**
     * Group by auction_email_template.email_template_group_id
     * @return static
     */
    public function groupByEmailTemplateGroupId(): static
    {
        $this->group($this->alias . '.email_template_group_id');
        return $this;
    }

    /**
     * Order by auction_email_template.email_template_group_id
     * @param bool $ascending
     * @return static
     */
    public function orderByEmailTemplateGroupId(bool $ascending = true): static
    {
        $this->order($this->alias . '.email_template_group_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_email_template.email_template_group_id
     * @param int $filterValue
     * @return static
     */
    public function filterEmailTemplateGroupIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.email_template_group_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_email_template.email_template_group_id
     * @param int $filterValue
     * @return static
     */
    public function filterEmailTemplateGroupIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.email_template_group_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_email_template.email_template_group_id
     * @param int $filterValue
     * @return static
     */
    public function filterEmailTemplateGroupIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.email_template_group_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_email_template.email_template_group_id
     * @param int $filterValue
     * @return static
     */
    public function filterEmailTemplateGroupIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.email_template_group_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_email_template.order
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterOrder(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.order', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_email_template.order from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipOrder(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.order', $skipValue);
        return $this;
    }

    /**
     * Group by auction_email_template.order
     * @return static
     */
    public function groupByOrder(): static
    {
        $this->group($this->alias . '.order');
        return $this;
    }

    /**
     * Order by auction_email_template.order
     * @param bool $ascending
     * @return static
     */
    public function orderByOrder(bool $ascending = true): static
    {
        $this->order($this->alias . '.order', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_email_template.order
     * @param int $filterValue
     * @return static
     */
    public function filterOrderGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_email_template.order
     * @param int $filterValue
     * @return static
     */
    public function filterOrderGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_email_template.order
     * @param int $filterValue
     * @return static
     */
    public function filterOrderLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_email_template.order
     * @param int $filterValue
     * @return static
     */
    public function filterOrderLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.order', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_email_template.disabled
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterDisabled(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.disabled', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_email_template.disabled from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipDisabled(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.disabled', $skipValue);
        return $this;
    }

    /**
     * Group by auction_email_template.disabled
     * @return static
     */
    public function groupByDisabled(): static
    {
        $this->group($this->alias . '.disabled');
        return $this;
    }

    /**
     * Order by auction_email_template.disabled
     * @param bool $ascending
     * @return static
     */
    public function orderByDisabled(bool $ascending = true): static
    {
        $this->order($this->alias . '.disabled', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_email_template.disabled
     * @param bool $filterValue
     * @return static
     */
    public function filterDisabledGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.disabled', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_email_template.disabled
     * @param bool $filterValue
     * @return static
     */
    public function filterDisabledGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.disabled', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_email_template.disabled
     * @param bool $filterValue
     * @return static
     */
    public function filterDisabledLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.disabled', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_email_template.disabled
     * @param bool $filterValue
     * @return static
     */
    public function filterDisabledLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.disabled', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_email_template.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_email_template.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by auction_email_template.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by auction_email_template.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_email_template.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_email_template.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_email_template.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_email_template.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_email_template.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_email_template.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by auction_email_template.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by auction_email_template.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_email_template.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_email_template.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_email_template.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_email_template.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_email_template.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_email_template.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by auction_email_template.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by auction_email_template.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_email_template.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_email_template.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_email_template.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_email_template.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_email_template.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_email_template.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by auction_email_template.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by auction_email_template.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_email_template.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_email_template.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_email_template.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_email_template.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by auction_email_template.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out auction_email_template.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by auction_email_template.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by auction_email_template.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than auction_email_template.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than auction_email_template.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than auction_email_template.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than auction_email_template.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
