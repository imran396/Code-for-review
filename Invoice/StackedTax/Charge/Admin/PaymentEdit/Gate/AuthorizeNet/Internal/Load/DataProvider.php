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

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\Internal\Load;

use Invoice;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Calculate\Basic\AnyInvoiceCalculator;
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

    public function loadUserBillingOrCreate(?int $userId, bool $isReadOnlyDb = false): UserBilling
    {
        return UserLoader::new()->loadUserBillingOrCreate($userId, $isReadOnlyDb);
    }

    public function isAuthNetCim(int $accountId): bool
    {
        return (bool)SettingsManager::new()->get(Constants\Setting::AUTH_NET_CIM, $accountId);
    }

    public function decryptValue(string $value): string
    {
        return BlockCipherProvider::new()->construct()->decrypt($value);
    }

    public function calculateBalanceDue(Invoice $invoice): float
    {
        return AnyInvoiceCalculator::new()->calcRoundedBalanceDue($invoice);
    }
}
