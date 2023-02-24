<?php
/**
 * SAM-11061: Stacked Tax. Invoice Management pages. Implement Stacked Tax Invoice Generation logic
 * SAM-5151: Invoice generation and reverse proxy timeout improvements
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           01.08.2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */


namespace Sam\Invoice\StackedTax\Generate;


/**
 * Trait InvoiceSequentialGenerationManagerCreateTrait
 * @package
 */
trait StackedTaxInvoiceSequentialGenerationManagerCreateTrait
{
    /**
     * @var StackedTaxInvoiceSequentialGenerationManager|null
     */
    protected ?StackedTaxInvoiceSequentialGenerationManager $stackedTaxInvoiceSequentialGenerationManager = null;

    /**
     * @return StackedTaxInvoiceSequentialGenerationManager
     */
    protected function createStackedTaxInvoiceSequentialGenerationManager(): StackedTaxInvoiceSequentialGenerationManager
    {
        return $this->stackedTaxInvoiceSequentialGenerationManager ?: StackedTaxInvoiceSequentialGenerationManager::new();
    }

    /**
     * @param StackedTaxInvoiceSequentialGenerationManager $stackedTaxInvoiceSequentialGenerationManager
     * @return static
     * @internal
     */
    public function setStackedTaxInvoiceSequentialGenerationManager(StackedTaxInvoiceSequentialGenerationManager $stackedTaxInvoiceSequentialGenerationManager): static
    {
        $this->stackedTaxInvoiceSequentialGenerationManager = $stackedTaxInvoiceSequentialGenerationManager;
        return $this;
    }
}
