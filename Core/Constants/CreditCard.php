<?php

namespace Sam\Core\Constants;

/**
 * Class CreditCard
 * @package Sam\Core\Constants
 */
class CreditCard
{
    public const T_NONE = 0;
    public const T_AMEX = 1;
    public const T_DISCOVER = 2;
    public const T_VISA = 3;
    public const T_MASTERCARD = 4;
    public const T_DINERS_CLUB = 5;
    public const T_JCB = 6;

    /** @var string[][] */
    public static array $ccTypes = [
        self::T_AMEX => ['amex', 'american express'],
        self::T_DISCOVER => ['discover'],
        self::T_VISA => ['visa'],
        self::T_MASTERCARD => ['mastercard'],
        self::T_DINERS_CLUB => ['diners club'],
        self::T_JCB => ['jcb'],
    ];

    /** @var int[] */
    public static array $epayCardTypes = [
        0 => 0,
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 10,
        11 => 11,
        12 => 12,
        13 => 13,
        14 => 14,
        15 => 15,
        16 => 16,
        17 => 17,
        18 => 18,
        19 => 19,
        20 => 20,
        21 => 21,
        22 => 22,
        23 => 23,
        24 => 24,
        25 => 25,
    ];
}
