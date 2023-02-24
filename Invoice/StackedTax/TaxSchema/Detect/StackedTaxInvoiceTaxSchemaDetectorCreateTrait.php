<?php
/**
 * SAM-11061: Stacked Tax. Invoice Management pages. Implement Stacked Tax Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\TaxSchema\Detect;

/**
 * Trait StackedTaxInvoiceTaxSchemaDetectorCreateTrait
 * @package Sam\Invoice\StackedTax\TaxSchema\Detect
 */
trait StackedTaxInvoiceTaxSchemaDetectorCreateTrait
{
    protected ?StackedTaxInvoiceTaxSchemaDetector $stackedTaxInvoiceTaxSchemaDetector = null;

    /**
     * @return StackedTaxInvoiceTaxSchemaDetector
     */
    protected function createStackedTaxInvoiceTaxSchemaDetector(): StackedTaxInvoiceTaxSchemaDetector
    {
        return $this->stackedTaxInvoiceTaxSchemaDetector ?: StackedTaxInvoiceTaxSchemaDetector::new();
    }

    /**
     * @param StackedTaxInvoiceTaxSchemaDetector $stackedTaxInvoiceTaxSchemaDetector
     * @return $this
     * @internal
     */
    public function setStackedTaxInvoiceTaxSchemaDetector(StackedTaxInvoiceTaxSchemaDetector $stackedTaxInvoiceTaxSchemaDetector): static
    {
        $this->stackedTaxInvoiceTaxSchemaDetector = $stackedTaxInvoiceTaxSchemaDetector;
        return $this;
    }
}
