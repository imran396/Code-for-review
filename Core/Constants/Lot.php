<?php

namespace Sam\Core\Constants;

/**
 * Class Lot
 * @package Sam\Core\Constants
 */
class Lot
{
    // Lot Status
    public const LS_UNASSIGNED = 0;    // lot item not assigned to auction and don't have status
    public const LS_ACTIVE = 1;        // lot is active in sale
    public const LS_UNSOLD = 2;        // lot ends unsold/unawarded
    public const LS_SOLD = 3;          // lot is sold/awarded
    public const LS_DELETED = 4;       // lot deleted or removed from auction
    public const LS_RECEIVED = 6;      // extending of Sold status

    /** @var int[] */
    public static array $lotStatuses = [self::LS_ACTIVE, self::LS_UNSOLD, self::LS_SOLD, self::LS_DELETED, self::LS_RECEIVED];

    /**
     * lot status names for forward auction
     * @var string[]
     */
    public static array $lotStatusNames = [
        self::LS_UNASSIGNED => 'Unassigned',
        self::LS_ACTIVE => 'Active',
        self::LS_UNSOLD => 'Unsold',
        self::LS_SOLD => 'Sold',
        self::LS_DELETED => 'Deleted',
        self::LS_RECEIVED => 'Received',
    ];

    /**
     * lot status names for reverse auction
     * @var string[]
     */
    public static array $lotStatusNamesForReverseAuction = [
        self::LS_UNASSIGNED => 'Unassigned',
        self::LS_ACTIVE => 'Active',
        self::LS_UNSOLD => 'Unawarded',
        self::LS_SOLD => 'Awarded',
        self::LS_DELETED => 'Deleted',
        self::LS_RECEIVED => 'Received',
    ];

    public const SOLD_WITH_RESERVATION_NAME = 'Sold with reservation';

    /**
     * Statuses of available lots (not deleted)
     * @var int[]
     */
    public static array $availableLotStatuses = [
        self::LS_ACTIVE,
        self::LS_UNSOLD,
        self::LS_SOLD,
        self::LS_RECEIVED,
    ];

    /**
     * Statuses of available lots in case of Timed auction
     * @var int[]
     */
    public static array $availableLotStatusesForTimed = [
        self::LS_ACTIVE,
        self::LS_UNSOLD,
        self::LS_SOLD,
        self::LS_RECEIVED,
    ];

    /** @var int[] */
    public static array $inAuctionStatuses = [
        self::LS_ACTIVE,
        self::LS_UNSOLD,
        self::LS_SOLD,
    ];

    /** @var int[] */
    public static array $closedLotStatuses = [
        self::LS_UNSOLD,
        self::LS_SOLD,
        self::LS_RECEIVED,
    ];

    /** @var int[] */
    public static array $wonLotStatuses = [
        self::LS_SOLD,
        self::LS_RECEIVED,
    ];

    public const FILTER_BID_COUNT_NONE = 0;
    public const FILTER_BID_COUNT_HAS_BIDS = 1;
    public const FILTER_BID_COUNT_HAS_NO_BIDS = 2;

    /**
     * names for bid count filtering on auction lot list page
     * @var string[]
     */
    public static array $filterBidCountNames = [
        self::FILTER_BID_COUNT_NONE => 'Any',
        self::FILTER_BID_COUNT_HAS_BIDS => 'Bids',
        self::FILTER_BID_COUNT_HAS_NO_BIDS => 'No bids'
    ];

    public const FILTER_RESERVE_NONE = 0;
    public const FILTER_RESERVE_MEET = 1;
    public const FILTER_RESERVE_NOT_MEET = 2;

    /**
     * names for reserve meet filtering on auction lot list page
     * @var string[]
     */
    public static array $filterReserveMeetNames = [
        self::FILTER_RESERVE_NONE => 'All',
        self::FILTER_RESERVE_MEET => 'Reserve met',
        self::FILTER_RESERVE_NOT_MEET => 'Reserve not met'
    ];

    public const LOT_NO_AUTO_INC_OFF = 0;                // Lot num auto incrementing is off
    public const LOT_NO_AUTO_INC_ON = 1;                 // Lot num auto incrementing is on
    public const LOT_NO_AUTO_INC_AUCTION_OPTION = 2;     // Lot num auto incrementing depends on auction option auction.auto_populate_empty_lot_num

    // Statuses for TimeLeft data
    public const TL_LOT_ENDED = -1;
    public const TL_LOT_PAUSED = -2;

    // Item# constraints
    public const ITEM_NUM_EXT_MAX_LENGTH_FOR_EXISTING_LOT_ITEM = 8;
    public const ITEM_NUM_EXT_MAX_LENGTH_FOR_NEW_LOT_ITEM = 4;

    // Lot# constraints
    public const LOT_NUM_EXT_MAX_LENGTH_FOR_EXISTING_LOT = 7;
    public const LOT_NUM_EXT_MAX_LENGTH_FOR_NEW_LOT = 3;

    // Quantity constraints
    public const LOT_QUANTITY_MAX_INTEGER_DIGITS = 11;
    public const LOT_QUANTITY_MAX_FRACTIONAL_DIGITS = 8;
    public const LOT_QUANTITY_MAX_PRECISION = 15;

    // TODO: Remove when will be implemented we routes for create entities (include for create lot)
    // see: SAM-9610: https://bidpath.atlassian.net/browse/SAM-9610?focusedCommentId=138673
    public const NEW_LOT_ID = 0;
}
