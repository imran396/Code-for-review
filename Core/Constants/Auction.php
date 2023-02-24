<?php

namespace Sam\Core\Constants;

/**
 * Class Auction
 * @package Sam\Core\Constants
 */
class Auction
{
    // Auction Type
    public const LIVE = 'L';
    public const TIMED = 'T';
    public const HYBRID = 'H';
    /** @var string[] */
    public static array $auctionTypeLabels = [
        self::TIMED => 'Timed online auction',
        self::LIVE => 'Live auction',
        self::HYBRID => 'Hybrid auction',
    ];
    /** @var string[] */
    public const AUCTION_TYPES = [self::TIMED, self::LIVE, self::HYBRID];

    /**
     * Auction types processed by rtbd. TODO: apply in code
     * @var string[]
     */
    public const RTB_AUCTION_TYPES = [self::LIVE, self::HYBRID];
    /** @var string[] */
    public static array $auctionTypeNames = [
        self::TIMED => 'Timed',
        self::LIVE => 'Live',
        self::HYBRID => 'Hybrid',
    ];
    /** @var string[] */
    public static array $auctionTypeParams = [
        self::TIMED => 'timed',
        self::LIVE => 'live',
        self::HYBRID => 'hybrid',
    ];

    // Auction Status
    public const AS_NONE = 0;
    public const AS_ACTIVE = 1;
    public const AS_STARTED = 2;
    public const AS_CLOSED = 3;
    public const AS_DELETED = 4;
    public const AS_ARCHIVED = 5;
    public const AS_PAUSED = 6;

    /** @var int[] */
    public static array $auctionStatuses = [
        self::AS_ACTIVE,
        self::AS_STARTED,
        self::AS_CLOSED,
        self::AS_DELETED,
        self::AS_ARCHIVED,
        self::AS_PAUSED,
    ];

    // Statuses of available auctions (not archived and deleted)
    /** @var int[] */
    public static array $availableAuctionStatuses = [
        self::AS_ACTIVE,
        self::AS_STARTED,
        self::AS_CLOSED,
        self::AS_PAUSED,
    ];
    /** @var int[] */
    public static array $notDeletedAuctionStatuses = [
        self::AS_ACTIVE,
        self::AS_STARTED,
        self::AS_CLOSED,
        self::AS_ARCHIVED,
        self::AS_PAUSED,
    ];

    // Names of auction statuses
    /** @var string[] */
    public static array $auctionStatusNames = [
        self::AS_ACTIVE => 'Active',
        self::AS_STARTED => 'Started',
        self::AS_CLOSED => 'Closed',
        self::AS_DELETED => 'Deleted',
        self::AS_ARCHIVED => 'Archived',
        self::AS_PAUSED => 'Paused',
    ];
    /** @var int[] */
    public static array $openAuctionStatuses = [
        self::AS_ACTIVE,
        self::AS_STARTED,
        self::AS_PAUSED,
    ];

    // Clerking Style
    public const CS_SIMPLE = 'S';
    public const CS_ADVANCED = 'A';
    /** @var string[] */
    public static array $clerkingStyleNames = [
        self::CS_SIMPLE => 'Simple',
        self::CS_ADVANCED => 'Advanced',
    ];

    // Event Type
    public const ET_SCHEDULED = 0;
    public const ET_ONGOING = 1;
    /** @var int[] */
    public static array $eventTypes = [self::ET_SCHEDULED, self::ET_ONGOING];
    /** @var string[] */
    public static array $eventTypeNames = [
        self::ET_SCHEDULED => 'Scheduled',
        self::ET_ONGOING => 'Ongoing',
    ];
    /** @var string[] */
    public static array $eventTypeFullNames = [
        self::ET_SCHEDULED => 'Scheduled Event',
        self::ET_ONGOING => 'Ongoing Event',
    ];

    // Absentee Bid Display: use Constants\AuctionParameters
    //const ABD_DO_NOT_DISPLAY = 'N';
    //const ABD_NUMBER_OF_ABSENTEE = 'NA';
    //const ABD_NUMBER_OF_ABSENTEE_LINK = 'NL';
    //const ABD_NUMBER_OF_ABSENTEE_HIGH = 'NH';

    // Timed Date Options
    public const DAS_AUCTION_TO_ITEMS = 1;
    public const DAS_ITEMS_TO_AUCTION = 2;
    //const TDO_INDEPENDENT = '3';

    /** @var string[] */
    public static array $dateAssignmentStrategies = [
        self::DAS_AUCTION_TO_ITEMS => "Apply auction's start bidding date and start closing date to items",
        self::DAS_ITEMS_TO_AUCTION => "Apply item's min. start bidding date and max. start closing date to auction",
        //self::TDO_INDEPENDENT => "Auction's and items's start and end time are independent",
    ];

