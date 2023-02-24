<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           10/12/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Auth;

/**
 * Trait LoginServiceAwareTrait
 * @package Sam\User\Auth
 */
trait LoginServiceAwareTrait
{
    protected ?LoginService $loginService = null;

    /**
     * @return LoginService
     */
    protected function getLoginService(): LoginService
    {
        if ($this->loginService === null) {
            $this->loginService = LoginService::new();
        }
        return $this->loginService;
    }

    /**
     * @param LoginService $loginService
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLoginService(LoginService $loginService): static
    {
        $this->loginService = $loginService;
        return $this;
    }
}
