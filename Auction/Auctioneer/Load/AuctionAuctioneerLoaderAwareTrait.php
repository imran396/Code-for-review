<?php
/**
 * Trait for AuctioneerLoader
 *
 * SAM-4241 Auction Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 16, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Auctioneer\Load;

/**
 * Trait AuctionAuctioneerLoaderAwareTrait
 * @package Sam\Auction\Auctioneer\Load
 */
trait AuctionAuctioneerLoaderAwareTrait
{
    protected ?AuctionAuctioneerLoader $auctionAuctioneerLoader = null;

    /**
     * @return AuctionAuctioneerLoader
     */
    protected function getAuctionAuctioneerLoader(): AuctionAuctioneerLoader
    {
        if ($this->auctionAuctioneerLoader === null) {
            $this->auctionAuctioneerLoader = AuctionAuctioneerLoader::new();
        }
        return $this->auctionAuctioneerLoader;
    }

    /**
     * @param AuctionAuctioneerLoader
     * @return static
     * @internal
     */
    public function setAuctionAuctioneerLoader(AuctionAuctioneerLoader $auctionAuctioneerLoader): static
    {
        $this->auctionAuctioneerLoader = $auctionAuctioneerLoader;
        return $this;
    }
}
