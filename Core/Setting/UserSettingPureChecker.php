<?php
/**
 * Availability checks for "Simplified signup page" related options.
 * They follow the rule of hierarchical options defined in spec: "20201110-hierarchical-options.md"
 *
 * SAM-6895: Apply hierarchical options rules for "Simplified Signup"
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Setting;

use Sam\Core\Service\CustomizableClass;

/**
 * Class UserSettingChecker
 * @package Sam\Settings\User
 */
class UserSettingPureChecker extends CustomizableClass
{
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
     * @param bool $isSimplifiedSignup
     * @return bool
     */
    public function isSimplifiedSignup(bool $isSimplifiedSignup): bool
    {
        return $isSimplifiedSignup;
    }

    /**
     * @param bool $isIncludeBasicInfo
     * @param bool $isSimplifiedSignup
     * @return bool
     */
    public function isIncludeBasicInfo(bool $isIncludeBasicInfo, bool $isSimplifiedSignup): bool
    {
        return $this->isSimplifiedSignup($isSimplifiedSignup)
            && $isIncludeBasicInfo;
    }

    /**
     * @param bool $isMandatoryBasicInfo
     * @param bool $isIncludeBasicInfo
     * @param bool $isSimplifiedSignup
     * @return bool
     */
    public function isMandatoryBasicInfo(
        bool $isMandatoryBasicInfo,
        bool $isIncludeBasicInfo,
        bool $isSimplifiedSignup
    ): bool {
        return $this->isIncludeBasicInfo($isIncludeBasicInfo, $isSimplifiedSignup)
            && $isMandatoryBasicInfo;
    }

    /**
     * @param bool $isIncludeBillingInfo
     * @param bool $isSimplifiedSignup
     * @return bool
     */
    public function isIncludeBillingInfo(bool $isIncludeBillingInfo, bool $isSimplifiedSignup): bool
    {
        return $this->isSimplifiedSignup($isSimplifiedSignup)
            && $isIncludeBillingInfo;
    }

    /**
     * @param bool $isMandatoryBillingInfo
     * @param bool $isIncludeBillingInfo
     * @param bool $isSimplifiedSignup
     * @return bool
     */
    public function isMandatoryBillingInfo(
        bool $isMandatoryBillingInfo,
        bool $isIncludeBillingInfo,
        bool $isSimplifiedSignup
    ): bool {
        return $this->isIncludeBillingInfo($isIncludeBillingInfo, $isSimplifiedSignup)
            && $isMandatoryBillingInfo;
    }

    /**
     * @param bool $isIncludeAchInfo
     * @param bool $isSimplifiedSignup
     * @return bool
     */
    public function isIncludeAchInfo(bool $isIncludeAchInfo, bool $isSimplifiedSignup): bool
    {
        return $this->isSimplifiedSignup($isSimplifiedSignup)
            && $isIncludeAchInfo;
    }

    /**
     * @param bool $isMandatoryAchInfo
     * @param bool $isIncludeAchInfo
     * @param bool $isSimplifiedSignup
     * @return bool
     */
    public function isMandatoryAchInfo(
        bool $isMandatoryAchInfo,
        bool $isIncludeAchInfo,
        bool $isSimplifiedSignup
    ): bool {
        return $this->isIncludeAchInfo($isIncludeAchInfo, $isSimplifiedSignup)
            && $isMandatoryAchInfo;
    }

    /**
     * @param bool $isIncludeCcInfo
     * @param bool $isSimplifiedSignup
     * @param bool $isRequireCcOnSignup
     * @return bool
     */
    public function isIncludeCcInfo(
        bool $isIncludeCcInfo,
        bool $isSimplifiedSignup,
        bool $isRequireCcOnSignup
    ): bool {
        return $this->isSimplifiedSignup($isSimplifiedSignup)
            && (
                $isRequireCcOnSignup
                || $isIncludeCcInfo
            );
    }

    /**
     * @param bool $isMandatoryCcInfo
     * @param bool $isIncludeCcInfo
     * @param bool $isSimplifiedSignup
     * @param bool $isRequireCcOnSignup
     * @return bool
     */
    public function isMandatoryCcInfo(
        bool $isMandatoryCcInfo,
        bool $isIncludeCcInfo,
        bool $isSimplifiedSignup,
        bool $isRequireCcOnSignup
    ): bool {
        return $this->isIncludeCcInfo($isIncludeCcInfo, $isSimplifiedSignup, $isRequireCcOnSignup)
            && (
                $isRequireCcOnSignup
                || $isMandatoryCcInfo
            );
    }

    /**
     * @param bool $isProfileBillingOptional
     * @param bool $isSimplifiedSignup
     * @return bool
     */
    public function isProfileBillingOptional(bool $isProfileBillingOptional, bool $isSimplifiedSignup): bool
    {
        return !$this->isSimplifiedSignup($isSimplifiedSignup)
            && $isProfileBillingOptional;
    }

    /**
     * @param bool $isProfileShippingOptional
     * @param bool $isSimplifiedSignup
     * @return bool
     */
    public function isProfileShippingOptional(bool $isProfileShippingOptional, bool $isSimplifiedSignup): bool
    {
        return !$this->isSimplifiedSignup($isSimplifiedSignup)
            && $isProfileShippingOptional;
    }

    /**
     * @param bool $isProfileShippingOptional
     * @param bool $isSimplifiedSignup
     * @return bool
     */
    public function isProfileShippingRequired(bool $isProfileShippingOptional, bool $isSimplifiedSignup): bool
    {
        return !$this->isSimplifiedSignup($isSimplifiedSignup)
            && !$isProfileShippingOptional;
    }
}
