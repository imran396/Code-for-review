<?php
/**
 * SAM-10615: Supply uniqueness of auction fields: sale#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 09, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Lock;

/**
 * Trait AuctionMakerLockerCreateTrait
 * @package Sam\EntityMaker\Auction\Lock
 */
trait AuctionMakerLockerCreateTrait
{
    protected ?AuctionMakerLocker $auctionMakerLocker = null;

    /**
     * @return AuctionMakerLocker
     */
    protected function createAuctionMakerLocker(): AuctionMakerLocker
    {
        return $this->auctionMakerLocker ?: AuctionMakerLocker::new();
    }

    /**
     * @param AuctionMakerLocker $auctionMakerLocker
     * @return static
     * @internal
     */
    public function setAuctionMakerLocker(AuctionMakerLocker $auctionMakerLocker): static
    {
        $this->auctionMakerLocker = $auctionMakerLocker;
        return $this;
    }
}
