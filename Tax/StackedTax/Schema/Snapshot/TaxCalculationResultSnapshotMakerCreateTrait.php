<?php
/**
 * SAM-11239: Stacked Tax. Store invoice tax amounts per definition
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 14, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Snapshot;

/**
 * Trait TaxCalculationResultSnapshotMakerCreateTrait
 * @package Sam\Tax\StackedTax\Schema\Snapshot
 */
trait TaxCalculationResultSnapshotMakerCreateTrait
{
    protected ?TaxCalculationResultSnapshotMaker $taxCalculationResultSnapshotMaker = null;

    /**
     * @return TaxCalculationResultSnapshotMaker
     */
    protected function createTaxCalculationResultSnapshotMaker(): TaxCalculationResultSnapshotMaker
    {
        return $this->taxCalculationResultSnapshotMaker ?: TaxCalculationResultSnapshotMaker::new();
    }

    /**
     * @param TaxCalculationResultSnapshotMaker $taxCalculationResultSnapshotMaker
     * @return static
     * @internal
     */
    public function setTaxCalculationResultSnapshotMaker(TaxCalculationResultSnapshotMaker $taxCalculationResultSnapshotMaker): static
    {
        $this->taxCalculationResultSnapshotMaker = $taxCalculationResultSnapshotMaker;
        return $this;
    }
}
