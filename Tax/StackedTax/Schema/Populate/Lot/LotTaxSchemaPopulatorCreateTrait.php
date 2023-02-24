<?php
/**
 * SAM-10826: Stacked Tax. Lot categories (Stage-2)
 * SAM-12045: Stacked Tax - Stage 2: Lot categories: Lot Category and Location based tax schema detection
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 13, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Populate\Lot;

/**
 * Trait LotTaxSchemaPopulatorCreateTrait
 * @package Sam\Tax\StackedTax\Schema\Populate\Lot
 */
trait LotTaxSchemaPopulatorCreateTrait
{
    protected ?LotTaxSchemaPopulator $lotTaxSchemaPopulator = null;

    /**
     * @return LotTaxSchemaPopulator
     */
    protected function createLotTaxSchemaPopulator(): LotTaxSchemaPopulator
    {
        return $this->lotTaxSchemaPopulator ?: LotTaxSchemaPopulator::new();
    }

    /**
     * @param LotTaxSchemaPopulator $lotTaxSchemaPopulator
     * @return $this
     * @internal
     */
    public function setLotTaxSchemaPopulator(LotTaxSchemaPopulator $lotTaxSchemaPopulator): self
    {
        $this->lotTaxSchemaPopulator = $lotTaxSchemaPopulator;
        return $this;
    }
}
