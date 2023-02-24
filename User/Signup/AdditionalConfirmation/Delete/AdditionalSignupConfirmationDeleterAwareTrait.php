<?php
/**
 * SAM-4727 : Additional Signup Confirmation Editor and Deleter
 * https://bidpath.atlassian.net/browse/SAM-4727
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/9/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Signup\AdditionalConfirmation\Delete;

/**
 * Trait AdditionalSignupConfirmationDeleterAwareTrait
 * @package Sam\User\Signup\AdditionalConfirmation\Delete
 */
trait AdditionalSignupConfirmationDeleterAwareTrait
{
    protected ?AdditionalSignupConfirmationDeleter $additionalSignupConfirmationDeleter = null;

    /**
     * @return AdditionalSignupConfirmationDeleter
     */
    protected function getAdditionalSignupConfirmationDeleter(): AdditionalSignupConfirmationDeleter
    {
        if ($this->additionalSignupConfirmationDeleter === null) {
            $this->additionalSignupConfirmationDeleter = AdditionalSignupConfirmationDeleter::new();
        }
        return $this->additionalSignupConfirmationDeleter;
    }

    /**
     * @param AdditionalSignupConfirmationDeleter $additionalSignupConfirmationDeleter
     * @return static
     * @internal
     */
    public function setAdditionalSignupConfirmationDeleter(AdditionalSignupConfirmationDeleter $additionalSignupConfirmationDeleter): static
    {
        $this->additionalSignupConfirmationDeleter = $additionalSignupConfirmationDeleter;
        return $this;
    }
}
