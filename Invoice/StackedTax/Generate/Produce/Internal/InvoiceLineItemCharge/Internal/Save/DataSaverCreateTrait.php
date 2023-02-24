<?php
/**
 * SAM-11061: Stacked Tax. Invoice Management pages. Implement Stacked Tax Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 24, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Produce\Internal\InvoiceLineItemCharge\Internal\Save;

/**
 * Trait DataSaverCreateTrait
 * @package Sam\Invoice\StackedTax\Generate\Produce\Internal\InvoiceLineItemCharge\Internal\Save
 */
trait DataSaverCreateTrait
{
    protected ?DataSaver $dataSaver = null;

    /**
     * @return DataSaver
     */
    protected function createDataSaver(): DataSaver
    {
        return $this->dataSaver ?: DataSaver::new();
    }

    /**
     * @param DataSaver $dataSaver
     * @return $this
     * @internal
     */
    public function setDataSaver(DataSaver $dataSaver): static
    {
        $this->dataSaver = $dataSaver;
        return $this;
    }
}
