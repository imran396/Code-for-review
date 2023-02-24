<?php
/**
 * SAM-9966: Optimize db queries for Public/Admin Invoice List/Edit
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 31, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Calculate\Tax\Internal\Load;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;

/**
 * Class DataProvider
 * @package ${NAMESPACE}
 */
class DataProvider extends CustomizableClass
{
    use InvoiceLoaderAwareTrait;
    use InvoiceItemLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadInvoiceById($invoiceId, $isReadOnlyDb): ?Invoice
    {
        return $this->getInvoiceLoader()->load($invoiceId, $isReadOnlyDb);
    }

    public function loadInvoiceItemSelectedByInvoiceId($invoiceId, $isReadOnlyDb): array
    {
        return $this->getInvoiceItemLoader()->loadSelectedByInvoiceId(
            [
                'ii.hammer_price',
                'ii.buyers_premium',
                'ii.sales_tax',
                'ii.tax_application',
            ],
            $invoiceId,
            $isReadOnlyDb
        );
    }
}
