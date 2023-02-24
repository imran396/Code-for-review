<?php
/**
 * Trait for BidIncrementLoader
 *
 * SAM-4474: Modules for Bid Increments
 *
 * @author        Victor Pautoff
 * @since         Oct 15, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidding\BidIncrement\Load;

/**
 * Trait BidIncrementLoaderAwareTrait
 * @package Sam\Bidding\BidIncrement\Load
 */
trait BidIncrementLoaderAwareTrait
{
    /**
     * @var BidIncrementLoader|null
     */
    protected ?BidIncrementLoader $bidIncrementLoader = null;

    /**
     * @return BidIncrementLoader
     */
    protected function getBidIncrementLoader(): BidIncrementLoader
    {
        if ($this->bidIncrementLoader === null) {
            $this->bidIncrementLoader = BidIncrementLoader::new();
        }
        return $this->bidIncrementLoader;
    }

    /**
     * @param BidIncrementLoader $bidIncrementLoader
     * @return static
     * @internal
     */
    public function setBidIncrementLoader(BidIncrementLoader $bidIncrementLoader): static
    {
        $this->bidIncrementLoader = $bidIncrementLoader;
        return $this;
    }
}
