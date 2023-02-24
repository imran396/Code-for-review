<?php
/**
 * SAM-5620: Refactoring and unit tests for Actual Current Bid Detector module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 12, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\CurrentBid\Actual\Load;

/**
 * Trait ActualCurrentBidLoaderCreateTrait
 * @package Sam\Bidding\CurrentBid\Actual\Load
 */
trait ActualCurrentBidLoaderCreateTrait
{
    protected ?ActualCurrentBidLoader $actualCurrentBidLoader = null;

    /**
     * @return ActualCurrentBidLoader
     */
    protected function createActualCurrentBidLoader(): ActualCurrentBidLoader
    {
        return $this->actualCurrentBidLoader ?: ActualCurrentBidLoader::new();
    }

    /**
     * @param ActualCurrentBidLoader $actualCurrentBidLoader
     * @return $this
     * @internal
     */
    public function setActualCurrentBidLoader(ActualCurrentBidLoader $actualCurrentBidLoader): static
    {
        $this->actualCurrentBidLoader = $actualCurrentBidLoader;
        return $this;
    }
}
