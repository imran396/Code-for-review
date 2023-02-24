<?php
/**
 * SAM-5171: Application layer
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/3/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Index\Base\Concrete;

use Sam\Core\Service\CustomizableClass;
use Sam\Application\Cookie\CookieHelperCreateTrait;
use Sam\Core\Constants;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class ApplicationSessionInitializer
 * @package
 */
class AuthIdentityInitializer extends CustomizableClass
{
    use AuthIdentityManagerCreateTrait;
    use CookieHelperCreateTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function initialize(): void
    {
        $isAuthCookie = $this->createCookieHelper()->isAuthenticated();
        $authIdentityManager = $this->createAuthIdentityManager();
        // should check first
        if ($isAuthCookie) {
            $editorUserId = $authIdentityManager->getUserId();
            if ($editorUserId) {
                $logData = ['u' => $editorUserId];
                $editorUser = $this->getUserLoader()->load($editorUserId);
                if (!$editorUser) {
                    $authIdentityManager->clearSessionData();
                    log_info('Auth identity not initialized, because available user not found' . composeSuffix($logData));
                    return;
                }

                if ($editorUser->Flag === Constants\User::FLAG_BLOCK) {
                    // Purge blocked user
                    $authIdentityManager->clearSessionData();
                    log_info('Auth identity not initialized, because user is blocked' . composeSuffix($logData));
                    return;
                }

                $authIdentityManager->applyUser($editorUserId);
            }
        }
    }
}
