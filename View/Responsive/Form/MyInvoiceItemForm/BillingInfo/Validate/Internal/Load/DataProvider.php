<?php
/**
 * SAM-11027: Stacked Tax. Public My Invoice pages. Save user data before CC charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\MyInvoiceItemForm\BillingInfo\Validate\Internal\Load;

use CreditCard;
use DateTime;
use Invoice;
use Sam\Billing\CreditCard\Load\CreditCardLoader;
use Sam\Billing\CreditCard\Validate\CreditCardValidator;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceLoader;
use Sam\Security\Crypt\BlockCipherProvider;
use Sam\Settings\SettingsManager;
use Sam\User\Load\UserLoader;
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

    public function loadInvoice(int $invoiceId, bool $isReadOnlyDb = false): ?Invoice
    {
        return InvoiceLoader::new()->load($invoiceId, $isReadOnlyDb);
    }

    public function isValidCreditCard(string $ccNumber, ?string $ccTypeName): bool
    {
        return CreditCardValidator::new()->validateNumber($ccNumber, $ccTypeName);
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

    public function loadUserBillingOrCreate(?int $userId, bool $isReadOnlyDb = false): UserBilling
    {
        return UserLoader::new()->loadUserBillingOrCreate($userId, $isReadOnlyDb);
    }

    public function decryptValue(string $value): string
    {
        return BlockCipherProvider::new()->construct()->decrypt($value);
    }

    public function getCardExpDate(string $expMonth, string $expYear): DateTime
    {
        return new DateTime($expYear . '-' . $expMonth . '-01 00:00:00');
    }

    public function loadCreditCard(int $creditCardId, bool $isReadOnlyDb = false): ?CreditCard
    {
        return CreditCardLoader::new()->load($creditCardId, $isReadOnlyDb);
    }

    public function getEwayEncryptionKey(int $accountId): string
    {
        return (string)SettingsManager::new()->get(Constants\Setting::EWAY_ENCRYPTION_KEY, $accountId);
    }

    public function isEwayEnabled(int $accountId): bool
    {
        return (bool)SettingsManager::new()->get(Constants\Setting::CC_PAYMENT_EWAY, $accountId);
    }

    public function isAchPayment(int $accountId): bool
    {
        return (bool)SettingsManager::new()->get(Constants\Setting::ACH_PAYMENT, $accountId);
    }
}
