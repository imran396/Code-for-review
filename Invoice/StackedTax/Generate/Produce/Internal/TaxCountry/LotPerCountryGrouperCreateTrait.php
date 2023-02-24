<?php
/**
 * SAM-11950: Stacked Tax. Country based invoice (Stage 2)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 31, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Produce\Internal\TaxCountry;

trait LotPerCountryGrouperCreateTrait
{
    protected ?LotPerCountryGrouper $lotPerCountryGrouper = null;

    /**
     * @return LotPerCountryGrouper
     */
    protected function createLotPerCountryGrouper(): LotPerCountryGrouper
    {
        return $this->lotPerCountryGrouper ?: LotPerCountryGrouper::new();
    }

    /**
     * @param LotPerCountryGrouper $lotPerCountryGrouper
     * @return $this
     * @internal
     */
    public function setLotPerCountryGrouper(LotPerCountryGrouper $lotPerCountryGrouper): self
    {
        $this->lotPerCountryGrouper = $lotPerCountryGrouper;
        return $this;
    }
}
