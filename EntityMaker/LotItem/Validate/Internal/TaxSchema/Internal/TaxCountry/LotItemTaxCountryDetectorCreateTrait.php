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

namespace Sam\EntityMaker\LotItem\Validate\Internal\TaxSchema\Internal\TaxCountry;

trait LotItemTaxCountryDetectorCreateTrait
{
    protected ?LotItemTaxCountryDetector $lotItemTaxCountryDetector = null;

    /**
     * @return LotItemTaxCountryDetector
     */
    protected function createLotItemTaxCountryDetector(): LotItemTaxCountryDetector
    {
        return $this->lotItemTaxCountryDetector ?: LotItemTaxCountryDetector::new();
    }

    /**
     * @param LotItemTaxCountryDetector $lotItemTaxCountryDetector
     * @return $this
     * @internal
     */
    public function setLotItemTaxCountryDetector(LotItemTaxCountryDetector $lotItemTaxCountryDetector): self
    {
        $this->lotItemTaxCountryDetector = $lotItemTaxCountryDetector;
        return $this;
    }
}
