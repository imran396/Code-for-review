<?php

namespace Sam\Core\Constants;

use Sam\Core\Constants;

/**
 * @package Sam\Core\Constants
 */
class Location
{
    public const TYPE_AUCTION_EVENT = 1;
    public const TYPE_AUCTION_INVOICE = 2;
    public const TYPE_LOT_ITEM = 3;
    public const TYPE_USER = 4;

    /** @var string[] */
    public const TYPE_TO_DB_ALIAS_MAP = [
        self::TYPE_AUCTION_EVENT => Constants\Db::A_AUCTION,
        self::TYPE_AUCTION_INVOICE => Constants\Db::A_AUCTION,
        self::TYPE_LOT_ITEM => Constants\Db::A_LOT_ITEM,
        self::TYPE_USER => Constants\Db::A_USER,
    ];

    /** @var string[] */
    public const TYPE_TO_DB_FIELD = [
        self::TYPE_AUCTION_EVENT => 'EventLocationId',
        self::TYPE_AUCTION_INVOICE => 'InvoiceLocationId',
        self::TYPE_LOT_ITEM => 'LocationId',
        self::TYPE_USER => 'LocationId',
    ];

    public const USA_STATES = 'USA states';
    public const CANADIAN_PROVINCES = 'Canadian provinces';
    public const MEXICO_STATES = 'Mexico states';
}
