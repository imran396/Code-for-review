<?php
/**
 * SAM-10915: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Authorize.Net invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\AuthorizeNet\Internal\OtherCcCharge\Internal\Load;

use InvoiceUserBilling;
use Payment_AuthorizeNet;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoader;
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

    public function createAuthNetManager(int $accountId): Payment_AuthorizeNet
    {
        return new Payment_AuthorizeNet($accountId);
    }

    public function loadInvoiceUserBillingOrCreate(?int $invoiceId, bool $isReadOnlyDb = false): InvoiceUserBilling
    {
        return InvoiceUserLoader::new()->loadInvoiceUserBillingOrCreate($invoiceId, $isReadOnlyDb);
    }
}
