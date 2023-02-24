<?php
/**
 * Help methods for auction image loading
 *
 * SAM-4746: AuctionImage loader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 26, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Image\Load;

/**
 * Trait AuctionImageLoaderAwareTrait
 * @package Sam\Auction\Image\Load
 */
trait AuctionImageLoaderAwareTrait
{
    protected ?AuctionImageLoader $auctionImageLoader = null;

    /**
     * @return AuctionImageLoader
     */
    protected function getAuctionImageLoader(): AuctionImageLoader
    {
        if ($this->auctionImageLoader === null) {
            $this->auctionImageLoader = AuctionImageLoader::new();
        }
        return $this->auctionImageLoader;
    }

    /**
     * @param AuctionImageLoader $auctionImageLoader
     * @return static
     * @internal
     */
    public function setAuctionImageLoader(AuctionImageLoader $auctionImageLoader): static
    {
        $this->auctionImageLoader = $auctionImageLoader;
        return $this;
    }
}
