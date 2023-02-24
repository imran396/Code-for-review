<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingUser;

use Sam\Core\Constants\Db;
use Sam\Storage\DeleteRepository\Entity\DeleteRepositoryBase;

abstract class AbstractSettingUserDeleteRepository extends DeleteRepositoryBase
{
    protected string $table = Db::TBL_SETTING_USER;
    protected string $alias = Db::A_SETTING_USER;

    /**
     * Filter by setting_user.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.account_id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterAccountId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.account_id', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.account_id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipAccountId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.account_id', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.auto_preferred
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAutoPreferred(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auto_preferred', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.auto_preferred from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAutoPreferred(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auto_preferred', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.verify_email
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterVerifyEmail(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.verify_email', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.verify_email from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipVerifyEmail(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.verify_email', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.registration_require_cc
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRegistrationRequireCc(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.registration_require_cc', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.registration_require_cc from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRegistrationRequireCc(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.registration_require_cc', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.signup_tracking_code
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterSignupTrackingCode(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.signup_tracking_code', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.signup_tracking_code from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipSignupTrackingCode(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.signup_tracking_code', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.simplified_signup
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSimplifiedSignup(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.simplified_signup', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.simplified_signup from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSimplifiedSignup(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.simplified_signup', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.include_basic_info
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterIncludeBasicInfo(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.include_basic_info', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.include_basic_info from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipIncludeBasicInfo(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.include_basic_info', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.mandatory_basic_info
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterMandatoryBasicInfo(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.mandatory_basic_info', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.mandatory_basic_info from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipMandatoryBasicInfo(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.mandatory_basic_info', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.include_billing_info
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterIncludeBillingInfo(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.include_billing_info', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.include_billing_info from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipIncludeBillingInfo(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.include_billing_info', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.mandatory_billing_info
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterMandatoryBillingInfo(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.mandatory_billing_info', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.mandatory_billing_info from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipMandatoryBillingInfo(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.mandatory_billing_info', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.include_cc_info
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterIncludeCcInfo(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.include_cc_info', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.include_cc_info from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipIncludeCcInfo(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.include_cc_info', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.mandatory_cc_info
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterMandatoryCcInfo(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.mandatory_cc_info', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.mandatory_cc_info from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipMandatoryCcInfo(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.mandatory_cc_info', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.include_ach_info
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterIncludeAchInfo(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.include_ach_info', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.include_ach_info from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipIncludeAchInfo(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.include_ach_info', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.mandatory_ach_info
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterMandatoryAchInfo(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.mandatory_ach_info', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.mandatory_ach_info from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipMandatoryAchInfo(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.mandatory_ach_info', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.enable_consignor_payment_info
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterEnableConsignorPaymentInfo(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.enable_consignor_payment_info', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.enable_consignor_payment_info from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipEnableConsignorPaymentInfo(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.enable_consignor_payment_info', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.profile_billing_optional
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterProfileBillingOptional(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.profile_billing_optional', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.profile_billing_optional from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipProfileBillingOptional(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.profile_billing_optional', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.profile_shipping_optional
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterProfileShippingOptional(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.profile_shipping_optional', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.profile_shipping_optional from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipProfileShippingOptional(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.profile_shipping_optional', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.auto_preferred_credit_card
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAutoPreferredCreditCard(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auto_preferred_credit_card', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.auto_preferred_credit_card from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAutoPreferredCreditCard(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auto_preferred_credit_card', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.on_registration
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterOnRegistration(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.on_registration', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.on_registration from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipOnRegistration(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.on_registration', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.on_registration_amount
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterOnRegistrationAmount(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.on_registration_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.on_registration_amount from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipOnRegistrationAmount(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.on_registration_amount', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.on_registration_expires
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterOnRegistrationExpires(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.on_registration_expires', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.on_registration_expires from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipOnRegistrationExpires(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.on_registration_expires', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.include_user_preferences
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterIncludeUserPreferences(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.include_user_preferences', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.include_user_preferences from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipIncludeUserPreferences(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.include_user_preferences', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.mandatory_user_preferences
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterMandatoryUserPreferences(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.mandatory_user_preferences', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.mandatory_user_preferences from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipMandatoryUserPreferences(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.mandatory_user_preferences', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.require_identification
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRequireIdentification(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.require_identification', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.require_identification from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRequireIdentification(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.require_identification', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.make_permanent_bidder_num
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterMakePermanentBidderNum(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.make_permanent_bidder_num', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.make_permanent_bidder_num from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipMakePermanentBidderNum(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.make_permanent_bidder_num', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.send_confirmation_link
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSendConfirmationLink(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.send_confirmation_link', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.send_confirmation_link from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSendConfirmationLink(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.send_confirmation_link', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.enable_reseller_reg
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterEnableResellerReg(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.enable_reseller_reg', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.enable_reseller_reg from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipEnableResellerReg(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.enable_reseller_reg', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.revoke_preferred_bidder
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRevokePreferredBidder(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.revoke_preferred_bidder', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.revoke_preferred_bidder from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRevokePreferredBidder(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.revoke_preferred_bidder', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.enable_user_company
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterEnableUserCompany(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.enable_user_company', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.enable_user_company from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipEnableUserCompany(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.enable_user_company', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.agent_option
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAgentOption(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.agent_option', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.agent_option from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAgentOption(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.agent_option', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.auto_increment_customer_num
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterAutoIncrementCustomerNum(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.auto_increment_customer_num', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.auto_increment_customer_num from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipAutoIncrementCustomerNum(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.auto_increment_customer_num', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.hide_country_code_selection
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterHideCountryCodeSelection(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hide_country_code_selection', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.hide_country_code_selection from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipHideCountryCodeSelection(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hide_country_code_selection', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.default_country_code
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterDefaultCountryCode(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.default_country_code', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.default_country_code from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipDefaultCountryCode(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.default_country_code', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.save_reseller_cert_in_profile
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterSaveResellerCertInProfile(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.save_reseller_cert_in_profile', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.save_reseller_cert_in_profile from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipSaveResellerCertInProfile(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.save_reseller_cert_in_profile', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.stay_on_account_domain
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterStayOnAccountDomain(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.stay_on_account_domain', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.stay_on_account_domain from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipStayOnAccountDomain(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.stay_on_account_domain', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.authorization_use
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterAuthorizationUse(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.authorization_use', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.authorization_use from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipAuthorizationUse(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.authorization_use', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.no_auto_authorization
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNoAutoAuthorization(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.no_auto_authorization', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.no_auto_authorization from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNoAutoAuthorization(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.no_auto_authorization', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.enable_user_resume
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterEnableUserResume(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.enable_user_resume', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.enable_user_resume from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipEnableUserResume(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.enable_user_resume', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.show_user_resume
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterShowUserResume(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.show_user_resume', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.show_user_resume from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipShowUserResume(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.show_user_resume', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.newsletter_option
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterNewsletterOption(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.newsletter_option', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.newsletter_option from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipNewsletterOption(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.newsletter_option', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Filter by setting_user.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out setting_user.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }
}
