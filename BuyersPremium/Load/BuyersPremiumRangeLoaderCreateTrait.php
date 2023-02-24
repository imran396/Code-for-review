<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Load;

/**
 * Trait BuyersPremiumRangeLoaderCreateTrait
 * @package Sam\BuyersPremium\Load
 */
trait BuyersPremiumRangeLoaderCreateTrait
{
    /**
     * @var BuyersPremiumRangeLoader|null
     */
    protected ?BuyersPremiumRangeLoader $buyersPremiumRangeLoader = null;

    /**
     * @return BuyersPremiumRangeLoader
     */
    protected function createBuyersPremiumRangeLoader(): BuyersPremiumRangeLoader
    {
        return $this->buyersPremiumRangeLoader ?: BuyersPremiumRangeLoader::new();
    }

    /**
     * @param BuyersPremiumRangeLoader $buyersPremiumRangeLoader
     * @return static
     * @internal
     */
    public function setBuyersPremiumRangeLoader(BuyersPremiumRangeLoader $buyersPremiumRangeLoader): static
    {
        $this->buyersPremiumRangeLoader = $buyersPremiumRangeLoader;
        return $this;
    }
}
