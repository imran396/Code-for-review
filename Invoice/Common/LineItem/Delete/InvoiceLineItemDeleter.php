<?php
/**
 *
 * SAM-4724: Invoice Line item deleter
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-23
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\LineItem\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\LineItem\Load\InvoiceLineItemLoaderAwareTrait;
use Sam\Invoice\Common\LineItem\LotCategory\InvoiceLineItemLotCategoryLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceLineItem\InvoiceLineItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceLineItemLotCat\InvoiceLineItemLotCatWriteRepositoryAwareTrait;

/**
 * Class InvoiceLineItemDeleter
 * @package Sam\Invoice\Common\LineItem\Delete
 */
class InvoiceLineItemDeleter extends CustomizableClass
{
    use InvoiceLineItemLoaderAwareTrait;
    use InvoiceLineItemLotCatWriteRepositoryAwareTrait;
    use InvoiceLineItemLotCategoryLoaderAwareTrait;
    use InvoiceLineItemWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete invoice line by id
     * @param int $invoiceLineItemId
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     */
    public function deleteById(int $invoiceLineItemId, int $editorUserId, bool $isReadOnlyDb = false): void
    {
        $invoiceLineItem = $this->getInvoiceLineItemLoader()->load($invoiceLineItemId, $isReadOnlyDb);
        if (!$invoiceLineItem) {
            log_error("Available invoice line item not found" . composeSuffix(['id' => $invoiceLineItemId]));
            return;
        }

        $invoiceLineItem->Active = false;
        $this->getInvoiceLineItemWriteRepository()->saveWithModifier($invoiceLineItem, $editorUserId);

        $invoiceLineItemsLotCats = $this->getInvoiceLineItemLotCategoryLoader()
            ->loadByInvoiceLineId($invoiceLineItemId);
        foreach ($invoiceLineItemsLotCats as $invoiceLineItemLotCat) {
            $invoiceLineItemLotCat->Active = false;
            $this->getInvoiceLineItemLotCatWriteRepository()->saveWithModifier($invoiceLineItemLotCat, $editorUserId);
        }
    }
}
