<?php

namespace Sam\Core\Constants;

/**
 * Class LotBulkGroup
 * @package Sam\Core\Constants
 */
class LotBulkGroup
{
    // Lot Bulk Group Role labels` value
    public const LBGR_MASTER = 'MASTER';
    public const LBGR_NONE = '';

    // Lot Bulk Group Role names
    public const LBGR_NAMES = [
        self::LBGR_NONE => 'None',
        self::LBGR_MASTER => 'Bulk Master',
    ];

    public const BMWBD_MASTER = 1;
    public const BMWBD_EQUALLY = 2;
    public const BMWBD_WINNING = 3;

    /** @var string[] */
    public static array $bulkWinBidDistributionNames = [
        self::BMWBD_MASTER => 'MASTER',
        self::BMWBD_EQUALLY => 'EQUALLY',
        self::BMWBD_WINNING => 'WINNING',
    ];
}
