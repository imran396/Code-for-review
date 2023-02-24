<?php
/**
 * SAM-10584: SAM SSO
 * SAM-10725: Add user through SSO
 *
 * Project        sam
 * @author        Georgi Nikolov
 * @version       SVN: $Id: $
 * @since         Jun 15, 2022
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Sso\OpenId\Signup;

trait OpenIdUserProducerCreateTrait
{
    protected ?OpenIdUserProducer $openIdUserProducer = null;

    /**
     * @return OpenIdUserProducer
     */
    protected function createOpenIdUserProducer(): OpenIdUserProducer
    {
        return $this->openIdUserProducer ?: OpenIdUserProducer::new();
    }

    /**
     * @param OpenIdUserProducer $openIdUserProducer
     * @return $this
     * @internal
     */
    public function setOpenIdUserProducer(OpenIdUserProducer $openIdUserProducer): static
    {
        $this->openIdUserProducer = $openIdUserProducer;
        return $this;
    }
}
