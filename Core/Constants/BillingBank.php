<?php

namespace Sam\Core\Constants;

/**
 * Class BillingBank
 * @package Sam\Core\Constants
 */
class BillingBank
{
    // Billing Bank Account Type

    public const AT_CHECKING = 'C';
    public const AT_BUSINESS_CHECKING = 'B';
    public const AT_SAVINGS = 'S';

    /** @var string[] */
    public const ACCOUNT_TYPES = [self::AT_CHECKING, self::AT_BUSINESS_CHECKING, self::AT_SAVINGS];

    /** @var string[] */
    public const ACCOUNT_TYPE_NAMES = [
        self::AT_CHECKING => 'Checking',
        self::AT_BUSINESS_CHECKING => 'Business Checking',
        self::AT_SAVINGS => 'Savings',
    ];

    /** @var string[] */
    public const ACCOUNT_TYPE_NMI_VALUES = [
        self::AT_CHECKING => 'checking',
        self::AT_BUSINESS_CHECKING => 'businesschecking',
        self::AT_SAVINGS => 'savings',
    ];

    /** @var string[] */
    public const ACCOUNT_TYPE_AUTH_NET_VALUES = [
        self::AT_CHECKING => 'CHECKING',
        self::AT_BUSINESS_CHECKING => 'BUSINESSCHECKING',
        self::AT_SAVINGS => 'SAVINGS',
    ];

    /** @var string[] */
    public const ACCOUNT_TYPE_SOAP_VALUES = [
        self::AT_CHECKING => 'CHECKING',
        self::AT_BUSINESS_CHECKING => 'BUSINESSCHECKING',
        self::AT_SAVINGS => 'SAVINGS',
    ];

    // Billing Bank Account Holder Type

    public const AHT_BUSINESS = 'B';
    public const AHT_PERSONAL = 'P';

    /** @var string[] */
    public const ACCOUNT_HOLDER_TYPE_NAMES = [
        self::AHT_BUSINESS => 'Business',
        self::AHT_PERSONAL => 'Personal',
    ];

    /** @var string[] */
    public const ACCOUNT_HOLDER_TYPE_NMI_VALUES = [
        self::AHT_BUSINESS => 'business',
        self::AHT_PERSONAL => 'personal',
    ];
}
