<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\Printing\Single\Update;

/**
 * Trait SingleSettlementCheckPrintingUpdaterCreateTrait
 * @package Sam\Settlement\Check
 */
trait SingleSettlementCheckPrintingUpdaterCreateTrait
{
    protected ?SingleSettlementCheckPrintingUpdater $singleSettlementCheckPrintingUpdater = null;

    /**
     * @return SingleSettlementCheckPrintingUpdater
     */
    protected function createSingleSettlementCheckPrintingUpdater(): SingleSettlementCheckPrintingUpdater
    {
        return $this->singleSettlementCheckPrintingUpdater ?: SingleSettlementCheckPrintingUpdater::new();
    }

    /**
     * @param SingleSettlementCheckPrintingUpdater $singleSettlementCheckPrintingUpdater
     * @return $this
     * @internal
     */
    public function setSingleSettlementCheckPrintingUpdater(SingleSettlementCheckPrintingUpdater $singleSettlementCheckPrintingUpdater): static
    {
        $this->singleSettlementCheckPrintingUpdater = $singleSettlementCheckPrintingUpdater;
        return $this;
    }
}
