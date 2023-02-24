<?php
/**
 * SAM-4452: Apply Auction Bidder Deleter
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/17/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Delete;

/**
 * Trait AuctionBidderDeleterAwareTrait
 * @package Sam\Bidder\AuctionBidder\Delete
 */
trait AuctionBidderDeleterCreateTrait
{
    protected ?AuctionBidderDeleter $auctionBidderDeleter = null;

    /**
     * @return AuctionBidderDeleter
     */
    protected function createAuctionBidderDeleter(): AuctionBidderDeleter
    {
        return $this->auctionBidderDeleter ?: AuctionBidderDeleter::new();
    }

    /**
     * @param AuctionBidderDeleter $auctionBidderDeleter
     * @return static
     * @internal
     */
    public function setAuctionBidderDeleter(AuctionBidderDeleter $auctionBidderDeleter): static
    {
        $this->auctionBidderDeleter = $auctionBidderDeleter;
        return $this;
    }
}
