<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserBilling;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractUserBillingDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_USER_BILLING;
    protected string $alias = Db::A_USER_BILLING;

    /**
     * Filter by user_billing.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.user_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterUserId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.user_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.user_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipUserId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.user_id', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.company_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCompanyName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.company_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.company_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCompanyName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.company_name', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.first_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFirstName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.first_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.first_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFirstName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.first_name', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.last_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLastName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.last_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.last_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLastName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.last_name', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.phone
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPhone(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.phone', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.phone from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPhone(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.phone', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.fax
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterFax(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.fax', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.fax from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipFax(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.fax', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.email
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEmail(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.email', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.email from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEmail(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.email', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.country
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCountry(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.country', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.country from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCountry(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.country', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.address
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.address from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.address2
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress2(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address2', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.address2 from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress2(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address2', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.city
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCity(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.city', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.city from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCity(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.city', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.state
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterState(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.state', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.state from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipState(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.state', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.zip
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterZip(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.zip', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.zip from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipZip(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.zip', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.cc_type
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCcType(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_type', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.cc_type from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCcType(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_type', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.cc_number
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCcNumber(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_number', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.cc_number from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCcNumber(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_number', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.cc_number_eway
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCcNumberEway(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_number_eway', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.cc_number_eway from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCcNumberEway(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_number_eway', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.cc_number_hash
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCcNumberHash(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_number_hash', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.cc_number_hash from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCcNumberHash(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_number_hash', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.cc_exp_date
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCcExpDate(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.cc_exp_date', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.cc_exp_date from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCcExpDate(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.cc_exp_date', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.use_card
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterUseCard(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.use_card', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.use_card from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipUseCard(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.use_card', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.bank_routing_number
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBankRoutingNumber(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bank_routing_number', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.bank_routing_number from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBankRoutingNumber(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bank_routing_number', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.bank_account_number
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBankAccountNumber(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bank_account_number', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.bank_account_number from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBankAccountNumber(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bank_account_number', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.bank_account_type
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBankAccountType(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bank_account_type', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.bank_account_type from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBankAccountType(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bank_account_type', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.bank_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBankName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bank_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.bank_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBankName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bank_name', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.bank_account_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBankAccountName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bank_account_name', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.bank_account_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBankAccountName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bank_account_name', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.address3
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAddress3(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.address3', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.address3 from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAddress3(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.address3', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.contact_type
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterContactType(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.contact_type', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.contact_type from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipContactType(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.contact_type', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.bank_account_holder_type
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterBankAccountHolderType(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bank_account_holder_type', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.bank_account_holder_type from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipBankAccountHolderType(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bank_account_holder_type', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.auth_net_cpi
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuthNetCpi(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auth_net_cpi', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.auth_net_cpi from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuthNetCpi(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auth_net_cpi', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.auth_net_cppi
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuthNetCppi(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auth_net_cppi', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.auth_net_cppi from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuthNetCppi(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auth_net_cppi', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.auth_net_cai
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuthNetCai(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auth_net_cai', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.auth_net_cai from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuthNetCai(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auth_net_cai', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.pay_trace_cust_id
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterPayTraceCustId(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.pay_trace_cust_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.pay_trace_cust_id from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipPayTraceCustId(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.pay_trace_cust_id', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.nmi_vault_id
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterNmiVaultId(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.nmi_vault_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.nmi_vault_id from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipNmiVaultId(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.nmi_vault_id', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.opayo_token_id
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterOpayoTokenId(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.opayo_token_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.opayo_token_id from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipOpayoTokenId(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.opayo_token_id', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.eway_token_id
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterEwayTokenId(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.eway_token_id', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.eway_token_id from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipEwayTokenId(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.eway_token_id', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by user_billing.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out user_billing.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }
}
