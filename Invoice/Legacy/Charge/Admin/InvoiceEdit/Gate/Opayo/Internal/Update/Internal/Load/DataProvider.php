<?php
/**
 * SAM-10913: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Opayo invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 14, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Opayo\Internal\Update\Internal\Load;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Common\AdminInvoiceEditChargingHelper;
use Sam\Invoice\Common\Load\InvoiceLoader;
use Sam\User\Load\UserLoader;
use User;

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

    public function loadUser(?int $userId, bool $isReadOnlyDb = false): ?User
    {
        return UserLoader::new()->load($userId, $isReadOnlyDb);
    }

    public function loadInvoice(int $invoiceId, bool $isReadOnlyDb = false): ?Invoice
    {
        return InvoiceLoader::new()->load($invoiceId, $isReadOnlyDb);
    }

    public function getParams(
        Invoice $invoice,
        string $ccNumber,
        int $ccType,
        string $expMonth,
        string $expYear,
        string $cvv,
    ): array {
        return AdminInvoiceEditChargingHelper::new()->getParams(
            $invoice,
            $ccNumber,
            $ccType,
            $expMonth,
            $expYear,
            $cvv
        );
    }
}
