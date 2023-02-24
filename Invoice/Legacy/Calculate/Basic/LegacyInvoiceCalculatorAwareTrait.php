<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           15.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Legacy\Calculate\Basic;

/**
 * Trait InvoiceCalculatorAwareTrait
 * @package Sam\Invoice\Calculate
 */
trait LegacyInvoiceCalculatorAwareTrait
{
    /**
     * @var LegacyInvoiceCalculator|null
     */
    protected ?LegacyInvoiceCalculator $legacyInvoiceCalculator = null;

    /**
     * @return LegacyInvoiceCalculator
     */
    protected function getLegacyInvoiceCalculator(): LegacyInvoiceCalculator
    {
        if ($this->legacyInvoiceCalculator === null) {
            $this->legacyInvoiceCalculator = LegacyInvoiceCalculator::new();
        }
        return $this->legacyInvoiceCalculator;
    }

    /**
     * @param LegacyInvoiceCalculator $legacyInvoiceCalculator
     * @return static
     * @internal
     */
    public function setLegacyInvoiceCalculator(LegacyInvoiceCalculator $legacyInvoiceCalculator): static
    {
        $this->legacyInvoiceCalculator = $legacyInvoiceCalculator;
        return $this;
    }
}
