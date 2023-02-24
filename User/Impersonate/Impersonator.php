<?php
/**
 * User impersonation service
 *
 * required to set before impersonation:
 * setTargetUser(\User) - to set result user
 *
 * SAM-3559: Admin impersonate improvements
 * fix: SAM-5083: Admin impersonate user problem
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           23 Dec, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Impersonate;

use Admin;
use Sam\AuditTrail\AuditTrailLoggerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;
use Sam\User\Impersonate\Original\OriginalUserManagerCreateTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;
use Sam\User\Privilege\Validate\RoleCheckerAwareTrait;
use User;

/**
 * Class Impersonator
 * @package Sam\User\Impersonate
 */
class Impersonator extends CustomizableClass
{
    use AdminPrivilegeCheckerAwareTrait;
    use AuditTrailLoggerAwareTrait;
    use AuthIdentityManagerCreateTrait;
    use EditorUserAwareTrait;

    // logged in user. it is impersonated user's context or originally authorized user before impersonating

    use OriginalUserManagerCreateTrait;
    use RoleCheckerAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Impersonated user
     */
    protected ?User $targetUser = null;
    protected ?Admin $targetAdmin = null;
    /**
     * User, that was initially impersonated
     */
    protected ?User $originalUser = null;
    protected ?Admin $originalAdmin = null;
    /** source user */
    protected bool $isAdminSourceUser = false;
    protected bool $hasPrivilegeForManageUsers = false;
    protected bool $hasPrivilegeForSuperadmin = false;
    protected bool $areSameAccounts = false;
    /** target user */
    protected bool $isAdminTargetUser = false;
    /**
     * Admin sub privileges
     */
    protected ?array $subPrivileges = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Set target user to impersonate
     * @param User|null $targetUser null - in case of new user
     * @return static
     */
    public function setTargetUser(User $targetUser = null): static
    {
        $this->targetUser = $targetUser;
        return $this;
    }

    /**
     * Impersonate current logged user to $this->targetUser
     * @return bool
     */
    public function impersonate(): bool
    {
        if (!$this->allowed()) {
            $this->logRejectedStateForImpersonate();
            return false;
        }

        $originalUserManager = $this->createOriginalUserManager();
        if (!$originalUserManager->exist()) {
            $user = $this->getEditorUser();
            if (!$user) {
                log_error("Available current user not found, when impersonating" . composeSuffix(['u' => $this->getEditorUserId()]));
                return false;
            }
            $originalUserManager->register($user);
        }
        $this->createAuthIdentityManager()->applyUser($this->getTargetUser()->Id);

        $this->logAuditTrail();

        return true;
    }

    /**
     * Check, if impersonation is allowed
     * @return bool
     */
    public function allowed(): bool
    {
        $sourceUser = $this->getSourceUser();
        $targetUser = $this->getTargetUser();

        // Source or target user unknown
        if (
            !$sourceUser
            || !$targetUser
        ) {
            return false;
        }

        // Cannot impersonate the same user
        if ($sourceUser->Id === $targetUser->Id) {
            return false;
        }

        // Cannot impersonate, when editor is not set (should be authorized initial user)
        if (!$this->getEditorUser()) {
            return false;
        }

        // Cannot impersonate to logged user
        if ($this->getEditorUserId() === $targetUser->Id) {
            return false;
        }

        $this->isAdminSourceUser = $this->getRoleChecker()->isAdmin($sourceUser->Id, true);

        // Only admin can impersonate
        if (!$this->isAdminSourceUser) {
            return false;
        }

        $this->isAdminTargetUser = $this->getRoleChecker()->isAdmin($targetUser->Id, true);

        $this->getAdminPrivilegeChecker()->initByAdmin($this->getSourceAdmin());
        $this->hasPrivilegeForManageUsers = $this->getAdminPrivilegeChecker()->hasPrivilegeForManageUsers();
        $this->hasPrivilegeForSuperadmin = $this->getAdminPrivilegeChecker()->hasPrivilegeForSuperadmin();

        // If target user isn't admin (he is bidder or consignor), we allow impersonate
        if (
            !$this->isAdminTargetUser
            && $this->hasPrivilegeForManageUsers
        ) {
            return true;
        }

        $this->areSameAccounts = $sourceUser->AccountId === $targetUser->AccountId;
        $allowed = false;
        if ($this->hasPrivilegeForManageUsers) {
            if ($this->areSameAccounts) {
                $allowed = true;
            } else {
                if ($this->hasPrivilegeForSuperadmin) {
                    $allowed = true;
                }
            }
        }
        if (
            $allowed
            && (
                !$this->checkPrivileges()
                || !$this->checkSubPrivileges()
            )
        ) {
            $allowed = false;
        }
        return $allowed;
    }

