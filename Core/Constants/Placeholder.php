<?php

namespace Sam\Core\Constants;

/**
 * Class Placeholder
 * @package Sam\Core\Constants
 */
class Placeholder
{
    public const REGULAR = 1;
    public const MONEY = 2;
    public const URL = 3;
    public const INDEXED_ARRAY = 4;
    public const DATE = 5;
    public const DATE_ADDITIONAL = 6;
    public const BOOLEAN = 7;
    public const AUCTION_CUSTOM_FIELD = 8;
    public const LOT_CUSTOM_FIELD = 9;
    public const USER_CUSTOM_FIELD = 10;
    public const LANG_LABEL = 11;
    public const INLINE_TEXT = 12;
    public const COMPOSITE = 13;
    public const BEGIN_END = 14;

    /** @var string[] */
    public static array $typeNames = [
        self::REGULAR => 'Regular',
        self::MONEY => 'Money amount',
        self::URL => 'URLs',
        self::INDEXED_ARRAY => 'Indexed array',
        self::DATE => 'Date',
        self::DATE_ADDITIONAL => 'Date additional',
        self::BOOLEAN => 'Boolean',
        self::AUCTION_CUSTOM_FIELD => 'Auction custom field',
        self::LOT_CUSTOM_FIELD => 'Lot custom field',
        self::USER_CUSTOM_FIELD => 'User custom field',
        self::LANG_LABEL => 'Translation label',
        self::INLINE_TEXT => 'Inline text',
        self::COMPOSITE => 'Composite',
        self::BEGIN_END => 'Begin-End block',
    ];

    /** @var int[] */
    public static array $customFieldTypes = [self::AUCTION_CUSTOM_FIELD, self::LOT_CUSTOM_FIELD, self::USER_CUSTOM_FIELD];

    public const OPTION_BEGIN_END_LOGICAL_NOT = 'logicalNot';

    public const HTML_ID_TIME_LEFT_COUNTDOWN = 'time_left_countdown';
}
