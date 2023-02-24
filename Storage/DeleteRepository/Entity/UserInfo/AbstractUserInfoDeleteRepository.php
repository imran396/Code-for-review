<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserInfo;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractUserInfoDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_USER_INFO;
    protected string $alias = Db::A_USER_INFO;

    /**
     * Filter by user_info.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.user_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.user_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.first_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFirstName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.first_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.first_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFirstName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.first_name', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.last_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLastName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.last_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.last_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLastName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.last_name', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.sales_tax
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterSalesTax(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.sales_tax', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.sales_tax from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipSalesTax(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.sales_tax', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.tax_application
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterTaxApplication(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_application', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.tax_application from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipTaxApplication(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_application', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.no_tax
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNoTax(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.no_tax', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.no_tax from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNoTax(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.no_tax', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.no_tax_bp
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNoTaxBp(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.no_tax_bp', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.no_tax_bp from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNoTaxBp(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.no_tax_bp', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.note
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNote(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.note', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.note from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNote(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.note', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.resume
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterResume(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.resume', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.resume from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipResume(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.resume', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.phone
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPhone(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.phone', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.phone from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPhone(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.phone', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.view_language
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterViewLanguage(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.view_language', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.view_language from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipViewLanguage(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.view_language', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.news_letter
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNewsLetter(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.news_letter', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.news_letter from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNewsLetter(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.news_letter', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.company_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCompanyName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.company_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.company_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCompanyName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.company_name', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.reg_auth_date
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterRegAuthDate(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.reg_auth_date', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.reg_auth_date from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipRegAuthDate(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.reg_auth_date', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.phone_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterPhoneType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.phone_type', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.phone_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipPhoneType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.phone_type', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.identification
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterIdentification(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.identification', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.identification from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipIdentification(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.identification', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.identification_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterIdentificationType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.identification_type', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.identification_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipIdentificationType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.identification_type', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.location_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLocationId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.location_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.location_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLocationId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.location_id', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.send_text_alerts
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSendTextAlerts(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.send_text_alerts', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.send_text_alerts from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSendTextAlerts(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.send_text_alerts', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.referrer
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterReferrer(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.referrer', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.referrer from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipReferrer(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.referrer', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.referrer_host
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterReferrerHost(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.referrer_host', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.referrer_host from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipReferrerHost(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.referrer_host', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.reseller_cert_approved
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterResellerCertApproved(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reseller_cert_approved', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.reseller_cert_approved from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipResellerCertApproved(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reseller_cert_approved', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.reseller_cert_file
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterResellerCertFile(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.reseller_cert_file', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.reseller_cert_file from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipResellerCertFile(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.reseller_cert_file', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.reseller_cert_expiration
     * @param string|string[]|null $filterValue
     * @return static
     */
    public function filterResellerCertExpiration(string|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.reseller_cert_expiration', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.reseller_cert_expiration from result
     * @param string|string[]|null $skipValue
     * @return static
     */
    public function skipResellerCertExpiration(string|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.reseller_cert_expiration', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.max_outstanding
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterMaxOutstanding(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.max_outstanding', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.max_outstanding from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipMaxOutstanding(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.max_outstanding', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.locale
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLocale(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.locale', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.locale from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLocale(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.locale', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.timezone_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterTimezoneId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.timezone_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.timezone_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipTimezoneId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.timezone_id', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by user_info.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_info.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
