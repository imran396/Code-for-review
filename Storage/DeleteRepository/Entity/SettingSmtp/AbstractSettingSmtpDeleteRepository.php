<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingSmtp;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingSmtpDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_SETTING_SMTP;
    protected string $alias = Db::A_SETTING_SMTP;

    /**
     * Filter by setting_smtp.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_smtp.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_smtp.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_smtp.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_smtp.smtp_auth
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSmtpAuth(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.smtp_auth', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_smtp.smtp_auth from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSmtpAuth(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.smtp_auth', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_smtp.smtp_username
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSmtpUsername(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.smtp_username', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_smtp.smtp_username from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSmtpUsername(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.smtp_username', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_smtp.smtp_password
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSmtpPassword(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.smtp_password', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_smtp.smtp_password from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSmtpPassword(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.smtp_password', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_smtp.smtp_port
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterSmtpPort(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.smtp_port', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_smtp.smtp_port from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipSmtpPort(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.smtp_port', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_smtp.smtp_server
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSmtpServer(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.smtp_server', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_smtp.smtp_server from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSmtpServer(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.smtp_server', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_smtp.smtp_ssl_type
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterSmtpSslType(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.smtp_ssl_type', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_smtp.smtp_ssl_type from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipSmtpSslType(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.smtp_ssl_type', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_smtp.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_smtp.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_smtp.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_smtp.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_smtp.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_smtp.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_smtp.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_smtp.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_smtp.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_smtp.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
