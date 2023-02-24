<?php

namespace Sam\Core\Constants;

/**
 * Class BillingSmartPay
 * @package Sam\Core\Constants
 */
class BillingSmartPay
{
    // SmartPayMerchantMode
    public const MM_AUTOMATIC = 1;
    public const MM_MANUAL = 2;
    /** @var string[] */
    public static array $merchantModeNames = [
        self::MM_AUTOMATIC => 'Automatic',
        self::MM_MANUAL => 'Manual',
    ];
}
