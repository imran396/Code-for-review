<?php
/**
 * SAM-10724: Login through SSO
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 13, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\OpenId\Authenticate\Internal\Validate;

trait AuthValidatorCreateTrait
{
    protected ?AuthValidator $authValidator = null;

    /**
     * @return AuthValidator
     */
    protected function createAuthValidator(): AuthValidator
    {
        return $this->authValidator ?: AuthValidator::new();
    }

    /**
     * @param AuthValidator $authValidator
     * @return $this
     * @internal
     */
    public function setAuthValidator(AuthValidator $authValidator): static
    {
        $this->authValidator = $authValidator;
        return $this;
    }
}
