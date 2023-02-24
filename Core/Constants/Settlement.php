<?php

namespace Sam\Core\Constants;

/**
 * Class Settlement
 * @package Sam\Core\Constants
 */
class Settlement
{
    // Settlement Status
    public const SS_PENDING = 1;
    public const SS_PAID = 2;
    public const SS_DELETED = 3;
    public const SS_OPEN = 4;

    /** @var int[] */
    public static array $settlementStatuses = [
        self::SS_PENDING,
        self::SS_PAID,
        self::SS_DELETED,
        self::SS_OPEN,
    ];

    /** @var string[] */
    public static array $settlementStatusNames = [
        self::SS_PENDING => 'Pending',
        self::SS_PAID => 'Paid',
        self::SS_DELETED => 'Deleted',
        self::SS_OPEN => 'Open',
    ];

    /** @var int[] */
    public static array $availableSettlementStatuses = [
        self::SS_PENDING,
        self::SS_PAID,
        self::SS_OPEN,
    ];

    /** @var int[] */
    public static array $publicAvailableSettlementStatuses = [
        self::SS_PENDING,
        self::SS_PAID,
    ];
}
