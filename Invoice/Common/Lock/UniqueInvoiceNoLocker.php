<?php
/**
 * SAM-10616: Supply uniqueness of invoice fields: invoice#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Lock;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Lock\Internal\Detect\UniqueInvoiceNoLockRequirementsCheckerCreateTrait;
use Sam\Storage\Lock\DbLockerCreateTrait;

/**
 * Class UniqueInvoiceNoLocker
 * @package Sam\Invoice\Legacy\Generate\Lock
 */
class UniqueInvoiceNoLocker extends CustomizableClass
{
    use DbLockerCreateTrait;
    use UniqueInvoiceNoLockRequirementsCheckerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function lock(?int $invoiceId, int|string $invoiceNoInput, int $accountId): bool
    {
        $requirementCheckingResult = $this->createUniqueInvoiceNoLockRequirementsChecker()->check($invoiceId, $invoiceNoInput);
        if ($requirementCheckingResult->mustLock()) {
            log_debug($requirementCheckingResult->message());
            return $this->acquireLock($accountId);
        }

        log_trace($requirementCheckingResult->message());
        return true;
    }

    public function unlock(int $accountId): bool
    {
        $lockName = $this->makeLockName($accountId);
        $isUnlocked = $this->createDbLocker()->releaseLock($lockName);
        return $isUnlocked;
    }

    protected function acquireLock(int $accountId): bool
    {
        $lockName = $this->makeLockName($accountId);
        $isLocked = $this->createDbLocker()->getLock($lockName);
        return $isLocked;
    }

    protected function makeLockName(int $accountId): string
    {
        return sprintf(Constants\DbLock::INVOICE_BY_ACCOUNT_ID_TPL, $accountId);
    }
}
