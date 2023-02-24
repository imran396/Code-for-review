<?php

namespace Sam\Core\Constants;

/**
 * Class Rtb
 * SAM-5201: Apply constants for rtbd request/response keys and data: Applied constants
 * @package Sam\Core\Constants
 */
class Rtb
{
    // Rtb User (Client Console) Type, TODO: rename prefix to CT_ at php/js sides
    public const UT_CLERK = 1;
    public const UT_BIDDER = 2;
    public const UT_VIEWER = 3;
    public const UT_PROJECTOR = 5;
    public const UT_CLIENT = 6;
    public const UT_SYSTEM = 7;
    public const UT_AUCTIONEER = 8;

    /** @var int[] */
    public static array $userTypes = [
        self::UT_CLERK,
        self::UT_BIDDER,
        self::UT_VIEWER,
        self::UT_PROJECTOR,
        self::UT_CLIENT,
        self::UT_SYSTEM,
        self::UT_AUCTIONEER,
    ];

    /** @var int[] */
    public static array $rtbConsoleUserTypes = [
        self::UT_CLERK,
        self::UT_BIDDER,
        self::UT_VIEWER,
        self::UT_PROJECTOR,
        self::UT_AUCTIONEER,
        self::UT_CLIENT,
    ];

    // Response Type
    public const RT_SINGLE = '1';
    public const RT_CLERK = '2';
    public const RT_BIDDER = '3';
    public const RT_VIEWER = '4';
    public const RT_PROJECTOR = '6';
    public const RT_INDIVIDUAL = '7';
    public const RT_SIMULT_BIDDER = '8';
    public const RT_SIMULT_VIEWER = '9';
    public const RT_SMS = '10';
    public const RT_SIMULT_INDIVIDUAL = '11';
    public const RT_SYSTEM = '12';
    public const RT_AUCTIONEER = '13';

    public const GROUP_ALL_FOR_ONE = 'O';
    public const GROUP_X_THE = 'X';
    public const GROUP_CHOICE = 'C';
    public const GROUP_QUANTITY = 'Q';

    /** @var string[] */
    public static array $groupTypes = [
        self::GROUP_ALL_FOR_ONE,
        self::GROUP_X_THE,
        self::GROUP_CHOICE,
        self::GROUP_QUANTITY,
    ];

