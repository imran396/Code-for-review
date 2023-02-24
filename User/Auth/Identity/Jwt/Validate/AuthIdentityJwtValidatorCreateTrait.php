<?php
/**
 * SAM-5181: Implement JWT (Json Web Tokens) for authorization
 * SAM-10709: Implement the Bearer authorization method for GraphQL endpoint
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\User\Auth\Identity\Jwt\Validate;

/**
 * Trait AuthIdentityJwtValidatorCreateTrait
 * @package Sam\User\Auth\Identity\Jwt\Validate
 */
trait AuthIdentityJwtValidatorCreateTrait
{
    protected ?AuthIdentityJwtValidator $authIdentityJwtValidator = null;

    /**
     * @return AuthIdentityJwtValidator
     */
    protected function createAuthIdentityJwtValidator(): AuthIdentityJwtValidator
    {
        return $this->authIdentityJwtValidator ?: AuthIdentityJwtValidator::new();
    }

    /**
     * @param AuthIdentityJwtValidator $authIdentityJwtValidator
     * @return static
     * @internal
     */
    public function setAuthIdentityJwtValidator(AuthIdentityJwtValidator $authIdentityJwtValidator): static
    {
        $this->authIdentityJwtValidator = $authIdentityJwtValidator;
        return $this;
    }
}
