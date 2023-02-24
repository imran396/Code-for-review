<?php
/**
 *
 * * SAM-4723: Invoice Line item editor
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-20
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\LineItem\LotCategory;

/**
 * Trait InvoiceLineItemLotCategoryLoaderAwareTrait
 * @package Sam\Invoice\Common\LineItem\LotCategory
 */
trait InvoiceLineItemLotCategoryLoaderAwareTrait
{
    /**
     * @var InvoiceLineItemLotCategoryLoader|null
     */
    protected ?InvoiceLineItemLotCategoryLoader $invoiceLineItemLotCategoryLoader = null;

    /**
     * @return InvoiceLineItemLotCategoryLoader
     */
    protected function getInvoiceLineItemLotCategoryLoader(): InvoiceLineItemLotCategoryLoader
    {
        if ($this->invoiceLineItemLotCategoryLoader === null) {
            $this->invoiceLineItemLotCategoryLoader = InvoiceLineItemLotCategoryLoader::new();
        }
        return $this->invoiceLineItemLotCategoryLoader;
    }

    /**
     * @param InvoiceLineItemLotCategoryLoader $invoiceLineItemLotCategoryLoader
     * @return static
     * @internal
     */
    public function setInvoiceLineItemLotCategoryLoader(InvoiceLineItemLotCategoryLoader $invoiceLineItemLotCategoryLoader): static
    {
        $this->invoiceLineItemLotCategoryLoader = $invoiceLineItemLotCategoryLoader;
        return $this;
    }
}
