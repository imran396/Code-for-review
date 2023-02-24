<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingUser;

use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use SettingUser;

/**
 * Abstract class AbstractSettingUserReadRepository
 * @method SettingUser[] loadEntities()
 * @method SettingUser|null loadEntity()
 */
abstract class AbstractSettingUserReadRepository extends ReadRepositoryBase
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
     * Group by setting_user.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by setting_user.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
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
     * Group by setting_user.account_id
     * @return static
     */
    public function groupByAccountId(): static
    {
        $this->group($this->alias . '.account_id');
        return $this;
    }

    /**
     * Order by setting_user.account_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAccountId(bool $ascending = true): static
    {
        $this->order($this->alias . '.account_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.account_id
     * @param int $filterValue
     * @return static
     */
    public function filterAccountIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.account_id', $filterValue, '<=');
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
     * Group by setting_user.auto_preferred
     * @return static
     */
    public function groupByAutoPreferred(): static
    {
        $this->group($this->alias . '.auto_preferred');
        return $this;
    }

    /**
     * Order by setting_user.auto_preferred
     * @param bool $ascending
     * @return static
     */
    public function orderByAutoPreferred(bool $ascending = true): static
    {
        $this->order($this->alias . '.auto_preferred', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.auto_preferred
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoPreferredGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_preferred', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.auto_preferred
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoPreferredGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_preferred', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.auto_preferred
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoPreferredLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_preferred', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.auto_preferred
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoPreferredLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_preferred', $filterValue, '<=');
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
     * Group by setting_user.verify_email
     * @return static
     */
    public function groupByVerifyEmail(): static
    {
        $this->group($this->alias . '.verify_email');
        return $this;
    }

    /**
     * Order by setting_user.verify_email
     * @param bool $ascending
     * @return static
     */
    public function orderByVerifyEmail(bool $ascending = true): static
    {
        $this->order($this->alias . '.verify_email', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.verify_email
     * @param bool $filterValue
     * @return static
     */
    public function filterVerifyEmailGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.verify_email', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.verify_email
     * @param bool $filterValue
     * @return static
     */
    public function filterVerifyEmailGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.verify_email', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.verify_email
     * @param bool $filterValue
     * @return static
     */
    public function filterVerifyEmailLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.verify_email', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.verify_email
     * @param bool $filterValue
     * @return static
     */
    public function filterVerifyEmailLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.verify_email', $filterValue, '<=');
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
     * Group by setting_user.registration_require_cc
     * @return static
     */
    public function groupByRegistrationRequireCc(): static
    {
        $this->group($this->alias . '.registration_require_cc');
        return $this;
    }

    /**
     * Order by setting_user.registration_require_cc
     * @param bool $ascending
     * @return static
     */
    public function orderByRegistrationRequireCc(bool $ascending = true): static
    {
        $this->order($this->alias . '.registration_require_cc', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.registration_require_cc
     * @param bool $filterValue
     * @return static
     */
    public function filterRegistrationRequireCcGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.registration_require_cc', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.registration_require_cc
     * @param bool $filterValue
     * @return static
     */
    public function filterRegistrationRequireCcGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.registration_require_cc', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.registration_require_cc
     * @param bool $filterValue
     * @return static
     */
    public function filterRegistrationRequireCcLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.registration_require_cc', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.registration_require_cc
     * @param bool $filterValue
     * @return static
     */
    public function filterRegistrationRequireCcLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.registration_require_cc', $filterValue, '<=');
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
     * Group by setting_user.signup_tracking_code
     * @return static
     */
    public function groupBySignupTrackingCode(): static
    {
        $this->group($this->alias . '.signup_tracking_code');
        return $this;
    }

    /**
     * Order by setting_user.signup_tracking_code
     * @param bool $ascending
     * @return static
     */
    public function orderBySignupTrackingCode(bool $ascending = true): static
    {
        $this->order($this->alias . '.signup_tracking_code', $ascending);
        return $this;
    }

    /**
     * Filter setting_user.signup_tracking_code by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeSignupTrackingCode(string $filterValue): static
    {
        $this->like($this->alias . '.signup_tracking_code', "%{$filterValue}%");
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
     * Group by setting_user.simplified_signup
     * @return static
     */
    public function groupBySimplifiedSignup(): static
    {
        $this->group($this->alias . '.simplified_signup');
        return $this;
    }

    /**
     * Order by setting_user.simplified_signup
     * @param bool $ascending
     * @return static
     */
    public function orderBySimplifiedSignup(bool $ascending = true): static
    {
        $this->order($this->alias . '.simplified_signup', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.simplified_signup
     * @param bool $filterValue
     * @return static
     */
    public function filterSimplifiedSignupGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.simplified_signup', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.simplified_signup
     * @param bool $filterValue
     * @return static
     */
    public function filterSimplifiedSignupGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.simplified_signup', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.simplified_signup
     * @param bool $filterValue
     * @return static
     */
    public function filterSimplifiedSignupLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.simplified_signup', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.simplified_signup
     * @param bool $filterValue
     * @return static
     */
    public function filterSimplifiedSignupLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.simplified_signup', $filterValue, '<=');
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
     * Group by setting_user.include_basic_info
     * @return static
     */
    public function groupByIncludeBasicInfo(): static
    {
        $this->group($this->alias . '.include_basic_info');
        return $this;
    }

    /**
     * Order by setting_user.include_basic_info
     * @param bool $ascending
     * @return static
     */
    public function orderByIncludeBasicInfo(bool $ascending = true): static
    {
        $this->order($this->alias . '.include_basic_info', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.include_basic_info
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeBasicInfoGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_basic_info', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.include_basic_info
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeBasicInfoGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_basic_info', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.include_basic_info
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeBasicInfoLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_basic_info', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.include_basic_info
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeBasicInfoLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_basic_info', $filterValue, '<=');
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
     * Group by setting_user.mandatory_basic_info
     * @return static
     */
    public function groupByMandatoryBasicInfo(): static
    {
        $this->group($this->alias . '.mandatory_basic_info');
        return $this;
    }

    /**
     * Order by setting_user.mandatory_basic_info
     * @param bool $ascending
     * @return static
     */
    public function orderByMandatoryBasicInfo(bool $ascending = true): static
    {
        $this->order($this->alias . '.mandatory_basic_info', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.mandatory_basic_info
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryBasicInfoGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_basic_info', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.mandatory_basic_info
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryBasicInfoGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_basic_info', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.mandatory_basic_info
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryBasicInfoLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_basic_info', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.mandatory_basic_info
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryBasicInfoLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_basic_info', $filterValue, '<=');
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
     * Group by setting_user.include_billing_info
     * @return static
     */
    public function groupByIncludeBillingInfo(): static
    {
        $this->group($this->alias . '.include_billing_info');
        return $this;
    }

    /**
     * Order by setting_user.include_billing_info
     * @param bool $ascending
     * @return static
     */
    public function orderByIncludeBillingInfo(bool $ascending = true): static
    {
        $this->order($this->alias . '.include_billing_info', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.include_billing_info
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeBillingInfoGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_billing_info', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.include_billing_info
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeBillingInfoGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_billing_info', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.include_billing_info
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeBillingInfoLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_billing_info', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.include_billing_info
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeBillingInfoLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_billing_info', $filterValue, '<=');
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
     * Group by setting_user.mandatory_billing_info
     * @return static
     */
    public function groupByMandatoryBillingInfo(): static
    {
        $this->group($this->alias . '.mandatory_billing_info');
        return $this;
    }

    /**
     * Order by setting_user.mandatory_billing_info
     * @param bool $ascending
     * @return static
     */
    public function orderByMandatoryBillingInfo(bool $ascending = true): static
    {
        $this->order($this->alias . '.mandatory_billing_info', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.mandatory_billing_info
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryBillingInfoGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_billing_info', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.mandatory_billing_info
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryBillingInfoGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_billing_info', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.mandatory_billing_info
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryBillingInfoLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_billing_info', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.mandatory_billing_info
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryBillingInfoLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_billing_info', $filterValue, '<=');
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
     * Group by setting_user.include_cc_info
     * @return static
     */
    public function groupByIncludeCcInfo(): static
    {
        $this->group($this->alias . '.include_cc_info');
        return $this;
    }

    /**
     * Order by setting_user.include_cc_info
     * @param bool $ascending
     * @return static
     */
    public function orderByIncludeCcInfo(bool $ascending = true): static
    {
        $this->order($this->alias . '.include_cc_info', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.include_cc_info
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeCcInfoGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_cc_info', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.include_cc_info
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeCcInfoGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_cc_info', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.include_cc_info
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeCcInfoLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_cc_info', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.include_cc_info
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeCcInfoLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_cc_info', $filterValue, '<=');
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
     * Group by setting_user.mandatory_cc_info
     * @return static
     */
    public function groupByMandatoryCcInfo(): static
    {
        $this->group($this->alias . '.mandatory_cc_info');
        return $this;
    }

    /**
     * Order by setting_user.mandatory_cc_info
     * @param bool $ascending
     * @return static
     */
    public function orderByMandatoryCcInfo(bool $ascending = true): static
    {
        $this->order($this->alias . '.mandatory_cc_info', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.mandatory_cc_info
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryCcInfoGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_cc_info', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.mandatory_cc_info
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryCcInfoGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_cc_info', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.mandatory_cc_info
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryCcInfoLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_cc_info', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.mandatory_cc_info
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryCcInfoLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_cc_info', $filterValue, '<=');
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
     * Group by setting_user.include_ach_info
     * @return static
     */
    public function groupByIncludeAchInfo(): static
    {
        $this->group($this->alias . '.include_ach_info');
        return $this;
    }

    /**
     * Order by setting_user.include_ach_info
     * @param bool $ascending
     * @return static
     */
    public function orderByIncludeAchInfo(bool $ascending = true): static
    {
        $this->order($this->alias . '.include_ach_info', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.include_ach_info
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeAchInfoGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_ach_info', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.include_ach_info
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeAchInfoGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_ach_info', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.include_ach_info
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeAchInfoLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_ach_info', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.include_ach_info
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeAchInfoLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_ach_info', $filterValue, '<=');
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
     * Group by setting_user.mandatory_ach_info
     * @return static
     */
    public function groupByMandatoryAchInfo(): static
    {
        $this->group($this->alias . '.mandatory_ach_info');
        return $this;
    }

    /**
     * Order by setting_user.mandatory_ach_info
     * @param bool $ascending
     * @return static
     */
    public function orderByMandatoryAchInfo(bool $ascending = true): static
    {
        $this->order($this->alias . '.mandatory_ach_info', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.mandatory_ach_info
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryAchInfoGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_ach_info', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.mandatory_ach_info
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryAchInfoGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_ach_info', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.mandatory_ach_info
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryAchInfoLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_ach_info', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.mandatory_ach_info
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryAchInfoLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_ach_info', $filterValue, '<=');
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
     * Group by setting_user.enable_consignor_payment_info
     * @return static
     */
    public function groupByEnableConsignorPaymentInfo(): static
    {
        $this->group($this->alias . '.enable_consignor_payment_info');
        return $this;
    }

    /**
     * Order by setting_user.enable_consignor_payment_info
     * @param bool $ascending
     * @return static
     */
    public function orderByEnableConsignorPaymentInfo(bool $ascending = true): static
    {
        $this->order($this->alias . '.enable_consignor_payment_info', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.enable_consignor_payment_info
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableConsignorPaymentInfoGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_consignor_payment_info', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.enable_consignor_payment_info
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableConsignorPaymentInfoGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_consignor_payment_info', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.enable_consignor_payment_info
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableConsignorPaymentInfoLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_consignor_payment_info', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.enable_consignor_payment_info
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableConsignorPaymentInfoLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_consignor_payment_info', $filterValue, '<=');
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
     * Group by setting_user.profile_billing_optional
     * @return static
     */
    public function groupByProfileBillingOptional(): static
    {
        $this->group($this->alias . '.profile_billing_optional');
        return $this;
    }

    /**
     * Order by setting_user.profile_billing_optional
     * @param bool $ascending
     * @return static
     */
    public function orderByProfileBillingOptional(bool $ascending = true): static
    {
        $this->order($this->alias . '.profile_billing_optional', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.profile_billing_optional
     * @param bool $filterValue
     * @return static
     */
    public function filterProfileBillingOptionalGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.profile_billing_optional', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.profile_billing_optional
     * @param bool $filterValue
     * @return static
     */
    public function filterProfileBillingOptionalGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.profile_billing_optional', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.profile_billing_optional
     * @param bool $filterValue
     * @return static
     */
    public function filterProfileBillingOptionalLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.profile_billing_optional', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.profile_billing_optional
     * @param bool $filterValue
     * @return static
     */
    public function filterProfileBillingOptionalLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.profile_billing_optional', $filterValue, '<=');
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
     * Group by setting_user.profile_shipping_optional
     * @return static
     */
    public function groupByProfileShippingOptional(): static
    {
        $this->group($this->alias . '.profile_shipping_optional');
        return $this;
    }

    /**
     * Order by setting_user.profile_shipping_optional
     * @param bool $ascending
     * @return static
     */
    public function orderByProfileShippingOptional(bool $ascending = true): static
    {
        $this->order($this->alias . '.profile_shipping_optional', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.profile_shipping_optional
     * @param bool $filterValue
     * @return static
     */
    public function filterProfileShippingOptionalGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.profile_shipping_optional', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.profile_shipping_optional
     * @param bool $filterValue
     * @return static
     */
    public function filterProfileShippingOptionalGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.profile_shipping_optional', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.profile_shipping_optional
     * @param bool $filterValue
     * @return static
     */
    public function filterProfileShippingOptionalLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.profile_shipping_optional', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.profile_shipping_optional
     * @param bool $filterValue
     * @return static
     */
    public function filterProfileShippingOptionalLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.profile_shipping_optional', $filterValue, '<=');
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
     * Group by setting_user.auto_preferred_credit_card
     * @return static
     */
    public function groupByAutoPreferredCreditCard(): static
    {
        $this->group($this->alias . '.auto_preferred_credit_card');
        return $this;
    }

    /**
     * Order by setting_user.auto_preferred_credit_card
     * @param bool $ascending
     * @return static
     */
    public function orderByAutoPreferredCreditCard(bool $ascending = true): static
    {
        $this->order($this->alias . '.auto_preferred_credit_card', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.auto_preferred_credit_card
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoPreferredCreditCardGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_preferred_credit_card', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.auto_preferred_credit_card
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoPreferredCreditCardGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_preferred_credit_card', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.auto_preferred_credit_card
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoPreferredCreditCardLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_preferred_credit_card', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.auto_preferred_credit_card
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoPreferredCreditCardLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_preferred_credit_card', $filterValue, '<=');
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
     * Group by setting_user.on_registration
     * @return static
     */
    public function groupByOnRegistration(): static
    {
        $this->group($this->alias . '.on_registration');
        return $this;
    }

    /**
     * Order by setting_user.on_registration
     * @param bool $ascending
     * @return static
     */
    public function orderByOnRegistration(bool $ascending = true): static
    {
        $this->order($this->alias . '.on_registration', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.on_registration
     * @param int $filterValue
     * @return static
     */
    public function filterOnRegistrationGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_registration', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.on_registration
     * @param int $filterValue
     * @return static
     */
    public function filterOnRegistrationGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_registration', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.on_registration
     * @param int $filterValue
     * @return static
     */
    public function filterOnRegistrationLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_registration', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.on_registration
     * @param int $filterValue
     * @return static
     */
    public function filterOnRegistrationLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_registration', $filterValue, '<=');
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
     * Group by setting_user.on_registration_amount
     * @return static
     */
    public function groupByOnRegistrationAmount(): static
    {
        $this->group($this->alias . '.on_registration_amount');
        return $this;
    }

    /**
     * Order by setting_user.on_registration_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByOnRegistrationAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.on_registration_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.on_registration_amount
     * @param float $filterValue
     * @return static
     */
    public function filterOnRegistrationAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_registration_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.on_registration_amount
     * @param float $filterValue
     * @return static
     */
    public function filterOnRegistrationAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_registration_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.on_registration_amount
     * @param float $filterValue
     * @return static
     */
    public function filterOnRegistrationAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_registration_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.on_registration_amount
     * @param float $filterValue
     * @return static
     */
    public function filterOnRegistrationAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_registration_amount', $filterValue, '<=');
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
     * Group by setting_user.on_registration_expires
     * @return static
     */
    public function groupByOnRegistrationExpires(): static
    {
        $this->group($this->alias . '.on_registration_expires');
        return $this;
    }

    /**
     * Order by setting_user.on_registration_expires
     * @param bool $ascending
     * @return static
     */
    public function orderByOnRegistrationExpires(bool $ascending = true): static
    {
        $this->order($this->alias . '.on_registration_expires', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.on_registration_expires
     * @param int $filterValue
     * @return static
     */
    public function filterOnRegistrationExpiresGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_registration_expires', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.on_registration_expires
     * @param int $filterValue
     * @return static
     */
    public function filterOnRegistrationExpiresGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_registration_expires', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.on_registration_expires
     * @param int $filterValue
     * @return static
     */
    public function filterOnRegistrationExpiresLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_registration_expires', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.on_registration_expires
     * @param int $filterValue
     * @return static
     */
    public function filterOnRegistrationExpiresLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.on_registration_expires', $filterValue, '<=');
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
     * Group by setting_user.include_user_preferences
     * @return static
     */
    public function groupByIncludeUserPreferences(): static
    {
        $this->group($this->alias . '.include_user_preferences');
        return $this;
    }

    /**
     * Order by setting_user.include_user_preferences
     * @param bool $ascending
     * @return static
     */
    public function orderByIncludeUserPreferences(bool $ascending = true): static
    {
        $this->order($this->alias . '.include_user_preferences', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.include_user_preferences
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeUserPreferencesGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_user_preferences', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.include_user_preferences
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeUserPreferencesGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_user_preferences', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.include_user_preferences
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeUserPreferencesLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_user_preferences', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.include_user_preferences
     * @param bool $filterValue
     * @return static
     */
    public function filterIncludeUserPreferencesLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.include_user_preferences', $filterValue, '<=');
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
     * Group by setting_user.mandatory_user_preferences
     * @return static
     */
    public function groupByMandatoryUserPreferences(): static
    {
        $this->group($this->alias . '.mandatory_user_preferences');
        return $this;
    }

    /**
     * Order by setting_user.mandatory_user_preferences
     * @param bool $ascending
     * @return static
     */
    public function orderByMandatoryUserPreferences(bool $ascending = true): static
    {
        $this->order($this->alias . '.mandatory_user_preferences', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.mandatory_user_preferences
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryUserPreferencesGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_user_preferences', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.mandatory_user_preferences
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryUserPreferencesGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_user_preferences', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.mandatory_user_preferences
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryUserPreferencesLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_user_preferences', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.mandatory_user_preferences
     * @param bool $filterValue
     * @return static
     */
    public function filterMandatoryUserPreferencesLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.mandatory_user_preferences', $filterValue, '<=');
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
     * Group by setting_user.require_identification
     * @return static
     */
    public function groupByRequireIdentification(): static
    {
        $this->group($this->alias . '.require_identification');
        return $this;
    }

    /**
     * Order by setting_user.require_identification
     * @param bool $ascending
     * @return static
     */
    public function orderByRequireIdentification(bool $ascending = true): static
    {
        $this->order($this->alias . '.require_identification', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.require_identification
     * @param bool $filterValue
     * @return static
     */
    public function filterRequireIdentificationGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.require_identification', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.require_identification
     * @param bool $filterValue
     * @return static
     */
    public function filterRequireIdentificationGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.require_identification', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.require_identification
     * @param bool $filterValue
     * @return static
     */
    public function filterRequireIdentificationLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.require_identification', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.require_identification
     * @param bool $filterValue
     * @return static
     */
    public function filterRequireIdentificationLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.require_identification', $filterValue, '<=');
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
     * Group by setting_user.make_permanent_bidder_num
     * @return static
     */
    public function groupByMakePermanentBidderNum(): static
    {
        $this->group($this->alias . '.make_permanent_bidder_num');
        return $this;
    }

    /**
     * Order by setting_user.make_permanent_bidder_num
     * @param bool $ascending
     * @return static
     */
    public function orderByMakePermanentBidderNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.make_permanent_bidder_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.make_permanent_bidder_num
     * @param bool $filterValue
     * @return static
     */
    public function filterMakePermanentBidderNumGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.make_permanent_bidder_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.make_permanent_bidder_num
     * @param bool $filterValue
     * @return static
     */
    public function filterMakePermanentBidderNumGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.make_permanent_bidder_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.make_permanent_bidder_num
     * @param bool $filterValue
     * @return static
     */
    public function filterMakePermanentBidderNumLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.make_permanent_bidder_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.make_permanent_bidder_num
     * @param bool $filterValue
     * @return static
     */
    public function filterMakePermanentBidderNumLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.make_permanent_bidder_num', $filterValue, '<=');
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
     * Group by setting_user.send_confirmation_link
     * @return static
     */
    public function groupBySendConfirmationLink(): static
    {
        $this->group($this->alias . '.send_confirmation_link');
        return $this;
    }

    /**
     * Order by setting_user.send_confirmation_link
     * @param bool $ascending
     * @return static
     */
    public function orderBySendConfirmationLink(bool $ascending = true): static
    {
        $this->order($this->alias . '.send_confirmation_link', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.send_confirmation_link
     * @param bool $filterValue
     * @return static
     */
    public function filterSendConfirmationLinkGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.send_confirmation_link', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.send_confirmation_link
     * @param bool $filterValue
     * @return static
     */
    public function filterSendConfirmationLinkGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.send_confirmation_link', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.send_confirmation_link
     * @param bool $filterValue
     * @return static
     */
    public function filterSendConfirmationLinkLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.send_confirmation_link', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.send_confirmation_link
     * @param bool $filterValue
     * @return static
     */
    public function filterSendConfirmationLinkLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.send_confirmation_link', $filterValue, '<=');
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
     * Group by setting_user.enable_reseller_reg
     * @return static
     */
    public function groupByEnableResellerReg(): static
    {
        $this->group($this->alias . '.enable_reseller_reg');
        return $this;
    }

    /**
     * Order by setting_user.enable_reseller_reg
     * @param bool $ascending
     * @return static
     */
    public function orderByEnableResellerReg(bool $ascending = true): static
    {
        $this->order($this->alias . '.enable_reseller_reg', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.enable_reseller_reg
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableResellerRegGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_reseller_reg', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.enable_reseller_reg
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableResellerRegGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_reseller_reg', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.enable_reseller_reg
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableResellerRegLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_reseller_reg', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.enable_reseller_reg
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableResellerRegLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_reseller_reg', $filterValue, '<=');
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
     * Group by setting_user.revoke_preferred_bidder
     * @return static
     */
    public function groupByRevokePreferredBidder(): static
    {
        $this->group($this->alias . '.revoke_preferred_bidder');
        return $this;
    }

    /**
     * Order by setting_user.revoke_preferred_bidder
     * @param bool $ascending
     * @return static
     */
    public function orderByRevokePreferredBidder(bool $ascending = true): static
    {
        $this->order($this->alias . '.revoke_preferred_bidder', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.revoke_preferred_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterRevokePreferredBidderGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.revoke_preferred_bidder', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.revoke_preferred_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterRevokePreferredBidderGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.revoke_preferred_bidder', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.revoke_preferred_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterRevokePreferredBidderLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.revoke_preferred_bidder', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.revoke_preferred_bidder
     * @param bool $filterValue
     * @return static
     */
    public function filterRevokePreferredBidderLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.revoke_preferred_bidder', $filterValue, '<=');
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
     * Group by setting_user.enable_user_company
     * @return static
     */
    public function groupByEnableUserCompany(): static
    {
        $this->group($this->alias . '.enable_user_company');
        return $this;
    }

    /**
     * Order by setting_user.enable_user_company
     * @param bool $ascending
     * @return static
     */
    public function orderByEnableUserCompany(bool $ascending = true): static
    {
        $this->order($this->alias . '.enable_user_company', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.enable_user_company
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableUserCompanyGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_user_company', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.enable_user_company
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableUserCompanyGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_user_company', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.enable_user_company
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableUserCompanyLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_user_company', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.enable_user_company
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableUserCompanyLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_user_company', $filterValue, '<=');
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
     * Group by setting_user.agent_option
     * @return static
     */
    public function groupByAgentOption(): static
    {
        $this->group($this->alias . '.agent_option');
        return $this;
    }

    /**
     * Order by setting_user.agent_option
     * @param bool $ascending
     * @return static
     */
    public function orderByAgentOption(bool $ascending = true): static
    {
        $this->order($this->alias . '.agent_option', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.agent_option
     * @param bool $filterValue
     * @return static
     */
    public function filterAgentOptionGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.agent_option', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.agent_option
     * @param bool $filterValue
     * @return static
     */
    public function filterAgentOptionGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.agent_option', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.agent_option
     * @param bool $filterValue
     * @return static
     */
    public function filterAgentOptionLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.agent_option', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.agent_option
     * @param bool $filterValue
     * @return static
     */
    public function filterAgentOptionLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.agent_option', $filterValue, '<=');
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
     * Group by setting_user.auto_increment_customer_num
     * @return static
     */
    public function groupByAutoIncrementCustomerNum(): static
    {
        $this->group($this->alias . '.auto_increment_customer_num');
        return $this;
    }

    /**
     * Order by setting_user.auto_increment_customer_num
     * @param bool $ascending
     * @return static
     */
    public function orderByAutoIncrementCustomerNum(bool $ascending = true): static
    {
        $this->order($this->alias . '.auto_increment_customer_num', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.auto_increment_customer_num
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoIncrementCustomerNumGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_increment_customer_num', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.auto_increment_customer_num
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoIncrementCustomerNumGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_increment_customer_num', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.auto_increment_customer_num
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoIncrementCustomerNumLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_increment_customer_num', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.auto_increment_customer_num
     * @param bool $filterValue
     * @return static
     */
    public function filterAutoIncrementCustomerNumLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.auto_increment_customer_num', $filterValue, '<=');
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
     * Group by setting_user.hide_country_code_selection
     * @return static
     */
    public function groupByHideCountryCodeSelection(): static
    {
        $this->group($this->alias . '.hide_country_code_selection');
        return $this;
    }

    /**
     * Order by setting_user.hide_country_code_selection
     * @param bool $ascending
     * @return static
     */
    public function orderByHideCountryCodeSelection(bool $ascending = true): static
    {
        $this->order($this->alias . '.hide_country_code_selection', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.hide_country_code_selection
     * @param bool $filterValue
     * @return static
     */
    public function filterHideCountryCodeSelectionGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_country_code_selection', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.hide_country_code_selection
     * @param bool $filterValue
     * @return static
     */
    public function filterHideCountryCodeSelectionGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_country_code_selection', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.hide_country_code_selection
     * @param bool $filterValue
     * @return static
     */
    public function filterHideCountryCodeSelectionLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_country_code_selection', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.hide_country_code_selection
     * @param bool $filterValue
     * @return static
     */
    public function filterHideCountryCodeSelectionLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.hide_country_code_selection', $filterValue, '<=');
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
     * Group by setting_user.default_country_code
     * @return static
     */
    public function groupByDefaultCountryCode(): static
    {
        $this->group($this->alias . '.default_country_code');
        return $this;
    }

    /**
     * Order by setting_user.default_country_code
     * @param bool $ascending
     * @return static
     */
    public function orderByDefaultCountryCode(bool $ascending = true): static
    {
        $this->order($this->alias . '.default_country_code', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.default_country_code
     * @param int $filterValue
     * @return static
     */
    public function filterDefaultCountryCodeGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_country_code', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.default_country_code
     * @param int $filterValue
     * @return static
     */
    public function filterDefaultCountryCodeGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_country_code', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.default_country_code
     * @param int $filterValue
     * @return static
     */
    public function filterDefaultCountryCodeLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_country_code', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.default_country_code
     * @param int $filterValue
     * @return static
     */
    public function filterDefaultCountryCodeLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.default_country_code', $filterValue, '<=');
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
     * Group by setting_user.save_reseller_cert_in_profile
     * @return static
     */
    public function groupBySaveResellerCertInProfile(): static
    {
        $this->group($this->alias . '.save_reseller_cert_in_profile');
        return $this;
    }

    /**
     * Order by setting_user.save_reseller_cert_in_profile
     * @param bool $ascending
     * @return static
     */
    public function orderBySaveResellerCertInProfile(bool $ascending = true): static
    {
        $this->order($this->alias . '.save_reseller_cert_in_profile', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.save_reseller_cert_in_profile
     * @param bool $filterValue
     * @return static
     */
    public function filterSaveResellerCertInProfileGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.save_reseller_cert_in_profile', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.save_reseller_cert_in_profile
     * @param bool $filterValue
     * @return static
     */
    public function filterSaveResellerCertInProfileGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.save_reseller_cert_in_profile', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.save_reseller_cert_in_profile
     * @param bool $filterValue
     * @return static
     */
    public function filterSaveResellerCertInProfileLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.save_reseller_cert_in_profile', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.save_reseller_cert_in_profile
     * @param bool $filterValue
     * @return static
     */
    public function filterSaveResellerCertInProfileLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.save_reseller_cert_in_profile', $filterValue, '<=');
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
     * Group by setting_user.stay_on_account_domain
     * @return static
     */
    public function groupByStayOnAccountDomain(): static
    {
        $this->group($this->alias . '.stay_on_account_domain');
        return $this;
    }

    /**
     * Order by setting_user.stay_on_account_domain
     * @param bool $ascending
     * @return static
     */
    public function orderByStayOnAccountDomain(bool $ascending = true): static
    {
        $this->order($this->alias . '.stay_on_account_domain', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.stay_on_account_domain
     * @param bool $filterValue
     * @return static
     */
    public function filterStayOnAccountDomainGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.stay_on_account_domain', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.stay_on_account_domain
     * @param bool $filterValue
     * @return static
     */
    public function filterStayOnAccountDomainGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.stay_on_account_domain', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.stay_on_account_domain
     * @param bool $filterValue
     * @return static
     */
    public function filterStayOnAccountDomainLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.stay_on_account_domain', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.stay_on_account_domain
     * @param bool $filterValue
     * @return static
     */
    public function filterStayOnAccountDomainLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.stay_on_account_domain', $filterValue, '<=');
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
     * Group by setting_user.authorization_use
     * @return static
     */
    public function groupByAuthorizationUse(): static
    {
        $this->group($this->alias . '.authorization_use');
        return $this;
    }

    /**
     * Order by setting_user.authorization_use
     * @param bool $ascending
     * @return static
     */
    public function orderByAuthorizationUse(bool $ascending = true): static
    {
        $this->order($this->alias . '.authorization_use', $ascending);
        return $this;
    }

    /**
     * Filter setting_user.authorization_use by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeAuthorizationUse(string $filterValue): static
    {
        $this->like($this->alias . '.authorization_use', "%{$filterValue}%");
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
     * Group by setting_user.no_auto_authorization
     * @return static
     */
    public function groupByNoAutoAuthorization(): static
    {
        $this->group($this->alias . '.no_auto_authorization');
        return $this;
    }

    /**
     * Order by setting_user.no_auto_authorization
     * @param bool $ascending
     * @return static
     */
    public function orderByNoAutoAuthorization(bool $ascending = true): static
    {
        $this->order($this->alias . '.no_auto_authorization', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.no_auto_authorization
     * @param bool $filterValue
     * @return static
     */
    public function filterNoAutoAuthorizationGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_auto_authorization', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.no_auto_authorization
     * @param bool $filterValue
     * @return static
     */
    public function filterNoAutoAuthorizationGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_auto_authorization', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.no_auto_authorization
     * @param bool $filterValue
     * @return static
     */
    public function filterNoAutoAuthorizationLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_auto_authorization', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.no_auto_authorization
     * @param bool $filterValue
     * @return static
     */
    public function filterNoAutoAuthorizationLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.no_auto_authorization', $filterValue, '<=');
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
     * Group by setting_user.enable_user_resume
     * @return static
     */
    public function groupByEnableUserResume(): static
    {
        $this->group($this->alias . '.enable_user_resume');
        return $this;
    }

    /**
     * Order by setting_user.enable_user_resume
     * @param bool $ascending
     * @return static
     */
    public function orderByEnableUserResume(bool $ascending = true): static
    {
        $this->order($this->alias . '.enable_user_resume', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.enable_user_resume
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableUserResumeGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_user_resume', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.enable_user_resume
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableUserResumeGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_user_resume', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.enable_user_resume
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableUserResumeLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_user_resume', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.enable_user_resume
     * @param bool $filterValue
     * @return static
     */
    public function filterEnableUserResumeLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.enable_user_resume', $filterValue, '<=');
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
     * Group by setting_user.show_user_resume
     * @return static
     */
    public function groupByShowUserResume(): static
    {
        $this->group($this->alias . '.show_user_resume');
        return $this;
    }

    /**
     * Order by setting_user.show_user_resume
     * @param bool $ascending
     * @return static
     */
    public function orderByShowUserResume(bool $ascending = true): static
    {
        $this->order($this->alias . '.show_user_resume', $ascending);
        return $this;
    }

    /**
     * Filter setting_user.show_user_resume by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeShowUserResume(string $filterValue): static
    {
        $this->like($this->alias . '.show_user_resume', "%{$filterValue}%");
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
     * Group by setting_user.newsletter_option
     * @return static
     */
    public function groupByNewsletterOption(): static
    {
        $this->group($this->alias . '.newsletter_option');
        return $this;
    }

    /**
     * Order by setting_user.newsletter_option
     * @param bool $ascending
     * @return static
     */
    public function orderByNewsletterOption(bool $ascending = true): static
    {
        $this->order($this->alias . '.newsletter_option', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.newsletter_option
     * @param bool $filterValue
     * @return static
     */
    public function filterNewsletterOptionGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.newsletter_option', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.newsletter_option
     * @param bool $filterValue
     * @return static
     */
    public function filterNewsletterOptionGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.newsletter_option', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.newsletter_option
     * @param bool $filterValue
     * @return static
     */
    public function filterNewsletterOptionLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.newsletter_option', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.newsletter_option
     * @param bool $filterValue
     * @return static
     */
    public function filterNewsletterOptionLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.newsletter_option', $filterValue, '<=');
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
     * Group by setting_user.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by setting_user.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
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
     * Group by setting_user.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by setting_user.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
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
     * Group by setting_user.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by setting_user.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
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
     * Group by setting_user.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by setting_user.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
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

    /**
     * Group by setting_user.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by setting_user.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than setting_user.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than setting_user.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than setting_user.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than setting_user.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }
}
