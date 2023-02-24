<?php
/**
 * SAM-4039: Auction deleter class
 * SAM-6671: Auction deleter for v3.5
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

namespace Sam\CustomField\Auction\Delete;

/**
 * Trait AuctionCustomDataDeleterCreateTrait
 * @package Sam\CustomField\Auction\Delete
 */
trait AuctionCustomDataDeleterCreateTrait
{
    protected ?AuctionCustomDataDeleter $auctionCustomDataDeleter = null;

    /**
     * @return AuctionCustomDataDeleter
     */
    protected function createAuctionCustomDataDeleter(): AuctionCustomDataDeleter
    {
        return $this->auctionCustomDataDeleter ?: AuctionCustomDataDeleter::new();
    }

    /**
     * @param AuctionCustomDataDeleter $auctionCustomDataDeleter
     * @return $this
     * @internal
     */
    public function setAuctionCustomDataDeleter(AuctionCustomDataDeleter $auctionCustomDataDeleter): static
    {
        $this->auctionCustomDataDeleter = $auctionCustomDataDeleter;
        return $this;
    }
}
