<?php
/**
 * SAM-11027: Stacked Tax. Public My Invoice pages. Save user data before CC charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\MyInvoiceItemForm\ShippingInfo\Save\Internal\Load;

use Invoice;
use InvoiceUserShipping;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoader;
use Sam\Invoice\Common\Load\InvoiceLoader;
use Sam\User\Load\UserLoader;
use UserShipping;

class DataProvider extends CustomizableClass
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadInvoice(int $invoiceId, bool $isReadOnlyDb = false): ?Invoice
    {
        return InvoiceLoader::new()->load($invoiceId, $isReadOnlyDb);
    }

    public function loadUserShippingOrCreate(int $userId, bool $isReadOnlyDb = false): UserShipping
    {
        return UserLoader::new()->loadUserShippingOrCreate($userId, $isReadOnlyDb);
    }

    public function loadInvoiceUserShippingOrCreate(int $invoiceId, bool $isReadOnlyDb = false): InvoiceUserShipping
    {
        return InvoiceUserLoader::new()->loadInvoiceUserShippingOrCreate($invoiceId, $isReadOnlyDb);
    }
}
