<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingSms;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingSmsDeleteRepository extends DeleteRepositoryBase
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
}