    /**
     * Get target user to impersonate
     * @return User|null
     */
    public function getTargetUser(): ?User
    {
        return $this->targetUser;
    }

    /**
     * Get Admin record of new user
     * @return Admin|null
     */
    protected function getTargetAdmin(): ?Admin
    {
        if ($this->targetAdmin === null) {
            $userId = $this->getTargetUser()?->Id;
            $this->targetAdmin = $this->getUserLoader()->loadAdmin($userId);
        }
        return $this->targetAdmin;
    }

    /**
     * Set Admin record of new user
     * @param Admin|null $targetAdmin null - in case of non-admin user, e.g. bidder
     * @return static
     */
    public function setTargetAdmin(Admin $targetAdmin = null): static
    {
        $this->targetAdmin = $targetAdmin;
        return $this;
    }

    /**
     * Set initial user
     * @param User $user
     * @return static
     * @noinspection PhpUnused
     */
    public function setOriginalUser(User $user): static
    {
        $this->originalUser = $user;
        return $this;
    }

    /**
     * Get initially impersonated user
     * @return User|null
     */
    public function getOriginalUser(): ?User
    {
        if ($this->originalUser === null) {
            $this->originalUser = $this->createOriginalUserManager()->loadUser();
        }
        return $this->originalUser;
    }

    /**
     * Set Admin info record of initially logged user
     * @param Admin $originalAdmin
     * @return static
     * @noinspection PhpUnused
     */
    public function setOriginalAdmin(Admin $originalAdmin): static
    {
        $this->originalAdmin = $originalAdmin;
        return $this;
    }

    /**
     * Get Admin info record of initially impersonated user
     * @return Admin|null
     */
    protected function getOriginalAdmin(): ?Admin
    {
        if ($this->originalAdmin === null) {
            $this->originalAdmin = $this->getUserLoader()->loadAdmin($this->getOriginalUser()->Id);
        }
        return $this->originalAdmin;
    }

    /**
     * @return array
     */
    public function getSubPrivileges(): array
    {
        if ($this->subPrivileges === null) {
            $this->subPrivileges = array_merge(
                Constants\AdminPrivilege::$manageAuctionSubPrivileges,
                Constants\AdminPrivilege::$manageUserSubPrivileges
            );
        }
        return $this->subPrivileges;
    }

    /**
     * Return the user, who is the first in the chain of impersonation (currently logged or initial)
     * We compare source user privileges with target user privileges
     * @return User|null
     */
    protected function getSourceUser(): ?User
    {
        if ($this->createOriginalUserManager()->exist()) {
            $sourceUser = $this->getOriginalUser();
        } else {
            $sourceUser = $this->getEditorUser();
        }
        return $sourceUser;
    }

    /**
     * @return Admin|null
     */
    protected function getSourceAdmin(): ?Admin
    {
        if ($this->createOriginalUserManager()->exist()) {
            $sourceAdmin = $this->getOriginalAdmin();
        } else {
            $sourceAdmin = $this->getEditorAdminOrCreate();
        }
        return $sourceAdmin;
    }

