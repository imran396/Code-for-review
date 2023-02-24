<?php
/**
 * Availability checks for "Simplified signup page" related options.
 * They follow the rule of hierarchical options defined in spec: "20201110-hierarchical-options.md"
 *
 * SAM-6895: Apply hierarchical options rules for "Simplified Signup"
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Ivan Zgoniaiko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\User;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Setting\UserSettingPureChecker;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class UserSettingChecker
 * @package Sam\Settings\User
 */
class UserSettingChecker extends CustomizableClass
{
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if the IsSimplified is configured on settings
     * @return bool
     */
    public function isSimplifiedSignup(): bool
    {
        $isSimplifiedSignup = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::SIMPLIFIED_SIGNUP);
        return UserSettingPureChecker::new()->isSimplifiedSignup($isSimplifiedSignup);
    }

    /**
     * @return bool
     */
    public function isIncludeBasicInfo(): bool
    {
        $isIncludeBasicInfo = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::INCLUDE_BASIC_INFO);
        $isSimplifiedSignup = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::SIMPLIFIED_SIGNUP);
        return UserSettingPureChecker::new()->isIncludeBasicInfo($isIncludeBasicInfo, $isSimplifiedSignup);
    }

    /**
     * @return bool
     */
    public function isMandatoryBasicInfo(): bool
    {
        $isMandatoryBasicInfo = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::MANDATORY_BASIC_INFO);
        $isIncludeBasicInfo = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::INCLUDE_BASIC_INFO);
        $isSimplifiedSignup = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::SIMPLIFIED_SIGNUP);
        return UserSettingPureChecker::new()->isMandatoryBasicInfo($isMandatoryBasicInfo, $isIncludeBasicInfo, $isSimplifiedSignup);
    }

    /**
     * @return bool
     */
    public function isIncludeBillingInfo(): bool
    {
        $isIncludeBillingInfo = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::INCLUDE_BILLING_INFO);
        $isSimplifiedSignup = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::SIMPLIFIED_SIGNUP);
        return UserSettingPureChecker::new()->isIncludeBillingInfo($isIncludeBillingInfo, $isSimplifiedSignup);
    }

    /**
     * @return bool
     */
    public function isMandatoryBillingInfo(): bool
    {
        $isMandatoryBillingInfo = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::MANDATORY_BILLING_INFO);
        $isIncludeBillingInfo = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::INCLUDE_BILLING_INFO);
        $isSimplifiedSignup = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::SIMPLIFIED_SIGNUP);
        return UserSettingPureChecker::new()->isMandatoryBillingInfo($isMandatoryBillingInfo, $isIncludeBillingInfo, $isSimplifiedSignup);
    }

    /**
     * @return bool
     */
    public function isIncludeAchInfo(): bool
    {
        $isIncludeAchInfo = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::INCLUDE_ACH_INFO);
        $isSimplifiedSignup = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::SIMPLIFIED_SIGNUP);
        return UserSettingPureChecker::new()->isIncludeAchInfo($isIncludeAchInfo, $isSimplifiedSignup);
    }

    /**
     * @return bool
     */
    public function isMandatoryAchInfo(): bool
    {
        $isMandatoryAchInfo = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::MANDATORY_ACH_INFO);
        $isIncludeAchInfo = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::INCLUDE_ACH_INFO);
        $isSimplifiedSignup = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::SIMPLIFIED_SIGNUP);
        return UserSettingPureChecker::new()->isMandatoryAchInfo($isMandatoryAchInfo, $isIncludeAchInfo, $isSimplifiedSignup);
    }

    /**
     * @return bool
     */
    public function isIncludeCcInfo(): bool
    {
        $isIncludeCcInfo = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::INCLUDE_CC_INFO);
        $isSimplifiedSignup = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::SIMPLIFIED_SIGNUP);
        $isRequireCcOnSignup = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::REGISTRATION_REQUIRE_CC);
        return UserSettingPureChecker::new()->isIncludeCcInfo($isIncludeCcInfo, $isSimplifiedSignup, $isRequireCcOnSignup);
    }

    /**
     * @return bool
     */
    public function isMandatoryCcInfo(): bool
    {
        $isMandatoryCcInfo = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::MANDATORY_CC_INFO);
        $isIncludeCcInfo = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::INCLUDE_CC_INFO);
        $isSimplifiedSignup = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::SIMPLIFIED_SIGNUP);
        $isRequireCcOnSignup = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::REGISTRATION_REQUIRE_CC);
        return UserSettingPureChecker::new()->isMandatoryCcInfo($isMandatoryCcInfo, $isIncludeCcInfo, $isSimplifiedSignup, $isRequireCcOnSignup);
    }

    /**
     * @return bool
     */
    public function isProfileBillingOptional(): bool
    {
        $isProfileBillingOptional = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::PROFILE_BILLING_OPTIONAL);
        $isSimplifiedSignup = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::SIMPLIFIED_SIGNUP);
        return UserSettingPureChecker::new()->isProfileBillingOptional($isProfileBillingOptional, $isSimplifiedSignup);
    }

    /**
     * @return bool
     */
    public function isProfileShippingOptional(): bool
    {
        $isProfileShippingOptional = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::PROFILE_SHIPPING_OPTIONAL);
        $isSimplifiedSignup = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::SIMPLIFIED_SIGNUP);
        return UserSettingPureChecker::new()->isProfileShippingOptional($isProfileShippingOptional, $isSimplifiedSignup);
    }

    /**
     * @return bool
     */
    public function isProfileShippingRequired(): bool
    {
        $isProfileShippingOptional = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::PROFILE_SHIPPING_OPTIONAL);
        $isSimplifiedSignup = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::SIMPLIFIED_SIGNUP);
        return UserSettingPureChecker::new()->isProfileShippingRequired($isProfileShippingOptional, $isSimplifiedSignup);
    }
}
