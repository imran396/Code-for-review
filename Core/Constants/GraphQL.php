<?php
/**
 * SAM-10319: Implement a GraphQL prototype for a list of auctions
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 20, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

use Sam\Core\Constants;

/**
 * Class GraphQL
 * @package Sam\Core\Constants
 */
class GraphQL
{
    public const AUCTION_LIST_STATUS_FILTER = 'status';
    public const AUCTION_LIST_AUCTION_TYPE_FILTER = 'auctionType';
    public const AUCTION_LIST_ACCOUNT_FILTER = 'accountId';
    public const AUCTION_LIST_AUCTIONEER_FILTER = 'auctioneerId';
    public const AUCTION_LIST_ONLY_REGISTERED_IN_FILTER = 'onlyRegisteredIn';

    public const AUCTION_LIST_STATUS_FILTER_ALL = 'ALL';
    public const AUCTION_LIST_STATUS_FILTER_BIDDING_UPCOMING = 'BIDDING_UPCOMING';
    public const AUCTION_LIST_STATUS_FILTER_BIDDING = 'BIDDING';
    public const AUCTION_LIST_STATUS_FILTER_UPCOMING = 'UPCOMING';
    public const AUCTION_LIST_STATUS_FILTER_CLOSED = 'CLOSED';

    public const AUCTION_LIST_TYPE_FILTER_ALL = 'ALL';
    public const AUCTION_LIST_TYPE_FILTER_LIVEONLY = 'LIVEONLY';
    public const AUCTION_LIST_TYPE_FILTER_TIMEDONLY = 'TIMEDONLY';
    public const AUCTION_LIST_TYPE_FILTER_HYBRIDONLY = 'HYBRIDONLY';

    public const AUCTION_TYPE_ENUM_VALUES = [
        Constants\Auction::TIMED => 'TIMED',
        Constants\Auction::LIVE => 'LIVE',
        Constants\Auction::HYBRID => 'HYBRID',
    ];
    public const AUCTION_EVENT_TYPE_ENUM_VALUES = [
        Constants\Auction::ET_SCHEDULED => 'SCHEDULED',
        Constants\Auction::ET_ONGOING => 'ONGOING',
    ];

    public const AUCTION_PROGRESS_STATUS_ENUM_VALUES = [
        Constants\Auction::STATUS_IN_PROGRESS => 'IN_PROGRESS',
        Constants\Auction::STATUS_UPCOMING => 'UPCOMING',
        Constants\Auction::STATUS_CLOSED => 'CLOSED',
    ];

    public const AUCTION_STATUS_ENUM_VALUES = [
        Constants\Auction::AS_ACTIVE => 'ACTIVE',
        Constants\Auction::AS_STARTED => 'STARTED',
        Constants\Auction::AS_CLOSED => 'CLOSED',
        Constants\Auction::AS_ARCHIVED => 'ARCHIVED',
        Constants\Auction::AS_PAUSED => 'PAUSED',
    ];

    public const AUCTION_DATE_ASSIGNMENT_STRATEGY_ENUM_VALUES = [
        Constants\Auction::DAS_AUCTION_TO_ITEMS => 'AUCTION_TO_ITEMS',
        Constants\Auction::DAS_ITEMS_TO_AUCTION => 'ITEMS_TO_AUCTION',
    ];

    public const AUCTION_ABSENTEE_BID_DISPLAY_ENUM_VALUES = [
        Constants\SettingAuction::ABD_DO_NOT_DISPLAY => 'DO_NOT_DISPLAY',
        Constants\SettingAuction::ABD_NUMBER_OF_ABSENTEE => 'NUMBER_OF_ABSENTEE',
        Constants\SettingAuction::ABD_NUMBER_OF_ABSENTEE_HIGH => 'NUMBER_OF_ABSENTEE_HIGH',
        Constants\SettingAuction::ABD_NUMBER_OF_ABSENTEE_LINK => 'NUMBER_OF_ABSENTEE_LINK',
    ];

