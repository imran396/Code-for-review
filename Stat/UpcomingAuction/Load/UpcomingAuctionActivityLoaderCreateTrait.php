<?php
/**
 * SAM-7949: Predictive upcoming auction stats script
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Stat\UpcomingAuction\Load;

/**
 * Trait UpcomingAuctionActivityLoaderCreateTrait
 * @package Sam\Stat\UpcomingAuction\Load
 */
trait UpcomingAuctionActivityLoaderCreateTrait
{
    protected ?UpcomingAuctionActivityLoader $upcomingAuctionActivityLoader = null;

    /**
     * @return UpcomingAuctionActivityLoader
     */
    protected function createUpcomingAuctionActivityLoader(): UpcomingAuctionActivityLoader
    {
        return $this->upcomingAuctionActivityLoader ?: UpcomingAuctionActivityLoader::new();
    }

    /**
     * @param UpcomingAuctionActivityLoader $upcomingAuctionActivityLoader
     * @return static
     * @internal
     */
    public function setUpcomingAuctionActivityLoader(UpcomingAuctionActivityLoader $upcomingAuctionActivityLoader): static
    {
        $this->upcomingAuctionActivityLoader = $upcomingAuctionActivityLoader;
        return $this;
    }
}
