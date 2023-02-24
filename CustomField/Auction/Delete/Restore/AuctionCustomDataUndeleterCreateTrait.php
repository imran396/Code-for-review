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

namespace Sam\CustomField\Auction\Delete\Restore;

/**
 * Trait AuctionCustomDataUndeleterCreateTrait
 * @package Sam\CustomField\Auction\Delete\Restore
 */
trait AuctionCustomDataUndeleterCreateTrait
{
    protected ?AuctionCustomDataUndeleter $auctionCustomDataUndeleter = null;

    /**
     * @return AuctionCustomDataUndeleter
     */
    protected function createAuctionCustomDataUndeleter(): AuctionCustomDataUndeleter
    {
        return $this->auctionCustomDataUndeleter ?: AuctionCustomDataUndeleter::new();
    }

    /**
     * @param AuctionCustomDataUndeleter $auctionCustomDataUndeleter
     * @return static
     * @internal
     */
    public function setAuctionCustomDataUndeleter(AuctionCustomDataUndeleter $auctionCustomDataUndeleter): static
    {
        $this->auctionCustomDataUndeleter = $auctionCustomDataUndeleter;
        return $this;
    }
}
