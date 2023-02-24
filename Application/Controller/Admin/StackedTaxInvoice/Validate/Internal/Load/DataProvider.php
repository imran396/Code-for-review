<?php
/**
 * SAM-6792 : Validations at controller layer for v3.5 - InvoiceControllerValidator at responsive and admin sites
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           5/01/2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\StackedTaxInvoice\Validate\Internal\Load;

use Invoice;
use Sam\Account\Validate\AccountExistenceChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceLoader;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;

/**
 * Class DataProvider
 * @package Sam\Application\Controller\Admin\StackedTaxInvoice\Validate\Internal\Load
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

    /**
     * @param int|null $invoiceId
     * @param bool $isReadOnlyDb
     * @return Invoice|null
     */
    public function loadInvoice(?int $invoiceId, bool $isReadOnlyDb): ?Invoice
    {
        return InvoiceLoader::new()->load($invoiceId, $isReadOnlyDb);
    }

    /**
     * @param int $userId
     * @return bool
     */
    public function hasPrivilegeForManageInvoices(int $userId): bool
    {
        return AdminPrivilegeChecker::new()
            ->initByUserId($userId)
            ->hasPrivilegeForManageInvoices();
    }

    /**
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isInvoiceAccountExist(int $accountId, bool $isReadOnlyDb): bool
    {
        return AccountExistenceChecker::new()->existById($accountId, $isReadOnlyDb);
    }
}
