<?php
/**
 * SAM-10584: SAM SSO
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

namespace Sam\Sso\OpenId\Url;

trait OpenIdUrlProviderCreateTrait
{
    protected ?OpenIdUrlProvider $openIdUrlProvider = null;

    /**
     * @return OpenIdUrlProvider
     */
    protected function createOpenIdUrlProvider(): OpenIdUrlProvider
    {
        return $this->openIdUrlProvider ?: OpenIdUrlProvider::new();
    }

    /**
     * @param OpenIdUrlProvider $openIdUrlProvider
     * @return $this
     * @internal
     */
    public function setOpenIdUrlProvider(OpenIdUrlProvider $openIdUrlProvider): static
    {
        $this->openIdUrlProvider = $openIdUrlProvider;
        return $this;
    }
}
