<?php
/**
 * SAM-9890: Check Printing for Settlements: Implementation of printing content rendering
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\Printing\Multiple\Update;

/**
 * Trait MultipleSettlementCheckPrintingUpdaterCreateTrait
 * @package Sam\Settlement\Check
 */
trait MultipleSettlementCheckPrintingUpdaterCreateTrait
{
    protected ?MultipleSettlementCheckPrintingUpdater $multipleSettlementCheckPrintingUpdater = null;

    /**
     * @return MultipleSettlementCheckPrintingUpdater
     */
    protected function createMultipleSettlementCheckPrintingUpdater(): MultipleSettlementCheckPrintingUpdater
    {
        return $this->multipleSettlementCheckPrintingUpdater ?: MultipleSettlementCheckPrintingUpdater::new();
    }

    /**
     * @param MultipleSettlementCheckPrintingUpdater $multipleSettlementCheckPrintingUpdater
     * @return static
     * @internal
     */
    public function setMultipleSettlementCheckPrintingUpdater(MultipleSettlementCheckPrintingUpdater $multipleSettlementCheckPrintingUpdater): static
    {
        $this->multipleSettlementCheckPrintingUpdater = $multipleSettlementCheckPrintingUpdater;
        return $this;
    }
}
