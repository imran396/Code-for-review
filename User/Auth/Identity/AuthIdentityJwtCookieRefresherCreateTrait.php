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


/**
 * Trait AuthIdentityJwtCookieRefresherCreateTrait
 * @package Sam\User\Auth\Identity\Storage
 */
trait AuthIdentityJwtCookieRefresherCreateTrait
{
    protected ?AuthIdentityJwtCookieRefresher $authIdentityJwtCookieRefresher = null;

    /**
     * @return AuthIdentityJwtCookieRefresher
     */
    protected function createAuthIdentityJwtCookieRefresher(): AuthIdentityJwtCookieRefresher
    {
        return $this->authIdentityJwtCookieRefresher ?: AuthIdentityJwtCookieRefresher::new();
    }

    /**
     * @param AuthIdentityJwtCookieRefresher $authIdentityJwtCookieRefresher
     * @return static
     * @internal
     */
    public function setAuthIdentityJwtCookieRefresher(AuthIdentityJwtCookieRefresher $authIdentityJwtCookieRefresher): static
    {
        $this->authIdentityJwtCookieRefresher = $authIdentityJwtCookieRefresher;
        return $this;
    }
}
