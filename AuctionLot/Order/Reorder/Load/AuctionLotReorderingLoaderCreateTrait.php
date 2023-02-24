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

namespace Sam\AuctionLot\Order\Reorder\Load;

/**
 * Trait AuctionLotReorderingLoaderCreateTrait
 * @package ${NAMESPACE}
 */
trait AuctionLotReorderingLoaderCreateTrait
{
    protected ?AuctionLotReorderingLoader $auctionLotReorderingLoader = null;

    /**
     * @return AuctionLotReorderingLoader
     */
    protected function createAuctionLotReorderingLoader(): AuctionLotReorderingLoader
    {
        return $this->auctionLotReorderingLoader ?: AuctionLotReorderingLoader::new();
    }

    /**
     * @param AuctionLotReorderingLoader $auctionLotReorderingLoader
     * @return static
     * @internal
     */
    public function setAuctionLotReorderingLoader(AuctionLotReorderingLoader $auctionLotReorderingLoader): static
    {
        $this->auctionLotReorderingLoader = $auctionLotReorderingLoader;
        return $this;
    }
}
