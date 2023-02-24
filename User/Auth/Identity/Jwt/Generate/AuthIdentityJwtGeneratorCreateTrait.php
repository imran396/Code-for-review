<?php
/**
 * SAM-5181: Implement JWT (Json Web Tokens) for authorization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct. 26, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Auth\Identity\Jwt\Generate;

/**
 * Trait AuthIdentityJwtGeneratorCreateTrait
 * @package Sam\User\Auth\Identity\Jwt\Generate
 */
trait AuthIdentityJwtGeneratorCreateTrait
{
    protected ?AuthIdentityJwtGenerator $authIdentityJwtGenerator = null;

    /**
     * @return AuthIdentityJwtGenerator
     */
    protected function createAuthIdentityJwtGenerator(): AuthIdentityJwtGenerator
    {
        return $this->authIdentityJwtGenerator ?: AuthIdentityJwtGenerator::new();
    }

    /**
     * @param AuthIdentityJwtGenerator $authIdentityJwtGenerator
     * @return static
     * @internal
     */
    public function setAuthIdentityJwtGenerator(AuthIdentityJwtGenerator $authIdentityJwtGenerator): static
    {
        $this->authIdentityJwtGenerator = $authIdentityJwtGenerator;
        return $this;
    }
}