    public const CMD_AUTH_Q = 'auq';
    public const CMD_AUTH_S = 'aus';
    public const CMD_ERR_S = 'ers';
    public const CMD_SYNC_Q = 'snq';
    public const CMD_SYNC_S = 'sns';
    public const CMD_START_Q = 'stq';
    public const CMD_PAUSE_Q = 'paq';
    public const CMD_PAUSE_S = 'pas';
    public const CMD_FAIR_Q = 'fwq';
    public const CMD_FAIR_S = 'fws';
    public const CMD_FLOOR_Q = 'fbdq';
    public const CMD_PLACE_Q = 'pbdq';
    public const CMD_PLACE_S = 'pbds';
    public const CMD_ACCEPT_LOT_CHANGES_Q = 'chnglq';
    public const CMD_ACCEPT_Q = 'abdq';
    public const CMD_UPDATE_S = 'ubds';
    public const CMD_CHANGE_ASKING_BID_Q = 'chabidq';
    public const CMD_CHANGE_ASKING_BID_S = 'chabids';
    public const CMD_GO_TO_LOT_Q = 'glotq';
    public const CMD_SEND_MESSAGE_Q = 'sndmsgq';
    public const CMD_SEND_MESSAGE_S = 'sndmsgs';
    public const CMD_SOLD_Q = 'sldq';
    public const CMD_UNDO_SNAPSHOT_Q = 'undsnq';
    public const CMD_PASS_Q = 'pasq';
    public const CMD_FLOOR_PRIORITY_Q = 'flprq';
    public const CMD_ABSENTEE_PRIORITY_Q = 'abprq';
    public const CMD_START_LOT_Q = 'actq';
    public const CMD_START_LOT_S = 'acts';
    public const CMD_PAUSE_LOT_Q = 'palq';
    public const CMD_PAUSE_LOT_S = 'palq';
    public const CMD_STOP_Q = 'spq';
    public const CMD_STOP_S = 'sps';
    public const CMD_SELL_LOTS_Q = 'alq';
    public const CMD_SELL_LOTS_S = 'als';
    public const CMD_WAIT_SELL_LOTS_S = 'wals';
    public const CMD_CLEAR_AUCTION_GAME_LOG_Q = "clrgamelogq";
    public const CMD_CLEAR_CHAT_LOG_Q = "clrchatlogq";
    public const CMD_RESET_LOT_Q = 'rslotq';
    public const CMD_RESET_INCREMENT_Q = 'rsincq';
    public const CMD_GROUP_LOT_Q = 'grplotq';
    public const CMD_GROUP_LOT_S = 'grplots';
    public const CMD_UNGROUP_LOT_Q = 'ugrplotq';
    public const CMD_UNGROUP_LOT_S = 'ugrplots';
    public const CMD_CHANGE_INCREMENT_Q = 'chincq';
    public const CMD_CHANGE_DEFAULT_INCREMENT_Q = 'chdincq';
    public const CMD_ENABLE_DECREMENT_Q = 'deconq';
    public const CMD_SELECT_BUYER_Q = 'selbuyq';
    public const CMD_SELECT_BUYER_S = 'selbuys';
    public const CMD_WAIT_SELECT_BUYER_S = 'wselbuys';
    public const CMD_SEND_MESSAGE_TO_SIMULTANEOUS_AUCTION_Q = 'simulmsgq';
    public const CMD_ENTER_BIDDER_NUM_Q = 'ebnq';
    public const CMD_ENTER_BIDDER_NUM_S = 'ebns';
    public const CMD_CANCEL_ENTER_BIDDER_NUM_Q = 'cebnq';
    public const CMD_CANCEL_ENTER_BIDDER_NUM_S = 'cebns';
    public const CMD_NOOP = 'noop';
    public const CMD_DISABLE_BID_Q = 'disbidq';
    public const CMD_CONFIRM_BID_ON_SOLD_LOT_S = 'cbsbs';
    public const CMD_INTEREST_Q = 'intq';
    public const CMD_INTEREST_S = 'ints';
    public const CMD_DROP_INTEREST_Q = 'dintq';
    public const CMD_DROP_INTEREST_S = 'dints';
    public const CMD_SYNC_INTEREST_Q = 'sintq';
    public const CMD_SYNC_INTEREST_S = 'sints';
    public const CMD_BUY_NOW_Q = 'buynq';
    public const CMD_BUY_NOW_S = 'buyns';
    public const CMD_PING_Q = 'pingq';
    public const CMD_PING_S = 'pings';
    public const CMD_REVERSE_PING_Q = 'rpingq';
    public const CMD_REVERSE_PING_S = 'rpings';
    public const CMD_REVERSE_PING_RESULT_S = 'rpingress';
    public const CMD_INIT_BID_COUNTDOWN_Q = 'bidcntdq';
    public const CMD_INIT_BID_COUNTDOWN_S = 'bidcntds';
    public const CMD_TERMINATE_CONNECTION_Q = 'termconq';
    public const CMD_TERMINATE_CONNECTION_S = 'termcons';
    public const CMD_RESET_RUNNING_LOT_COUNTDOWN_Q = 'rstrlcq';        // Handle "Reset" button click
    public const CMD_RESET_RUNNING_LOT_COUNTDOWN_S = 'rstrlcs';
    public const CMD_ENABLE_AUTO_START_Q = 'eaustq';                   // Handle "Auto Start" checkbox click
    public const CMD_ENABLE_AUTO_START_S = 'eausts';
    public const CMD_ENABLE_ENTER_FLOOR_NO_Q = 'eflnoq';               // Handle "Enter Floor#" checkbox click
    public const CMD_ENABLE_ENTER_FLOOR_NO_S = 'eflnos';
    public const CMD_RESET_PENDING_ACTION_COUNTDOWN_Q = 'rstpacq';
    public const CMD_RESET_PENDING_ACTION_COUNTDOWN_S = 'rstpacs';
    public const CMD_RESYNC_Q = 'resyncq';                       // Resync rtb state (after auction reset or any change in rtb objects)
    public const CMD_RESYNC_S = 'resyncs';
    public const CMD_LINK_RTBD_Q = 'linkrtbdq';                        // Link pool's rtbd instance to auction
    public const CMD_LINK_RTBD_S = 'linkrtbds';
    public const CMD_RELOAD_S = 'reloads';                             // Reload page
    public const CMD_SAVE_SETTINGS = 'savesettings';

