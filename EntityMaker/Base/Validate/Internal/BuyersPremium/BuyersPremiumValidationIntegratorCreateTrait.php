<?php
/**
 * SAM-8107: Issues related to Validation and Values of Buyer's Premium
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 21, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Validate\Internal\BuyersPremium;

/**
 * Trait BuyersPremiumValidationIntegratorCreateTrait
 * @package Sam\EntityMaker\Base\Validate\Internal\BuyersPremium
 */
trait BuyersPremiumValidationIntegratorCreateTrait
{
    /**
     * @var BuyersPremiumValidationIntegrator|null
     */
    protected ?BuyersPremiumValidationIntegrator $buyersPremiumValidationIntegrator = null;

    /**
     * @return BuyersPremiumValidationIntegrator
     */
    protected function createBuyersPremiumValidationIntegrator(): BuyersPremiumValidationIntegrator
    {
        return $this->buyersPremiumValidationIntegrator ?: BuyersPremiumValidationIntegrator::new();
    }

    /**
     * @param BuyersPremiumValidationIntegrator $buyersPremiumValidationIntegrator
     * @return $this
     * @internal
     */
    public function setBuyersPremiumValidationIntegrator(BuyersPremiumValidationIntegrator $buyersPremiumValidationIntegrator): static
    {
        $this->buyersPremiumValidationIntegrator = $buyersPremiumValidationIntegrator;
        return $this;
    }
}
