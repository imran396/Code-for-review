<?php
/**
 * SAM-4696: Page constants
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class AuctionBidReportFormConstants
 * @package Sam\Core\Constants\Admin
 */
class AuctionBidReportFormConstants
{
    // date range types
    public const DR_BID = 'BD';
    public const DR_AUCTION = 'AD';
    public static array $dateRanges = [self::DR_BID, self::DR_AUCTION];
    public static array $dateRangeNames = [
        self::DR_BID => 'Bid date',
        self::DR_AUCTION => 'Auction date',
    ];
}
