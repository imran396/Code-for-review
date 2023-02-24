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

namespace Sam\EntityMaker\Auction\Lock\SaleNo;

/**
 * Trait AuctionUniqueSaleNoLockerCreateTrait
 * @package Sam\EntityMaker\Auction\Lock\SaleNo
 */
trait AuctionUniqueSaleNoLockerCreateTrait
{
    protected ?AuctionUniqueSaleNoLocker $auctionUniqueSaleNoLocker = null;

    /**
     * @return AuctionUniqueSaleNoLocker
     */
    protected function createAuctionUniqueSaleNoLocker(): AuctionUniqueSaleNoLocker
    {
        return $this->auctionUniqueSaleNoLocker ?: AuctionUniqueSaleNoLocker::new();
    }

    /**
     * @param AuctionUniqueSaleNoLocker $auctionUniqueSaleNoLocker
     * @return static
     * @internal
     */
    public function setAuctionUniqueSaleNoLocker(AuctionUniqueSaleNoLocker $auctionUniqueSaleNoLocker): static
    {
        $this->auctionUniqueSaleNoLocker = $auctionUniqueSaleNoLocker;
        return $this;
    }
}
