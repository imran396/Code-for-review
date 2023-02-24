<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/17/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Signup\AdditionalConfirmation\Load;

/**
 * Trait AdditionalSignupConfirmationLoaderAwareTrait
 * @package Sam\User\Signup\AdditionalConfirmation\Load
 */
trait AdditionalSignupConfirmationLoaderAwareTrait
{
    protected ?AdditionalSignupConfirmationLoader $additionalSignupConfirmationLoader = null;

    /**
     * @return AdditionalSignupConfirmationLoader
     */
    protected function getAdditionalSignupConfirmationLoader(): AdditionalSignupConfirmationLoader
    {
        if ($this->additionalSignupConfirmationLoader === null) {
            $this->additionalSignupConfirmationLoader = AdditionalSignupConfirmationLoader::new();
        }
        return $this->additionalSignupConfirmationLoader;
    }

    /**
     * @param AdditionalSignupConfirmationLoader $additionalSignupConfirmationLoader
     * @return static
     * @internal
     */
    public function setAdditionalSignupConfirmationLoader(AdditionalSignupConfirmationLoader $additionalSignupConfirmationLoader): static
    {
        $this->additionalSignupConfirmationLoader = $additionalSignupConfirmationLoader;
        return $this;
    }
}
