<?php
/**
 * SAM-8005: Allow decimals in quantity
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Quantity\Scale;

/**
 * Trait LotQuantityScaleLoaderCreateTrait
 * @package Sam\AuctionLot\Quantity\Scale
 */
trait LotQuantityScaleLoaderCreateTrait
{
    /**
     * @var LotQuantityScaleLoader|null
     */
    protected ?LotQuantityScaleLoader $lotQuantityScaleLoader = null;

    /**
     * @return LotQuantityScaleLoader
     */
    protected function createLotQuantityScaleLoader(): LotQuantityScaleLoader
    {
        return $this->lotQuantityScaleLoader ?: LotQuantityScaleLoader::new();
    }

    /**
     * @param LotQuantityScaleLoader $lotQuantityScaleLoader
     * @return static
     * @internal
     */
    public function setLotQuantityScaleLoader(LotQuantityScaleLoader $lotQuantityScaleLoader): static
    {
        $this->lotQuantityScaleLoader = $lotQuantityScaleLoader;
        return $this;
    }
}
