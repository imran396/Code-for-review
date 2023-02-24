<?php
/**
 * SAM-10997: Stacked Tax. New Invoice Edit page: Goods section (Invoice Items)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\DeleteLot\Internal\Load;

use Invoice;
use InvoiceItem;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Invoice\InvoiceReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepositoryCreateTrait;
use Sam\Core\Constants;

/**
 * Class DataProvider
 * @package Sam\Invoice\StackedTax\DeleteLot\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use InvoiceItemReadRepositoryCreateTrait;
    use InvoiceReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return InvoiceItem[]
     */
    public function loadInvoiceItemsByLotItemId(int $lotItemId, bool $isReadOnlyDb = false): array
    {
        return $this->createInvoiceItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterLotItemId($lotItemId)
            ->loadEntities();
    }

    /**
     * @return Invoice[]
     */
    public function findEmptyInvoices(array $invoiceIds, bool $isReadOnlyDb = false): array
    {
        $emptyInvoices = $this->createInvoiceReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($invoiceIds)
            ->filterInvoiceStatusId(Constants\Invoice::$availableInvoiceStatuses)
            ->joinAccountFilterActive(true)
            ->inlineCondition('(SELECT COUNT(1) FROM invoice_item WHERE invoice_id = i.id AND active = true) = 0')
            ->loadEntities();
        return $emptyInvoices;
    }
}