    // Pending Actions
    public const PA_ENTER_BIDDER_NUM = 1;          // Admin enters floor bidder#
    public const PA_SELECT_BUYER_BY_AGENT = 2;     // Agent selects buyer
    public const PA_SELECT_GROUPED_LOTS = 3;       // Admin or Bidder selects grouped lots

    // Running lot Interval
    // const RI_CLOSING_DELAY = 'clo';  // currently "Closing Delay" interval is considered as "Extend Time" interval
    public const RI_EXTEND_TIME = 'ext';
    public const RI_LOT_START_GAP_TIME = 'gap';

    public const BD_OUTBID = 1;
    public const BD_OUTBID_BY_FASTER = 2;
    public const BD_LOT_NOT_ACTIVE = 3;
    public const BD_BIDDING_PAUSED = 4;
    public const BD_NO_BIDDER_ROLE = 5;
    public const BD_CANNOT_BID = 6;
    public const BD_ALREADY_PLACED = 7;
    public const BD_CURRENT_HIGH = 8;
    public const BD_RESTRICTED_GROUP = 9;
    public const BD_OUTSTANDING_EXCEED = 10;

    /**
     * Request data keys
     */
    // (string)
    public const REQ_COMMAND = 'Cmd';
    // (array)
    public const REQ_DATA = 'Data';
    // Auction id (int)
    public const REQ_AUCTION_ID = 'AuId';
    // (string)
    public const REQ_UKEY = 'Ukey';
    // rtbc.auto_start (int) TODO: bool
    public const REQ_LOT_AUTO_START = 'AS';
    // (float)
    public const REQ_ASKING_BID = 'ABid';
    // (float) Current Bid from client console state (is used for synchronization check)
    public const REQ_CURRENT_BID = 'CBid';
    // Running lot item id rtbc.lot_item_id (int)
    public const REQ_RUNNING_LOT_ITEM_ID = 'LotId';
    // (string) TODO: (int[]), change comma separated to []
    public const REQ_LOT_ITEM_IDS = 'Lots';
    // (int) TODO: => (bool)
    public const REQ_IS_CONFIRM_BUY = 'CBuy';
    // User, who placed bid, but it isn't accepted yet (int)
    public const REQ_PENDING_BID_USER_HASH = 'BidByH';
    // HttpReferer url (string)
    public const REQ_REFERRER = 'Referrer';
    // Is enabled decrementing (int) TODO: => (bool)
    public const REQ_IS_DECREMENT = 'Decre';
    // Go to lot item id li.id (int)
    public const REQ_GO_TO_LOT_ITEM_ID = 'LotSel';
    // General note (string)
    public const REQ_GENERAL_NOTE = 'GNote';
    // Winning bidder user id
    public const REQ_WINNING_BIDDER_USER_ID = 'BidWn';
    // Winning buyer user id (int)
    public const REQ_WINNING_BUYER_USER_ID = 'WnBuy';
    // Grouping type (string)
    public const REQ_GROUP = 'Grp';
    // Quantity (int)
    public const REQ_GROUP_LOT_QUANTITY = 'Qty';
    // Only Sell Running Lot (bool)
    public const REQ_ONLY_SELL_RUNNING_LOT = 'SellRunLot';
    // (string)
    public const REQ_MESSAGE = 'Msg';
    // (int)
    public const REQ_MESSAGE_ID = 'MsgId';
    // Message receiver user id (int)
    public const REQ_RECEIVER_USER_ID = 'Usr';
    // Message receiver bidder# (string)
    public const REQ_RECEIVER_BIDDER_NO = 'BidNo';
    // New increment amount (float)
    public const REQ_INCREMENT_NEW = 'NInc';
    // Default increment amount (float)
    public const REQ_INCREMENT_DEFAULT = 'DInc';
    // Buy Now lot item id (int)
    public const REQ_BUY_NOW_LOT_ITEM_ID = 'BuyLotId';
    // Buy Now hammer price (float)
    public const REQ_HAMMER_PRICE = 'BuyAmt';
    // Enabled entering floor bidder no (bool)
    public const REQ_IS_ENTER_FLOOR_NO = 'EFlNo';
    // TODO:
    public const REQ_LOT_CHANGES = 'Chng';
    // TODO:
    public const REQ_IS_LOT_CHANGES = 'ChngSt';
    // TODO: remove or fix
    public const REQ_BID_COUNTDOWN = 'LblCnt';
    // (int)
    public const REQ_USER_ID = 'UId';
    // (string)
    public const REQ_IP = 'IpAdd';
    // New rtbd instance name (string)
    public const REQ_NEW_RTBD_NAME = 'NewRtbdName';
    // Old rtbd instance name (string)
    public const REQ_OLD_RTBD_NAME = 'OldRtbdName';
    // Hybrid auction settings (int)
    public const REQ_EXTEND_TIME = 'AeTime';
    // Hybrid auction settings (int)
    public const REQ_LOT_START_GAP_TIME = 'LsgTime';
    // Hybrid auction settings (bool)
    public const REQ_ALLOW_BIDDING_DURING_START_GAP = 'AllowBdsg';
    // Ping timestamp
    public const REQ_PING_TS = 'ReqPingTs';
    // Reverse ping timestamp
    public const REQ_REVERSE_PING_TS = 'ReqRevPingTs';
    // SMS payload (string)
    public const REQ_SMS_PAYLOAD = 'SmsPayload';
    /**
     * Viewer-repeater client identifier (int) (SAM-10677)
     * Viewer connection resource id is tracked by viewer-repeater and adds it to SyncQ request for rtbd processing. Then it comes back to repeater for identifying exact receiver.
     */
    public const REQ_VIEWER_RESOURCE_ID = 'ReqVRID';

