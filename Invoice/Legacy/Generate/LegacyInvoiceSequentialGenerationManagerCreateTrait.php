<?php
/**
 * SAM-5151: Invoice generation and reverse proxy timeout improvements
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           28.06.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */


namespace Sam\Invoice\Legacy\Generate;


/**
 * Trait InvoiceSequentialGenerationManagerCreateTrait
 * @package
 */
trait LegacyInvoiceSequentialGenerationManagerCreateTrait
{
    /**
     * @var LegacyInvoiceSequentialGenerationManager|null
     */
    protected ?LegacyInvoiceSequentialGenerationManager $legacyInvoiceSequentialGenerationManager = null;

    /**
     * @return LegacyInvoiceSequentialGenerationManager
     */
    protected function createLegacyInvoiceSequentialGenerationManager(): LegacyInvoiceSequentialGenerationManager
    {
        $invoiceSequentialGenerationManager = $this->legacyInvoiceSequentialGenerationManager ?: LegacyInvoiceSequentialGenerationManager::new();
        return $invoiceSequentialGenerationManager;
    }

    /**
     * @param LegacyInvoiceSequentialGenerationManager $legacyInvoiceSequentialGenerationManager
     * @return static
     * @internal
     */
    public function setLegacyInvoiceSequentialGenerationManager(LegacyInvoiceSequentialGenerationManager $legacyInvoiceSequentialGenerationManager): static
    {
        $this->legacyInvoiceSequentialGenerationManager = $legacyInvoiceSequentialGenerationManager;
        return $this;
    }
}
