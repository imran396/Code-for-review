<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\BuyerGroup\Load;

/**
 * Trait BuyerGroupLoaderCreateTrait
 * @package Sam\Lot\BuyerGroup\Load
 */
trait BuyerGroupLoaderCreateTrait
{
    /**
     * @var BuyerGroupLoader|null
     */
    protected ?BuyerGroupLoader $buyerGroupLoader = null;

    /**
     * @return BuyerGroupLoader
     */
    protected function createBuyerGroupLoader(): BuyerGroupLoader
    {
        return $this->buyerGroupLoader ?: BuyerGroupLoader::new();
    }

    /**
     * @param BuyerGroupLoader $buyerGroupLoader
     * @return static
     * @internal
     */
    public function setBuyerGroupLoader(BuyerGroupLoader $buyerGroupLoader): static
    {
        $this->buyerGroupLoader = $buyerGroupLoader;
        return $this;
    }
}
