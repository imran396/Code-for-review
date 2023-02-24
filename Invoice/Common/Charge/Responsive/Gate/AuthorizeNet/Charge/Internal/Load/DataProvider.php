<?php
/**
 * SAM-10971: Stacked Tax. Public My Invoice pages. Extract Authorize.net invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\AuthorizeNet\Charge\Internal\Load;

use CreditCard;
use Sam\Billing\CreditCard\Load\CreditCardLoader;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Security\Crypt\BlockCipherProvider;
use Sam\Settings\SettingsManager;
use Sam\User\Load\UserLoader;
use Sam\User\Validate\UserExistenceChecker;
use User;
use UserBilling;

class DataProvider extends CustomizableClass
{

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

    public function decryptValue(string $value): string
    {
        return BlockCipherProvider::new()->construct()->decrypt($value);
    }

    public function loadCreditCard(int $creditCardId, bool $isReadOnlyDb = false): ?CreditCard
    {
        return CreditCardLoader::new()->load($creditCardId, $isReadOnlyDb);
    }

    public function isAuthNetCim(int $accountId): bool
    {
        return (bool)SettingsManager::new()->get(Constants\Setting::AUTH_NET_CIM, $accountId);
    }
}
