<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/28/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Simultaneous\Load;

/**
 * Trait SimultaneousAuctionLoaderAwareTrait
 * @package
 */
trait SimultaneousAuctionLoaderAwareTrait
{
    /**
     * @var SimultaneousAuctionLoader|null
     */
    protected ?SimultaneousAuctionLoader $simultaneousAuctionLoader = null;

    /**
     * @return SimultaneousAuctionLoader
     */
    protected function getSimultaneousAuctionLoader(): SimultaneousAuctionLoader
    {
        if ($this->simultaneousAuctionLoader === null) {
            $this->simultaneousAuctionLoader = SimultaneousAuctionLoader::new();
        }
        return $this->simultaneousAuctionLoader;
    }

    /**
     * @param SimultaneousAuctionLoader $simultaneousAuctionLoader
     * @return static
     * @internal
     */
    public function setSimultaneousAuctionLoader(SimultaneousAuctionLoader $simultaneousAuctionLoader): static
    {
        $this->simultaneousAuctionLoader = $simultaneousAuctionLoader;
        return $this;
    }
}
