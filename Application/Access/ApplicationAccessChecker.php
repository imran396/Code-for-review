<?php
/**
 * SAM-5419: Access checkers for application features
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/25/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Access;

use Sam\Account\Validate\MultipleTenantAccountSimpleChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;

/**
 * Class ApplicationAccessChecker
 * @package
 */
class ApplicationAccessChecker extends CustomizableClass
{
    use AdminPrivilegeCheckerAwareTrait;
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isMainAccount(int $accountId): bool
    {
        return MultipleTenantAccountSimpleChecker::new()->isMainAccount(
            $accountId,
            $this->isMultipleTenant(),
            (int)$this->cfg()->get('core->portal->mainAccountId')
        );
    }

    public function isPortalAccount(int $accountId): bool
    {
        return MultipleTenantAccountSimpleChecker::new()->isPortalAccount(
            $accountId,
            $this->isMultipleTenant(),
            (int)$this->cfg()->get('core->portal->mainAccountId')
        );
    }

    public function isSingleTenant(): bool
    {
        return !$this->isMultipleTenant();
    }

    public function isMultipleTenant(): bool
    {
        return $this->cfg()->get('core->portal->enabled');
    }

    public function isMainAccountForMultipleTenant(int $accountId): bool
    {
        return $this->isMainAccount($accountId)
            && $this->isMultipleTenant();
    }

    public function isMainAccountForMultipleTenantOrSingleTenant(int $accountId): bool
    {
        return $this->isSingleTenant()
            || $this->isMainAccountForMultipleTenant($accountId);
    }

    public function isAdmin(?int $userId, bool $isReadOnlyDb = false): bool
    {
        return $this->getAdminPrivilegeChecker()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->initByUserId($userId)
            ->isAdmin();
    }

    public function isCrossDomainAdmin(?int $userId, bool $isReadOnlyDb = false): bool
    {
        return $this->getAdminPrivilegeChecker()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->initByUserId($userId)
            ->hasPrivilegeForSuperadmin();
    }

    public function isAdminForSingleTenant(?int $userId, bool $isReadOnlyDb = false): bool
    {
        return $this->isSingleTenant()
            && $this->isAdmin($userId, $isReadOnlyDb);
    }

    public function isAdminForMultipleTenant(?int $userId, bool $isReadOnlyDb = false): bool
    {
        return $this->isMultipleTenant()
            && $this->isAdmin($userId, $isReadOnlyDb);
    }

    public function isCrossDomainAdminForMultipleTenant(?int $userId, bool $isReadOnlyDb = false): bool
    {
        return $this->isMultipleTenant()
            && $this->isCrossDomainAdmin($userId, $isReadOnlyDb);
    }

    public function isCrossDomainAdminOnMainAccountForMultipleTenant(
        ?int $userId,
        int $accountId,
        bool $isReadOnlyDb = false
    ): bool {
        return $this->isMultipleTenant()
            && $this->isMainAccount($accountId)
            && $this->isCrossDomainAdmin($userId, $isReadOnlyDb);
    }

    public function isCrossDomainAdminOnPortalAccountForMultipleTenant(
        ?int $userId,
        int $accountId,
        bool $isReadOnlyDb = false
    ): bool {
        return $this->isMultipleTenant()
            && $this->isPortalAccount($accountId)
            && $this->isCrossDomainAdmin($userId, $isReadOnlyDb);
    }

    /**
     * Should be
     * - either cross-domain admin on multiple-tenant installation, who visits any domain (main or portal),
     * - or regular admin who visits single-tenant installation.
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isCrossDomainAdminForMultipleTenantOrAdminForSingleTenant(
        ?int $userId,
        bool $isReadOnlyDb = false
    ): bool {
        return $this->isAdminForSingleTenant($userId, $isReadOnlyDb)
            || $this->isCrossDomainAdminForMultipleTenant($userId, $isReadOnlyDb);
    }

    /**
     * Should be
     * - either cross-domain admin who visits domain of main account on multiple-tenant installation,
     * - or regular admin who visits single-tenant installation.
     * @param int|null $userId
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isCrossDomainAdminOnMainAccountForMultipleTenantOrAdminForSingleTenant(
        ?int $userId,
        int $accountId,
        bool $isReadOnlyDb = false
    ): bool {
        return $this->isAdminForSingleTenant($userId, $isReadOnlyDb)
            || $this->isCrossDomainAdminOnMainAccountForMultipleTenant($userId, $accountId, $isReadOnlyDb);
    }
}
