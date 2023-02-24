<?php

namespace Sam\Core\Constants\Csv;

/**
 * Class ImportSampler
 * @package Sam\Core\Constants\Csv
 */
class ImportSampler
{
    public const ADMIN_INVENTORY = 1;
    public const ADMIN_TIMED = 2;
    public const ADMIN_LIVE = 3;
    public const ADMIN_USER = 4;
    public const ADMIN_POST_AUCTION = 5;
    public const ADMIN_BIDDER = 6;
    public const ADMIN_LOCATION = 7;
    /** @var int[] */
    public static array $types = [
        self::ADMIN_INVENTORY,
        self::ADMIN_TIMED,
        self::ADMIN_LIVE,
        self::ADMIN_USER,
        self::ADMIN_POST_AUCTION,
        self::ADMIN_BIDDER,
        self::ADMIN_LOCATION,
    ];
}
