<?php
/**
 * Trait for Auction Loader
 *
 * SAM-3919: Auction Loader class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 19, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Load;

/**
 * Trait AuctionLoaderAwareTrait
 * @package Sam\Auction\Load
 */
trait AuctionLoaderAwareTrait
{
    /**
     * @var AuctionLoader|null
     */
    protected ?AuctionLoader $auctionLoader = null;

    /**
     * @return AuctionLoader
     */
    protected function getAuctionLoader(): AuctionLoader
    {
        if ($this->auctionLoader === null) {
            $this->auctionLoader = AuctionLoader::new();
        }
        return $this->auctionLoader;
    }

    /**
     * @param AuctionLoader $auctionLoader
     * @return static
     * @internal
     */
    public function setAuctionLoader(AuctionLoader $auctionLoader): static
    {
        $this->auctionLoader = $auctionLoader;
        return $this;
    }
}
