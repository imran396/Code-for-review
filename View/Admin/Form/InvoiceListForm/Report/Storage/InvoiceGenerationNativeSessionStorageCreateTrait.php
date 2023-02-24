<?php
/**
 * SAM-10948: Stacked Tax. Invoice Management pages. Prepare Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceListForm\Report\Storage;

/**
 * Trait InvoiceGenerationNativeSessionStorageCreateTrait
 * @package Sam\View\Admin\Form\InvoiceListForm\Report\Storage
 */
trait InvoiceGenerationNativeSessionStorageCreateTrait
{
    protected ?InvoiceGenerationNativeSessionStorage $invoiceGenerationNativeSessionStorage = null;

    /**
     * @return InvoiceGenerationNativeSessionStorage
     */
    protected function createInvoiceGenerationNativeSessionStorage(): InvoiceGenerationNativeSessionStorage
    {
        return $this->invoiceGenerationNativeSessionStorage ?: InvoiceGenerationNativeSessionStorage::new();
    }

    /**
     * @param InvoiceGenerationNativeSessionStorage $invoiceGenerationNativeSessionStorage
     * @return $this
     * @internal
     */
    public function setInvoiceGenerationNativeSessionStorage(InvoiceGenerationNativeSessionStorage $invoiceGenerationNativeSessionStorage): static
    {
        $this->invoiceGenerationNativeSessionStorage = $invoiceGenerationNativeSessionStorage;
        return $this;
    }
}
