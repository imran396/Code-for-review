<?php
/**
 * SAM-11778: Refactor Invoice Notifier for v4.0
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 21, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Notify\Single\Internal\Validate\Internal\Load;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceLoader;
use Sam\User\Load\UserLoader;
use User;

/**
 * Class DataProvider
 * @package Sam\Invoice\Common\Notify\Single\Internal\Validate\Internal\Load
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

    public function loadInvoice(int $invoiceId, bool $isReadOnlyDb): ?Invoice
    {
        return InvoiceLoader::new()->load($invoiceId, $isReadOnlyDb);
    }

    public function loadUser(int $userId, bool $isReadOnlyDb): ?User
    {
        return UserLoader::new()->load($userId, $isReadOnlyDb);
    }
}
