<?php
/**
 * "New Account on Signup" feature checking logic
 *
 * SAM-7957: Unit tests for "new account on signup" feature
 * SAM-3655: Front end create "account" on signup restrictions
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Signup\NewAccount\Feature;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;

/**
 * Class SignupNewAccountFeatureAvailabilityChecker
 * @package Sam\User\Signup\NewAccount\Feature
 */
class NewAccountOnSignupFeatureAvailabilityChecker extends CustomizableClass
{
    use OptionalsTrait;

    // --- Incoming values ---

    public const OP_IS_MULTIPLE_TENANT = OptionalKeyConstants::KEY_IS_MULTIPLE_TENANT; // bool
    public const OP_IS_ACCOUNT_ADMIN_SIGNUP = 'isAccountAdminSignup'; // bool
    public const OP_EXPECTED_PARAM_VALUE = 'expectedParamValue'; // string
    public const OP_EXPECTED_PARAM_NAME = 'expectedParamName'; // string

    // --- Outgoing values ---

    protected const PARAM_NAME_DEF = 'type';
    protected const PARAM_VALUE_DEF = 'account_admin';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Check, if new account registration on signup feature is available.
     * @return bool
     */
    public function isFeatureAvailable(): bool
    {
        $isMultipleTenant = (bool)$this->fetchOptional(self::OP_IS_MULTIPLE_TENANT);
        $isAccountAdminSignup = (bool)$this->fetchOptional(self::OP_IS_ACCOUNT_ADMIN_SIGNUP);
        return $isMultipleTenant
            && $isAccountAdminSignup;
    }

    /**
     * Check, if new account registration on signup is required, according to value of specific parameter in request.
     * @param string $paramValue
     * @return bool
     */
    public function isNewAccountRequested(string $paramValue): bool
    {
        return $paramValue === $this->paramValue();
    }

    /**
     * Check, if new account registration on signup is required by parameter in request, but the feature is disabled.
     * @param string $paramValue
     * @return bool
     */
    public function isNewAccountRequestedWhenFeatureDisabled(string $paramValue): bool
    {
        return $this->isNewAccountRequested($paramValue)
            && !$this->isFeatureAvailable();
    }

    /**
     * Check, if new account registration on signup is required by parameter in request and this feature is enabled.
     * @param string $paramValue
     * @return bool
     */
    public function isNewAccountRequestedWhenFeatureEnabled(string $paramValue): bool
    {
        return $this->isNewAccountRequested($paramValue)
            && $this->isFeatureAvailable();
    }

    /**
     * Return parameter name that starts new account registration action.
     * @return string
     */
    public function paramName(): string
    {
        return (string)$this->fetchOptional(self::OP_EXPECTED_PARAM_NAME);
    }

    /**
     * Return expected value for starting new account registration action.
     * @return string
     */
    public function paramValue(): string
    {
        return (string)$this->fetchOptional(self::OP_EXPECTED_PARAM_VALUE);
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IS_MULTIPLE_TENANT] = $optionals[self::OP_IS_MULTIPLE_TENANT]
            ?? static function (): bool {
                return (bool)cfg()->core->portal->enabled;
            };
        $optionals[self::OP_IS_ACCOUNT_ADMIN_SIGNUP] = $optionals[self::OP_IS_ACCOUNT_ADMIN_SIGNUP]
            ?? static function (): bool {
                return (bool)cfg()->core->portal->enableAccountAdminSignup;
            };
        $optionals[self::OP_EXPECTED_PARAM_NAME] = $optionals[self::OP_EXPECTED_PARAM_NAME] ?? self::PARAM_NAME_DEF;
        $optionals[self::OP_EXPECTED_PARAM_VALUE] = $optionals[self::OP_EXPECTED_PARAM_VALUE] ?? self::PARAM_VALUE_DEF;
        $this->setOptionals($optionals);
    }
}
