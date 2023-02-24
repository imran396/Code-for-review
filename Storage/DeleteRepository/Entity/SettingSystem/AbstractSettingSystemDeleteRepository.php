<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingSystem;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingSystemDeleteRepository extends DeleteRepositoryBase
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
}
