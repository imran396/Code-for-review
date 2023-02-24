<?php

namespace Sam\Core\Constants;

/**
 * Class RegistrationReminder
 * @package Sam\Core\Constants
 */
class RegistrationReminder
{
    /** @var string[] */
    public static array $intervalHourNames = [
        self::INTERVAL_ONCE_PER_4_HOURS => "4 hrs",
        self::INTERVAL_ONCE_PER_8_HOURS => "8 hrs",
        self::INTERVAL_ONCE_PER_12_HOURS => "12 hrs",
        self::INTERVAL_ONCE_PER_DAY => "1 day",
        self::INTERVAL_ONCE_PER_2_DAYS => "2 day",
        self::INTERVAL_ONCE_PER_3_DAYS => "3 day",
        self::INTERVAL_ONCE_PER_4_DAYS => "4 day",
        self::INTERVAL_ONCE_PER_5_DAYS => "5 day",
        self::INTERVAL_ONCE_PER_6_DAYS => "6 day",
        self::INTERVAL_ONCE_PER_7_DAYS => "7 day",
    ];

    public const INTERVAL_ONCE_PER_4_HOURS = 4;
    public const INTERVAL_ONCE_PER_8_HOURS = 8;
    public const INTERVAL_ONCE_PER_12_HOURS = 12;
    public const INTERVAL_ONCE_PER_DAY = 24;
    public const INTERVAL_ONCE_PER_2_DAYS = 48;
    public const INTERVAL_ONCE_PER_3_DAYS = 72;
    public const INTERVAL_ONCE_PER_4_DAYS = 96;
    public const INTERVAL_ONCE_PER_5_DAYS = 120;
    public const INTERVAL_ONCE_PER_6_DAYS = 144;
    public const INTERVAL_ONCE_PER_7_DAYS = 168;

    public const INTERVALS_LESS_THAN_DAY = [
        self::INTERVAL_ONCE_PER_4_HOURS,
        self::INTERVAL_ONCE_PER_8_HOURS,
        self::INTERVAL_ONCE_PER_12_HOURS
    ];
}
