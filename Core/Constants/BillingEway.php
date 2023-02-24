<?php

namespace Sam\Core\Constants;

/**
 * Class BillingEway
 * @package Sam\Core\Constants
 */
class BillingEway
{
    public const ACC_TYPE_DEVELOPER = 'D';
    public const ACC_TYPE_MERCHANT = 'P';
    /** @var string[] */
    public static array $accountTypeNames = [
        self::ACC_TYPE_DEVELOPER => 'Sandbox',
        self::ACC_TYPE_MERCHANT => 'Live',
    ];

    public const P_CC_NUMBER_EWAY = 'CCNumberEway';
}
