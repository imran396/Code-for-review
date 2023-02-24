<?php
/**
 * SAM-11950: Stacked Tax - Stage 2: Locations and tax authority: Display Geo Taxes in invoice
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 17, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Validate\Internal\TaxSchema;

/**
 * Trait AuctionLotTaxSchemaValidationIntegratorCreateTrait
 * @package Sam\EntityMaker\AuctionLot\Validate\Internal\TaxSchema
 */
trait AuctionLotTaxSchemaValidationIntegratorCreateTrait
{
    protected ?AuctionLotTaxSchemaValidationIntegrator $auctionLotTaxSchemaValidationIntegrator = null;

    /**
     * @return AuctionLotTaxSchemaValidationIntegrator
     */
    protected function createAuctionLotTaxSchemaValidationIntegrator(): AuctionLotTaxSchemaValidationIntegrator
    {
        return $this->auctionLotTaxSchemaValidationIntegrator ?: AuctionLotTaxSchemaValidationIntegrator::new();
    }

    /**
     * @param AuctionLotTaxSchemaValidationIntegrator $auctionLotTaxSchemaValidationIntegrator
     * @return $this
     * @internal
     */
    public function setAuctionLotTaxSchemaValidationIntegrator(AuctionLotTaxSchemaValidationIntegrator $auctionLotTaxSchemaValidationIntegrator): self
    {
        $this->auctionLotTaxSchemaValidationIntegrator = $auctionLotTaxSchemaValidationIntegrator;
        return $this;
    }
}
