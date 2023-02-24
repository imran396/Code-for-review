<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/11/2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Load;

/**
 * Trait BidPlacedUserLoaderAwareTrait
 * @package Sam\Bidder\AuctionBidder\Load
 */
trait BidPlacedUserLoaderAwareTrait
{
    /**
     * @var BidPlacedUserLoader|null
     */
    protected ?BidPlacedUserLoader $bidPlacedUserLoader = null;

    /**
     * @return BidPlacedUserLoader
     */
    protected function getBidPlacedUserLoader(): BidPlacedUserLoader
    {
        if ($this->bidPlacedUserLoader === null) {
            $this->bidPlacedUserLoader = BidPlacedUserLoader::new();
        }
        return $this->bidPlacedUserLoader;
    }

    /**
     * @param BidPlacedUserLoader $bidPlacedUserLoader
     * @return static
     * @internal
     */
    public function setBidPlacedUserLoader(BidPlacedUserLoader $bidPlacedUserLoader): static
    {
        $this->bidPlacedUserLoader = $bidPlacedUserLoader;
        return $this;
    }
}
