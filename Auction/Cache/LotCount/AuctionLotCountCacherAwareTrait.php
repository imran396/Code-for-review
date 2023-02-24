<?php
/**
 * Aware-trait for singleton dependency
 *
 * SAM-6042: Extract lot count updating logic for auction cache to separate class
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Cache\LotCount;

/**
 * Trait AuctionLotCountCacherAwareTrait
 * @package Sam\Auction\Cache
 */
trait AuctionLotCountCacherAwareTrait
{
    protected ?AuctionLotCountCacher $auctionLotCountCacher = null;

    /**
     * @return AuctionLotCountCacher
     */
    protected function getAuctionLotCountCacher(): AuctionLotCountCacher
    {
        // This is getter for singleton dependency
        return $this->auctionLotCountCacher ?: AuctionLotCountCacher::getInstance();
    }

    /**
     * @param AuctionLotCountCacher $auctionLotCountCacher
     * @return $this
     * @internal
     */
    public function setAuctionLotCountCacher(AuctionLotCountCacher $auctionLotCountCacher): static
    {
        $this->auctionLotCountCacher = $auctionLotCountCacher;
        return $this;
    }
}
