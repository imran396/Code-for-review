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

namespace Sam\Sso\OpenId\Authenticate;

trait OpenIdAuthenticatorCreateTrait
{
    protected ?OpenIdAuthenticator $openIdAuthenticator = null;

    /**
     * @return OpenIdAuthenticator
     */
    protected function createOpenIdAuthenticator(): OpenIdAuthenticator
    {
        return $this->openIdAuthenticator ?: OpenIdAuthenticator::new();
    }

    /**
     * @param OpenIdAuthenticator $openIdAuthenticator
     * @return $this
     * @internal
     */
    public function setOpenIdAuthenticator(OpenIdAuthenticator $openIdAuthenticator): static
    {
        $this->openIdAuthenticator = $openIdAuthenticator;
        return $this;
    }
}
