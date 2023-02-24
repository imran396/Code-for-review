<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingSms;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingSms;

/**
 * Abstract class AbstractSettingSmsReadRepository
 * @method SettingSms[] loadEntities()
 * @method SettingSms|null loadEntity()
 */
abstract class AbstractSettingSmsReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_SETTING_SMS;
    protected string $alias = Db::A_SETTING_SMS;

    /**
     * Filter by setting_sms.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_sms.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_sms.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_sms.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_sms.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_sms.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_sms.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_sms.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_sms.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_sms.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_sms.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_sms.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_sms.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_sms.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_sms.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_sms.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_sms.text_msg_api_notification
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTextMsgApiNotification(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.text_msg_api_notification', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_sms.text_msg_api_notification from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTextMsgApiNotification(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.text_msg_api_notification', $skipValue);
        return $this;
    }

    /**
     * Group by setting_sms.text_msg_api_notification
     * @return static
     */
    public function groupByTextMsgApiNotification(): static
    {
        $this->group($this->alias . '.text_msg_api_notification');
        return $this;
    }

    /**
     * Order by setting_sms.text_msg_api_notification
     * @param bool $ascending
     * @return static
     */
    public function orderByTextMsgApiNotification(bool $ascending = true): static
    {
        $this->order($this->alias . '.text_msg_api_notification', $ascending);
        return $this;
    }

    /**
     * Filter setting_sms.text_msg_api_notification by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTextMsgApiNotification(string $filterValue): static
    {
        $this->like($this->alias . '.text_msg_api_notification', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_sms.text_msg_api_outbid_notification
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTextMsgApiOutbidNotification(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.text_msg_api_outbid_notification', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_sms.text_msg_api_outbid_notification from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTextMsgApiOutbidNotification(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.text_msg_api_outbid_notification', $skipValue);
        return $this;
    }

    /**
     * Group by setting_sms.text_msg_api_outbid_notification
     * @return static
     */
    public function groupByTextMsgApiOutbidNotification(): static
    {
        $this->group($this->alias . '.text_msg_api_outbid_notification');
        return $this;
    }

    /**
     * Order by setting_sms.text_msg_api_outbid_notification
     * @param bool $ascending
     * @return static
     */
    public function orderByTextMsgApiOutbidNotification(bool $ascending = true): static
    {
        $this->order($this->alias . '.text_msg_api_outbid_notification', $ascending);
        return $this;
    }

    /**
     * Filter setting_sms.text_msg_api_outbid_notification by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTextMsgApiOutbidNotification(string $filterValue): static
    {
        $this->like($this->alias . '.text_msg_api_outbid_notification', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_sms.text_msg_api_post_var
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTextMsgApiPostVar(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.text_msg_api_post_var', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_sms.text_msg_api_post_var from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTextMsgApiPostVar(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.text_msg_api_post_var', $skipValue);
        return $this;
    }

    /**
     * Group by setting_sms.text_msg_api_post_var
     * @return static
     */
    public function groupByTextMsgApiPostVar(): static
    {
        $this->group($this->alias . '.text_msg_api_post_var');
        return $this;
    }

    /**
     * Order by setting_sms.text_msg_api_post_var
     * @param bool $ascending
     * @return static
     */
    public function orderByTextMsgApiPostVar(bool $ascending = true): static
    {
        $this->order($this->alias . '.text_msg_api_post_var', $ascending);
        return $this;
    }

    /**
     * Filter setting_sms.text_msg_api_post_var by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTextMsgApiPostVar(string $filterValue): static
    {
        $this->like($this->alias . '.text_msg_api_post_var', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_sms.text_msg_api_url
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterTextMsgApiUrl(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.text_msg_api_url', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_sms.text_msg_api_url from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipTextMsgApiUrl(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.text_msg_api_url', $skipValue);
        return $this;
    }

    /**
     * Group by setting_sms.text_msg_api_url
     * @return static
     */
    public function groupByTextMsgApiUrl(): static
    {
        $this->group($this->alias . '.text_msg_api_url');
        return $this;
    }

    /**
     * Order by setting_sms.text_msg_api_url
     * @param bool $ascending
     * @return static
     */
    public function orderByTextMsgApiUrl(bool $ascending = true): static
    {
        $this->order($this->alias . '.text_msg_api_url', $ascending);
        return $this;
    }

    /**
     * Filter setting_sms.text_msg_api_url by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeTextMsgApiUrl(string $filterValue): static
    {
        $this->like($this->alias . '.text_msg_api_url', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_sms.text_msg_enabled
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterTextMsgEnabled(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.text_msg_enabled', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_sms.text_msg_enabled from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipTextMsgEnabled(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.text_msg_enabled', $skipValue);
        return $this;
    }

    /**
     * Group by setting_sms.text_msg_enabled
     * @return static
     */
    public function groupByTextMsgEnabled(): static
    {
        $this->group($this->alias . '.text_msg_enabled');
        return $this;
    }

    /**
     * Order by setting_sms.text_msg_enabled
     * @param bool $ascending
     * @return static
     */
    public function orderByTextMsgEnabled(bool $ascending = true): static
    {
        $this->order($this->alias . '.text_msg_enabled', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_sms.text_msg_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterTextMsgEnabledGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.text_msg_enabled', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_sms.text_msg_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterTextMsgEnabledGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.text_msg_enabled', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_sms.text_msg_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterTextMsgEnabledLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.text_msg_enabled', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_sms.text_msg_enabled
     * @param bool $filterValue
     * @return static
     */
    public function filterTextMsgEnabledLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.text_msg_enabled', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_sms.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_sms.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_sms.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_sms.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_sms.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_sms.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_sms.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_sms.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_sms.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_sms.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_sms.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_sms.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_sms.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_sms.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_sms.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_sms.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_sms.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_sms.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_sms.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_sms.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_sms.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_sms.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_sms.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_sms.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_sms.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_sms.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_sms.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_sms.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_sms.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_sms.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_sms.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_sms.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_sms.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_sms.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by setting_sms.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_sms.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_sms.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_sms.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_sms.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_sms.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
