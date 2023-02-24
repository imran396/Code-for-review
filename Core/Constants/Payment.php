<?php

namespace Sam\Core\Constants;

/**
 * Class Payment
 * @package Sam\Core\Constants
 */
class Payment
{
    // Transaction type (payment.tran_type)
    public const TT_INVOICE = 'I';
    public const TT_SETTLEMENT = 'P';
    public const TT_PENDING = 'T';

    // payment.payment_method_id
    public const PM_CC = 1;
    public const PM_BANK_WIRE = 2;
    public const PM_GOOGLE_CHECKOUT = 3;
    public const PM_PAYPAL = 4;
    public const PM_OTHER = 5;
    public const PM_CASH = 6;
    public const PM_CHECK = 7;
    public const PM_MONEY_ORDER = 8;
    public const PM_CASHIERS_CHECK = 9;
    public const PM_CREDIT_MEMO = 10;
    public const PM_SMART_PAY = 11;
    public const PM_CC_ON_INPUT = 12;
    public const PM_CC_ON_FILE = 13;
    public const PM_CC_EXTERNALLY = 14;

    /**
     * Represents list of payment methods that are available for stacked taxes invoicing (self::PM_CC replaced with self::PM_CC_ON_FILE, self::PM_CC_EXTERNALLY)
     * @var int[]
     */
    public static array $paymentMethods = [
        self::PM_CC_ON_INPUT,
        self::PM_CC_ON_FILE,
        self::PM_CC_EXTERNALLY,
        self::PM_BANK_WIRE,
        self::PM_GOOGLE_CHECKOUT,
        self::PM_PAYPAL,
        self::PM_OTHER,
        self::PM_CASH,
        self::PM_CHECK,
        self::PM_MONEY_ORDER,
        self::PM_CASHIERS_CHECK,
        self::PM_CREDIT_MEMO,
    ];

    /**
     * Represents list of payment methods for the legacy invoicing and settlement with hard-coded names.
     * @var string[]
     */
    public static array $paymentMethodNames = [
        self::PM_CC => 'Credit Card',
        self::PM_BANK_WIRE => 'Bank Wire',
        self::PM_GOOGLE_CHECKOUT => 'Google Checkout',
        self::PM_PAYPAL => 'Paypal',
        self::PM_OTHER => 'Other',
        self::PM_CASH => 'Cash',
        self::PM_CHECK => 'Check',
        self::PM_MONEY_ORDER => 'Money order',
        self::PM_CASHIERS_CHECK => 'Cashiers check',
        self::PM_CREDIT_MEMO => 'Credit Memo',
        // self::PM_SMART_PAY => 'Smart Pay',   // non-active
    ];

    public const CC_PAYMENT_METHODS = [
        self::PM_CC,
        self::PM_CC_ON_INPUT,
        self::PM_CC_ON_FILE,
        self::PM_CC_EXTERNALLY
    ];
    /**
     * Invoice approved Payment Methods.
     * invoice_payment_method.payment_method_id
     */
    public const IPM_PAYPAL = 1;
    public const IPM_CC = 2;
    public const IPM_ACH = 3;
    public const IPM_SMART_PAY = 11;

    public const INVOICE_PAYMENT_METHOD_NAMES = [
        self::IPM_PAYPAL => 'Paypal',
        self::IPM_CC => 'Credit Card',
        self::IPM_ACH => 'ACH',
        self::IPM_SMART_PAY => 'SmartPay'
    ];

    public const PAYMENT_NOTE_DEF = 'note';
}
