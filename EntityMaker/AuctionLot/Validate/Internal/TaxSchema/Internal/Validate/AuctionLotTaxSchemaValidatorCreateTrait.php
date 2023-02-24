<?php
/**
 * SAM-11950: Stacked Tax - Stage 2: Locations and tax authority: Display Geo Taxes in invoice
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 16, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Validate\Internal\TaxSchema\Internal\Validate;

trait AuctionLotTaxSchemaValidatorCreateTrait
{
    protected ?AuctionLotTaxSchemaValidator $auctionLotTaxSchemaValidator = null;

    /**
     * @return AuctionLotTaxSchemaValidator
     */
    protected function createAuctionLotTaxSchemaValidator(): AuctionLotTaxSchemaValidator
    {
        return $this->auctionLotTaxSchemaValidator ?: AuctionLotTaxSchemaValidator::new();
    }

    /**
     * @param AuctionLotTaxSchemaValidator $auctionLotTaxSchemaValidator
     * @return $this
     * @internal
     */
    public function setAuctionLotTaxSchemaValidator(AuctionLotTaxSchemaValidator $auctionLotTaxSchemaValidator): self
    {
        $this->auctionLotTaxSchemaValidator = $auctionLotTaxSchemaValidator;
        return $this;
    }
}
