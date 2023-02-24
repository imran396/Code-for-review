<?php
/**
 * SAM-11091: Stacked Tax. New Invoice Edit page: Invoice Item Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemEditForm\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepositoryCreateTrait;

/**
 * Class InvoiceItemDataLoader
 * @package Sam\View\Admin\Form\InvoiceItemEditForm\Load
 */
class InvoiceItemDataLoader extends CustomizableClass
{
    use InvoiceItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load invoice item and related data, and fill result DTO object.
     * @param int $invoiceItemId
     * @param bool $isReadOnlyDb
     * @return InvoiceItemData|null
     */
    public function load(int $invoiceItemId, bool $isReadOnlyDb = false): ?InvoiceItemData
    {
        $select = [
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
        $row = $this->createInvoiceItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($invoiceItemId)
            ->joinInvoiceAuction()
            ->joinInvoiceFilterInvoiceStatusId(Constants\Invoice::$availableInvoiceStatuses)
            ->select($select)
            ->loadRow();
        if ($row) {
            return InvoiceItemData::new()->fromDbRow($row);
        }
        return null;
    }
}
