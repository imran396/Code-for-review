<?php
/**
 * SAM-8891: Auction entity-maker - Extract sale# validation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\SaleNo;

/**
 * Trait SaleNoValidationIntegratorCreateTrait
 * @package
 */
trait SaleNoValidationIntegratorCreateTrait
{
    /**
     * @var SaleNoValidationIntegrator|null
     */
    protected ?SaleNoValidationIntegrator $saleNoValidationIntegrator = null;

    /**
     * @return SaleNoValidationIntegrator
     */
    protected function createSaleNoValidationIntegrator(): SaleNoValidationIntegrator
    {
        return $this->saleNoValidationIntegrator ?: SaleNoValidationIntegrator::new();
    }

    /**
     * @param SaleNoValidationIntegrator $saleNoValidationIntegrator
     * @return $this
     * @internal
     */
    public function setSaleNoValidationIntegrator(SaleNoValidationIntegrator $saleNoValidationIntegrator): static
    {
        $this->saleNoValidationIntegrator = $saleNoValidationIntegrator;
        return $this;
    }
}
