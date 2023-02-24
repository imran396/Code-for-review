<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           22.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Legacy\Calculate\Summary;

/**
 * Trait InvoiceSummaryCalculatorAwareTrait
 * @package Sam\Invoice\Calculate
 */
trait LegacyInvoiceSummaryCalculatorAwareTrait
{
    /**
     * @var LegacyInvoiceSummaryCalculator|null
     */
    protected ?LegacyInvoiceSummaryCalculator $legacyInvoiceSummaryCalculator = null;

    /**
     * @return LegacyInvoiceSummaryCalculator
     */
    protected function getLegacyInvoiceSummaryCalculator(): LegacyInvoiceSummaryCalculator
    {
        if ($this->legacyInvoiceSummaryCalculator === null) {
            $this->legacyInvoiceSummaryCalculator = LegacyInvoiceSummaryCalculator::new();
        }
        return $this->legacyInvoiceSummaryCalculator;
    }

    /**
     * @param LegacyInvoiceSummaryCalculator $legacyInvoiceSummaryCalculator
     * @return static
     * @internal
     */
    public function setLegacyInvoiceSummaryCalculator(LegacyInvoiceSummaryCalculator $legacyInvoiceSummaryCalculator): static
    {
        $this->legacyInvoiceSummaryCalculator = $legacyInvoiceSummaryCalculator;
        return $this;
    }
}
