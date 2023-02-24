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
 * Trait BuyersPremiumLoaderCreateTrait
 * @package Sam\BuyersPremium\Load
 */
trait BuyersPremiumLoaderCreateTrait
{
    /**
     * @var BuyersPremiumLoader|null
     */
    protected ?BuyersPremiumLoader $buyersPremiumLoader = null;

    /**
     * @return BuyersPremiumLoader
     */
    protected function createBuyersPremiumLoader(): BuyersPremiumLoader
    {
        return $this->buyersPremiumLoader ?: BuyersPremiumLoader::new();
    }

    /**
     * @param BuyersPremiumLoader $buyersPremiumLoader
     * @return static
     * @internal
     */
    public function setBuyersPremiumLoader(BuyersPremiumLoader $buyersPremiumLoader): static
    {
        $this->buyersPremiumLoader = $buyersPremiumLoader;
        return $this;
    }
}
