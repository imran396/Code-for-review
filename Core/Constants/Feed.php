<?php

namespace Sam\Core\Constants;

/**
 * Class Feed
 * @package Sam\Core\Constants
 */
class Feed
{
    public const TYPE_AUCTIONS = 'A';
    public const TYPE_LOTS = 'L';
    public const TYPE_CATEGORY = 'C';

    /** @var string[] */
    public static array $types = [self::TYPE_AUCTIONS, self::TYPE_LOTS];

    public const ESC_NONE = 1;
    public const ESC_HTMLENTITIES = 2;
    public const ESC_CSV_EXCEL = 3;
    public const ESC_URL = 4;
    public const ESC_RTF = 5;
    public const ESC_JSON_ENCODE = 6;

    /** @var int[] */
    public static array $escapings = [
        self::ESC_NONE,
        self::ESC_HTMLENTITIES,
        self::ESC_CSV_EXCEL,
        self::ESC_URL,
        self::ESC_RTF,
        self::ESC_JSON_ENCODE,
    ];

    /** @var string[] */
    public static array $escapingNames = [
        self::ESC_NONE => 'none',
        self::ESC_HTMLENTITIES => 'htmlentitites',
        self::ESC_CSV_EXCEL => 'csv excel',
        self::ESC_URL => 'url',
        self::ESC_RTF => 'RTF escaping',
        self::ESC_JSON_ENCODE => 'json encode',
    ];

    /** @var string[] */
    public static array $typeNames = [
        self::TYPE_AUCTIONS => 'Auctions',
        self::TYPE_LOTS => 'Lots',
    ];

    /** @var int[] */
    public static array $cachingTimeouts = [
        '5mins' => 300,
        '15mins' => 900,
        '30mins' => 1800,
        '1hr' => 3600,
        '2hrs' => 7200,
        '6hrs' => 21600,
        '12hrs' => 43200,
        '24hrs' => 86400,
    ];

    public const CATEGORY_SLUG = 'categories.xml';
}
