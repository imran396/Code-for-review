<?php
/**
 * SAM-5202: Apply constants for LotGroup lot grouping and refactor
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           6/26/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\LotGroup\Load;

/**
 * Trait LotGroupAuctionLotLoaderAwareTrait
 * @package Sam\AuctionLot\LotGroup\Load
 */
trait LotGroupAuctionLotLoaderAwareTrait
{
    /**
     * @var LotGroupAuctionLotLoader|null
     */
    protected ?LotGroupAuctionLotLoader $lotGroupAuctionLotLoader = null;

    /**
     * @return LotGroupAuctionLotLoader
     */
    protected function getLotGroupAuctionLotLoader(): LotGroupAuctionLotLoader
    {
        if ($this->lotGroupAuctionLotLoader === null) {
            $this->lotGroupAuctionLotLoader = LotGroupAuctionLotLoader::new();
        }
        return $this->lotGroupAuctionLotLoader;
    }

    /**
     * @param LotGroupAuctionLotLoader $lotGroupAuctionLotLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotGroupAuctionLotLoader(LotGroupAuctionLotLoader $lotGroupAuctionLotLoader): static
    {
        $this->lotGroupAuctionLotLoader = $lotGroupAuctionLotLoader;
        return $this;
    }
}
