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

use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\InvoiceLineItemLotCat\InvoiceLineItemLotCatReadRepositoryCreateTrait;

/**
 * Class InvoiceLineItemLotCategoryLoader
 * @package Sam\Invoice\Common\LineItem\LotCategory
 */
class InvoiceLineItemLotCategoryLoader extends EntityLoaderBase
{
    use InvoiceLineItemLotCatReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param $invoiceLineItemId
     * @param bool $isReadOnlyDb
     * @return \InvoiceLineItemLotCat[]
     */
    public function loadByInvoiceLineId(int $invoiceLineItemId, bool $isReadOnlyDb = false): array
    {
        $invoiceLineItemsLotCats = $this->createInvoiceLineItemLotCatReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceLineId($invoiceLineItemId)
            ->loadEntities();
        return $invoiceLineItemsLotCats;
    }

    /**
     * @param int $invoiceLineItemId
     * @param int[]|null[] $lotCatId
     * @param bool $isReadOnlyDb
     * @return \InvoiceLineItemLotCat
     */
    public function loadByInvoiceLineIdAndLotCatId(
        int $invoiceLineItemId,
        array $lotCatId,
        bool $isReadOnlyDb = false
    ): ?\InvoiceLineItemLotCat {
        $invoiceCat = $this->createInvoiceLineItemLotCatReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceLineId($invoiceLineItemId)
            ->filterLotCatId($lotCatId)
            ->loadEntity();
        return $invoiceCat;
    }
}