    // Stream Display
    public const SD_NONE = 'N';
    // TODO: make integers along with Auction->StreamDisplay
    public const SD_BPAV = '1'; // audio video
    public const SD_BPV = '2'; // video
    public const SD_BPA = '3'; // audio

    /** @var string[] */
    public static array $streamDisplayNames = [
        self::SD_NONE => '',
    ];

    /** @var string[] */
    public static array $streamDisplayValues = [
        self::SD_NONE,
    ];

    /** @var string[] */
    public static array $streamDisplayCoded = [
        self::SD_BPAV => 'BidPath Audio/Video',
        self::SD_BPV => 'BidPath Video',
        self::SD_BPA => 'BidPath Audio',
    ];

    /** @var string[] */
    public static array $streamDisplayCodedValues = [
        self::SD_BPAV,
        self::SD_BPV,
        self::SD_BPA,
    ];

    // Lot Order Types
    public const LOT_ORDER_BY_LOT_NUMBER = 1;
    public const LOT_ORDER_BY_ITEM_NUMBER = 2;
    public const LOT_ORDER_BY_CUST_FIELD = 3;
    public const LOT_ORDER_BY_CATEGORY = 4;
    public const LOT_ORDER_BY_NAME = 5;
    public const LOT_ORDER_BY_NONE = 0;

    /** @var int[] */
    public static array $lotOrderTypes = [
        self::LOT_ORDER_BY_NONE,
        self::LOT_ORDER_BY_LOT_NUMBER,
        self::LOT_ORDER_BY_ITEM_NUMBER,
        self::LOT_ORDER_BY_CUST_FIELD,
        self::LOT_ORDER_BY_CATEGORY,
        self::LOT_ORDER_BY_NAME,
    ];

    /** @var int[] */
    public static array $lotOrderPrimaryTypes = [
        self::LOT_ORDER_BY_LOT_NUMBER,
        self::LOT_ORDER_BY_ITEM_NUMBER,
        self::LOT_ORDER_BY_CUST_FIELD,
        self::LOT_ORDER_BY_CATEGORY,
        self::LOT_ORDER_BY_NAME,
    ];

    // General statuses of auction. Used in auction lists.
    public const STATUS_IN_PROGRESS = 1;
    public const STATUS_UPCOMING = 2;
    public const STATUS_CLOSED = 3;

    /** @var string[] */
    public static array $generalStatusNames = [
        self::STATUS_IN_PROGRESS => 'In progress',
        self::STATUS_UPCOMING => 'Upcoming',
        self::STATUS_CLOSED => 'Closed',
    ];

    /** @var string[] */
    public static array $generalStatusParams = [
        self::STATUS_IN_PROGRESS => 'in-progress',
        self::STATUS_UPCOMING => 'upcoming',
        self::STATUS_CLOSED => 'closed',
    ];

    // Time Statuses from classes/Sam/AuctionLot/Sync/Renderer.php
    public const TS_UPCOMING = 1;
    public const TS_IN_PROGRESS = 2;
    public const TS_LOT_ENDED = 3;
    public const TS_AUCTION_CLOSED = 4;
    public const TS_LOT_CLOSED = 5;

    public const SMS_NOTIFICATION_TEXT = 'Text EVENT_NAME {lot_number} to NUMBER to receive text alert for this lot!';

    public const ACCESS_RESTYPE_AUCTION_VISIBILITY = 1;
    public const ACCESS_RESTYPE_AUCTION_INFO = 2;
    public const ACCESS_RESTYPE_AUCTION_CATALOG = 3;
    public const ACCESS_RESTYPE_LIVE_VIEW = 4;
    public const ACCESS_RESTYPE_LOT_DETAILS = 5;
    public const ACCESS_RESTYPE_LOT_BIDDING_HISTORY = 6;
    public const ACCESS_RESTYPE_LOT_WINNING_BID = 7;
    public const ACCESS_RESTYPE_LOT_BIDDING_INFO = 8;
    public const ACCESS_RESTYPE_LOT_STARTING_BID = 9;

    // Buy Now Restriction for Timed auction, SAM-4264
    public const BNTR_NONE = 'N';
    public const BNTR_FIRST_BID = 'FB';
    public const BNTR_CURRENT_BID = 'CB';
    /** @var string[] */
    public const BUY_NOW_TIMED_RESTRICTIONS = [self::BNTR_NONE, self::BNTR_FIRST_BID, self::BNTR_CURRENT_BID];
    /** @var string[] */
    public const BUY_NOW_TIMED_RESTRICTION_NAMES = [
        self::BNTR_NONE => 'None',
        self::BNTR_FIRST_BID => 'First Bid',
        self::BNTR_CURRENT_BID => 'Current Bid',
    ];
}
