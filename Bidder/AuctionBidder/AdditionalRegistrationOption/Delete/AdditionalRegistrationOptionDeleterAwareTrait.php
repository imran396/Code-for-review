<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/11/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\AdditionalRegistrationOption\Delete;

/**
 * Trait AdditionalRegistrationOptionDeleterAwareTrait
 * @package Sam\Bidder\AuctionBidder\AdditionalRegistrationOption\Delete
 */
trait AdditionalRegistrationOptionDeleterAwareTrait
{
    protected ?AdditionalRegistrationOptionDeleter $additionalRegistrationOptionDeleter = null;

    /**
     * @return AdditionalRegistrationOptionDeleter
     */
    protected function getAdditionalRegistrationOptionDeleter(): AdditionalRegistrationOptionDeleter
    {
        if ($this->additionalRegistrationOptionDeleter === null) {
            $this->additionalRegistrationOptionDeleter = AdditionalRegistrationOptionDeleter::new();
        }
        return $this->additionalRegistrationOptionDeleter;
    }

    /**
     * @param AdditionalRegistrationOptionDeleter $additionalRegistrationOptionDeleter
     * @return static
     * @internal
     */
    public function setAdditionalRegistrationOptionDeleter(AdditionalRegistrationOptionDeleter $additionalRegistrationOptionDeleter): static
    {
        $this->additionalRegistrationOptionDeleter = $additionalRegistrationOptionDeleter;
        return $this;
    }
}
