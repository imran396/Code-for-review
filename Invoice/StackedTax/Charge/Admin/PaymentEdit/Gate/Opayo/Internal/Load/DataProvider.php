<?php
/**
 * Stacked Tax. Admin - Add to Stacked Tax Payment page (Invoice) the functionality from Pay Invoice page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Opayo\Internal\Load;

use CreditCard;
use Invoice;
use InvoiceUserBilling;
use Sam\Billing\CreditCard\Load\CreditCardLoader;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoader;
use Sam\Invoice\Common\Calculate\Basic\AnyInvoiceCalculator;
use Sam\Invoice\Common\Charge\Common\Total\InvoiceTotalsUpdater;
use Sam\Security\Crypt\BlockCipherProvider;
use Sam\Settings\SettingsManager;
use Sam\User\Load\UserLoader;
use Sam\User\Validate\UserBillingChecker;
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

    public function makeFullName(string $firstName, string $lastName): string
    {
        return UserPureRenderer::new()->makeFullName($firstName, $lastName);
    }

    public function loadCreditCard(?int $creditCardId, bool $isReadOnlyDb = false): ?CreditCard
    {
        return CreditCardLoader::new()->load($creditCardId, $isReadOnlyDb);
    }

    public function calculateBalanceDue(Invoice $invoice): float
    {
        return AnyInvoiceCalculator::new()->calcRoundedBalanceDue($invoice);
    }

    public function calculateAndAssign(Invoice $invoice, bool $isReadOnlyDb = false): Invoice
    {
        return InvoiceTotalsUpdater::new()->calcAndAssign($invoice, $isReadOnlyDb);
    }

    public function isCcInfoExists(UserBilling $userBilling): bool
    {
        return UserBillingChecker::new()->isCcInfoExists($userBilling);
    }

    public function isOpayoToken(int $accountId): bool
    {
        return (bool)SettingsManager::new()->get(Constants\Setting::OPAYO_TOKEN, $accountId);
    }

    public function loadUserBillingOrCreate(?int $userId, bool $isReadOnlyDb = false): UserBilling
    {
        return UserLoader::new()->loadUserBillingOrCreate($userId, $isReadOnlyDb);
    }

    public function loadInvoiceBillingOrCreate(?int $invoiceId, bool $isReadOnlyDb = false): InvoiceUserBilling
    {
        return InvoiceUserLoader::new()->loadInvoiceUserBillingOrCreate($invoiceId, $isReadOnlyDb);
    }

    public function decryptValue(string $value): string
    {
        return BlockCipherProvider::new()->construct()->decrypt($value);
    }

}
