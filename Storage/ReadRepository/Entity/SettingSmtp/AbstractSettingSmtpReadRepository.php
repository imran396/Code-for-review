<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingSmtp;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingSmtp;

/**
 * Abstract class AbstractSettingSmtpReadRepository
 * @method SettingSmtp[] loadEntities()
 * @method SettingSmtp|null loadEntity()
 */
abstract class AbstractSettingSmtpReadRepository extends ReadRepositoryBase
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
     * Group by setting_smtp.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_smtp.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_smtp.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_smtp.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_smtp.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_smtp.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by setting_smtp.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_smtp.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_smtp.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_smtp.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_smtp.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_smtp.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
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
     * Group by setting_smtp.smtp_auth
     * @return static
     */
    public function groupBySmtpAuth(): static
    {
        $this->group($this->alias . '.smtp_auth');
        return $this;
    }

    /**
     * Order by setting_smtp.smtp_auth
     * @param bool $ascending
     * @return static
     */
    public function orderBySmtpAuth(bool $ascending = true): static
    {
        $this->order($this->alias . '.smtp_auth', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_smtp.smtp_auth
     * @param bool $filterValue
     * @return static
     */
    public function filterSmtpAuthGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.smtp_auth', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_smtp.smtp_auth
     * @param bool $filterValue
     * @return static
     */
    public function filterSmtpAuthGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.smtp_auth', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_smtp.smtp_auth
     * @param bool $filterValue
     * @return static
     */
    public function filterSmtpAuthLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.smtp_auth', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_smtp.smtp_auth
     * @param bool $filterValue
     * @return static
     */
    public function filterSmtpAuthLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.smtp_auth', $filterValue, '<=');
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
     * Group by setting_smtp.smtp_username
     * @return static
     */
    public function groupBySmtpUsername(): static
    {
        $this->group($this->alias . '.smtp_username');
        return $this;
    }

    /**
     * Order by setting_smtp.smtp_username
     * @param bool $ascending
     * @return static
     */
    public function orderBySmtpUsername(bool $ascending = true): static
    {
        $this->order($this->alias . '.smtp_username', $ascending);
        return $this;
    }

    /**
     * Filter setting_smtp.smtp_username by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSmtpUsername(string $filterValue): static
    {
        $this->like($this->alias . '.smtp_username', "%{$filterValue}%");
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
     * Group by setting_smtp.smtp_password
     * @return static
     */
    public function groupBySmtpPassword(): static
    {
        $this->group($this->alias . '.smtp_password');
        return $this;
    }

    /**
     * Order by setting_smtp.smtp_password
     * @param bool $ascending
     * @return static
     */
    public function orderBySmtpPassword(bool $ascending = true): static
    {
        $this->order($this->alias . '.smtp_password', $ascending);
        return $this;
    }

    /**
     * Filter setting_smtp.smtp_password by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSmtpPassword(string $filterValue): static
    {
        $this->like($this->alias . '.smtp_password', "%{$filterValue}%");
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
     * Group by setting_smtp.smtp_port
     * @return static
     */
    public function groupBySmtpPort(): static
    {
        $this->group($this->alias . '.smtp_port');
        return $this;
    }

    /**
     * Order by setting_smtp.smtp_port
     * @param bool $ascending
     * @return static
     */
    public function orderBySmtpPort(bool $ascending = true): static
    {
        $this->order($this->alias . '.smtp_port', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_smtp.smtp_port
     * @param int $filterValue
     * @return static
     */
    public function filterSmtpPortGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.smtp_port', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_smtp.smtp_port
     * @param int $filterValue
     * @return static
     */
    public function filterSmtpPortGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.smtp_port', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_smtp.smtp_port
     * @param int $filterValue
     * @return static
     */
    public function filterSmtpPortLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.smtp_port', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_smtp.smtp_port
     * @param int $filterValue
     * @return static
     */
    public function filterSmtpPortLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.smtp_port', $filterValue, '<=');
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
     * Group by setting_smtp.smtp_server
     * @return static
     */
    public function groupBySmtpServer(): static
    {
        $this->group($this->alias . '.smtp_server');
        return $this;
    }

    /**
     * Order by setting_smtp.smtp_server
     * @param bool $ascending
     * @return static
     */
    public function orderBySmtpServer(bool $ascending = true): static
    {
        $this->order($this->alias . '.smtp_server', $ascending);
        return $this;
    }

    /**
     * Filter setting_smtp.smtp_server by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSmtpServer(string $filterValue): static
    {
        $this->like($this->alias . '.smtp_server', "%{$filterValue}%");
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
     * Group by setting_smtp.smtp_ssl_type
     * @return static
     */
    public function groupBySmtpSslType(): static
    {
        $this->group($this->alias . '.smtp_ssl_type');
        return $this;
    }

    /**
     * Order by setting_smtp.smtp_ssl_type
     * @param bool $ascending
     * @return static
     */
    public function orderBySmtpSslType(bool $ascending = true): static
    {
        $this->order($this->alias . '.smtp_ssl_type', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_smtp.smtp_ssl_type
     * @param int $filterValue
     * @return static
     */
    public function filterSmtpSslTypeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.smtp_ssl_type', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_smtp.smtp_ssl_type
     * @param int $filterValue
     * @return static
     */
    public function filterSmtpSslTypeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.smtp_ssl_type', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_smtp.smtp_ssl_type
     * @param int $filterValue
     * @return static
     */
    public function filterSmtpSslTypeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.smtp_ssl_type', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_smtp.smtp_ssl_type
     * @param int $filterValue
     * @return static
     */
    public function filterSmtpSslTypeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.smtp_ssl_type', $filterValue, '<=');
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
     * Group by setting_smtp.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_smtp.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_smtp.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_smtp.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_smtp.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_smtp.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by setting_smtp.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_smtp.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_smtp.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_smtp.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_smtp.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_smtp.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by setting_smtp.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_smtp.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_smtp.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_smtp.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_smtp.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_smtp.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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
     * Group by setting_smtp.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_smtp.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_smtp.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_smtp.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_smtp.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_smtp.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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

    /**
     * Group by setting_smtp.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_smtp.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_smtp.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_smtp.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_smtp.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_smtp.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
