<?php
/**
 * SAM-5654 Auction lot reorderer
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 26, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Order\Reorder;

/**
 * Trait AuctionLotReordererAwareTrait
 */
trait AuctionLotReordererAwareTrait
{
    /**
     * @var AuctionLotReorderer|null
     */
    protected ?AuctionLotReorderer $auctionLotReorderer = null;

    /**
     * @return AuctionLotReorderer
     */
    protected function getAuctionLotReorderer(): AuctionLotReorderer
    {
        if ($this->auctionLotReorderer === null) {
            $this->auctionLotReorderer = AuctionLotReorderer::new();
        }
        return $this->auctionLotReorderer;
    }

    /**
     * @param AuctionLotReorderer $auctionLotReorderer
     * @return static
     * @internal
     */
    public function setAuctionLotReorderer(AuctionLotReorderer $auctionLotReorderer): static
    {
        $this->auctionLotReorderer = $auctionLotReorderer;
        return $this;
    }
}