    /**
     * Response data keys
     */
    // (string)
    public const RES_COMMAND = 'Cmd';
    // (array)
    public const RES_DATA = 'Data';
    // Auction status id a.auction_status_id (int)
    public const RES_AUCTION_STATUS_ID = 'AuSt';
    // Running lot changes text li.changes (string)
    public const RES_LOT_CHANGES = 'Chng';
    // TODO: not used in js code, compare with v3-1
    public const RES_IS_LOT_CHANGES = 'NChng';
    // Running lot item id li.id (int)
    public const RES_LOT_ITEM_ID = 'Id';
    // Running lot name (string)
    public const RES_LOT_NAME = 'Name';
    // Running lot description li.description (string)
    public const RES_LOT_DESCRIPTION = 'Desc';
    // Running lot# (string)
    public const RES_LOT_NO = 'No';
    // Running lot position in auction (int), TODO: refactor with \Lot_Factory::findLotPosition()
    public const RES_LOT_POSITION = 'Pos';
    // Running lot category list separated by comma (string) TODO: string[]
    public const RES_LOT_CATEGORIES = 'Cats';
    // Running lot consignor full name or company name (string)
    public const RES_CONSIGNOR_NAME = 'Cons';
    // (string) TODO: bool
    public const RES_IS_AGREEMENT = 'bidderAgreement';
    // Running lot terms (string)
    public const RES_LOT_TERMS = 'lotTerms';
    // Running lot quantity ali.quantity (int)
    public const RES_QUANTITY = 'LQty';
    // ali.quantity_x_money (int) TODO: bool
    public const RES_IS_QUANTITY_X_MONEY = 'LQtyXM';
    // Currently pending bid did come from Absentee Bid rtbc.absentee_bid (int) TODO: (bool)
    public const RES_IS_ABSENTEE_BID = 'IsAbs';
    // Is bid placed on running lot (bool)
    public const RES_IS_CURRENT_BID = 'PTran';
    // Is bid placed by online bidder, not by floor bidder (bool)
    public const RES_IS_INTERNET_BID = 'IBid';
    // Is running lot active in play (bool)
    public const RES_LOT_ACTIVITY = 'Act';
    // Low estimate price (float)
    public const RES_LOW_ESTIMATE = 'LEst';
    // High estimate price (float)
    public const RES_HIGH_ESTIMATE = 'HEst';
    // Reserve price (float)
    public const RES_RESERVE_PRICE = 'Res';
    // Starting bid amount (float)
    public const RES_STARTING_BID = 'SBid';
    // Asking bid amount (float)
    public const RES_ASKING_BID = 'ABid';
    // Current bid amount (float)
    public const RES_CURRENT_BID = 'CBid';
    // Current bid full amount CurrentBid * Quantity (float)
    public const RES_CURRENT_BID_FULL_AMOUNT = 'Amt';
    // Currently running lot high bidder address (string), SAM-6393
    public const RES_CURRENT_BIDDER_ADDRESS = 'CurAddr';
    // Currently running lot high bidder bidder# w/o padding (string)
    public const RES_CURRENT_BIDDER_NO = 'CurNo';
    // Currently running lot high bidder тфьу (string)
    public const RES_CURRENT_BIDDER_NAME = 'CurBName';
    // Currently running lot high bidder u.id (int)
    public const RES_CURRENT_BIDDER_USER_ID = 'CurWn';
    // Currently running lot high bidder u.id hash (SAM-5067)
    public const RES_CURRENT_BIDDER_USER_HASH = 'CurWnH';
    // Outbid bidder u.id hash (SAM-5067)
    public const RES_OUTBID_USER_HASH = 'OutWnH';
    // Outbid pending bid user hash rtbc.new_bid_by (SAM-5067)
    public const RES_OUTBID_PENDING_BID_USER_HASH = 'OBidByH';
    // Outbid bidder bidder# w/o padding (string)
    public const RES_OUTBID_BIDDER_NO = 'OutNo';
    // Pending bid owner, contender to be high bidder of running lot, hash (SAM-5067)
    public const RES_PENDING_BID_USER_HASH = 'BidByH';
    // Pending bid owner bidder# w/o padding (string)
    public const RES_PENDING_BID_BIDDER_NO = 'PadNo';
    // Info for rendering Accept Bid button at clerk console (string)
    public const RES_PLACE_BID_BUTTON_INFO = 'UsrBd';
    // User id of high absentee bid owner (hash)
    public const RES_HIGH_ABSENTEE_USER_HASH = 'HAbsBH';
    // ali.listing_only (bool)
    public const RES_LISTING_ONLY = 'LstOnly';
    // Text for countdown function (string)
    public const RES_BID_COUNTDOWN = 'LblCnt';
    // ali.seo_url (string)
    public const RES_SEO_URL = 'SeoUrl';
    // Paths for small images (string[])
    public const RES_IMAGE_PATHS = 'Imgs';
    // Paths for big images (string[])
    public const RES_IMAGE_BIG_PATHS = 'ImgsB';
    // Paths for default image of next lot
    public const RES_NEXT_LOT_DEFAULT_IMAGE_PATH = 'NextLotImg';
    // Paths for thumb images (string[])
    public const RES_IMAGE_THUMB_PATHS = 'Thumbs';
    // Path for preload images (string[])
    public const RES_IMAGE_PRELOAD_PATHS = 'PImgs';
    // Path for preload big images (string[])
    public const RES_IMAGE_PRELOAD_BIG_PATHS = 'PImgsB';
    // Path for projector images (string[])
    public const RES_IMAGE_PROJECTOR_PATHS = 'ImgsP';
    // Path for videos (string[])
    public const RES_VIDEO_PATHS = 'Vids';
    // Path for video thumbs (string[])
    public const RES_VIDEO_THUMB_PATHS = 'VidsThumb';
    // Data for high/second absentee info rendering (string[])
    public const RES_ABSENTEE_BID_INFO = 'Abss';
    // Note to clerk (string)
    public const RES_CLERK_NOTE = 'CNote';
    // General note (string)
    public const RES_GENERAL_NOTE = 'GNote';
    // Next lot li.id (int)
    public const RES_NEXT_LOT_ITEM_ID = 'NLot';
    // (array)
    public const RES_ENTER_FLOOR_NO = 'EFlNo';
    // Default increment amount (float)
    public const RES_INCREMENT_DEFAULT = 'DInc';
    // Current increment amount (float)
    public const RES_INCREMENT_CURRENT = 'CInc';
    // Restore (previous) increment amount (float)
    public const RES_INCREMENT_RESTORE = 'RInc';
    // Next 1st increment amount (float)
    public const RES_INCREMENT_NEXT_1 = 'NInc1';
    // Next 2nd increment amount (float)
    public const RES_INCREMENT_NEXT_2 = 'NInc2';
    // Next 3rd increment amount (float)
    public const RES_INCREMENT_NEXT_3 = 'NInc3';
    // Next 4th increment amount (float)
    public const RES_INCREMENT_NEXT_4 = 'NInc4';
    //milliseconds
    public const RES_REVERSE_PING_DURATION = 'RPingD';
    /**
     * Current its values may be 'Floor' (set in PlaceFloorBid, PlaceFloorPriorityBid), 'Accept' (set in AcceptBid, PlaceAbsenteePriorityBid)
     * Get rid of this parameter, use Data['meta']['Cmd'] instead in js code
     */
    public const RES_BID_TO_STATUS = 'BidTo';
    public const RES_BID_TO_ACCEPT = 'Accept';
    public const RES_BID_TO_FLOOR = 'Floor';
    // buyer_group.id (string, TODO: int[])
    public const RES_BUYER_GROUP_IDS = 'BuyGrpRes';
    // Interest user info (string)
    public const RES_INTEREST_USER_LABEL = 'Lbl';
    // (int) TODO: bool
    public const RES_IS_BUY_NOW = 'SBuy';

