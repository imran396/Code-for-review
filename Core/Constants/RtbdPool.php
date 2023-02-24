<?php

namespace Sam\Core\Constants;

/**
 * Class RtbdPool
 * SAM-5201: Apply constants for rtbd request/response keys and data: Applied constants
 * @package Sam\Core\Constants
 */
class RtbdPool
{
    // Discovery Strategy of rtbd instance in pool
    public const DS_FAIR = 1;
    public const DS_MANUAL = 2;
    public const DS_ROUND_ROBIN = 3;
    /** @var int[] */
    public static array $discoveryStrategies = [
        self::DS_FAIR,
        self::DS_MANUAL,
        self::DS_ROUND_ROBIN
    ];
    /** @var string[] */
    public static array $discoveryStrategyNames = [
        self::DS_FAIR => 'fair',
        self::DS_MANUAL => 'manual',
        self::DS_ROUND_ROBIN => 'round robin',
    ];

    public const LOG_FILE_NAME_TPL = 'rtb-%s.log';
    public const PID_FILE_NAME_TPL = 'rtb-%s.pid';
}
