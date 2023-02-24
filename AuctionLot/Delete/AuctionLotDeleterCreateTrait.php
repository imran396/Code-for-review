<?php
/**
 * SAM-6697: Lot deleters for v3.5 https://bidpath.atlassian.net/browse/SAM-6697
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Delete;

/**
 * Trait AuctionLotDeleterCreateTrait
 * @package
 */
trait AuctionLotDeleterCreateTrait
{
    /**
     * @var AuctionLotDeleter|null
     */
    protected ?AuctionLotDeleter $auctionLotDeleter = null;

    /**
     * @return AuctionLotDeleter
     */
    protected function createAuctionLotDeleter(): AuctionLotDeleter
    {
        return $this->auctionLotDeleter ?: AuctionLotDeleter::new();
    }

    /**
     * @param AuctionLotDeleter $auctionLotDeleter
     * @return $this
     * @internal
     */
    public function setAuctionLotDeleter(AuctionLotDeleter $auctionLotDeleter): static
    {
        $this->auctionLotDeleter = $auctionLotDeleter;
        return $this;
    }
}
