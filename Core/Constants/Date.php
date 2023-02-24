<?php

namespace Sam\Core\Constants;

/**
 * Class Date
 * @package Sam\Core\Constants
 */
class Date
{
    public const ISO = 'Y-m-d H:i:s';
    public const ISO_TZ = 'Y-m-d H:i:s T';
    public const ISO_1ST_DAY_OF_MONTH = 'Y-m-01 H:i:s'; // first day of current month
    public const ISO_N_DAY_OF_MONTH_TPL = 'Y-m-%s H:i:s'; // n-day of current month
    public const ISO_MS = 'Y-m-d H:i:s.u'; // microseconds are available from php7.1
    public const ISO_DATE = 'Y-m-d';
    public const ISO_DATE_START = 'Y-m-d 00:00:00';
    public const ISO_DATE_END = 'Y-m-d 23:59:59';
    public const ISO_TIME = 'H:i:s';
    public const ISO_TIME_HOUR_MINUTE = 'H:i';
    public const ISO_TIME_START = '00:00:00';
    public const ISO_TIME_END = '23:59:59';

    public const US_DATE_TIME = 'm/d/Y h:i A';    // from DATE_TIME constant
    public const US_DATE = 'm/d/Y';               // from DATE_ONLY constant
    public const US_DATE_HOUR_MINUTE_SEC = 'm/d/Y g:i:s a'; // from DATE_TIME constant
    public const US_COMPLETE = 'l, F jS Y h:i A'; // from DATE_COMPLETE constant

    // Admin Date Format
    public const ADF_US = 1;
    public const ADF_AU = 2;

    /** @var int[] */
    public static array $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

    /** @var string[] */
    public static array $monthNames = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ];

    /** @var string[] */
    public static array $dateTimeFormats = [
        1 => "MM/DD/YYYY h:mm zz",
        2 => "DD/MM/YYYY hhhh:mm",
    ];

    /** @var string[] */
    public static array $hourNames = [
        12 => '12',
        11 => '11',
        10 => '10',
        9 => '09',
        8 => '08',
        7 => '07',
        6 => '06',
        5 => '05',
        4 => '04',
        3 => '03',
        2 => '02',
        1 => '01',
    ];

    /** @var string[] */
    public static array $minuteNames = [
        0 => '00',
        1 => '01',
        2 => '02',
        3 => '03',
        4 => '04',
        5 => '05',
        6 => '06',
        7 => '07',
        8 => '08',
        9 => '09',
        10 => '10',
        11 => '11',
        12 => '12',
        13 => '13',
        14 => '14',
        15 => '15',
        16 => '16',
        17 => '17',
        18 => '18',
        19 => '19',
        20 => '20',
        21 => '21',
        22 => '22',
        23 => '23',
        24 => '24',
        25 => '25',
        26 => '26',
        27 => '27',
        28 => '28',
        29 => '29',
        30 => '30',
        31 => '31',
        32 => '32',
        33 => '33',
        34 => '34',
        35 => '35',
        36 => '36',
        37 => '37',
        38 => '38',
        39 => '39',
        40 => '40',
        41 => '41',
        42 => '42',
        43 => '43',
        44 => '44',
        45 => '45',
        46 => '46',
        47 => '47',
        48 => '48',
        49 => '49',
        50 => '50',
        51 => '51',
        52 => '52',
        53 => '53',
        54 => '54',
        55 => '55',
        56 => '56',
        57 => '57',
        58 => '58',
        59 => '59',
    ];

    /** @var string[] */
    public static array $meridiemNames = [
        'am' => 'AM',
        'pm' => 'PM',
    ];

    /** @var string[] */
    public static array $staggerClosingIntervalNames = [
        1 => '1min',
        2 => '2min',
        3 => '3min',
        4 => '4min',
        5 => '5min',
        10 => '10min',
        15 => '15min',
        20 => '20min',
        25 => '25min',
        30 => '30min',
        35 => '35min',
        40 => '40min',
        45 => '45min',
        50 => '50min',
        55 => '55min',
        60 => '60min',
    ];
}
