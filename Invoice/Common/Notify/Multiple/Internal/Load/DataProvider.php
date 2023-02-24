<?php
/**
 * SAM-11778: Refactor Invoice Notifier for v4.0
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 24, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Notify\Multiple\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Storage\ReadRepository\Entity\Invoice\InvoiceReadRepository;

/**
 * Class DataProvider
 * @package Sam\Invoice\Common\Notify\Multiple\Internal\Load
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

    public function loadInvoiceRow(int $invoiceId, bool $isReadOnlyDb = false): array
    {
        return InvoiceReadRepository::new()
            ->select([
                'i.invoice_no',
                'i.tax_designation',
                'i.bidder_id',
                'u.username'
            ])
            ->filterId($invoiceId)
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceStatusId(Constants\Invoice::$availableInvoiceStatuses)
            ->joinAccountFilterActive(true)
            ->joinUser()
            ->loadRow();
    }
}
