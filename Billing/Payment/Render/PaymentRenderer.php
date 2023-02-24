<?php
/**
 * Helping methods for rendering `payment` fields
 *
 * SAM-4111: Invoice and settlement fields renderers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 21, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Payment\Render;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class PaymentRenderer
 * @package Sam\Billing\Payment\Render
 */
class PaymentRenderer extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use TranslatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $paymentMethod
     * @return string
     */
    public function makePaymentMethod(?int $paymentMethod): string
    {
        return Constants\Payment::$paymentMethodNames[$paymentMethod] ?? '';
    }

    /**
     * @param int|null $paymentMethod
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     */
    public function makePaymentMethodTranslated(?int $paymentMethod, ?int $accountId = null, ?int $languageId = null): string
    {
        $langPaymentMethods = [
            Constants\Payment::PM_CC => 'PAYMENT_METHOD_CREDIT_CARD',
            Constants\Payment::PM_CC_EXTERNALLY => 'PAYMENT_METHOD_CREDIT_CARD',
            Constants\Payment::PM_CC_ON_FILE => 'PAYMENT_METHOD_CREDIT_CARD',
            Constants\Payment::PM_CC_ON_INPUT => 'PAYMENT_METHOD_CREDIT_CARD',
            Constants\Payment::PM_BANK_WIRE => 'PAYMENT_METHOD_BANK_WIRE',
            Constants\Payment::PM_GOOGLE_CHECKOUT => 'PAYMENT_METHOD_GOOGLE_CHECKOUT',
            Constants\Payment::PM_PAYPAL => 'PAYMENT_METHOD_PAYPAL',
            Constants\Payment::PM_OTHER => 'PAYMENT_METHOD_OTHER',
            Constants\Payment::PM_CASH => 'PAYMENT_METHOD_CASH',
            Constants\Payment::PM_CHECK => 'PAYMENT_METHOD_CHECK',
            Constants\Payment::PM_MONEY_ORDER => 'PAYMENT_METHOD_MONEY_ORDER',
            Constants\Payment::PM_CASHIERS_CHECK => 'PAYMENT_METHOD_CASHIERS_CHECK',
            Constants\Payment::PM_CREDIT_MEMO => 'PAYMENT_METHOD_CREDIT_MEMO',
            // Constants\Payment::PM_SMART_PAY => '...',   // non-active
        ];
        $output = isset($langPaymentMethods[$paymentMethod])
            ? $this->getTranslator()->translate($langPaymentMethods[$paymentMethod], 'auctions', $accountId, $languageId)
            : '';
        return $output;
    }

    public function makePaymentMethodTranslatedForAdmin(?int $paymentMethod, string $language, string $domain = 'admin_stacked_tax'): string
    {
        $langKeys = [
            Constants\Payment::PM_BANK_WIRE => 'payment.method.bank_wire.label',
            Constants\Payment::PM_GOOGLE_CHECKOUT => 'payment.method.google_checkout.label',
            Constants\Payment::PM_PAYPAL => 'payment.method.paypal.label',
            Constants\Payment::PM_OTHER => 'payment.method.other.label',
            Constants\Payment::PM_CASH => 'payment.method.cash.label',
            Constants\Payment::PM_CHECK => 'payment.method.check.label',
            Constants\Payment::PM_MONEY_ORDER => 'payment.method.money_order.label',
            Constants\Payment::PM_CASHIERS_CHECK => 'payment.method.cashiers_check.label',
            Constants\Payment::PM_CREDIT_MEMO => 'payment.method.credit_memo',
            Constants\Payment::PM_CC_ON_INPUT => 'payment.method.credit_card_on_input',
            Constants\Payment::PM_CC_ON_FILE => 'payment.method.credit_card_on_file',
            Constants\Payment::PM_CC_EXTERNALLY => 'payment.method.credit_card_externally',
            //Constants\Payment::PM_SMART_PAY => 'payment.method.smart_pay', //non-active
        ];
        if (!isset($langKeys[$paymentMethod])) {
            return '';
        }

        return $this->getAdminTranslator()->trans($langKeys[$paymentMethod], [], $domain, $language);
    }
}
