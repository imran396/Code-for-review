<?php
/**
 * SAM-7650 : Route and menu adjustments of Settings / System Parameters section
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Access\Management\Internal\Load;

use Sam\Account\Validate\AccountExistenceChecker;
use Sam\Application\Access\ApplicationAccessChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;

/**
 * Class DataProvider
 * @package Sam\Account\Access\Management\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function hasPrivilegeForManageSettings(?int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        return AdminPrivilegeChecker::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->initByUserId($editorUserId)
            ->hasPrivilegeForManageSettings();
    }

    public function isSingleTenant(): bool
    {
        return ApplicationAccessChecker::new()->isSingleTenant();
    }

    public function isPortalAccount(int $systemAccountId): bool
    {
        return ApplicationAccessChecker::new()->isPortalAccount($systemAccountId);
    }

    public function existAccountById(?int $accountId, bool $isReadOnlyDb = false): bool
    {
        return AccountExistenceChecker::new()->existById($accountId, $isReadOnlyDb);
    }
}
