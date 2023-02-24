<?php
/**
 * SAM
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

namespace Sam\User\Auth\Identity\Jwt\Parse;

/**
 * Trait AuthIdentityJwtParserCreateTrait
 * @package Sam\User\Auth\Identity\Jwt\Parse
 */
trait AuthIdentityJwtParserCreateTrait
{
    protected ?AuthIdentityJwtParser $authIdentityJwtParser = null;

    /**
     * @return AuthIdentityJwtParser
     */
    protected function createAuthIdentityJwtParser(): AuthIdentityJwtParser
    {
        return $this->authIdentityJwtParser ?: AuthIdentityJwtParser::new();
    }

    /**
     * @param AuthIdentityJwtParser $authIdentityJwtParser
     * @return static
     * @internal
     */
    public function setAuthIdentityJwtParser(AuthIdentityJwtParser $authIdentityJwtParser): static
    {
        $this->authIdentityJwtParser = $authIdentityJwtParser;
        return $this;
    }
}
