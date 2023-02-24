<?php
/**
 * SAM-3694:Invoice related repositories  https://bidpath.atlassian.net/browse/SAM-3694
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           19 August, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of InvoiceLineItem filtered by criteria
 * $invoiceLineItemRepository = \Sam\Storage\ReadRepository\Entity\InvoiceLineItem\InvoiceLineItemReadRepository::new()
 *     ->filterId($ids);   // array passed as argument
 *
 * $isFound = $invoiceLineItemRepository->exist();
 * $count = $invoiceLineItemRepository->count();
 * $invoiceLineItems = $invoiceLineItemRepository->loadEntities();
 *
 * // Sample2. Load single InvoiceLineItem
 * $invoiceLineItemRepository = \Sam\Storage\ReadRepository\Entity\InvoiceLineItem\InvoiceLineItemReadRepository::new()
 *     ->filterId(1);
 * $invoiceLineItem = $invoiceLineItemRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\InvoiceLineItem;

/**
 * Class InvoiceLineItemReadRepository
 * @package Sam\Storage\ReadRepository\Entity\InvoiceLineItem
 */
class InvoiceLineItemReadRepository extends AbstractInvoiceLineItemReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'invoice_line_item_lot_cat' => 'JOIN invoice_line_item_lot_cat AS ililc ON ili.id = ililc.invoice_line_id',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Define filtering by ililc.active
     * @param bool|bool[] $invoiceLineItemLotCatActive
     * @return static
     */
    public function joinInvoiceLineItemLotCatFilterActive(bool|array|null $invoiceLineItemLotCatActive): static
    {
        $this->join('invoice_line_item_lot_cat');
        $this->filterArray('ililc.active', $invoiceLineItemLotCatActive);
        return $this;
    }

    /**
     * Define filtering by ililc.lot_cat_id
     * @param int|int[] $invoiceLineItemLotCatLotCatId
     * @return static
     */
    public function innerJoinInvoiceLineItemLotCatFilterLotCatId(int|array|null $invoiceLineItemLotCatLotCatId): static
    {
        $this->innerJoin('invoice_line_item_lot_cat');
        $this->filterArray('ililc.lot_cat_id', $invoiceLineItemLotCatLotCatId);
        return $this;
    }
}
