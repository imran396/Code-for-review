<?php
/**
 * SAM-11027: Stacked Tax. Public My Invoice pages. Save user data before CC charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 31, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\MyInvoiceItemForm\BillingInfo\Save\Internal\Load;

use Invoice;
use InvoiceUserBilling;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoader;
use Sam\Invoice\Common\Load\InvoiceLoader;
use Sam\Security\Crypt\BlockCipherProvider;
use Sam\Settings\SettingsManager;
use Sam\User\Load\UserLoader;
use UserBilling;

class DataProvider extends CustomizableClass
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadUserBillingOrCreate(?int $userId, bool $isReadOnlyDb = false): UserBilling
    {
        return UserLoader::new()->loadUserBillingOrCreate($userId, $isReadOnlyDb);
    }

    public function loadInvoiceUserBillingOrCreate(int $invoiceId, bool $isReadOnlyDb = false): InvoiceUserBilling
    {
        return InvoiceUserLoader::new()->loadInvoiceUserBillingOrCreate($invoiceId, $isReadOnlyDb);
    }

    public function loadInvoice(int $invoiceId, bool $isReadOnlyDb = false): ?Invoice
    {
        return InvoiceLoader::new()->load($invoiceId, $isReadOnlyDb);
    }

    public function decrypt(string $value): string
    {
        return BlockCipherProvider::new()->construct()->decrypt($value);
    }

    public function encrypt(string $value): string
    {
        return BlockCipherProvider::new()->construct()->encrypt($value);
    }

    public function isAuthNetCim(int $accountId): bool
    {
        return (bool)SettingsManager::new()->get(Constants\Setting::AUTH_NET_CIM, $accountId);
    }

    public function isPayTraceCim(int $accountId): bool
    {
        return (bool)SettingsManager::new()->get(Constants\Setting::PAY_TRACE_CIM, $accountId);
    }

    public function isNmiVault(int $accountId): bool
    {
        return (bool)SettingsManager::new()->get(Constants\Setting::NMI_VAULT, $accountId);
    }

    public function isOpayoToken(int $accountId): bool
    {
        return (bool)SettingsManager::new()->get(Constants\Setting::OPAYO_TOKEN, $accountId);
    }

    public function getEwayEncryptionKey(int $accountId): string
    {
        return (string)SettingsManager::new()->get(Constants\Setting::EWAY_ENCRYPTION_KEY, $accountId);
    }

    public function isEwayEnabled(int $accountId): bool
    {
        return (bool)SettingsManager::new()->get(Constants\Setting::CC_PAYMENT_EWAY, $accountId);
    }
}
