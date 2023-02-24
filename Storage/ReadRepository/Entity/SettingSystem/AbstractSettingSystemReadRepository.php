<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingSystem;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingSystem;

/**
 * Abstract class AbstractSettingSystemReadRepository
 * @method SettingSystem[] loadEntities()
 * @method SettingSystem|null loadEntity()
 */
abstract class AbstractSettingSystemReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_SETTING_SYSTEM;
    protected string $alias = Db::A_SETTING_SYSTEM;

    /**
     * Filter by setting_system.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_system.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_system.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_system.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_system.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_system.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_system.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_system.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_system.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_system.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_system.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_system.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_system.timezone_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterTimezoneId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.timezone_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.timezone_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipTimezoneId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.timezone_id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.timezone_id
     * @return static
     */
    public function groupByTimezoneId(): static
    {
        $this->group($this->alias . '.timezone_id');
        return $this;
    }

    /**
     * Order by setting_system.timezone_id
     * @param bool $ascending
     * @return static
     */
    public function orderByTimezoneId(bool $ascending = true): static
    {
        $this->order($this->alias . '.timezone_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_system.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_system.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_system.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_system.timezone_id
     * @param int $filterValue
     * @return static
     */
    public function filterTimezoneIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.timezone_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_system.default_country
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterDefaultCountry(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.default_country', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.default_country from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipDefaultCountry(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.default_country', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.default_country
     * @return static
     */
    public function groupByDefaultCountry(): static
    {
        $this->group($this->alias . '.default_country');
        return $this;
    }

    /**
     * Order by setting_system.default_country
     * @param bool $ascending
     * @return static
     */
    public function orderByDefaultCountry(bool $ascending = true): static
    {
        $this->order($this->alias . '.default_country', $ascending);
        return $this;
    }

    /**
     * Filter setting_system.default_country by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeDefaultCountry(string $filterValue): static
    {
        $this->like($this->alias . '.default_country', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_system.support_email
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSupportEmail(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.support_email', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.support_email from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSupportEmail(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.support_email', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.support_email
     * @return static
     */
    public function groupBySupportEmail(): static
    {
        $this->group($this->alias . '.support_email');
        return $this;
    }

    /**
     * Order by setting_system.support_email
     * @param bool $ascending
     * @return static
     */
    public function orderBySupportEmail(bool $ascending = true): static
    {
        $this->order($this->alias . '.support_email', $ascending);
        return $this;
    }

    /**
     * Filter setting_system.support_email by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSupportEmail(string $filterValue): static
    {
        $this->like($this->alias . '.support_email', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_system.reg_reminder_email
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterRegReminderEmail(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.reg_reminder_email', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.reg_reminder_email from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipRegReminderEmail(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.reg_reminder_email', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.reg_reminder_email
     * @return static
     */
    public function groupByRegReminderEmail(): static
    {
        $this->group($this->alias . '.reg_reminder_email');
        return $this;
    }

    /**
     * Order by setting_system.reg_reminder_email
     * @param bool $ascending
     * @return static
     */
    public function orderByRegReminderEmail(bool $ascending = true): static
    {
        $this->order($this->alias . '.reg_reminder_email', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_system.reg_reminder_email
     * @param int $filterValue
     * @return static
     */
    public function filterRegReminderEmailGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_reminder_email', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_system.reg_reminder_email
     * @param int $filterValue
     * @return static
     */
    public function filterRegReminderEmailGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_reminder_email', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_system.reg_reminder_email
     * @param int $filterValue
     * @return static
     */
    public function filterRegReminderEmailLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_reminder_email', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_system.reg_reminder_email
     * @param int $filterValue
     * @return static
     */
    public function filterRegReminderEmailLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.reg_reminder_email', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_system.email_format
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEmailFormat(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.email_format', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.email_format from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEmailFormat(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.email_format', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.email_format
     * @return static
     */
    public function groupByEmailFormat(): static
    {
        $this->group($this->alias . '.email_format');
        return $this;
    }

    /**
     * Order by setting_system.email_format
     * @param bool $ascending
     * @return static
     */
    public function orderByEmailFormat(bool $ascending = true): static
    {
        $this->order($this->alias . '.email_format', $ascending);
        return $this;
    }

    /**
     * Filter setting_system.email_format by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeEmailFormat(string $filterValue): static
    {
        $this->like($this->alias . '.email_format', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_system.default_import_encoding
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterDefaultImportEncoding(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.default_import_encoding', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.default_import_encoding from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipDefaultImportEncoding(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.default_import_encoding', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.default_import_encoding
     * @return static
     */
    public function groupByDefaultImportEncoding(): static
    {
        $this->group($this->alias . '.default_import_encoding');
        return $this;
    }

    /**
     * Order by setting_system.default_import_encoding
     * @param bool $ascending
     * @return static
     */
    public function orderByDefaultImportEncoding(bool $ascending = true): static
    {
        $this->order($this->alias . '.default_import_encoding', $ascending);
        return $this;
    }

    /**
     * Filter setting_system.default_import_encoding by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeDefaultImportEncoding(string $filterValue): static
    {
        $this->like($this->alias . '.default_import_encoding', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_system.default_export_encoding
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterDefaultExportEncoding(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.default_export_encoding', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.default_export_encoding from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipDefaultExportEncoding(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.default_export_encoding', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.default_export_encoding
     * @return static
     */
    public function groupByDefaultExportEncoding(): static
    {
        $this->group($this->alias . '.default_export_encoding');
        return $this;
    }

    /**
     * Order by setting_system.default_export_encoding
     * @param bool $ascending
     * @return static
     */
    public function orderByDefaultExportEncoding(bool $ascending = true): static
    {
        $this->order($this->alias . '.default_export_encoding', $ascending);
        return $this;
    }

    /**
     * Filter setting_system.default_export_encoding by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeDefaultExportEncoding(string $filterValue): static
    {
        $this->like($this->alias . '.default_export_encoding', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_system.lot_category_global_order_available
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterLotCategoryGlobalOrderAvailable(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_category_global_order_available', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.lot_category_global_order_available from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipLotCategoryGlobalOrderAvailable(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_category_global_order_available', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.lot_category_global_order_available
     * @return static
     */
    public function groupByLotCategoryGlobalOrderAvailable(): static
    {
        $this->group($this->alias . '.lot_category_global_order_available');
        return $this;
    }

    /**
     * Order by setting_system.lot_category_global_order_available
     * @param bool $ascending
     * @return static
     */
    public function orderByLotCategoryGlobalOrderAvailable(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_category_global_order_available', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_system.lot_category_global_order_available
     * @param bool $filterValue
     * @return static
     */
    public function filterLotCategoryGlobalOrderAvailableGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_category_global_order_available', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_system.lot_category_global_order_available
     * @param bool $filterValue
     * @return static
     */
    public function filterLotCategoryGlobalOrderAvailableGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_category_global_order_available', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_system.lot_category_global_order_available
     * @param bool $filterValue
     * @return static
     */
    public function filterLotCategoryGlobalOrderAvailableLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_category_global_order_available', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_system.lot_category_global_order_available
     * @param bool $filterValue
     * @return static
     */
    public function filterLotCategoryGlobalOrderAvailableLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_category_global_order_available', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_system.pickup_reminder_email_frequency
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterPickupReminderEmailFrequency(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pickup_reminder_email_frequency', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.pickup_reminder_email_frequency from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipPickupReminderEmailFrequency(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pickup_reminder_email_frequency', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.pickup_reminder_email_frequency
     * @return static
     */
    public function groupByPickupReminderEmailFrequency(): static
    {
        $this->group($this->alias . '.pickup_reminder_email_frequency');
        return $this;
    }

    /**
     * Order by setting_system.pickup_reminder_email_frequency
     * @param bool $ascending
     * @return static
     */
    public function orderByPickupReminderEmailFrequency(bool $ascending = true): static
    {
        $this->order($this->alias . '.pickup_reminder_email_frequency', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_system.pickup_reminder_email_frequency
     * @param int $filterValue
     * @return static
     */
    public function filterPickupReminderEmailFrequencyGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pickup_reminder_email_frequency', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_system.pickup_reminder_email_frequency
     * @param int $filterValue
     * @return static
     */
    public function filterPickupReminderEmailFrequencyGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pickup_reminder_email_frequency', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_system.pickup_reminder_email_frequency
     * @param int $filterValue
     * @return static
     */
    public function filterPickupReminderEmailFrequencyLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pickup_reminder_email_frequency', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_system.pickup_reminder_email_frequency
     * @param int $filterValue
     * @return static
     */
    public function filterPickupReminderEmailFrequencyLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.pickup_reminder_email_frequency', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_system.payment_reminder_email_frequency
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterPaymentReminderEmailFrequency(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.payment_reminder_email_frequency', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.payment_reminder_email_frequency from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipPaymentReminderEmailFrequency(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.payment_reminder_email_frequency', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.payment_reminder_email_frequency
     * @return static
     */
    public function groupByPaymentReminderEmailFrequency(): static
    {
        $this->group($this->alias . '.payment_reminder_email_frequency');
        return $this;
    }

    /**
     * Order by setting_system.payment_reminder_email_frequency
     * @param bool $ascending
     * @return static
     */
    public function orderByPaymentReminderEmailFrequency(bool $ascending = true): static
    {
        $this->order($this->alias . '.payment_reminder_email_frequency', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_system.payment_reminder_email_frequency
     * @param int $filterValue
     * @return static
     */
    public function filterPaymentReminderEmailFrequencyGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.payment_reminder_email_frequency', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_system.payment_reminder_email_frequency
     * @param int $filterValue
     * @return static
     */
    public function filterPaymentReminderEmailFrequencyGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.payment_reminder_email_frequency', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_system.payment_reminder_email_frequency
     * @param int $filterValue
     * @return static
     */
    public function filterPaymentReminderEmailFrequencyLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.payment_reminder_email_frequency', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_system.payment_reminder_email_frequency
     * @param int $filterValue
     * @return static
     */
    public function filterPaymentReminderEmailFrequencyLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.payment_reminder_email_frequency', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_system.force_main_account_domain_mode
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterForceMainAccountDomainMode(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.force_main_account_domain_mode', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.force_main_account_domain_mode from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipForceMainAccountDomainMode(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.force_main_account_domain_mode', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.force_main_account_domain_mode
     * @return static
     */
    public function groupByForceMainAccountDomainMode(): static
    {
        $this->group($this->alias . '.force_main_account_domain_mode');
        return $this;
    }

    /**
     * Order by setting_system.force_main_account_domain_mode
     * @param bool $ascending
     * @return static
     */
    public function orderByForceMainAccountDomainMode(bool $ascending = true): static
    {
        $this->order($this->alias . '.force_main_account_domain_mode', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_system.force_main_account_domain_mode
     * @param bool $filterValue
     * @return static
     */
    public function filterForceMainAccountDomainModeGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.force_main_account_domain_mode', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_system.force_main_account_domain_mode
     * @param bool $filterValue
     * @return static
     */
    public function filterForceMainAccountDomainModeGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.force_main_account_domain_mode', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_system.force_main_account_domain_mode
     * @param bool $filterValue
     * @return static
     */
    public function filterForceMainAccountDomainModeLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.force_main_account_domain_mode', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_system.force_main_account_domain_mode
     * @param bool $filterValue
     * @return static
     */
    public function filterForceMainAccountDomainModeLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.force_main_account_domain_mode', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_system.locale
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLocale(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.locale', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.locale from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLocale(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.locale', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.locale
     * @return static
     */
    public function groupByLocale(): static
    {
        $this->group($this->alias . '.locale');
        return $this;
    }

    /**
     * Order by setting_system.locale
     * @param bool $ascending
     * @return static
     */
    public function orderByLocale(bool $ascending = true): static
    {
        $this->order($this->alias . '.locale', $ascending);
        return $this;
    }

    /**
     * Filter setting_system.locale by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLocale(string $filterValue): static
    {
        $this->like($this->alias . '.locale', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_system.primary_currency_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterPrimaryCurrencyId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.primary_currency_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.primary_currency_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipPrimaryCurrencyId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.primary_currency_id', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.primary_currency_id
     * @return static
     */
    public function groupByPrimaryCurrencyId(): static
    {
        $this->group($this->alias . '.primary_currency_id');
        return $this;
    }

    /**
     * Order by setting_system.primary_currency_id
     * @param bool $ascending
     * @return static
     */
    public function orderByPrimaryCurrencyId(bool $ascending = true): static
    {
        $this->order($this->alias . '.primary_currency_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_system.primary_currency_id
     * @param int $filterValue
     * @return static
     */
    public function filterPrimaryCurrencyIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.primary_currency_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_system.primary_currency_id
     * @param int $filterValue
     * @return static
     */
    public function filterPrimaryCurrencyIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.primary_currency_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_system.primary_currency_id
     * @param int $filterValue
     * @return static
     */
    public function filterPrimaryCurrencyIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.primary_currency_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_system.primary_currency_id
     * @param int $filterValue
     * @return static
     */
    public function filterPrimaryCurrencyIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.primary_currency_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_system.us_number_formatting
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterUsNumberFormatting(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.us_number_formatting', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.us_number_formatting from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipUsNumberFormatting(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.us_number_formatting', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.us_number_formatting
     * @return static
     */
    public function groupByUsNumberFormatting(): static
    {
        $this->group($this->alias . '.us_number_formatting');
        return $this;
    }

    /**
     * Order by setting_system.us_number_formatting
     * @param bool $ascending
     * @return static
     */
    public function orderByUsNumberFormatting(bool $ascending = true): static
    {
        $this->order($this->alias . '.us_number_formatting', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_system.us_number_formatting
     * @param bool $filterValue
     * @return static
     */
    public function filterUsNumberFormattingGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.us_number_formatting', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_system.us_number_formatting
     * @param bool $filterValue
     * @return static
     */
    public function filterUsNumberFormattingGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.us_number_formatting', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_system.us_number_formatting
     * @param bool $filterValue
     * @return static
     */
    public function filterUsNumberFormattingLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.us_number_formatting', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_system.us_number_formatting
     * @param bool $filterValue
     * @return static
     */
    public function filterUsNumberFormattingLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.us_number_formatting', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_system.admin_date_format
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAdminDateFormat(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.admin_date_format', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.admin_date_format from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAdminDateFormat(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.admin_date_format', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.admin_date_format
     * @return static
     */
    public function groupByAdminDateFormat(): static
    {
        $this->group($this->alias . '.admin_date_format');
        return $this;
    }

    /**
     * Order by setting_system.admin_date_format
     * @param bool $ascending
     * @return static
     */
    public function orderByAdminDateFormat(bool $ascending = true): static
    {
        $this->order($this->alias . '.admin_date_format', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_system.admin_date_format
     * @param int $filterValue
     * @return static
     */
    public function filterAdminDateFormatGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.admin_date_format', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_system.admin_date_format
     * @param int $filterValue
     * @return static
     */
    public function filterAdminDateFormatGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.admin_date_format', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_system.admin_date_format
     * @param int $filterValue
     * @return static
     */
    public function filterAdminDateFormatLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.admin_date_format', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_system.admin_date_format
     * @param int $filterValue
     * @return static
     */
    public function filterAdminDateFormatLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.admin_date_format', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_system.admin_language
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAdminLanguage(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.admin_language', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.admin_language from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAdminLanguage(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.admin_language', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.admin_language
     * @return static
     */
    public function groupByAdminLanguage(): static
    {
        $this->group($this->alias . '.admin_language');
        return $this;
    }

    /**
     * Order by setting_system.admin_language
     * @param bool $ascending
     * @return static
     */
    public function orderByAdminLanguage(bool $ascending = true): static
    {
        $this->order($this->alias . '.admin_language', $ascending);
        return $this;
    }

    /**
     * Filter setting_system.admin_language by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAdminLanguage(string $filterValue): static
    {
        $this->like($this->alias . '.admin_language', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_system.view_language
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterViewLanguage(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.view_language', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.view_language from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipViewLanguage(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.view_language', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.view_language
     * @return static
     */
    public function groupByViewLanguage(): static
    {
        $this->group($this->alias . '.view_language');
        return $this;
    }

    /**
     * Order by setting_system.view_language
     * @param bool $ascending
     * @return static
     */
    public function orderByViewLanguage(bool $ascending = true): static
    {
        $this->order($this->alias . '.view_language', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_system.view_language
     * @param int $filterValue
     * @return static
     */
    public function filterViewLanguageGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.view_language', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_system.view_language
     * @param int $filterValue
     * @return static
     */
    public function filterViewLanguageGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.view_language', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_system.view_language
     * @param int $filterValue
     * @return static
     */
    public function filterViewLanguageLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.view_language', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_system.view_language
     * @param int $filterValue
     * @return static
     */
    public function filterViewLanguageLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.view_language', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_system.wavebid_uat
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterWavebidUat(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.wavebid_uat', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.wavebid_uat from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipWavebidUat(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.wavebid_uat', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.wavebid_uat
     * @return static
     */
    public function groupByWavebidUat(): static
    {
        $this->group($this->alias . '.wavebid_uat');
        return $this;
    }

    /**
     * Order by setting_system.wavebid_uat
     * @param bool $ascending
     * @return static
     */
    public function orderByWavebidUat(bool $ascending = true): static
    {
        $this->order($this->alias . '.wavebid_uat', $ascending);
        return $this;
    }

    /**
     * Filter setting_system.wavebid_uat by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeWavebidUat(string $filterValue): static
    {
        $this->like($this->alias . '.wavebid_uat', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_system.wavebid_endpoint
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterWavebidEndpoint(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.wavebid_endpoint', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.wavebid_endpoint from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipWavebidEndpoint(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.wavebid_endpoint', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.wavebid_endpoint
     * @return static
     */
    public function groupByWavebidEndpoint(): static
    {
        $this->group($this->alias . '.wavebid_endpoint');
        return $this;
    }

    /**
     * Order by setting_system.wavebid_endpoint
     * @param bool $ascending
     * @return static
     */
    public function orderByWavebidEndpoint(bool $ascending = true): static
    {
        $this->order($this->alias . '.wavebid_endpoint', $ascending);
        return $this;
    }

    /**
     * Filter setting_system.wavebid_endpoint by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeWavebidEndpoint(string $filterValue): static
    {
        $this->like($this->alias . '.wavebid_endpoint', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_system.graphql_cors_allowed_origins
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterGraphqlCorsAllowedOrigins(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.graphql_cors_allowed_origins', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.graphql_cors_allowed_origins from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipGraphqlCorsAllowedOrigins(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.graphql_cors_allowed_origins', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.graphql_cors_allowed_origins
     * @return static
     */
    public function groupByGraphqlCorsAllowedOrigins(): static
    {
        $this->group($this->alias . '.graphql_cors_allowed_origins');
        return $this;
    }

    /**
     * Order by setting_system.graphql_cors_allowed_origins
     * @param bool $ascending
     * @return static
     */
    public function orderByGraphqlCorsAllowedOrigins(bool $ascending = true): static
    {
        $this->order($this->alias . '.graphql_cors_allowed_origins', $ascending);
        return $this;
    }

    /**
     * Filter setting_system.graphql_cors_allowed_origins by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeGraphqlCorsAllowedOrigins(string $filterValue): static
    {
        $this->like($this->alias . '.graphql_cors_allowed_origins', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by setting_system.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_system.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_system.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_system.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_system.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_system.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_system.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_system.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_system.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_system.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_system.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_system.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_system.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_system.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_system.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_system.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_system.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_system.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_system.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_system.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_system.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_system.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_system.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_system.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by setting_system.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_system.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by setting_system.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_system.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_system.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_system.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_system.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_system.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
