<?php
/**
 * SAM-10974: Stacked Tax. Public My Invoice pages. Extract NMI invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\Nmi\Charge\Internal\Load;

use InvoiceUserBilling;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManager;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoader;
use Sam\Security\Crypt\BlockCipherProvider;
use Sam\Settings\SettingsManager;
use Sam\User\Load\UserLoader;
use Sam\User\Validate\UserExistenceChecker;
use User;
use UserBilling;

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

    public function existUser(?int $userId, bool $isReadOnlyDb = false): bool
    {
        return UserExistenceChecker::new()->existById($userId, $isReadOnlyDb);
    }

    public function loadUser(?int $userId, bool $isReadOnlyDb = false): ?User
    {
        return UserLoader::new()->load($userId, $isReadOnlyDb);
    }

    public function buildCcSurcharge($creditCardId, $amount, $accountId): array
    {
        return InvoiceAdditionalChargeManager::new()->buildCcSurcharge($creditCardId, $amount, $accountId);
    }

    public function isNmiVault(int $accountId): bool
    {
        return (bool)SettingsManager::new()->get(Constants\Setting::NMI_VAULT, $accountId);
    }

    public function loadUserBillingOrCreate(?int $userId, bool $isReadOnlyDb = false): UserBilling
    {
        return UserLoader::new()->loadUserBillingOrCreate($userId, $isReadOnlyDb);
    }

    public function loadInvoiceUserBillingOrCreate(int $invoiceId, bool $isReadOnlyDb = false): InvoiceUserBilling
    {
        return InvoiceUserLoader::new()->loadInvoiceUserBillingOrCreate($invoiceId, $isReadOnlyDb);
    }

    public function decryptValue(string $value): string
    {
        return BlockCipherProvider::new()->construct()->decrypt($value);
    }
}