    // Grouping type (string)
    public const RES_GROUP = 'Grp';
    // Grouped lot title (string)
    public const RES_GROUP_TITLE = 'GrpTle';
    // Grouping message (string)
    public const RES_GROUP_MESSAGE = 'GrpMsg';
    // Grouped lot item ids. Is used for parcel grouping too (int[])
    public const RES_GROUP_LOT_ITEM_IDS = 'Lots';
    // Quantity-choice grouping lot count (int)
    public const RES_GROUP_LOT_QUANTITY = 'Qty';
    // rtbc.group_user (hash)
    public const RES_GROUP_WINNER_USER_HASH = 'GUH';
    // auction.parcel_choice (bool)
    public const RES_IS_PARCEL_CHOICE = 'PCh';
    // auction.group_id (int)
    public const RES_GROUP_ID = 'GrpId';

    // Is snapshot undone successfully (bool)
    public const RES_IS_UNDO = 'Undo';
    // Info for rendering Undo button at clerk console (array)
    public const RES_UNDO_BUTTON_DATA = 'UndoB';
    //
    public const RES_UNDO_COMMAND = 'Cmd';

    // Pending action (int)
    public const RES_PENDING_ACTION = 'PA';
    // Pending action seconds left (int)
    public const RES_PENDING_ACTION_SECOND_LEFT = 'PASL';
    // rtbc.buyer_user (hash)
    public const RES_WINNING_AGENT_USER_HASH = 'BUH';
    // rtbc.auto_start (int) TODO: bool
    public const RES_LOT_AUTO_START = 'AS';
    // (int) TODO: bool
    public const RES_IS_RELOAD_CATALOG = 'RLots';
    // Message text (string)
    public const RES_MESSAGE = 'Msg';
    // Message sender u.id (int)
    public const RES_USER_ID = 'UId';
    // Message sender bidder# (string)
    public const RES_MESSAGE_SENDER_BIDDER_NO = 'BidNo';
    // Message sender username (string)
    public const RES_MESSAGE_SENDER_USERNAME = 'UNm';
    // Should clear message center (int) TODO: bool
    public const RES_MESSAGE_CENTER_CLEAR = 'Clear';
    // Game round status text about auction and running lot (string)
    public const RES_STATUS = 'Stat';
    // TODO: remove as redundant?
    public const RES_REFERRER = 'Referrer';
    // Is outstanding limit exceeded for bidder (bool)
    public const RES_IS_OUTSTANDING_LIMIT_EXCEEDED = 'OutLE';
    // Auction result url (string)
    public const RES_AUCTION_RESULT_URL = 'RLink';
    // Auction bid history url (string)
    public const RES_AUCTION_BID_HISTORY_URL = 'HLink';
    // My won items url
    public const RES_MY_WON_ITEMS_URL = 'WLink';
    // Lot#s of sell/pass/goto action (array) [li.id => li.item_num]
    public const RES_SOLD_LOT_NO = "SLotN";
    // Lot hammer prices of sell/pass/goto action (array) [li.id => hp]
    public const RES_SOLD_LOT_HAMMER_PRICES = 'SLot';
    // Winner user bidder# (array) [li.winning_bidder_id => aub.bidder_num]
    public const RES_SOLD_LOT_WINNER_BIDDER_NO = 'SLotW';
    // Winner username (string)
    public const RES_SOLD_LOT_WINNER_USERNAME = 'SLotU';
    // Hammer price (float)
    public const RES_HAMMER_PRICE = 'Hp';
    // Url to file sounds on action (string)
    public const RES_SOUND_URL = 'SndEf';
    // Bid denied reason (int)
    public const RES_BID_DENIED = 'BidDen';
    // (numeric string) TODO: bool
    public const RES_CONFIRM = 'Confirm';
    // ap.delay_after_bid_accepted (int)
    public const RES_DELAY_AFTER_BID_ACCEPTED = 'DelayBid';
    // "Simultaneous auction" specific attribute for auction id (int)
    public const RES_SA_AUCTION_ID = 'auc_id';
    // "Simultaneous auction" specific attribute for message (string)
    public const RES_SA_MESSAGE = 'msg';
    // "Simultaneous auction" specific attribute for user id (int)
    public const RES_SA_USER_ID = 'u_id';

