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

namespace Sam\Bidder\AuctionBidder\AdditionalRegistrationOption\Load;

/**
 * Trait AdditionalRegistrationOptionLoaderAwareTrait
 * @package Sam\Bidder\AuctionBidder\AdditionalRegistrationOption\Load
 */
trait AdditionalRegistrationOptionLoaderAwareTrait
{
    protected ?AdditionalRegistrationOptionLoader $additionalRegistrationOptionLoader = null;

    /**
     * @return AdditionalRegistrationOptionLoader
     */
    protected function getAdditionalRegistrationOptionLoader(): AdditionalRegistrationOptionLoader
    {
        if ($this->additionalRegistrationOptionLoader === null) {
            $this->additionalRegistrationOptionLoader = AdditionalRegistrationOptionLoader::new();
        }
        return $this->additionalRegistrationOptionLoader;
    }

    /**
     * @param AdditionalRegistrationOptionLoader $additionalRegistrationOptionLoader
     * @return static
     * @internal
     */
    public function setAdditionalRegistrationOptionLoader(AdditionalRegistrationOptionLoader $additionalRegistrationOptionLoader): static
    {
        $this->additionalRegistrationOptionLoader = $additionalRegistrationOptionLoader;
        return $this;
    }
}
