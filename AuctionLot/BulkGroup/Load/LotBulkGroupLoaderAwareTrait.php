<?php
/**
 * SAM-6377: Separate bulk group related logic to classes
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 13, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\BulkGroup\Load;

/**
 * Trait LotBulkGroupLoaderAwareTrait
 * @package Sam\AuctionLot\BulkGroup
 */
trait LotBulkGroupLoaderAwareTrait
{
    /**
     * @var LotBulkGroupLoader|null
     */
    protected ?LotBulkGroupLoader $lotBulkGroupLoader = null;

    /**
     * @return LotBulkGroupLoader
     */
    protected function getLotBulkGroupLoader(): LotBulkGroupLoader
    {
        if ($this->lotBulkGroupLoader === null) {
            $this->lotBulkGroupLoader = LotBulkGroupLoader::new();
        }
        return $this->lotBulkGroupLoader;
    }

    /**
     * @param LotBulkGroupLoader $lotBulkGroupLoader
     * @return static
     * @internal
     */
    public function setLotBulkGroupLoader(LotBulkGroupLoader $lotBulkGroupLoader): static
    {
        $this->lotBulkGroupLoader = $lotBulkGroupLoader;
        return $this;
    }
}