    // Hybrid special
    // Seconds before running lot ends (int)
    public const RES_RUNNING_LOT_SECOND_LEFT = 'SBLE';
    // rtbc.extend_time (int)
    public const RES_EXTEND_TIME = 'ExtT';
    // rtbc.lot_start_gap_time (int)
    public const RES_LOT_START_GAP_TIME = 'LSGT';
    // a.allow_bidding_during_start_gap (bool)
    public const RES_IS_ALLOW_BIDDING_DURING_START_GAP = 'ABDSG';
    // rtbc.running_interval (string)
    public const RES_RUNNING_INTERVAL = 'RunInt';
    // Running lot order number ali.order (int)
    public const RES_RUNNING_LOT_ORDER_NUM = 'Order';
    /**
     * Current date utc timestamp (int) // TODO: start using RES_RESPONSE_MICRO_TS
     */
    public const RES_NOW_TS = 'NowTs';
    /**
     * Success message (string)
     */
    public const RES_SUCCESS_MESSAGE = 'SMsg';
    // Error message (string)
    public const RES_ERROR_MESSAGE = 'ErMsg';

    // Meta data
    // Response timestamp with microseconds (float, SAM-4620)
    public const RES_RESPONSE_MICRO_TS = 'RMTs';
    //Ping timestamp
    public const RES_PING_TS = 'ResPingTs';
    //Reverse ping timestamp
    public const RES_REVERSE_PING_TS = 'ResRevPingTs';
    // Admin Clerk console - Connected User List - bidder# replacement for un-assigned to auction user
    public const CUL_ABSENT_BIDDER_NO = 'Z';

