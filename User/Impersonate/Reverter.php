<?php
/**
 * Revert impersonation service
 *
 * SAM-3559: Admin impersonate improvements
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

use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;
use Sam\User\Impersonate\Original\OriginalUserManagerCreateTrait;

/**
 * Class Reverter
 * @package Sam\User\Impersonate
 */
class Reverter extends CustomizableClass
{
    use AuthIdentityManagerCreateTrait;
    use EditorUserAwareTrait;
    use OriginalUserManagerCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Impersonate back to initial user
     * @return bool
     */
    public function revertOriginalUser(): bool
    {
        if (!$this->allowed()) {
            $this->logRejectedStateForRevert();
            return false;
        }
        $originalUser = $this->createOriginalUserManager()->loadUser();
        if (!$originalUser) {
            log_error("Available original user not found, when reverting impersonated user to original");
            return false;
        }
        $this->createAuthIdentityManager()->applyUser($originalUser->Id);
        $this->createOriginalUserManager()->unregister();
        return true;
    }

    /**
     * Check, if allowed revert to original user
     * @return bool
     */
    public function allowed(): bool
    {
        $allowed = $this->createOriginalUserManager()->exist();
        return $allowed;
    }

    /**
     * Return Username of original user
     * @return string
     */
    public function getOriginalUsername(): string
    {
        return $this->createOriginalUserManager()->getUsername();
    }

    /**
     * Log info, if revert to original user action is rejected (should be impossible situation)
     */
    protected function logRejectedStateForRevert(): void
    {
        $loggedUserId = $this->getEditorUserId() ?: '-';
        $userId = $this->createOriginalUserManager()->getUserId();
        $logInfo = composeSuffix(['Logged User Id' => $loggedUserId, 'Original User Id' => $userId]);
        log_error('Impersonate Revert forbidden' . $logInfo);
    }
}
