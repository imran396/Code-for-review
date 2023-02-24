<?php
/**
 * SAM-8005: Allow decimals in quantity
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Validate\Internal\Quantity;

/**
 * Trait QuantityValidationIntegratorCreateTrait
 * @package Sam\EntityMaker\LotItem
 */
trait QuantityValidationIntegratorCreateTrait
{
    /**
     * @var QuantityValidationIntegrator|null
     */
    protected ?QuantityValidationIntegrator $quantityValidationIntegrator = null;

    /**
     * @return QuantityValidationIntegrator
     */
    protected function createQuantityValidationIntegrator(): QuantityValidationIntegrator
    {
        return $this->quantityValidationIntegrator ?: QuantityValidationIntegrator::new();
    }

    /**
     * @param QuantityValidationIntegrator $quantityValidationIntegrator
     * @return static
     * @internal
     */
    public function setQuantityValidationIntegrator(QuantityValidationIntegrator $quantityValidationIntegrator): static
    {
        $this->quantityValidationIntegrator = $quantityValidationIntegrator;
        return $this;
    }
}
