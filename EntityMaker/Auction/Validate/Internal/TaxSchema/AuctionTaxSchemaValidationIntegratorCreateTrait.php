<?php
/**
 * SAM-11950: Stacked Tax - Stage 2: Locations and tax authority: Display Geo Taxes in invoice
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 18, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\TaxSchema;

/**
 * Trait AuctionTaxSchemaValidationIntegratorCreateTrait
 * @package Sam\EntityMaker\Auction\Validate\Internal\TaxSchema
 */
trait AuctionTaxSchemaValidationIntegratorCreateTrait
{
    protected ?AuctionTaxSchemaValidationIntegrator $auctionTaxSchemaValidationIntegrator = null;

    /**
     * @return AuctionTaxSchemaValidationIntegrator
     */
    protected function createAuctionTaxSchemaValidationIntegrator(): AuctionTaxSchemaValidationIntegrator
    {
        return $this->auctionTaxSchemaValidationIntegrator ?: AuctionTaxSchemaValidationIntegrator::new();
    }

    /**
     * @param AuctionTaxSchemaValidationIntegrator $auctionTaxSchemaValidationIntegrator
     * @return $this
     * @internal
     */
    public function setAuctionTaxSchemaValidationIntegrator(AuctionTaxSchemaValidationIntegrator $auctionTaxSchemaValidationIntegrator): self
    {
        $this->auctionTaxSchemaValidationIntegrator = $auctionTaxSchemaValidationIntegrator;
        return $this;
    }
}
