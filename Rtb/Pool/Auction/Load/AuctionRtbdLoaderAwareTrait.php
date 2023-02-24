<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/30/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Auction\Load;

/**
 * Trait AuctionRtbdLoaderAwareTrait
 * @package
 */
trait AuctionRtbdLoaderAwareTrait
{
    /**
     * @var AuctionRtbdLoader|null
     */
    protected ?AuctionRtbdLoader $auctionRtbdLoader = null;

    /**
     * @return AuctionRtbdLoader
     */
    protected function getAuctionRtbdLoader(): AuctionRtbdLoader
    {
        if ($this->auctionRtbdLoader === null) {
            $this->auctionRtbdLoader = AuctionRtbdLoader::new();
        }
        return $this->auctionRtbdLoader;
    }

    /**
     * @param AuctionRtbdLoader $auctionRtbdLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionRtbdLoader(AuctionRtbdLoader $auctionRtbdLoader): static
    {
        $this->auctionRtbdLoader = $auctionRtbdLoader;
        return $this;
    }
}
