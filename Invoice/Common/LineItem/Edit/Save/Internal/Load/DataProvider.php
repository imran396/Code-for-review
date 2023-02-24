<?php
/**
 * SAM-9454: Refactor Invoice Line item editor for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 11, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\LineItem\Edit\Save\Internal\Load;

use InvoiceLineItem;
use InvoiceLineItemLotCat;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\LineItem\Load\InvoiceLineItemLoaderAwareTrait;
use Sam\Invoice\Common\LineItem\LotCategory\InvoiceLineItemLotCategoryLoaderAwareTrait;

/**
 * Class DataProvider
 * @package Sam\Invoice\Common\LineItem\Edit\Save\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use InvoiceLineItemLoaderAwareTrait;
    use InvoiceLineItemLotCategoryLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        return $this;
    }

    /**
     * @param int|null $invoiceLineItemId null leads to null result
     * @param bool $isReadOnlyDb
     * @return InvoiceLineItem|null
     */
    public function loadInvoiceLineItem(?int $invoiceLineItemId, bool $isReadOnlyDb = false): ?InvoiceLineItem
    {
        return $this->getInvoiceLineItemLoader()->load($invoiceLineItemId, $isReadOnlyDb);
    }

    /**
     * @param int $invoiceLineItemId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadInvoiceLineItemLotCategoryByInvoiceLineId(int $invoiceLineItemId, bool $isReadOnlyDb = false): array
    {
        return $this->getInvoiceLineItemLotCategoryLoader()->loadByInvoiceLineId($invoiceLineItemId, $isReadOnlyDb);
    }

    /**
     * @param int $invoiceLineItemId
     * @param array $lotCatIds
     * @param bool $isReadOnlyDb
     * @return InvoiceLineItemLotCat|null
     */
    public function loadInvoiceLineItemLotCategoryByInvoiceLineIdAndLotCatId(
        int $invoiceLineItemId,
        array $lotCatIds,
        bool $isReadOnlyDb = false
    ): ?InvoiceLineItemLotCat {
        return $this->getInvoiceLineItemLotCategoryLoader()->loadByInvoiceLineIdAndLotCatId($invoiceLineItemId, $lotCatIds, $isReadOnlyDb);
    }
}
