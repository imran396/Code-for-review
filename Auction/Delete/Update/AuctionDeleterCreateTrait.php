<?php
/**
 * SAM-4039: Auction deleter class
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Delete\Update;

/**
 * Trait AuctionDeleterCreateTrait
 * @package Sam\Auction\Delete
 */
trait AuctionDeleterCreateTrait
{
    protected ?AuctionDeleter $auctionDeleter = null;

    /**
     * @return AuctionDeleter
     */
    protected function createAuctionDeleter(): AuctionDeleter
    {
        return $this->auctionDeleter ?: AuctionDeleter::new();
    }

    /**
     * @param AuctionDeleter $auctionDeleter
     * @return $this
     * @internal
     */
    public function setAuctionDeleter(AuctionDeleter $auctionDeleter): static
    {
        $this->auctionDeleter = $auctionDeleter;
        return $this;
    }
}
