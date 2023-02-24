<?php
/**
 * SAM-10997: Stacked Tax. New Invoice Edit page: Goods section (Invoice Items)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 22, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\View\Common\Goods\Load;

use LotItemCustData;
use LotItemCustField;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItemCustData\LotItemCustDataReadRepositoryCreateTrait;

/**
 * Class InvoiceItemDataLoader
 * @package Sam\Invoice\StackedTax\View\Common\Goods\Load
 */
class InvoiceItemDataLoader extends CustomizableClass
{
    use InvoiceItemReadRepositoryCreateTrait;
    use LotItemCustDataReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load invoice items and related data, and fill result DTO object.
     * @param int $invoiceId
     * @param LotItemCustField[] $lotCustomFields
     * @param bool $isReadOnlyDb
     * @return InvoiceItemData[]
     */
    public function loadForInvoice(int $invoiceId, array $lotCustomFields, bool $isReadOnlyDb = false): array
    {
        /**
         * 'ali.lot_status_id' - we are interested in actual value of AuctionLotItem->LotStatusId (not snapshot),
         * because we have to make decision what page render link to: either to Auction Lot Edit page, or to Inventory Edit page.
         */
        $select = [
            'ali.lot_status_id',
            'i.account_id',
            'i.tax_country',
            'iauc.auction_type',
            'iauc.bidder_num',
            'iauc.event_type',
            'iauc.name',
            'iauc.sale_date',
            'iauc.sale_no',
            'iauc.timezone_location',
            'ii.*',
        ];
        $rows = $this->createInvoiceItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->extendJoinCondition('auction_lot_item', 'AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ')')
            ->filterActive(true)
            ->filterInvoiceId($invoiceId)
            ->joinAuctionLotItem()
            ->joinInvoiceFilterInvoiceStatusId(Constants\Invoice::$availableInvoiceStatuses)
            ->joinHpTaxSchemaActive()
            ->joinBpTaxSchemaActive()
            ->joinInvoiceAuctionOrderBySaleNo() // primary ordering
            ->orderByLotNo() // secondary ordering
            ->select($select)
            ->loadRows();

        $lotItemIds = array_map(static fn(array $row) => $row['lot_item_id'], $rows);
        $lotsCustomFieldsData = $this->loadCustomFieldsData($lotCustomFields, $lotItemIds, $isReadOnlyDb);
        $lotCustomFieldsDataByLotItemId = [];
        foreach ($lotsCustomFieldsData as $lotCustomFieldData) {
            $lotCustomFieldsDataByLotItemId[$lotCustomFieldData->LotItemId][$lotCustomFieldData->LotItemCustFieldId] = $lotCustomFieldData;
        }

        return array_map(
            fn(array $row) => InvoiceItemData::new()->construct($row, $lotCustomFieldsDataByLotItemId[$row['lot_item_id']] ?? []),
            $rows
        );
    }

    /**
     * @param LotItemCustField[] $lotCustomFields
     * @return LotItemCustData[]
     */
    protected function loadCustomFieldsData(array $lotCustomFields, array $lotItemIds, bool $isReadOnlyDb = false): array
    {
        $lotCustomFieldIds = ArrayHelper::toArrayByProperty($lotCustomFields, 'Id');
        return $this->createLotItemCustDataReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterLotItemCustFieldId($lotCustomFieldIds)
            ->filterLotItemId($lotItemIds)
            ->loadEntities();
    }
}
