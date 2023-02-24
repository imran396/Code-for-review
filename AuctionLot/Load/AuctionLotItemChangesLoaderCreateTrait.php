<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Load;

/**
 * Trait AuctionLotItemChangesLoaderCreateTrait
 * @package Sam\AuctionLot\Load
 */
trait AuctionLotItemChangesLoaderCreateTrait
{
    /**
     * @var AuctionLotItemChangesLoader|null
     */
    protected ?AuctionLotItemChangesLoader $auctionLotItemChangesLoader = null;

    /**
     * @return AuctionLotItemChangesLoader
     */
    protected function createAuctionLotItemChangesLoader(): AuctionLotItemChangesLoader
    {
        return $this->auctionLotItemChangesLoader ?: AuctionLotItemChangesLoader::new();
    }

    /**
     * @param AuctionLotItemChangesLoader $auctionLotItemChangesLoader
     * @return static
     * @internal
     */
    public function setAuctionLotItemChangesLoader(AuctionLotItemChangesLoader $auctionLotItemChangesLoader): static
    {
        $this->auctionLotItemChangesLoader = $auctionLotItemChangesLoader;
        return $this;
    }
}
