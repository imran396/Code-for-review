<?php
/**
 * SAM-10724: Login through SSO
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Georgi Nikolov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\OpenId\TokenValidity;

trait OpenIdTokenValidityCreateTrait
{
    protected ?OpenIdTokenValidity $openIdTokenValidity = null;

    /**
     * @return OpenIdTokenValidity
     */
    protected function createOpenIdTokenValidity(): OpenIdTokenValidity
    {
        return $this->openIdTokenValidity ?: OpenIdTokenValidity::new();
    }

    /**
     * @param OpenIdTokenValidity $openIdTokenValidity
     * @return $this
     * @internal
     */
    public function setOpenIdTokenValidity(OpenIdTokenValidity $openIdTokenValidity): static
    {
        $this->openIdTokenValidity = $openIdTokenValidity;
        return $this;
    }
}
