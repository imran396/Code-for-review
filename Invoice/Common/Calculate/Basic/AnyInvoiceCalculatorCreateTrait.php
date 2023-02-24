<?php
/**
 *
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Calculate\Basic;

/**
 * Trait AnyInvoiceCalculatorCreateTrait
 * @package Sam\Invoice\Common\Calculate\Basic
 */
trait AnyInvoiceCalculatorCreateTrait
{
    protected ?AnyInvoiceCalculator $anyInvoiceCalculator = null;

    /**
     * @return AnyInvoiceCalculator
     */
    protected function createAnyInvoiceCalculator(): AnyInvoiceCalculator
    {
        return $this->anyInvoiceCalculator ?: AnyInvoiceCalculator::new();
    }

    /**
     * @param AnyInvoiceCalculator $anyInvoiceCalculator
     * @return $this
     * @internal
     */
    public function setAnyInvoiceCalculator(AnyInvoiceCalculator $anyInvoiceCalculator): static
    {
        $this->anyInvoiceCalculator = $anyInvoiceCalculator;
        return $this;
    }
}
