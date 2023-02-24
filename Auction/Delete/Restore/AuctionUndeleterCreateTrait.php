<?php
/**
 * SAM-6856: Soft-deleted Auction restore
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Delete\Restore;

/**
 * Trait AuctionUndeleterCreateTrait
 * @package Sam\Auction\Delete\Restore
 */
trait AuctionUndeleterCreateTrait
{
    protected ?AuctionUndeleter $auctionUndeleter = null;

    /**
     * @return AuctionUndeleter
     */
    protected function createAuctionUndeleter(): AuctionUndeleter
    {
        return $this->auctionUndeleter ?: AuctionUndeleter::new();
    }

    /**
     * @param AuctionUndeleter $auctionUndeleter
     * @return static
     * @internal
     */
    public function setAuctionUndeleter(AuctionUndeleter $auctionUndeleter): static
    {
        $this->auctionUndeleter = $auctionUndeleter;
        return $this;
    }
}
