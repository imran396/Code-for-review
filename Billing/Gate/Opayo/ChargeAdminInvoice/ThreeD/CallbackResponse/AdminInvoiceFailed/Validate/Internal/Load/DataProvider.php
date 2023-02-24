<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Sept 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceFailed\Validate\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Validate\InvoiceExistenceChecker;
use Sam\User\Validate\UserExistenceChecker;


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

    public function existInvoiceById(int $invoiceId, bool $isReadOnlyDb = false): bool
    {
        return InvoiceExistenceChecker::new()->existById($invoiceId, $isReadOnlyDb);
    }

    public function existUserById(?int $userId, bool $isReadOnlyDb = false): bool
    {
        return UserExistenceChecker::new()->existById($userId, $isReadOnlyDb);
    }
}
