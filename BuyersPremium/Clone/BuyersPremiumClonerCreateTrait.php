<?php
/**
 * SAM-10464: Separate BP manager to services
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Clone;

/**
 * Trait BuyersPremiumClonerCreateTrait
 * @package Sam\BuyersPremium\Clone
 */
trait BuyersPremiumClonerCreateTrait
{
    protected ?BuyersPremiumCloner $buyersPremiumCloner = null;

    /**
     * @return BuyersPremiumCloner
     */
    protected function createBuyersPremiumCloner(): BuyersPremiumCloner
    {
        return $this->buyersPremiumCloner ?: BuyersPremiumCloner::new();
    }

    /**
     * @param BuyersPremiumCloner $buyersPremiumCloner
     * @return $this
     * @internal
     */
    public function setBuyersPremiumCloner(BuyersPremiumCloner $buyersPremiumCloner): static
    {
        $this->buyersPremiumCloner = $buyersPremiumCloner;
        return $this;
    }
}
