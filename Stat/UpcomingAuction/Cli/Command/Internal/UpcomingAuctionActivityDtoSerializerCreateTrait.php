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

namespace Sam\Stat\UpcomingAuction\Cli\Command\Internal;

/**
 * Trait UpcomingAuctionActivityDtoSerializerCreateTrait
 * @package Sam\Stat\UpcomingAuction\Cli\Command\Internal
 * @internal
 */
trait UpcomingAuctionActivityDtoSerializerCreateTrait
{
    protected ?UpcomingAuctionActivityDtoSerializer $upcomingAuctionActivityDtoSerializer = null;

    /**
     * @return UpcomingAuctionActivityDtoSerializer
     */
    protected function createUpcomingAuctionActivityDtoSerializer(): UpcomingAuctionActivityDtoSerializer
    {
        return $this->upcomingAuctionActivityDtoSerializer ?: UpcomingAuctionActivityDtoSerializer::new();
    }

    /**
     * @param UpcomingAuctionActivityDtoSerializer $upcomingAuctionActivityDtoSerializer
     * @return static
     * @internal
     */
    public function setUpcomingAuctionActivityDtoSerializer(UpcomingAuctionActivityDtoSerializer $upcomingAuctionActivityDtoSerializer): static
    {
        $this->upcomingAuctionActivityDtoSerializer = $upcomingAuctionActivityDtoSerializer;
        return $this;
    }
}
