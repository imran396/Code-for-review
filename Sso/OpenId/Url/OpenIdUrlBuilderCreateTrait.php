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

/**
 * Trait OpenIdUrlBuilderCreateTrait
 * @package Sam\Sso\OpenId\Client
 */
trait OpenIdUrlBuilderCreateTrait
{
    protected ?OpenIdUrlBuilder $openIdUrlBuilder = null;

    /**
     * @return OpenIdUrlBuilder
     */
    protected function createOpenIdUrlBuilder(): OpenIdUrlBuilder
    {
        return $this->openIdUrlBuilder ?: OpenIdUrlBuilder::new();
    }

    /**
     * @param OpenIdUrlBuilder $openIdUrlBuilder
     * @return $this
     * @internal
     */
    public function setOpenIdUrlBuilder(OpenIdUrlBuilder $openIdUrlBuilder): static
    {
        $this->openIdUrlBuilder = $openIdUrlBuilder;
        return $this;
    }
}
