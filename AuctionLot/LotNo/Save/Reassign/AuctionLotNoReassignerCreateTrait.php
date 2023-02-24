<?php
/**
 * SAM-5653 Auction lot no reassigner
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           12/28/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\LotNo\Save\Reassign;

/**
 * Trait AuctionLotNoReassignerCreateTrait
 * @package
 */
trait AuctionLotNoReassignerCreateTrait
{
    /**
     * @var AuctionLotNoReassigner|null
     */
    protected ?AuctionLotNoReassigner $auctionLotNoReassigner = null;

    /**
     * @return AuctionLotNoReassigner
     */
    protected function createAuctionLotNoReassigner(): AuctionLotNoReassigner
    {
        return $this->auctionLotNoReassigner ?: AuctionLotNoReassigner::new();
    }

    /**
     * @param AuctionLotNoReassigner $auctionLotNoReassigner
     * @return static
     * @internal
     */
    public function setAuctionLotNoReassigner(AuctionLotNoReassigner $auctionLotNoReassigner): static
    {
        $this->auctionLotNoReassigner = $auctionLotNoReassigner;
        return $this;
    }
}