    /**
     * Validate, if current user's privileges are sufficient for new impersonated user privileges
     * @return bool
     */
    protected function checkPrivileges(): bool
    {
        $maxLength = strlen(decbin(Constants\AdminPrivilege::SUM));
        $sourceAdminPrivBin = decbin($this->getSourceAdmin()->AdminPrivileges);
        $sourceAdminPrivBin = str_pad($sourceAdminPrivBin, $maxLength, '0', STR_PAD_LEFT);
        $targetAdminPrivBin = decbin($this->getTargetAdmin()->AdminPrivileges);
        $targetAdminPrivBin = str_pad($targetAdminPrivBin, $maxLength, '0', STR_PAD_LEFT);
        // log_debug('targetAdminPrivBin: ' . $targetAdminPrivBin . ', sourceAdminPrivBin: ' . $sourceAdminPrivBin);
        $sufficientPrivileges = true;
        for ($count = strlen($targetAdminPrivBin), $i = 0; $i < $count; $i++) {
            $hasPrivForTarget = $targetAdminPrivBin[$i];
            $hasPrivForSource = isset($sourceAdminPrivBin[$i]) && $sourceAdminPrivBin[$i];
            if (
                $hasPrivForTarget        // if target user has privilege
                && !$hasPrivForSource    // but source user doesn't have it
            ) {
                $sufficientPrivileges = false;     // then we can't impersonate new user
                break;
            }
        }
        return $sufficientPrivileges;
    }

    /**
     * Validate, if current user's sub-privileges are enough for new impersonated user privileges
     * @return bool
     */
    protected function checkSubPrivileges(): bool
    {
        $sourceAdmin = $this->getSourceAdmin();
        $targetAdmin = $this->getTargetAdmin();
        $sufficientPrivileges = true;
        foreach ($this->getSubPrivileges() as $privilege) {
            if (
                $targetAdmin
                && $targetAdmin->$privilege
                && (
                    !$sourceAdmin
                    || !$sourceAdmin->$privilege
                )
            ) {
                $sufficientPrivileges = false;
                break;
            }
        }
        return $sufficientPrivileges;
    }

    /**
     * Log to audit trail success message about impersonating from source to target user
     */
    protected function logAuditTrail(): void
    {
        $section = 'user/impersonate';
        $sourceUser = $this->getSourceUser();
        if (!$sourceUser) {
            log_error("Available source user not found, when log to audit trail");
            return;
        }
        $targetUser = $this->getTargetUser();
        if (!$targetUser) {
            log_error("Available target user not found, when log to audit trail");
            return;
        }
        $event = "Source user"
            . composeSuffix(['u' => $sourceUser->Id, 'username' => $sourceUser->Username])
            . " successfully impersonated to target user"
            . composeSuffix(['u' => $targetUser->Id, 'username' => $targetUser->Username]);
        $this->getAuditTrailLogger()
            ->setAccountId($sourceUser->AccountId)
            ->setEditorUserId($sourceUser->Id)
            ->setEvent($event)
            ->setSection($section)
            ->setUserId($sourceUser->Id)
            ->log();
    }

    /**
     * Log info, if impersonate action is rejected (should be impossible situation. we don't show button)
     */
    protected function logRejectedStateForImpersonate(): void
    {
        $sourceUserPrivBin = decbin($this->getSourceAdmin()->AdminPrivileges);
        $targetUserPrivBin = decbin($this->getTargetAdmin()->AdminPrivileges);
        $sourceAdmin = $this->getSourceAdmin();
        $targetAdmin = $this->getTargetAdmin();
        $sourceUserSubPrivileges = $targetUserSubPrivileges = '';
        foreach ($this->getSubPrivileges() as $privilege) {
            $sourceUserSubPrivileges .= $sourceAdmin->$privilege ?? 'x';
            $targetUserSubPrivileges .= $targetAdmin->$privilege ?? 'x';
        }
        $logInfo = composeSuffix(
            [
                'Has "Manage User" privilege' => (int)$this->hasPrivilegeForManageUsers,
                'Is superadmin' => (int)$this->hasPrivilegeForSuperadmin,
                'Same account users' => (int)$this->areSameAccounts,
                'Source user priv' => $sourceUserPrivBin . '-' . $sourceUserSubPrivileges,
                'Destination user priv' => $targetUserPrivBin . '-' . $targetUserSubPrivileges,
            ]
        );
        log_error('Impersonation forbidden' . $logInfo);
    }
}
