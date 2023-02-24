<?php
/**
 * SAM-6019: Auction end date overhaul
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Load;

/**
 * Trait AuctionDynamicLoaderAwareTrait
 * @package Sam\Auction\Load
 */
trait AuctionDynamicLoaderAwareTrait
{
    protected ?AuctionDynamicLoader $auctionDynamicLoader = null;

    /**
     * @return AuctionDynamicLoader
     */
    protected function getAuctionDynamicLoader(): AuctionDynamicLoader
    {
        if ($this->auctionDynamicLoader === null) {
            $this->auctionDynamicLoader = AuctionDynamicLoader::new();
        }
        return $this->auctionDynamicLoader;
    }

    /**
     * @param AuctionDynamicLoader $auctionDynamicLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionDynamicLoader(AuctionDynamicLoader $auctionDynamicLoader): static
    {
        $this->auctionDynamicLoader = $auctionDynamicLoader;
        return $this;
    }
}
