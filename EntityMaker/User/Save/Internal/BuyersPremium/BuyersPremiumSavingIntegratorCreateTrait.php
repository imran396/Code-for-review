<?php
/**
 * SAM-8107: Issues related to Validation and Values of Buyer's Premium
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 23, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Save\Internal\BuyersPremium;

/**
 * Trait BuyersPremiumSavingIntegratorCreateTrait
 * @package Sam\EntityMaker\User\Save\Internal\BuyersPremium
 */
trait BuyersPremiumSavingIntegratorCreateTrait
{
    protected ?BuyersPremiumSavingIntegrator $buyersPremiumSavingIntegrator = null;

    /**
     * @return BuyersPremiumSavingIntegrator
     */
    protected function createBuyersPremiumSavingIntegrator(): BuyersPremiumSavingIntegrator
    {
        return $this->buyersPremiumSavingIntegrator ?: BuyersPremiumSavingIntegrator::new();
    }

    /**
     * @param BuyersPremiumSavingIntegrator $buyersPremiumSavingIntegrator
     * @return $this
     * @internal
     */
    public function setBuyersPremiumSavingIntegrator(BuyersPremiumSavingIntegrator $buyersPremiumSavingIntegrator): static
    {
        $this->buyersPremiumSavingIntegrator = $buyersPremiumSavingIntegrator;
        return $this;
    }
}
