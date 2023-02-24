<?php
/**
 * SAM-5181: Implement JWT (Json Web Tokens) for authorization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct. 27, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Auth\Identity;


use Sam\Core\Service\CustomizableClass;
use Sam\User\Auth\Identity\Storage\AuthIdentityJwtCookieStorage;
use Sam\User\Auth\Identity\Storage\AuthIdentityStoragePoolAwareTrait;

/**
 * Class AuthIdentityJwtCookieRefresher
 * @package Sam\User\Auth\Identity
 */
class AuthIdentityJwtCookieRefresher extends CustomizableClass
{
    use AuthIdentityManagerCreateTrait;
    use AuthIdentityStoragePoolAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return bool
     */
    public function refresh(): bool
    {
        $authIdentityManager = $this->createAuthIdentityManager();
        if (!$authIdentityManager->isAuthorized()) {
            log_debug('Cannot refresh JWT auth-identity token, when user session is not authorized');
            return false;
        }

        $primaryStorage = $this->getAuthIdentityStoragePool()->primaryStorage();
        if (!$primaryStorage) {
            log_warning('Cannot refresh JWT auth-identity token, when primary auth-identity storage not available in pool');
            return false;
        }

        $authIdentityDto = $primaryStorage->readIdentity();
        AuthIdentityJwtCookieStorage::new()->refreshIdentityToken($authIdentityDto);
        return true;
    }
}
