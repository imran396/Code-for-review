<?php
/**
 * Stacked Tax. Admin - Add to Stacked Tax Payment page (Invoice) the functionality from Pay Invoice page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Opayo\Internal\Update\Internal\Load;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Common\AdminStackedTaxInvoicePaymentChargingHelper;
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
        return AdminStackedTaxInvoicePaymentChargingHelper::new()->getParams(
            $invoice,
            $ccNumber,
            $ccType,
            $expMonth,
            $expYear,
            $cvv
        );
    }
}
