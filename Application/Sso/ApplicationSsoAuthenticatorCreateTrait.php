<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Sso;

/**
 * Trait ApplicationSsoAuthenticatorCreateTrait
 * @package Sam\Application\Sso
 */
trait ApplicationSsoAuthenticatorCreateTrait
{
    protected ?ApplicationSsoAuthenticator $applicationSsoAuthenticator = null;

    /**
     * @return ApplicationSsoAuthenticator
     */
    protected function createApplicationSsoAuthenticator(): ApplicationSsoAuthenticator
    {
        return $this->applicationSsoAuthenticator ?: ApplicationSsoAuthenticator::new();
    }

    /**
     * @param ApplicationSsoAuthenticator $applicationSsoAuthenticator
     * @return $this
     * @internal
     */
    public function setApplicationSsoAuthenticator(ApplicationSsoAuthenticator $applicationSsoAuthenticator): static
    {
        $this->applicationSsoAuthenticator = $applicationSsoAuthenticator;
        return $this;
    }
}