    /**
     * Viewer-repeater client identifier (int) (SAM-10677)
     * Identifies viewer client connection by resource id. Only this viewer must receive SyncS response.
     */
    public const RES_VIEWER_RESOURCE_ID = 'ResVRID';

    // Auction lot high bidder address
    public const ABA_BILLING = 'billing';
    public const ABA_SHIPPING = 'shipping';

    /** @var string[] */
    public static array $auctionBidderAddresses = [
        self::ABA_BILLING,
        self::ABA_SHIPPING
    ];

    // ID of quality indicator html container
    public const CONNECTION_QUALITY_INDICATOR_CONTAINER_ID = 'quality-indicator';
    public const SERVER_CONNECTION_QUALITY_INDICATOR_CONTAINER_ID = 'server-quality-indicator';

    //SAM-5067
    public const USER_HASH_LENGTH = 12;
    public const USER_HASH_COMPONENTS_DELIMITER = '.';

    public const SELL_LOT_QUANTITY_GROUPING_CONFIRMATION = 0;
    public const SELL_LOT_CHOICE_GROUPING_CONFIRMATION = 1;
    public const SELL_LOT_CANCEL_CONFIRMATION = 2;
    public const SELL_LOT_CONTINUE_SALE_CONFIRMATION = 3;

    // Lot Activity status (rtbc.lot_active)
    public const LA_IDLE = 0;
    public const LA_STARTED = 1;
    public const LA_PAUSED = 2;
    public const LA_BY_AUTO_START = 3;
}
