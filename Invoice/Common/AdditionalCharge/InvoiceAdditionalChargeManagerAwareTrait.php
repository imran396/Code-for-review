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

namespace Sam\Invoice\Common\AdditionalCharge;

/**
 * Trait InvoiceAdditionalChargeManagerAwareTrait
 * @package Sam\Invoice\Common\AdditionalCharge
 */
trait InvoiceAdditionalChargeManagerAwareTrait
{
    /**
     * @var InvoiceAdditionalChargeManager|null
     */
    protected ?InvoiceAdditionalChargeManager $invoiceAdditionalChargeManager = null;

    /**
     * @return InvoiceAdditionalChargeManager
     */
    protected function getInvoiceAdditionalChargeManager(): InvoiceAdditionalChargeManager
    {
        if ($this->invoiceAdditionalChargeManager === null) {
            $this->invoiceAdditionalChargeManager = InvoiceAdditionalChargeManager::new();
        }
        return $this->invoiceAdditionalChargeManager;
    }

    /**
     * @param InvoiceAdditionalChargeManager $invoiceAdditionalChargeManager
     * @return static
     * @internal
     */
    public function setInvoiceAdditionalChargeManager(InvoiceAdditionalChargeManager $invoiceAdditionalChargeManager): static
    {
        $this->invoiceAdditionalChargeManager = $invoiceAdditionalChargeManager;
        return $this;
    }
}
