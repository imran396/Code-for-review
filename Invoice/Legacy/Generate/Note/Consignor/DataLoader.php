<?php
/**
 * SAM-4377: Invoice producer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           15.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */


namespace Sam\Invoice\Legacy\Generate\Note\Consignor;

use InvoiceItem;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepositoryCreateTrait;

/**
 * Class DataLoader
 * @package Sam\Invoice\Legacy\Generate\Note\Consignor
 */
class DataLoader extends CustomizableClass
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
     * @param int $invoiceId
     * @return InvoiceItem[]
     */
    public function load(int $invoiceId): array
    {
        return $this->createInvoiceItemReadRepository()
            ->filterInvoiceId($invoiceId)
            ->joinUserByLotItemConsignor()
            ->joinConsignorByLotItemConsignor()
            ->select(
                [
                    'ii.id',
                    'ii.lot_name',
                    'ii.auction_id',
                    'ii.hammer_price',
                    'ii.sales_tax',
                    'li.account_id',
                    'li.id as lot_item_id',
                    'li.consignor_id',
                    'u_by_li_cons.username as consignor_username',
                    'cons_by_li_cons.payment_info as consignor_payment_info',
                ]
            )
            ->loadRows();
    }
}
