<?php
/**
 * SAM-7978 : Decouple invoice merging service and apply unit tests
 * https://bidpath.atlassian.net/browse/SAM-7978
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Merge\Save\Internal\Load;

use InvoiceAdditional;
use InvoiceAuction;
use InvoiceItem;
use Payment;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\InvoiceNo\InvoiceNoAdviser;
use Sam\Invoice\Common\Payment\InvoicePaymentMethodManager;
use Sam\Storage\ReadRepository\Entity\InvoiceAdditional\InvoiceAdditionalReadRepository;
use Sam\Storage\ReadRepository\Entity\InvoiceAuction\InvoiceAuctionReadRepository;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepository;
use Sam\Storage\ReadRepository\Entity\Payment\PaymentReadRepository;

/**
 * Class DataProvider
 * @package Sam\Invoice\Legacy\Merge\Save\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load invoice Items
     * @param int[] $invoiceIds
     * @param bool $isReadOnlyDb
     * @return InvoiceItem[]
     */
    public function loadInvoiceItems(array $invoiceIds, bool $isReadOnlyDb = false): array
    {
        return InvoiceItemReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->joinInvoiceFilterInvoiceStatusId(Constants\Invoice::$availableInvoiceStatuses)
            ->extendJoinCondition('auction_lot_item', 'AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ')')
            ->filterInvoiceId($invoiceIds)
            ->joinAuctionOrderBySaleNo() // primary ordering
            ->joinAuctionLotItemOrderByLotNo() // secondary ordering
            ->loadEntities();
    }

    /**
     * Load invoice auctions
     * @param int[] $invoiceIds
     * @param bool $isReadOnlyDb
     * @return InvoiceAuction[]
     */
    public function loadInvoiceAuctions(array $invoiceIds, bool $isReadOnlyDb = false): array
    {
        return InvoiceAuctionReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceIds)
            ->loadEntities();
    }

    /**
     * Load invoice additional
     * @param int[] $invoiceIds
     * @param bool $isReadOnlyDb
     * @return InvoiceAdditional[]
     */
    public function loadInvoiceAdditionals(array $invoiceIds, bool $isReadOnlyDb = false): array
    {
        return InvoiceAdditionalReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceIds)
            ->loadEntities();
    }

    /**
     * Load payments
     * @param int[] $invoiceIds
     * @param bool $isReadOnlyDb
     * @return Payment[]
     */
    public function loadPayments(array $invoiceIds, bool $isReadOnlyDb = false): array
    {
        return PaymentReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterTranId($invoiceIds)
            ->filterTranType(Constants\Payment::TT_INVOICE)
            ->loadEntities();
    }

    public function loadPaymentMethods(int $accountId): array
    {
        //Do we need to change static method to class instance method?
        return InvoicePaymentMethodManager::new()->detectApprovedPaymentMethods($accountId);
    }

    public function suggestInvoiceNo(int $accountId, bool $isReadOnlyDb = false): int
    {
        return InvoiceNoAdviser::new()->suggest($accountId, $isReadOnlyDb);
    }
}

