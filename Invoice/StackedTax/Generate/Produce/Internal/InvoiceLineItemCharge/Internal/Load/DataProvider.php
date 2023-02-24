<?php
/**
 * SAM-11061: Stacked Tax. Invoice Management pages. Implement Stacked Tax Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 24, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Produce\Internal\InvoiceLineItemCharge\Internal\Load;

use Invoice;
use InvoiceAdditional;
use InvoiceLineItem;
use LotItemCategory;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Calculate\AdditionalCharge\StackedTaxInvoiceAdditionalChargeCalculator;
use Sam\Invoice\Common\LineItem\Load\InvoiceLineItemLoader;
use Sam\Invoice\Common\Load\InvoiceLoader;
use Sam\Lot\Category\Load\LotCategoryLoader;
use Sam\Storage\ReadRepository\Entity\InvoiceAdditional\InvoiceAdditionalReadRepository;
use Sam\Storage\ReadRepository\Entity\InvoiceAuction\InvoiceAuctionReadRepository;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepository;
use Sam\Storage\ReadRepository\Entity\InvoiceLineItem\InvoiceLineItemReadRepository;
use Sam\Storage\ReadRepository\Entity\LotItemCategory\LotItemCategoryReadRepository;

/**
 * Class DataProvider
 * @package Sam\Invoice\StackedTax\Generate\Produce\Internal\InvoiceLineItemCharge\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function countInvoiceLineItems(int $accountId, bool $isReadOnlyDb = false): int
    {
        return InvoiceLineItemReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->count();
    }

    public function existInvoiceAdditionalByInvoiceId(int $invoiceId, bool $isReadOnlyDb = false): bool
    {
        return InvoiceAdditionalReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceId)
            ->exist();
    }

    public function existInvoiceAdditionalByInvoiceIdAndName(int $invoiceId, string $name, bool $isReadOnlyDb = false): bool
    {
        return InvoiceAdditionalReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceId)
            ->filterName($name)
            ->exist();
    }

    /**
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return LotItemCategory[]
     */
    public function loadLotItemCategories(int $lotItemId, bool $isReadOnlyDb = false): array
    {
        return LotItemCategoryReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotItemId($lotItemId)
            ->loadEntities();
    }

    /**
     * @param int|null $lotCategoryId
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadCategoryWithAncestorIds(?int $lotCategoryId, bool $isReadOnlyDb = false): array
    {
        return LotCategoryLoader::new()->loadCategoryWithAncestorIds($lotCategoryId, $isReadOnlyDb);
    }

    public function loadInvoiceItemRows(int $invoiceId, bool $isReadOnlyDb = false): array
    {
        $select = [
            'ii.lot_item_id',
            'ii.auction_id',
            'ii.hammer_price',
            'ii.lot_name',
            'ii.lot_no',
        ];
        return InvoiceItemReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterInvoiceId($invoiceId)
            // ->joinAccountFilterActive(true)
            // ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            // ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            // ->joinLotItemFilterActive(true)
            // ->joinInvoiceFilterInvoiceStatusId(Constants\Invoice::$availableInvoiceStatuses)
            // ->joinUserWinningBidderFilterUserStatusId(Constants\User::US_ACTIVE)
            ->orderByLotItemId()
            ->select($select)
            ->loadRows();
    }

    public function loadInvoiceLineItemRows(int $accountId, string $auctionType, array $categoryWithAncestorIds, bool $isReadOnlyDb = false): array
    {
        $select = [
            'ili.id',
            'ili.label',
            'ili.amount',
            'ili.auction_type',
            'IF(ili.per_lot, 1,0) AS per_lot',
            'ili.active',
            'ililc.lot_cat_id',
            'ili.break_down',
            'if (ili.percentage, TRUE, FALSE) as percentage',
            'if (ili.leu_of_tax, TRUE, FALSE) as leu_of_tax',
        ];
        return InvoiceLineItemReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->filterAuctionType(['A', $auctionType])
            ->innerJoinInvoiceLineItemLotCatFilterLotCatId($categoryWithAncestorIds)
            ->joinInvoiceLineItemLotCatFilterActive(true)
            ->select($select)
            ->loadRows();
    }

    /**
     * Load invoice line item that has category all
     * @param int $accountId
     * @param string $auctionType
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadInvoiceLineItemRowsForAllCategories(int $accountId, string $auctionType, bool $isReadOnlyDb = false): array
    {
        $select = [
            'ili.id',
            'ili.label',
            'ili.amount',
            'ili.auction_type',
            'IF(ili.per_lot, 1,0) AS per_lot',
            'ili.active',
            'ililc.lot_cat_id',
            'ili.break_down',
            'if (ili.percentage, TRUE, FALSE) as percentage',
            'if (ili.leu_of_tax, TRUE, FALSE) as leu_of_tax',
        ];
        return InvoiceLineItemReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->filterAuctionType(['A', $auctionType])
            ->innerJoinInvoiceLineItemLotCatFilterLotCatId(null)
            ->joinInvoiceLineItemLotCatFilterActive(true)
            ->select($select)
            ->orderById()
            ->groupById()
            ->loadRows();
    }

    /**
     * Load data from "invoice_auction" table indexed by auction id.
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadInvoiceAuctionRows(int $invoiceId, bool $isReadOnlyDb = false): array
    {
        $select = [
            'iauc.auction_id',
            'iauc.auction_type',
        ];
        $invoiceAuctionRows = InvoiceAuctionReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceId)
            ->select($select)
            ->loadRows();
        $invoiceAuctionRows = ArrayHelper::indexRows($invoiceAuctionRows, 'auction_id');
        return $invoiceAuctionRows;
    }

    public function calcAdditionalCharge(
        Invoice $invoice,
        bool $isPerLot,
        bool $isLeuOfTax,
        bool $isPercentage,
        float $lineItemAmount,
        float $itemHammerPrice
    ): float {
        return StackedTaxInvoiceAdditionalChargeCalculator::new()->calcAdditionalCharge(
            $invoice,
            $isPerLot,
            $isLeuOfTax,
            $isPercentage,
            $lineItemAmount,
            $itemHammerPrice
        );
    }

    public function loadInvoiceLineItemByLabelAndAccountId(string $label, int $accountId, bool $isReadOnlyDb = false): ?InvoiceLineItem
    {
        return InvoiceLineItemLoader::new()->loadByLabelAndAccount($label, $accountId, $isReadOnlyDb);
    }

    /**
     * @param int $invoiceId
     * @param string $name
     * @param bool $isReadOnlyDb
     * @return InvoiceAdditional[]
     */
    public function loadInvoiceAdditionalByInvoiceIdAndName(int $invoiceId, string $name, bool $isReadOnlyDb = false): array
    {
        return InvoiceAdditionalReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceId)
            ->filterName($name)
            ->loadEntities();
    }
}
