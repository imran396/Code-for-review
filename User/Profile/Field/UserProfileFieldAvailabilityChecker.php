<?php
/**
 * SAM-9383: Sign Up: "Simplified signup page" condition uncheck, error occurs
 * SAM-9388: ACH Payment option for different payment gateways
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Profile\Field;

use Sam\Billing\Gate\Availability\BillingGateAvailabilityCheckerCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\User\UserSettingCheckerCreateTrait;

/**
 * Class UserProfileFieldAvailabilityChecker
 * @package Sam\User\Profile\Field
 */
class UserProfileFieldAvailabilityChecker extends CustomizableClass
{
    use BillingGateAvailabilityCheckerCreateTrait;
    use UserSettingCheckerCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check, if Billing Bank Account fields are available for editing at responsive site of Sign Up, Profile pages.
     * @param int $accountId
     * @return bool
     */
    public function isBillingBankInfoAvailableForResponsive(int $accountId): bool
    {
        $userSettingChecker = $this->createUserSettingChecker();
        $isSimplifiedSignup = $userSettingChecker->isSimplifiedSignup();
        if ($isSimplifiedSignup) {
            return $userSettingChecker->isIncludeAchInfo();
        }
        return $this->createBillingGateAvailabilityChecker()->isAchPaymentEnabled($accountId);
    }

    /**
     * Check, if Billing Bank Account fields are available for editing at admin site of User Edit page.
     * @param int $accountId
     * @return bool
     */
    public function isBillingBankInfoAvailableForAdmin(int $accountId): bool
    {
        return $this->createBillingGateAvailabilityChecker()->isAchPaymentEnabled($accountId);
    }
}
