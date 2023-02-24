<?php

namespace Sam\Core\Constants;

use Sam\Core\Constants;

/**
 * @package Sam\Core\Constants
 */
class EntitySync
{
    public const TYPE_ACCOUNT = 1;
    public const TYPE_AUCTION = 2;
    public const TYPE_AUCTION_LOT_ITEM = 3;
    public const TYPE_LOT_ITEM = 4;
    public const TYPE_USER = 5;
    public const TYPE_LOCATION = 6;

    /** @var string[] */
    public const TYPE_TO_DB_ALIAS_MAP = [
        self::TYPE_ACCOUNT => Constants\Db::A_ACCOUNT,
        self::TYPE_AUCTION => Constants\Db::A_AUCTION,
        self::TYPE_AUCTION_LOT_ITEM => Constants\Db::A_AUCTION_LOT_ITEM,
        self::TYPE_LOT_ITEM => Constants\Db::A_LOT_ITEM,
        self::TYPE_USER => Constants\Db::A_USER,
        self::TYPE_LOCATION => Constants\Db::A_LOCATION,
    ];
}
