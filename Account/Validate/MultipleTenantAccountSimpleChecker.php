<?php
/**
 * SAM-6657: Extract main/portal account detection logic to checker
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Validate;

use Sam\Core\Service\CustomizableClass;

/**
 * Class MultipleTenantAccountSimpleChecker
 * @package Sam\Account\Validate
 */
class MultipleTenantAccountSimpleChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $accountId
     * @param bool $isMultipleTenant
     * @param int $mainAccountId
     * @return bool
     */
    public function isPortalAccount(?int $accountId, bool $isMultipleTenant, int $mainAccountId): bool
    {
        return $isMultipleTenant
            && $accountId !== $mainAccountId;
    }

    /**
     * Check, if currently we are visiting main account.
     * It is always true for Single Tenant installation.
     * @param int|null $accountId
     * @param bool $isMultipleTenant
     * @param int $mainAccountId
     * @return bool
     */
    public function isMainAccount(?int $accountId, bool $isMultipleTenant, int $mainAccountId): bool
    {
        return !$isMultipleTenant
            || $accountId === $mainAccountId;
    }

    /**
     * Check, if currently we are visiting main account in SAM Portal installation
     * @param int|null $accountId checking account
     * @param bool $isMultipleTenant
     * @param int $mainAccountId
     * @return bool
     */
    public function isMainAccountForMultipleTenant(?int $accountId, bool $isMultipleTenant, int $mainAccountId): bool
    {
        $is = $isMultipleTenant
            && $accountId === $mainAccountId;
        return $is;
    }
}
