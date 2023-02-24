<?php
/**
 * SAM-9734: Fix email reminder behavior for the case when last run timestamps are missed
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Reminder\Concrete\Pickup\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\User\Load\UserLoader;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class DataProvider
 * @package ${NAMESPACE}
 */
class DataProvider extends CustomizableClass
{
    use InvoiceLoaderAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadUser(int $bidderId, $isReadOnly): ?\User
    {
        return $this->getUserLoader()->load($bidderId, $isReadOnly);
    }

    public function loadEditorUserId(): int
    {
        return UserLoader::new()->loadSystemUserId();
    }

    public function loadInvoice(int $invoiceId): ?\Invoice
    {
        return $this->getInvoiceLoader()->load($invoiceId);
    }

    public function loadReminderData(): array
    {
        return DataLoader::new()->load();
    }
}
