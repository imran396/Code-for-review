<?php

namespace Sam\Core\Constants;

/**
 * Class Coupon
 * @package Sam\Core\Constants
 */
class Coupon
{
    public const STATUS_ACTIVE = 1;
    public const STATUS_DEACTIVATED = 2;
    public const STATUS_DELETED = 3;
    /** @var int[] */
    public static array $couponStatuses = [self::STATUS_ACTIVE, self::STATUS_DEACTIVATED, self::STATUS_DELETED];
    /** @var int[] */
    public static array $availableCouponStatuses = [self::STATUS_ACTIVE, self::STATUS_DEACTIVATED];

    public const FREE_SHIPPING = 1;
    public const FIXED_AMOUNT = 2;
    public const PERCENTAGE = 3;
    /** @var int[] */
    public static array $types = [self::FREE_SHIPPING, self::FIXED_AMOUNT, self::PERCENTAGE];
    /** @var string[] */
    public static array $typeNames = [
        self::FREE_SHIPPING => 'Free Shipping',
        self::FIXED_AMOUNT => 'Fixed Amount Off',
        self::PERCENTAGE => 'Percentage Off',
    ];
}
