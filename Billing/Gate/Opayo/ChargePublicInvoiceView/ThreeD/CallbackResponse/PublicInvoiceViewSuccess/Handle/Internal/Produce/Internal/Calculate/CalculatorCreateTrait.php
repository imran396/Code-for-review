<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Produce\Internal\Calculate;

trait CalculatorCreateTrait
{
    /**
     * @var Calculator|null
     */
    protected ?Calculator $calculator = null;

    /**
     * @return Calculator
     */
    protected function createCalculator(): Calculator
    {
        return $this->calculator ?: Calculator::new();
    }

    /**
     * @param Calculator $calculator
     * @return $this
     * @internal
     */
    public function setCalculator(Calculator $calculator): static
    {
        $this->calculator = $calculator;
        return $this;
    }
}
