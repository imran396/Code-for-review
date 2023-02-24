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
 * Trait UpcomingAuctionActivityQueryBuilderCreateTrait
 * @package Sam\Stat\UpcomingAuction\Load
 */
trait UpcomingAuctionActivityQueryBuilderCreateTrait
{
    protected ?UpcomingAuctionActivityQueryBuilder $upcomingAuctionActivityQueryBuilder = null;

    /**
     * @return UpcomingAuctionActivityQueryBuilder
     */
    protected function createUpcomingAuctionActivityQueryBuilder(): UpcomingAuctionActivityQueryBuilder
    {
        return $this->upcomingAuctionActivityQueryBuilder ?: UpcomingAuctionActivityQueryBuilder::new();
    }

    /**
     * @param UpcomingAuctionActivityQueryBuilder $upcomingAuctionActivityQueryBuilder
     * @return static
     * @internal
     */
    public function setUpcomingAuctionActivityQueryBuilder(UpcomingAuctionActivityQueryBuilder $upcomingAuctionActivityQueryBuilder): static
    {
        $this->upcomingAuctionActivityQueryBuilder = $upcomingAuctionActivityQueryBuilder;
        return $this;
    }
}