    public const LOT_LIST_ACCOUNT_FILTER = 'accountId';
    public const LOT_LIST_AUCTIONEER_FILTER = 'auctioneerId';
    public const LOT_LIST_CATEGORY_ID_FILTER = 'categoryId';
    public const LOT_LIST_CATEGORY_MATCH_FILTER = 'categoryMatch';
    public const LOT_LIST_ONLY_FEATURED_FILTER = 'onlyFeatured';
    public const LOT_LIST_ONLY_UNASSIGNED_FILTER = 'onlyUnAssigned';
    public const LOT_LIST_AUCTION_FILTER = 'auctionId';
    public const LOT_LIST_AUCTION_TYPE_FILTER = 'auctionType';
    public const LOT_LIST_LOT_NO_FILTER = 'lotNo';
    public const LOT_LIST_MIN_PRICE_FILTER = 'minPrice';
    public const LOT_LIST_MAX_PRICE_FILTER = 'maxPrice';
    public const LOT_LIST_EXCLUDE_CLOSED_LOTS_FILTER = 'excludeClosedLots';
    public const LOT_LIST_SEARCH_KEY_FILTER = 'searchKey';
    public const LOT_LIST_TIMED_OPTION_FILTER = 'timedOption';
    public const LOT_LIST_CUSTOM_FIELDS_FILTER = 'customFields';

    public const LOT_LIST_AUCTION_TYPE_FILTER_LIVE = 'LIVE';
    public const LOT_LIST_AUCTION_TYPE_FILTER_TIMED = 'TIMED';
    public const LOT_LIST_AUCTION_TYPE_FILTER_HYBRID = 'HYBRID';

    public const LOT_LIST_TIMED_OPTION_FILTER_REGULAR_BIDDING = 'REGULAR_BIDDING';
    public const LOT_LIST_TIMED_OPTION_FILTER_BUY_NOW = 'BUY_NOW';
    public const LOT_LIST_TIMED_OPTION_FILTER_MAKE_OFFER = 'MAKE_OFFER';

    public const LOT_LIST_CATEGORY_MATCH_FILTER_ALL = 'ALL';
    public const LOT_LIST_CATEGORY_MATCH_FILTER_ANY = 'ANY';

    public const LOT_LIST_ORDER_NUM_SORT = 'ORDER_NUM';
    public const LOT_LIST_TIME_LEFT_SORT = 'TIME_LEFT';
    public const LOT_LIST_TIME_LEFT_DESC_SORT = 'TIME_LEFT_DESC';
    public const LOT_LIST_LOT_NUM_SORT = 'LOT_NUM';
    public const LOT_LIST_LOT_NAME_SORT = 'LOT_NAME';
    public const LOT_LIST_AUCTION_SORT = 'AUCTION';
    public const LOT_LIST_NEWEST_SORT = 'NEWEST';
    public const LOT_LIST_HIGHEST_PRICE_SORT = 'HIGHEST_PRICE';
    public const LOT_LIST_LOWEST_PRICE_SORT = 'LOWEST_PRICE';
    public const LOT_LIST_BIDS_SORT = 'BIDS';
    public const LOT_LIST_VIEWS_SORT = 'VIEWS';
    public const LOT_LIST_DISTANCE_SORT = 'DISTANCE';

    public const LOT_STATUS_ENUM_VALUES = [
        Constants\Lot::LS_ACTIVE => 'ACTIVE',
        Constants\Lot::LS_UNSOLD => 'UNSOLD',
        Constants\Lot::LS_SOLD => 'SOLD',
        Constants\Lot::LS_RECEIVED => 'RECEIVED',
    ];

    public const MY_ITEMS_PAGE_ALL = 'ALL';
    public const MY_ITEMS_PAGE_WON = 'WON';
    public const MY_ITEMS_PAGE_NOTWON = 'NOTWON';
    public const MY_ITEMS_PAGE_BIDDING = 'BIDDING';
    public const MY_ITEMS_PAGE_WATCHLIST = 'WATCHLIST';
    public const MY_ITEMS_PAGE_CONSIGNED = 'CONSIGNED';

    public const TAX_SCHEMA_AMOUNT_SOURCE_ENUM_VALUES = [
        Constants\StackedTax::AS_HAMMER_PRICE => 'HAMMER_PRICE',
        Constants\StackedTax::AS_BUYERS_PREMIUM => 'BUYERS_PREMIUM',
        Constants\StackedTax::AS_SERVICES => 'SERVICES',
    ];
}
