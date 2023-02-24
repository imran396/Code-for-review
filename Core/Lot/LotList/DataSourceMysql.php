<?php
/**
 * Abstract base class to help retrieve information for an ordered lot list from a MySQL database.
 * Can be used for my items pages, search results, catalog
 * Could be extended to be used with the feeds or any other type of lot list.
 *
 * Set core->general->debugLevel = 5 to see count and result queries in log
 *
 * SAM-2801: "My items" pages query optimization
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Tom Blondeau, Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Apr 15, 2015
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Lot\LotList;

use Account;
use Auction;
use AuctionLotItem;
use InvalidArgumentException;
use Location;
use LotItem;
use LotItemCustField;
use QMySqli5DatabaseResult;
use RuntimeException;
use Sam\Application\Access\Auction\AuctionAccessCheckerQueryBuilderHelperCreateTrait;
use Sam\AuctionLot\Order\Query\AuctionLotOrderMysqlQueryBuilderCreateTrait;
use Sam\Bidder\AuctionBidder\Query\AuctionBidderQueryBuilderHelperCreateTrait;
use Sam\Core\Constants;
use Sam\Core\CustomField\Decimal\CustomDataDecimalPureCalculator;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\DataSource\DataSourceInterface;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\CustomField\Lot\PostalCode\Distance\PostalCodeDistanceQueryBuilderCreateTrait;
use Sam\CustomField\Lot\Validate\LotCustomFieldExistenceCheckerCreateTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Search\Query\Build\Helper\LotSearchQueryBuilderHelper;
use Sam\Lot\Search\Query\Build\Helper\LotSearchQueryBuilderHelperCreateTrait;
use Sam\SearchIndex\Engine\Entity\LotItem\LotItemEntitySearchQueryBuilderCreateTrait;
use Sam\SearchIndex\Engine\Fulltext\FulltextSearchQueryBuilderCreateTrait;
use Sam\SearchIndex\SearchIndexManagerCreateTrait;
use Sam\SharedService\PostalCode\PostalCodeSharedServiceClientAwareTrait;
use Sam\User\Access\UnknownContextAccessCheckerAwareTrait;
use SettingAuction;

/**
 * Class DataSourceMysql
 * @package Sam\Core\Lot\LotList
 */
abstract class DataSourceMysql extends CustomizableClass implements DataSourceInterface
{
    use AuctionAccessCheckerQueryBuilderHelperCreateTrait;
    use AuctionBidderQueryBuilderHelperCreateTrait;
    use AuctionLotOrderMysqlQueryBuilderCreateTrait;
    use BaseCustomFieldHelperAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use DbConnectionTrait;
    use FulltextSearchQueryBuilderCreateTrait;
    use LotCategoryLoaderAwareTrait;
    use LotCustomFieldExistenceCheckerCreateTrait;
    use LotItemEntitySearchQueryBuilderCreateTrait;
    use LotSearchQueryBuilderHelperCreateTrait;
    use PostalCodeDistanceQueryBuilderCreateTrait;
    use PostalCodeSharedServiceClientAwareTrait;
    use SearchIndexManagerCreateTrait;
    use UnknownContextAccessCheckerAwareTrait;

    // Custom Field Query Types
    public const CFQT_SUBQUERY = 'subquery';
    public const CFQT_JOIN = 'join';

    // Timed Online Item Options
    public const TOIO_REGULAR_BIDDING = 1;     // means, we skip "no bidding" lots
    public const TOIO_BUY_NOW = 2;
    public const TOIO_MAKE_OFFER = 3;
    /** @var int[] */
    public static array $availableTimedOnlineOptions = [self::TOIO_REGULAR_BIDDING, self::TOIO_BUY_NOW, self::TOIO_MAKE_OFFER];

    /**
     * If filter property contains "null", we also consider it in filtering "IS NULL" condition
     * Filtering values
     */
    protected array $filter = [];
    /**
     * Filter like values
     */
    protected array $like = [];
    /**
     * installation's system account.id
     */
    protected ?int $systemAccountId = null;
    /**
     * account filter (drop down or sub domain)
     * @var int[]|null
     */
    protected ?array $filterAccountIds = null;
    /**
     * logged in user.id
     */
    protected ?int $userId = null;
    /**
     * result offset
     */
    protected int $offset = 0;
    /**
     * Result limit
     */
    protected ?int $limit = null;
    /**
     * Order key index and direction separated by a space
     */
    protected ?string $orderFields = null;
    /**
     * full text search term
     */
    protected string $searchKey = '';
    /**
     * category filter
     * @var int[]|null
     */
    protected ?array $lotCategoryIds = null;
    /**
     * category match option
     */
    protected int $categoryMatch = Constants\MySearch::CATEGORY_MATCH_ANY;
    /**
     * filter for showing only ongoing lots for auctions, that have enabled respective option
     */
    protected bool $isEnabledOnlyOngoingLots = false;
    /**
     * filter for not showing upcoming lots for auctions, that have enabled respective option
     */
    protected bool $isEnabledNotShowUpcomingLots = false;
    /**
     * exclude closed lots
     */
    protected bool $isEnabledExcludeClosedLots = false;
    /**
     * consider auction option "Hide Unsold Lots" (SAM-2877)
     */
    protected bool $isEnabledConsiderOptionHideUnsoldLots = false;
    /**
     * Filter by lot item custom fields
     */
    protected ?array $customFieldFilters = null;
    /**
     * Custom field accessing way in aggregated list query
     */
    protected string $customFieldQueryTypeForAggregatedList = self::CFQT_JOIN;
    /**
     * Custom field accessing way in filtering query
     */
    protected string $customFieldQueryTypeForFiltering = self::CFQT_JOIN;
    /**
     * Filter by min and max prices
     */
    protected array $priceFilter = ['min' => null, 'max' => null];
    /**
     * alias for distance selection (the first filled custom field of postal code)
     */
    protected ?string $orderByDistanceAlias = null;
    /**
     * name of base table for filtering query (FROM)
     */
    protected string $filterQueryBaseTable = 'auction_lot_item';
    /**
     * alias of base table for filtering query (FROM)
     */
    protected string $filterQueryBaseTableAlias = 'ali';
    /**
     * true (def) - search in public index, false - search in private index
     */
    protected bool $isPublic = true;
    /**
     * filter by timed online item properties
     * @var int[]|null
     */
    protected ?array $timedItemOptions = null;
    /**
     * filter by lot#
     */
    protected ?string $lotNum = null;
    /**
     * true - search for full lot#
     */
    protected bool $isFullLotNum = false;
    /**
     * Array with list of actual result field keys
     * @var string[]
     */
    protected array $resultSetFields = [];
    /**
     * @var LotItemCustField[]
     */
    protected array $lotCustomFields = [];
    /**
     * Array user access definitions
     */
    protected ?array $userAccesses = null;
    /**
     * Array of auction access checking resource types
     */
    protected array $auctionAccessChecks = [];
    /**
     * Array mapping result fields with the query needed to retrieve ('select')
     * and tables needed to join to retrieve the information ('join')
     * and prerequisite fields, that are required to calculate result field ('prerequisite')
     */
    protected array $resultFieldsMapping = [];
    /**
     * array mapping fields available for sorting the filtered result, the necessary order string and
     * tables needed to be joined to retrieve that information
     */
    protected array $orderFieldsMapping = [];
    /**
     * Mapping table names with the join necessary for the query
     */
    protected array $joinsMapping = [];
    /**
     * Return only lots, that have hammer price
     */
    protected bool $isOnlyWithHammerPrice = false;
    /**
     * Array of query parts for lot search
     * @var string[]|null
     */
    protected ?array $filterQueryParts = null;
    /**
     * Array of query parts for aggregated list
     * @var string[]
     */
    protected ?array $aggregatedListQueryParts = null;
    protected ?LotSearchQueryBuilderHelper $queryBuilderHelper = null;
    /**
     * @var int[] filter by auction general status, see Constants\Auction::STATUS_..
     */
    protected array $auctionGeneralStatuses = [];
    /**
     * @var string[]
     */
    protected array $preQueries = [];
    protected array $postalCodeCoordinates = [];
    protected ?bool $isAuctionPublished = null;
    protected ?bool $isLotPublished = null;
    protected ?int $filterOverallLotStatus = null;

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->queryBuilderHelper = $this->createLotSearchQueryBuilderHelper();
        $this->initResultFieldsMapping();
        $this->initJoinsMapping();
        $this->filterLotStatusIds(Constants\Lot::$availableLotStatuses);
        return $this;
    }

    /**
     * Define mapping for result fields
     * <result-field> => [
     *   'select' => <select statement>
     *   'join' => [<join tables>]
     *   // Describe model's entities and properties required for result build. We may want to track them in observers.
     *   'observe' => [
     *     ['class' => <class1>, 'properties' => [<property1>, <property2>, ...]]
     *     ['class' => <class2>, 'properties' => [<property1>, <property2>, ...]]
     *   ]
     * TODO: describe 'observe' option, when add new placeholder to available placeholder list of lot's seo url template
     * ATTENTION: dependent joins should be considered by declaration order. TODO: re-order joins based on requisites defined in 'join' mappings
     */
    protected function initResultFieldsMapping(): void
    {
        $lotStartDateExpr = $this->queryBuilderHelper->getLotStartDateExpr();
        $lotEndDateExpr = $this->queryBuilderHelper->getLotEndDateExpr();

        // @formatter:off
        $this->resultFieldsMapping = [
            'absentee_bids_display' => [
                'select' => 'a.absentee_bids_display',
                'join' => ['auction'],
            ],
            'account_company_name' => [
                'select' => 'acc.company_name',
                'join' => [
                    'lot_item',
                    'account'
                ],
                'observe' => [
                    ['class' => Account::class, 'properties' => ['CompanyName']],
                ],
            ],
            'account_id' => [
                'select' => 'li.account_id',
                'join' => ['lot_item'],
                'observe' => [
                    ['class' => LotItem::class, 'properties' => ['AccountId']],
                ],
            ],
            'account_name' => [
                'select' => 'acc.name',
                'join' => [
                    'lot_item',
                    'account'
                ],
                'observe' => [
                    ['class' => Account::class, 'properties' => ['Name']],
                ],
            ],
            'account_buy_now_select_quantity_enabled' => [
                'select' => 'acc.buy_now_select_quantity_enabled',
                'join' => ['account'],
                'observe' => [
                    ['class' => Account::class, 'properties' => ['BuyNowSelectQuantityEnabled']],
                ],
            ],
            'alid' => [
                'select' => 'ali.id',
            ],
            'allow_force_bid' => [
                'select' => 'a.allow_force_bid',
                'join' => ['auction'],
            ],
            'ap_buy_now_restriction' => [
                'select' => 'setrtb.buy_now_restriction',
                'join' => [
                    'lot_item',
                    'setting_rtb'
                ],
            ],
            'ap_buy_now_unsold' => [
                'select' => 'seta.buy_now_unsold',
                'join' => [
                    'lot_item',
                    'setting_auction'
                ],
            ],
            'ap_conditional_sales' => [
                'select' => 'seta.conditional_sales',
                'join' => [
                    'lot_item',
                    'setting_auction'
                ],
            ],
            'ap_confirm_timed_bid' => [
                'select' => 'seta.confirm_timed_bid',
                'join' => [
                    'lot_item',
                    'setting_auction'
                ],
            ],
            'ap_inline_bid_confirm' => [
                'select' => 'seta.inline_bid_confirm',
                'join' => [
                    'lot_item',
                    'setting_auction'
                ],
            ],
            'ap_main_display_quantity' => [
                'select' => 'seta_main.display_quantity',
                'join' => [
                    'setting_auction_main'
                ],
                /**
                 * TODO: IK, 2021-07: This option is from main account, it affects entities of all accounts.
                 * When we'll cache pre-built template with values dependent on this setting,
                 * then we'll need to invalidate and rebuilt cache for entities of all accounts while running changes observing logic.
                 * Thus we need to mark this fact, e.g. by 'affectAll' => true.
                 */
                'observe' => [
                    [
                        'class' => SettingAuction::class,
                        'properties' => ['DisplayQuantity']
                    ],
                ],
            ],
            'first_name' => [
                'select' => 'uhbi.first_name',
                'join' => ['user_high_bidder_info'],
            ],
            'last_name' => [
                'select' => 'uhbi.last_name',
                'join' => ['user_high_bidder_info'],
            ],
            'asking_bid' => [
                'select' => 'alic.asking_bid',
                'join' => ['auction_lot_item_cache'],
            ],
            'auc_en_dt' => [
                'select' => 'a.end_date',
                'join' => ['auction'],
            ],
            'auction_start_closing_date' => [
                'select' => 'IFNULL(adyn.extend_all_start_closing_date, a.start_closing_date)',
                'join' => [
                    'auction',
                    'auction_dynamic'
                ],
            ],
            'auc_tz_location' => [
                'select' => 'atz.location',
                'join' => [
                    'auction',
                    'auction_timezone'
                ],
            ],
            'auc_end_date' => [
                'select' =>
                    'IF(a.auction_type = "' . Constants\Auction::TIMED . '", ' .
                    'a.end_date, ' .
                    'a.start_closing_date)',
                'join' => ['auction'],
            ],
            'auc_st_dt' => [
                'select' => 'a.start_date',
                'join' => ['auction'],
            ],
            'auc_tz' => [
                'select' => 'a.timezone_id',
                'join' => ['auction'],
            ],
            'auc_tz_name' => [
                'select' => 'atz.location',
                'join' => [
                    'auction',
                    'auction_timezone'
                ],
            ],
            'auc_status_order' => [
                'select' =>
                    'IF (a.auction_type = "' . Constants\Auction::TIMED . '", ' .
                        'IF (alic.start_date >= @NowUtc, ' .
                            Constants\Auction::STATUS_UPCOMING . ', ' .
                            'IF (alic.end_date > @NowUtc, ' .
                                'IF (ali.lot_status_id = ' . Constants\Lot::LS_ACTIVE . ', ' .
                                    Constants\Auction::STATUS_IN_PROGRESS . ', ' .
                                    Constants\Auction::STATUS_CLOSED .
                                '), ' .
                                Constants\Auction::STATUS_CLOSED .
                            ')' .
                        '), ' .
                        'IF (alic.start_date >= @NowUtc ' .
                                'AND a.auction_status_id = ' . Constants\Auction::AS_ACTIVE . ', ' .
                            Constants\Auction::STATUS_UPCOMING . ', ' .
                            'IF (a.auction_status_id IN (' . Constants\Auction::AS_STARTED . ', ' . Constants\Auction::AS_PAUSED . '), ' .
                                'IF (ali.lot_status_id = ' . Constants\Lot::LS_ACTIVE . ', ' .
                                    Constants\Auction::STATUS_IN_PROGRESS . ', ' .
                                    Constants\Auction::STATUS_CLOSED .
                                '), ' .
                                Constants\Auction::STATUS_CLOSED .
                            ')' .
                        ')' .
                    ')',
                'join' => ['auction', 'auction_lot_item_cache'],
            ],
            /**
             * You can enable fetching of `auction_cache` field if necessary,
             * but remember, that it may contain stale data, when ac.modified = null.
             */
            // 'auc_total_bid' => [
            //     'select' => 'ac.total_bid',
            //     'join' => ['auction_cache'],
            // ],
            // 'auc_total_hammer_price' => [
            //     'select' => 'ac.total_hammer_price',
            //     'join' => ['auction_cache'],
            // ],
            // 'auc_total_hammer_price_internet' => [
            //     'select' => 'ac.total_hammer_price_internet',
            //     'join' => ['auction_cache'],
            // ],
            // 'auc_total_high_estimate' => [
            //     'select' => 'ac.total_high_estimate',
            //     'join' => ['auction_cache'],
            // ],
            // 'auc_total_lots' => [
            //     'select' => 'ac.total_lots',
            //     'join' => ['auction_cache'],
            // ],
            // 'auc_total_low_estimate' => [
            //     'select' => 'ac.total_low_estimate',
            //     'join' => ['auction_cache'],
            // ],
            // 'auc_total_max_bid' => [
            //     'select' => 'ac.total_max_bid',
            //     'join' => ['auction_cache'],
            // ],
            // 'auc_total_reserve' => [
            //     'select' => 'ac.total_reserve',
            //     'join' => ['auction_cache'],
            // ],
            // 'auc_total_starting_bid' => [
            //     'select' => 'ac.total_starting_bid',
            //     'join' => ['auction_cache'],
            // ],
            // 'auc_total_views' => [
            //     'select' => 'ac.total_views',
            //     'join' => ['auction_cache'],
            // ],
            'auction_bidder_approved' => [
                'select' => $this->createAuctionBidderQueryBuilderHelper()->makeApprovedBidderExpression(),
                'join' => ['auction_bidder'],
            ],
            'auction_bidder_bidder_num' => [
                'select' => 'aub.bidder_num',
                'join' => ['auction_bidder'],
            ],
            'auction_bidder_id' => [
                'select' => 'aub.id',
                'join' => ['auction_bidder'],
            ],
            'auction_date' => [
                'select' => 'IF(a.auction_type = "' . Constants\Auction::TIMED . '", a.end_date, a.start_closing_date)',
                'join' => ['auction'],
            ],
            'auction_email' => [
                'select' => 'a.email',
                'join' => ['auction'],
            ],
            'auction_end_register_date' => [
                'select' => 'a.end_register_date',
                'join' => ['auction'],
            ],
            'auction_event_id' => [
                'select' => 'a.event_id',
                'join' => ['auction'],
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['EventId']],
                ],
            ],
            'auction_hide_unsold_lots' => [
                'select' => 'a.hide_unsold_lots',
                'join' => ['auction'],
            ],
            'auction_id' => [
                'select' => 'ali.auction_id',
                'observe' => [],    // do not observe ali.auction_id change
            ],
            'auction_info' => [
                'select' => 'li.auction_info',
                'join' => ['lot_item'],
            ],
            'auction_listing' => [
                'select' => 'a.listing_only',
                'join' => ['auction'],
            ],
            'auction_seo_meta_description' => [
                'select' => 'a.seo_meta_description',
                'join' => ['auction'],
            ],
            'auction_seo_meta_keywords' => [
                'select' => 'a.seo_meta_keywords',
                'join' => ['auction'],
            ],
            'auction_seo_meta_title' => [
                'select' => 'a.seo_meta_title',
                'join' => ['auction'],
            ],
            'auction_start_register_date' => [
                'select' => 'a.start_register_date',
                'join' => ['auction'],
            ],
            'auction_name' => [
                'select' => 'a.name',
                'join' => ['auction'],
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['Name']],
                ],
            ],
            'auction_reverse' => [
                'select' => 'IF(a.reverse, 1, 0)',
                'join' => ['auction'],
            ],
            'auction_seo_url' => [
                'select' => 'adc_by_su.value',
                'join' => ['auction_details_cache_by_seo_url'],
            ],
            'auction_sold_name' => [
                'select' => 'asold.name',
                'join' => [
                    'lot_item',
                    'auction',
                    'auction_sold'
                ],
            ],
            'auction_sold_sale_num' => [
                'select' => 'asold.sale_num',
                'join' => [
                    'lot_item',
                    'auction',
                    'auction_sold'
                ],
            ],
            'auction_sold_sale_num_ext' => [
                'select' => 'asold.sale_num_ext',
                'join' => [
                    'lot_item',
                    'auction',
                    'auction_sold'
                ],
            ],
            'auction_sold_test_auction' => [
                'select' => 'asold.test_auction',
                'join' => [
                    'lot_item',
                    'auction',
                    'auction_sold'
                ],
            ],
            'auction_status_id' => [
                'select' => 'a.auction_status_id',
                'join' => ['auction'],
            ],
            'auction_test' => [
                'select' => 'a.test_auction',
                'join' => ['auction'],
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['TestAuction']],
                ],
            ],
            'auction_type' => [
                'select' => 'a.auction_type',
                'join' => ['auction'],
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['AuctionType']],
                ],
            ],
            'best_offer' => [
                'select' => 'IF(toi.best_offer, 1, 0)',
                'join' => ['timed_online_item'],
            ],
            'bid_count' => [
                'select' => 'alic.bid_count',
                'join' => ['auction_lot_item_cache'],
            ],
            'bidding_paused' => [
                'select' => 'a.bidding_paused',
                'join' => ['auction'],
            ],
            'bidder_terms_are_agreed' => [
                'select' => 'IF(alibt.agreed_on IS NULL, 0, 1)',
                'join' => ['auction_lot_item_bidder_terms'],
            ],
            'bidder_terms_agreed_on' => [
                'select' => 'alibt.agreed_on',
                'join' => ['auction_lot_item_bidder_terms'],
            ],
            'bulk_master_asking_bid' => [
                'select' => 'alic.bulk_master_asking_bid',
                'join' => ['auction_lot_item_cache'],
            ],
            'bulk_master_id' => [
                'select' => 'ali.bulk_master_id',
            ],
            'bulk_master_auction_id' => [
                'select' => 'bmali.auction_id',
                'join' => ['bulk_master_auction_lot_item', 'bulk_master_lot_item'],
            ],
            'bulk_master_lot_item_id' => [
                'select' => 'bmali.lot_item_id',
                'join' => ['bulk_master_auction_lot_item', 'bulk_master_lot_item'],
            ],
            'bulk_master_lot_num' => [
                'select' => 'bmali.lot_num',
                'join' => ['bulk_master_auction_lot_item', 'bulk_master_lot_item'],
            ],
            'bulk_master_lot_num_ext' => [
                'select' => 'bmali.lot_num_ext',
                'join' => ['bulk_master_auction_lot_item', 'bulk_master_lot_item'],
            ],
            'bulk_master_lot_num_prefix' => [
                'select' => 'bmali.lot_num_prefix',
                'join' => ['bulk_master_auction_lot_item', 'bulk_master_lot_item'],
            ],
            'bulk_master_name' => [
                'select' => 'bmli.name',
                'join' => ['bulk_master_auction_lot_item', 'bulk_master_lot_item'],
            ],
            'bulk_master_seo_url' => [
                'select' => 'bmalic.seo_url',
                'join' => ['bulk_master_auction_lot_item_cache', 'bulk_master_auction_lot_item', 'bulk_master_lot_item'],
            ],
            'buy_amount' => [
                'select' => 'ali.buy_now_amount',
                'observe' => [
                    ['class' => AuctionLotItem::class, 'properties' => ['BuyNowAmount']],
                ],
            ],
            'changes' => [
                'select' => 'li.changes',
                'join' => ['lot_item'],
            ],
            'consignor_id' => [
                'select' => 'li.consignor_id',
                'join' => ['lot_item'],
            ],
            'consignor_username' => [
                'select' => 'uc.username',
                'join' => [
                    'lot_item',
                    'user_consignor'
                ],
            ],
            'cost' => [
                'select' => 'li.cost',
                'join' => ['lot_item'],
            ],
            'created_username' => [ // TODO: rename to 'creator_username'
                'select' => 'ucr.username',
                'join' => [
                    'lot_item', // need for joining with user_creator
                    'user_creator',
                ],
            ],
            'currency' => [
                'select' => 'a.currency',
                'join' => ['auction'],
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['Currency']],
                ],
            ],
            'current_bid' => [
                'select' => 'alic.current_bid',
                'join' => ['auction_lot_item_cache'],
            ],
            'current_bid_id' => [
                'select' => 'ali.current_bid_id',
            ],
            'current_bidder_id' => [
                'select' => 'alic.current_bidder_id',
                'join' => ['auction_lot_item_cache'],
            ],
            'current_max_bid' => [
                'select' => 'alic.current_max_bid',
                'join' => ['auction_lot_item_cache'],
            ],
            'current_bid_placed' => [
                'select' => 'alic.current_bid_placed',
                'join' => ['auction_lot_item_cache'],
            ],
            'current_transaction_bid' => [
                'select' => 'bt.bid',
                'join' => ['bid_transaction'],
            ],
            'end_prebidding_date' => [
                'select' => 'IF(a.auction_type IN ("' . Constants\Auction::LIVE . '", "' . Constants\Auction::HYBRID . '"), '.
                            'IFNULL(ali.end_prebidding_date, a.end_prebidding_date), NULL)',
                'join' => ['auction'],
            ],
            'event_type' => [
                'select' => 'a.event_type',
                'join' => ['auction'],
            ],
            'ex_rate' => [
                'select' => 'IF(a.currency IS NOT NULL, IFNULL(curr.ex_rate, 0), 1)',
                'join' => ['auction', 'currency'],
            ],
            'extend_all' => [
                'select' => 'a.extend_all',
                'join' => ['auction'],
            ],
            'extend_time' => [
                'select' => 'a.extend_time',
                'join' => ['auction'],
            ],
            'general_note' => [
                'select' => 'ali.general_note',
            ],
            'hammer_price' => [
                'select' => 'li.hammer_price',
                'join' => ['lot_item'],
            ],
            'high_estimate' => [
                'select' => 'li.high_estimate',
                'join' => ['lot_item'],
            ],
            'id' => [
                'select' => 'li.id',
                'join' => ['lot_item'],
            ],
            'image_count' => [
                'select' => '(SELECT COUNT(1) FROM lot_image WHERE lot_item_id = li.id)',
                'join' => ['lot_item'],
            ],
            'img_id' => [
                'select' => 'IFNULL(img.lot_image_id, 0)',
                'join' => [
                    'lot_item',
                    'imgs_tmp',
                ],
            ],
            'internet_bid' => [
                'select' => 'li.internet_bid',
                'join' => ['lot_item'],
            ],
            'inv_id' => [
                'select' => 'i.id',
                'join' => [
                    'lot_item',
                    'invoice_item',
                    'invoice'],
            ],
            'is_bulk_master' => [
                'select' => 'ali.is_bulk_master',
            ],
            'item_num' => [
                'select' => 'li.item_num',
                'join' => ['lot_item'],
                'observe' => [
                    ['class' => LotItem::class, 'properties' => ['ItemNum']],
                ],
            ],
            'item_num_ext' => [
                'select' => 'li.item_num_ext',
                'join' => ['lot_item'],
                'observe' => [
                    ['class' => LotItem::class, 'properties' => ['ItemNumExt']],
                ],
            ],
            'li_id' => [
                'select' => 'ali.lot_item_id',
                'join' => ['lot_item'],
            ],
            'listing' => [
                'select' => 'ali.listing_only',
            ],
            'location_id' => [
                'select' => 'l.id',
                'join' => ['location'],
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['LocationId']],
                ],
            ],
            'location_address' => [
                'select' => 'l.address',
                'join' => ['location'],
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['LocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'Address']],
                ],
            ],
            'location_city' => [
                'select' => 'l.city',
                'join' => ['location'],
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['LocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'City']],
                ],
            ],
            'location_country' => [
                'select' => 'l.country',
                'join' => ['location'],
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['LocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'Country']],
                ],
            ],
            'location_county' => [
                'select' => 'l.county',
                'join' => ['location'],
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['LocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'County']],
                ],
            ],
            'location_name' => [
                'select' => 'l.name',
                'join' => ['location'],
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['LocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'Name']],
                ],
            ],
            'location_state' => [
                'select' => 'l.state',
                'join' => ['location'],
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['LocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'State']],
                ],
            ],
            'location_zip' => [
                'select' => 'l.zip',
                'join' => ['location'],
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['LocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'Zip']],
                ],
            ],
            'lot_desc' => [
                'select' => 'li.description',
                'join' => ['lot_item'],
                'observe' => [
                    ['class' => LotItem::class, 'properties' => ['Description']],
                ],
            ],
            'lot_modified' => [
                'select' => 'li.modified_on',
                'join' => ['lot_item'],
            ],
            'lot_created' => [
                'select' => 'li.created_on',
                'join' => ['lot_item'],
            ],
            // Lot end date in System TZ
            // 'lot_en_dt_sys_tz' => [
            //     'select' => "CONVERT_TZ(" . $lotEndDateExpr . ", 'UTC', systz.location)",
            //     'join' => [
            //         'auction',
            //         'auction_cache',
            //         'auction_lot_item_cache',
            //         'setting_system',
            //         'system_timezone',
            //     ],
            // ],
            'lot_en_dt' => [
                'select' => $lotEndDateExpr,
                'join' => [
                    'auction',
                    'auction_dynamic',
                    'auction_lot_item_cache'
                ],
            ],
            'lot_image_ids' => [
                'select' => "(SELECT GROUP_CONCAT(id ORDER BY `order`) FROM lot_image WHERE lot_item_id = li.id)",
                'join' => ['lot_item'],
            ],
            'lot_name' => [
                'select' => 'li.name',
                'join' => ['lot_item'],
                'observe' => [
                    ['class' => LotItem::class, 'properties' => ['Name']],
                ],
            ],
            'lot_num' => [
                'select' => 'ali.lot_num',
                'observe' => [
                    ['class' => AuctionLotItem::class, 'properties' => ['LotNum']],
                ],
            ],
            'lot_num_ext' => [
                'select' => 'ali.lot_num_ext',
                'observe' => [
                    ['class' => AuctionLotItem::class, 'properties' => ['LotNumExt']],
                ],
            ],
            'lot_num_prefix' => [
                'select' => 'ali.lot_num_prefix',
                'observe' => [
                    ['class' => AuctionLotItem::class, 'properties' => ['LotNumPrefix']],
                ],
            ],
            'lot_buy_now_select_quantity_enabled' => [
                'select' => 'ali.buy_now_select_quantity_enabled',
                'observe' => [
                    ['class' => AuctionLotItem::class, 'properties' => ['BuyNowSelectQuantityEnabled']],
                ],
            ],
            'lot_seo_url' => [
                'select' => 'alic.seo_url',
                'join' => ['auction_lot_item_cache'],
            ],
            'lot_st_dt' => [
                'select' => $lotStartDateExpr,
                'join' => ['auction'],
            ],
            'start_bidding_date' => [
                'select' => 'IFNULL(ali.start_bidding_date, a.start_bidding_date)',
                'join' => ['auction'],
            ],
            'lot_publish_date' => [
                'select' => 'IFNULL(ali.publish_date, a.publish_date)',
                'join' => ['auction'],
            ],
            'lot_unpublish_date' => [
                'select' => 'IFNULL(ali.unpublish_date, a.unpublish_date)',
                'join' => ['auction'],
            ],
            // lot start date in system timezone
            // 'lot_st_dt_sys_tz' => [
            //     'select' => "CONVERT_TZ(" . $lotStartDateExpr . ", "
            //         . "'+00:00', systz.time_offset)",
            //     'join' => [
            //         'auction',
            //         'auction_cache',
            //         'auction_lot_item_cache',
            //         'setting_system',
            //         'system_timezone',
            //     ],
            // ],
            'lot_tz' => [
                'select' => 'ali.timezone_id',
            ],
            'lot_tz_location' => [
                'select' => 'alitz.location',
                'join' => ['lot_timezone'],
            ],
            'lot_start_gap_time' => [
                'select' => 'a.lot_start_gap_time',
                'join' => ['auction'],
            ],
            'lot_status_id' => [
                'select' => 'ali.lot_status_id',
            ],
            'lots_per_interval' => [
                'select' => 'a.lots_per_interval',
                'join' => ['auction'],
            ],
            'low_estimate' => [
                'select' => 'li.low_estimate',
                'join' => ['lot_item'],
            ],
            'max_bid' => [
                'select' => 'bids.max_bid',
                'join' => ['bids_tmp'],
            ],
            'high_bidder_company' => [
                'select' => 'uhbi.company_name',
                'join' => [
                    'auction_lot_item_cache',
                    'user_high_bidder',
                    'user_high_bidder_info',
                ],
            ],
            'high_bidder_email' => [
                'select' => 'uhb.email',
                'join' => [
                    'auction_lot_item_cache',
                    'user_high_bidder'
                ],
            ],
            'high_bidder_house' => [
                'select' => 'uhbp.house',
                'join' => [
                    'auction_lot_item_cache',
                    'user_high_bidder_privileges',
                ],
            ],
            'high_bidder_username' => [
                'select' => 'uhb.username',
                'join' => [
                    'auction_lot_item_cache',
                    'user_high_bidder'
                ],
            ],
            'high_bidder_user_id' => [
                'select' => 'uhb.id',
                'join' => [
                    'auction_lot_item_cache',
                    'user_high_bidder'
                ],
            ],
            'modified_username' => [
                'select' => 'umo.username',
                'join' => [
                    'lot_item',
                    'LEFT JOIN user AS umo ON umo.id = li.modified_by',
                ],
            ],
            'next_bid_button' => [
                'select' => 'a.next_bid_button',
                'join' => ['auction'],
            ],
            'no_bidding' => [
                'select' => 'IF(toi.no_bidding, 1, 0)',
                'join' => ['timed_online_item'],
            ],
            'no_tax_oos' => [
                'select' => 'li.no_tax_oos',
                'join' => ['lot_item'],
            ],
            'note_to_clerk' => [
                'select' => 'ali.note_to_clerk',
            ],
            'notify_absentee_bidders' => [
                'select' => 'a.notify_absentee_bidders',
                'join' => ['auction'],
            ],
            'or_bid' => [
                'select' => 'bids.or_bid',
                'join' => ['bids_tmp'],
            ],
            'order_num' => [
                'select' => 'ali.order',
            ],
            'price' => [
                'select' =>
                    'IF(li.hammer_price IS NOT NULL, ' .
                        'li.hammer_price, ' .
                        '(SELECT IF(a.auction_type = "' . Constants\Auction::TIMED . '", ' .
                            '(IF(ali.current_bid_id IS NULL,' .
                                'li.starting_bid,' .
                                '(SELECT bid FROM bid_transaction WHERE id = ali.current_bid_id))),' .
                            'li.low_estimate)' .
                        ')' .
                    ')',
                'join' => ['auction', 'lot_item'],
            ],
            'qty' => [
                'select' => 'ali.quantity',
                'observe' => [
                    ['class' => AuctionLotItem::class, 'properties' => ['Quantity']],
                ],
            ],
            'qty_x_money' => [
                'select' => 'IF(ali.quantity_x_money, true, false)',
            ],
            'qty_scale' => [
                'select' => <<<SQL
COALESCE (
    ali.quantity_digits, 
    li.quantity_digits, 
    (SELECT lc.quantity_digits
        FROM lot_category lc
        INNER JOIN lot_item_category lic ON lc.id = lic.lot_category_id
        WHERE lic.lot_item_id = li.id
        ORDER BY lic.id
        LIMIT 1), 
    seta.quantity_digits)
SQL,
                'join' => ['lot_item', 'setting_auction'],
            ],
            'replacement_price' => [
                'select' => 'li.replacement_price',
                'join' => ['lot_item'],
            ],
            'reserve_not_met_notice' => [
                'select' => 'a.reserve_not_met_notice',
                'join' => ['auction'],
            ],
            'reserve_met_notice' => [
                'select' => 'a.reserve_met_notice',
                'join' => ['auction'],
            ],
            'reserve_price' => [
                'select' => 'li.reserve_price',
                'join' => ['lot_item'],
            ],
            'returned' => [
                'select' => 'li.returned',
                'join' => ['lot_item'],
            ],
            'rlcc' => [
                'select' => 'IF(a.require_lot_change_confirmation, true, false)',
                'join' => ['auction'],
            ],
            'rtb_current_lot_id' => [
                'select' => 'rtbc.lot_item_id',
                'join' => ['rtb_current'],
            ],
            'rtb_lot_active' => [
                'select' => 'rtbc.lot_active',
                'join' => ['rtb_current'],
            ],
            'rtb_lot_end_date' => [
                'select' => 'rtbc.lot_end_date',
                'join' => ['rtb_current'],
            ],
            'rtb_pause_date' => [
                'select' => 'rtbc.pause_date',
                'join' => ['rtb_current'],
            ],
            'sale_num' => [
                'select' => 'a.sale_num',
                'join' => ['auction'],
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['SaleNum']],
                ],
            ],
            'sale_num_ext' => [
                'select' => 'a.sale_num_ext',
                'join' => ['auction'],
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['SaleNumExt']],
                ],
            ],
            'sales_tax' => [
                'select' => 'li.sales_tax',
                'join' => ['lot_item'],
            ],
            'sample_lot' => [
                'select' => "ali.sample_lot",
                'observe' => [
                    ['class' => AuctionLotItem::class, 'properties' => ['SampleLot']],
                ],
            ],
            // 'seconds_after_end' => [
            //     'select' =>
            //         '@seconds_after_end := ' .
            //             '@NowUtcTs' .
            //             ' - UNIX_TIMESTAMP(' . $lotEndDateExpr . ')',
            //     'join' => [
            //         'auction',
            //         'auction_cache',
            //         'auction_lot_item_cache'],
            // ],
            'seconds_before' => [
                'select' =>
                    '@seconds_before := ' .
                        'UNIX_TIMESTAMP(' . $lotStartDateExpr . ') ' .
                        '- @NowUtcTs',
                'join' => [
                    'auction',
                    'auction_cache',
                    'auction_lot_item_cache'],
            ],
            'seconds_left' => [
                'select' =>
                    '@seconds_left := ' .
                        'UNIX_TIMESTAMP(' . $lotEndDateExpr . ') ' .
                        '- @NowUtcTs',
                'join' => [
                    'auction',
                    'auction_dynamic',
                    'auction_lot_item_cache'
                ],
            ],
            'seo_meta_description' => [
                'select' => 'li.seo_meta_description',
                'join' => ['lot_item'],
            ],
            'seo_meta_keywords' => [
                'select' => 'li.seo_meta_keywords',
                'join' => ['lot_item'],
            ],
            'seo_meta_title' => [
                'select' => 'li.seo_meta_title',
                'join' => ['lot_item'],
            ],
            'seo_url' => [
                'select' => 'ali.seo_url',
                'observe' => [
                    ['class' => AuctionLotItem::class, 'properties' => ['SeoUrl']],
                ],
            ],
            'set_id' => [
                'select' => 's.id',
                'join' => [
                    'lot_item',
                    'settlement_item',
                    'settlement'],
            ],
            'stagger_closing' => [
                'select' => 'a.stagger_closing',
                'join' => ['auction'],
            ],
            'starting_bid_normalized' => [
                'select' => 'alic.starting_bid_normalized',
                'join' => ['auction_lot_item_cache'],
            ],
            'starting_bid' => [
                'select' => 'li.starting_bid',
                'join' => ['lot_item'],
            ],
            'terms_and_conditions' => [
                'select' => 'ali.terms_and_conditions',
            ],
            'timeleft' => [
                'select' =>
                    'IF(ali.lot_status_id = ' . Constants\Lot::LS_ACTIVE
                            . ' AND a.auction_status_id IN (' . implode(',', Constants\Auction::$openAuctionStatuses) . '),'
                        . ' UNIX_TIMESTAMP(' . $lotEndDateExpr . ')'
                            . ' - @NowUtcTs,'
                        . ' @NowUtcTs'
                            . ' - UNIX_TIMESTAMP(' . $lotEndDateExpr . ')'
                            . ' + 10000000000'
                        . ')',
                'join' => [
                    'auction',
                    'auction_dynamic',
                    'auction_lot_item_cache'
                ],
            ],
            'date_assignment_strategy' => [
                'select' => 'a.date_assignment_strategy',
                'join' => ['auction'],
            ],
            'view_count' => [
                'select' => 'alic.view_count',
                'join' => ['auction_lot_item_cache'],
            ],
            'warranty' => [
                'select' => 'li.warranty',
                'join' => ['lot_item'],
            ],
            'watchlist_id' => [
                'select' => 'uw.id',
                'join' => ['watchlist'],
            ],
            'winner_bidder_num' => [
                'select' => 'abw.bidder_num',
                'join' => [
                    'lot_item',
                    'auction_bidder_winner'
                ],
            ],
            'winner_company' => [
                'select' => 'uiwin.company_name',
                'join' => [
                    'lot_item',
                    'user_winner',
                    'user_info_winner'
                ],
            ],
            'winner_customer_no' => [
                'select' => 'uwin.customer_no',
                'join' => [
                    'lot_item',
                    'user_winner'
                ],
            ],
            'winner_user_id' => [
                'select' => 'li.winning_bidder_id',
                'join' => ['lot_item']
            ],
            'winner_username' => [
                'select' => 'uwin.username',
                'join' => [
                    'lot_item',
                    'user_winner'
                ],
            ],
            'winner_email' => [
                'select' => 'uwin.email',
                'join' => [
                    'lot_item',
                    'user_winner'
                ],
            ],
            'winning_auction_id' => [
                'select' => 'li.auction_id',
                'join' => ['lot_item'],
            ],
            'orderby_recentauction_asc' => [
                'select' =>
                    'IFNULL('.
                        'IF(a.auction_type = "' . Constants\Auction::TIMED . '", ' .
                            'IFNULL('.$this->queryBuilderHelper->getTimedLotEndDateExpr().', a.end_date), ' .
                            'IFNULL(alic.start_date, a.start_closing_date)'.
                            '), ' .
                        '\'2100-01-01\''.
                    ')',
                'join' => [
                    'auction',
                    'auction_dynamic',
                    'auction_lot_item_cache'
                ],
            ],
            'orderby_recentauction_desc' => [
                'select' =>
                    'IFNULL('.
                        'IF(a.auction_type = "' . Constants\Auction::TIMED . '", ' .
                            'IFNULL('.$this->queryBuilderHelper->getTimedLotEndDateExpr().', a.end_date), ' .
                            'IFNULL(alic.start_date, a.start_bidding_date)'.
                            '), ' .
                        '\'1900-01-01\''.
                    ')',
                'join' => [
                    'auction',
                    'auction_dynamic',
                    'auction_lot_item_cache'
                ],
            ],
            'orderby_recentauction_secondary' => [
                'select' => 'IFNULL(ali.`order`, CONCAT(li.item_num, li.item_num_ext))'
            ]
        ];
        // @formatter:on
    }

    /**
     * Define mapping for joins
     *
     * Note: we should LEFT JOIN (not INNER) all tables, that are joined to ali, because ali not obligatory exists for li (e.g. consigned items)
     * TODO: implement prerequisite option for tables required for join operation.
     * Eg. end_date depends on timed_online_item, hence we need to call JOIN clause for 'timed_online_item' table earlier.
     */
    protected function initJoinsMapping(): void
    {
        // @formatter:off
        $this->joinsMapping = [
            'account' =>
                'INNER JOIN `account` acc ' .
                    'ON acc.id = li.account_id ' .
                    'AND acc.active',
            'auction' =>
                'LEFT JOIN `auction` a ' .
                    'ON a.id = ali.auction_id',
            'auction_dynamic' =>
                'LEFT JOIN `auction_dynamic` adyn ' .
                'ON adyn.auction_id = ali.auction_id',
            'auction_bidder' =>
                'LEFT JOIN `auction_bidder` aub ' .
                    'ON aub.auction_id = ali.auction_id ' .
                    'AND aub.user_id = @UserId',
            'auction_bidder_winner' =>
                'LEFT JOIN `auction_bidder` abw ' .
                    'ON abw.user_id = li.winning_bidder_id ' .
                    'AND abw.auction_id = li.auction_id',
            'auction_cache' =>
                'LEFT JOIN `auction_cache` ac ' .
                    'ON ac.auction_id = ali.auction_id',
            'auction_details_cache_by_seo_url' =>
                'LEFT JOIN `auction_details_cache` adc_by_su ' .
                    'ON adc_by_su.auction_id = a.id and adc_by_su.`key` = '
                        . $this->escape(Constants\AuctionDetailsCache::SEO_URL),
            'auction_lot_item_cache' =>
                'LEFT JOIN `auction_lot_item_cache` alic ' .
                    'ON alic.auction_lot_item_id = ali.id',
            'auction_lot_item_bidder_terms' =>
                'LEFT JOIN `auction_lot_item_bidder_terms` alibt ' .
                    'ON alibt.auction_id = ali.auction_id ' .
                    'AND alibt.lot_item_id =  ali.lot_item_id ' .
                    'AND alibt.user_id = @UserId',
            'setting_auction_main' =>
                'LEFT JOIN `setting_auction` seta_main ' .
                    'ON seta_main.account_id = ' . $this->cfg()->get('core->portal->mainAccountId'),
            'setting_auction' =>
                'LEFT JOIN `setting_auction` seta ' .
                'ON seta.account_id = li.account_id',
            'setting_rtb' =>
                'LEFT JOIN `setting_rtb` setrtb ' .
                'ON setrtb.account_id = li.account_id',
            'setting_system' =>
                'LEFT JOIN `setting_system` setsys ' .
                'ON setsys.account_id = li.account_id',
            'auction_timezone' =>
                'LEFT JOIN `timezone` AS atz ' .
                    'ON atz.id = a.timezone_id ' .
                    'AND atz.active',
            'auction_sold' =>
                'LEFT JOIN `auction` asold ' .
                    'ON asold.id = li.auction_id ' .
                    'AND asold.auction_status_id IN (' . implode(',', Constants\Auction::$notDeletedAuctionStatuses) . ')',
            'bid_transaction' =>
                'LEFT JOIN `bid_transaction` bt ' .
                    'ON bt.id = ali.current_bid_id ' .
                    'AND !failed ' .
                    'AND !deleted',
            'bulk_master_auction_lot_item' =>
                'LEFT JOIN `auction_lot_item` bmali ' .
                    'ON bmali.id = ali.bulk_master_id ' .
                    'AND bmali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ')',
            'bulk_master_auction_lot_item_cache' =>
                'LEFT JOIN `auction_lot_item_cache` bmalic ' .
                    'ON bmalic.auction_lot_item_id = ali.bulk_master_id',
            'bulk_master_lot_item' =>
                'LEFT JOIN `lot_item` bmli ' .
                    'ON bmli.id = bmali.lot_item_id ' .
                    'AND bmli.active',
            'currency' =>
                'LEFT JOIN `currency` AS curr ' .
                    'ON curr.id = a.currency ' .
                    'AND curr.active',
            'location' =>
                'LEFT JOIN `location` l ' .
                    'ON (l.id = li.location_id ' .
                        ' OR (l.entity_id = li.id AND l.entity_type = '
                            . $this->escape(Constants\Location::TYPE_LOT_ITEM) . ')) ' .
                    'AND l.active',
            'lot_item' =>
                'INNER JOIN `lot_item` li ' .
                    'ON li.id = lt.li_id ' .
                    'AND li.active',
            'lot_timezone' =>
                'LEFT JOIN `timezone` AS alitz ' .
                'ON alitz.id = ali.timezone_id ' .
                'AND alitz.active',
            'invoice' =>
                'LEFT JOIN `invoice` i ' .
                    'ON i.id = ii.invoice_id ' .
                    'AND i.invoice_status_id IN (' .
                        implode(',', Constants\Invoice::$publicAvailableInvoiceStatuses) .
                    ') ' .
                    'AND i.bidder_id = li.winning_bidder_id ' .
                    'AND IF(@UserId, i.bidder_id = @UserId, 1)',
            'invoice_item' =>
                'LEFT JOIN `invoice_item` ii ' .
                    'ON ii.lot_item_id = li.id ' .
                    'AND ii.active ' .
                    'AND ii.winning_bidder_id = li.winning_bidder_id ' .
                    'AND ii.auction_id = li.auction_id',
            'rtb_current' =>
                'LEFT JOIN `rtb_current` rtbc ' .
                    'ON rtbc.auction_id = ali.auction_id',
            'settlement' =>
                'LEFT JOIN `settlement` s ' .
                    'ON s.id = si.settlement_id ' .
                    'AND s.settlement_status_id = ' . Constants\Settlement::SS_PAID . ' ' .
                    'AND s.consignor_id = li.consignor_id ' .
                    'AND IF(@UserId, s.consignor_id = @UserId, 1)',
            'settlement_item' =>
                'LEFT JOIN `settlement_item` si ' .
                    'ON si.lot_item_id = li.id ' .
                    'AND si.auction_id = li.auction_id ' .
                    'AND si.active',
            'system_timezone' =>
                'LEFT JOIN `timezone` AS systz ' .
                    'ON systz.id = setsys.timezone_id ' .
                    'AND systz.active',
            'timed_online_item' =>
                'LEFT JOIN `timed_online_item` toi ' .
                    'ON toi.lot_item_id = ali.lot_item_id ' .
                    'AND toi.auction_id = ali.auction_id',
            'user_high_bidder' =>
                'LEFT JOIN `user` uhb ' .
                    'ON alic.current_bidder_id = uhb.id ',
            'user_consignor' =>
                'LEFT JOIN `user` uc ' .
                    'ON li.consignor_id = uc.id ' .
                    'AND uc.user_status_id = ' . Constants\User::US_ACTIVE,
            'user_creator' =>
                'LEFT JOIN `user` ucr ' .
                    'ON ucr.id = li.created_by',
            'user_high_bidder_info' =>
                'LEFT JOIN `user_info` uhbi ' .
                    'ON alic.current_bidder_id = uhbi.user_id ',
            'user_high_bidder_privileges' =>
                'LEFT JOIN `bidder` uhbp ' .
                    'ON alic.current_bidder_id = uhbp.user_id ',
            'user_winner' =>
                'LEFT JOIN `user` uwin ' .
                    'ON li.winning_bidder_id = uwin.id ' .
                    'AND uwin.user_status_id = ' . Constants\User::US_ACTIVE,
            'user_info_winner' =>
                'LEFT JOIN `user_info` uiwin ' .
                    'ON li.winning_bidder_id = uiwin.user_id ',
            'watchlist' =>
                'LEFT JOIN `user_watchlist` uw ' .
                    'ON uw.lot_item_id = ali.lot_item_id ' .
                    'AND uw.auction_id = ali.auction_id ' .
                    'AND uw.user_id = @UserId',
            'imgs_tmp' =>
                'LEFT JOIN imgs_tmp img ' .
                    'ON img.lot_item_id = li.id',
            'bids_tmp' =>
                'LEFT JOIN bids_tmp bids ' .
                    'ON bids.ali_id = ali.id',
        ];
        // @formatter:on
    }

    /**
     * Define mappings for complex ORDER BY expressions
     */
    protected function initOrderFieldsMapping(): void
    {
        // @formatter:off
        $lotOrderClauseAsc = $this->createAuctionLotOrderMysqlQueryBuilder()->buildLotOrderClause();
        $lotOrderClauseDesc = $this->createAuctionLotOrderMysqlQueryBuilder()->buildLotOrderClause(false);
        $this->setOrderFieldsMapping([
            'auc_lot_status' => [
                'asc' => [
                    'order' =>
                        'IF (a.auction_type IN ("' . Constants\Auction::LIVE . '", "' . Constants\Auction::HYBRID . '"), ' .
                        '(CASE ali.lot_status_id ' .
                            'WHEN 1 THEN ' .
                            '(CASE a.auction_status_id ' .
                                'WHEN 1 THEN IF (a.start_closing_date > @NowUtc, 1, 3) ' .
                                'WHEN 2 THEN 2 ' .
                                'WHEN 6 THEN 2 ' .
                                'WHEN 3 THEN 3 ' .
                            'END) ' .
                            'WHEN 2 THEN 3 ' .
                            'WHEN 3 THEN 3 ' .
                        'END), ' .
                        '(CASE ali.lot_status_id ' .
                            'WHEN 1 THEN ' .
                            'IF (@seconds_before > 0, ' .
                            '1, ' .
                            '(IF (@seconds_left > 0, 2, 3))) ' .
                                'WHEN 2 THEN 3 ' .
                                'WHEN 3 THEN 3 ' .
                            'END)' .
                        ') ASC, ' .
                        $lotOrderClauseAsc,
                    'join' => [
                        'auction',
                    ],
                    'prerequisite' => [
                        'seconds_before',
                        'seconds_left'
                    ],
                ],
                'desc' => [
                    'order' =>
                        'IF (a.auction_type IN ("' . Constants\Auction::LIVE . '", "' . Constants\Auction::HYBRID . '"), ' .
                            '(CASE ali.lot_status_id ' .
                                'WHEN 1 THEN ' .
                                '(CASE a.auction_status_id ' .
                                    'WHEN 1 THEN IF (a.start_closing_date > @NowUtc, 1, 3) ' .
                                    'WHEN 2 THEN 2 ' .
                                    'WHEN 6 THEN 2 ' .
                                    'WHEN 3 THEN 3 ' .
                                'END) ' .
                                'WHEN 2 THEN 3 ' .
                                'WHEN 3 THEN 3 ' .
                            'END), ' .
                            '(CASE ali.lot_status_id ' .
                                'WHEN 1 THEN ' .
                                'IF (@seconds_before > 0, ' .
                                    '1, ' .
                                    '(IF (@seconds_left > 0, 2, 3))) ' .
                                'WHEN 2 THEN 3 ' .
                                'WHEN 3 THEN 3 ' .
                            'END)' .
                        ') DESC, ' .
                        $lotOrderClauseDesc,
                    'join' => [
                        'auction',
                    ],
                    'prerequisite' => [
                        'seconds_before',
                        'seconds_left'
                    ],
                ],
            ],
            'auc_name' => [
                'asc' => [
                    'order' => 'a.name ASC, ' . $lotOrderClauseAsc,
                    'join' => ['auction']],
                'desc' => [
                    'order' => 'a.name DESC, ' . $lotOrderClauseDesc,
                    'join' => ['auction']],
            ],
            'auction' => [
                'asc' => [
                    'order' =>
                        'IF(a.auction_type = "' . Constants\Auction::TIMED . '", ' .
                        'a.end_date, ' .
                        'a.start_closing_date) ASC, ' .
                        $lotOrderClauseAsc,
                    'join' => ['auction']],
                'desc' => [
                    'order' =>
                        'IF(a.auction_type = "' . Constants\Auction::TIMED . '", ' .
                        'a.end_date, ' .
                        'a.start_closing_date) DESC, ' .
                        $lotOrderClauseDesc,
                    'join' => ['auction']],
            ],
            'bids' => [
                'asc' => [
                    'order' => 'alic.bid_count ASC, ' . $lotOrderClauseAsc,
                    'join' => ['auction_lot_item_cache']],
                'desc' => [
                    'order' => 'alic.bid_count DESC, ' . $lotOrderClauseDesc,
                    'join' => ['auction_lot_item_cache']],
            ],
            'current_bid' => [
                'asc' => [
                    'order' => 'alic.current_bid ASC, ' . $lotOrderClauseAsc,
                    'join' => ['auction_lot_item_cache']],
                'desc' => [
                    'order' => 'alic.current_bid DESC, ' . $lotOrderClauseDesc,
                    'join' => ['auction_lot_item_cache']],
            ],
            'distance' => [    // search page
                'asc' => [
                    'order' => $this->buildDistanceOrderClause(true),
                ],
                'desc' => [
                    'order' => $this->buildDistanceOrderClause(false),
                ],
            ],
            'hammer_price' => [
                'asc' => [
                    'order' => 'li.hammer_price ASC, ' . $lotOrderClauseAsc,
                    'join' => ['lot_item']],
                'desc' => [
                    'order' => 'li.hammer_price DESC, ' . $lotOrderClauseDesc,
                    'join' => ['lot_item']],
            ],
            'highest' => [    // search page
                'asc' => [
                    'order' => 'price DESC, a.start_closing_date, ' . $lotOrderClauseAsc,
                    'join' => ['auction'],
                    'prerequisite' => ['price']],
            ],
            'image_count' => [
                'asc' => [
                    'order' => 'image_count ASC',
                    'prerequisite' => ['image_count']],
                'desc' => [
                    'order' => 'image_count DESC',
                    'prerequisite' => ['image_count']],
            ],
            'lot_num' => [
                'asc' => [
                    'order' => 'ali.lot_num_prefix ASC, ali.lot_num ASC, ali.lot_num_ext ASC, ali.`order` ASC'],
                'desc' => [
                    'order' => 'ali.lot_num_prefix DESC, ali.lot_num DESC, ali.lot_num_ext DESC, ali.`order` DESC'],
            ],
            'lot_name' => [
                'asc' => [
                    'order' => 'li.name ASC, ' . $lotOrderClauseAsc,
                    'join' => ['lot_item', 'auction']],
                'desc' => [
                    'order' => 'li.name DESC, ' . $lotOrderClauseDesc,
                    'join' => ['lot_item', 'auction']],
            ],
            'low_est' => [
                'asc' => [
                    'order' => 'li.low_estimate ASC, ' . $lotOrderClauseAsc],
                'desc' => [
                    'order' => 'li.low_estimate DESC, ' . $lotOrderClauseDesc],
            ],
            'lowest' => [    // search page
                'asc' => [
                    'order' => 'price ASC, a.start_closing_date, ' . $lotOrderClauseAsc,
                    'join' => ['auction'],
                    'prerequisite' => ['price']],
            ],
            'newest' => [    // search page
                'asc' => [
                    'order' => 'a.created_on DESC, a.start_closing_date, ali.lot_item_id',
                    'join' => ['auction']],
            ],
            'order_num' => [
                'asc' => [
                    'order' => $lotOrderClauseAsc,
                ],
                'desc' => [
                    'order' => $lotOrderClauseDesc,
                ],
            ],
            'qty' => [
                'asc' => [
                    'order' => 'ali.quantity ASC, ' . $lotOrderClauseAsc],
                'desc' => [
                    'order' => 'ali.quantity DESC, ' . $lotOrderClauseDesc],
            ],
            'reserve_price' => [
                'asc' => [
                    'order' => 'li.reserve_price ASC, ' . $lotOrderClauseAsc,
                    'join' => ['lot_item']],
                'desc' => [
                    'order' => 'li.reserve_price DESC, ' . $lotOrderClauseDesc,
                    'join' => ['lot_item']],
            ],
            'recent_auction' => [
                'asc' => [
                    'order' => 'orderby_recentauction_asc ASC, orderby_recentauction_secondary ASC, ' . $lotOrderClauseAsc,
                    'prerequisite' => ['orderby_recentauction_asc', 'orderby_recentauction_secondary'],
                ],
                'desc' => [
                    'order' => 'orderby_recentauction_desc DESC, orderby_recentauction_secondary DESC, ' . $lotOrderClauseAsc,
                    'prerequisite' => ['orderby_recentauction_desc', 'orderby_recentauction_secondary'],
                ]
            ],
            'sale_no' => [
                'asc' => [
                    'order' => 'a.sale_num ASC, ' . $lotOrderClauseAsc,
                    'join' => ['lot_item', 'auction']],
                'desc' => [
                    'order' => 'a.sale_num DESC, ' . $lotOrderClauseDesc,
                    'join' => ['lot_item', 'auction']],
            ],
            'starting_bid_normalized' => [
                'asc' => [
                    'order' => 'alic.starting_bid_normalized ASC, ' . $lotOrderClauseAsc,
                    'join' => ['auction_lot_item_cache']],
                'desc' => [
                    'order' => 'alic.starting_bid_normalized DESC, ' . $lotOrderClauseDesc,
                    'join' => ['auction_lot_item_cache']],
            ],
            'timeleft' => [
                'asc' => [
                    'order' => 'timeleft ASC, ' . $lotOrderClauseAsc,
                    'prerequisite' => ['timeleft'],
                ],
                'desc' => [
                    'order' => 'timeleft  DESC, ' . $lotOrderClauseDesc,
                    'prerequisite' => ['timeleft'],
                ],
            ],
            'views' => [
                'asc' => [
                    'order' => 'alic.view_count ASC, ' . $lotOrderClauseAsc,
                    'join' => ['auction_lot_item_cache']],
                'desc' => [
                    'order' => 'alic.view_count DESC, ' . $lotOrderClauseDesc,
                    'join' => ['auction_lot_item_cache']],
            ],
            'warranty' => [
                'asc' => [
                    'order' => 'li.warranty ASC, ' . $lotOrderClauseAsc],
                'desc' => [
                    'order' => 'li.warranty DESC, ' . $lotOrderClauseDesc],
            ],
        ]);
        // @formatter:on
    }

    /**
     * @return array
     */
    public function getOrderFieldsMapping(): array
    {
        if (!$this->orderFieldsMapping) { // for null and []
            $this->initOrderFieldsMapping();
        }
        return $this->orderFieldsMapping;
    }

    /**
     * @param string $alias
     * @param array $props
     * @return static
     */
    public function addOrderFieldsMapping(string $alias, array $props): static
    {
        if (!$this->orderFieldsMapping) { // for null and []
            $this->initOrderFieldsMapping();
        }
        $this->orderFieldsMapping[$alias] = $props;
        return $this;
    }

    /**
     * @param array $mapping
     * @return static
     */
    public function setOrderFieldsMapping(array $mapping): static
    {
        $this->orderFieldsMapping = $mapping;
        return $this;
    }

    /**
     * Check initialization settings
     * @return bool
     */
    protected function checkInit(): bool
    {
        return true;
    }

    /**
     * Return number of results
     * @return int
     */
    public function getCount(): int
    {
        if (!$this->checkInit()) {
            return 0;
        }
        $queries = $this->getCountQueries();
        $countQuery = $this->flatten($queries);
        $dbResults = $this->multiQuery($countQuery);
        $dbResult = current($dbResults);
        $count = 0;
        if (
            $dbResult
            && $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_NUM)
        ) {
            $count = (int)($row[0] ?? 0);
        }
        return $count;
    }

    /**
     * Get an array with all elements of the count query
     * @return array
     */
    protected function getCountQueries(): array
    {
        $filterQueryParts = $this->getFilterQueryParts();
        $countQuery = $this->composeQuery($filterQueryParts, false);
        $queries = [
            'pre-queries' => $this->getPreQueries(),
            'count-query' => $countQuery,
            'post-queries' => $this->getPostQueries(),
        ];
        return $queries;
    }

    /**
     * Get all results in an array
     *
     * @return array
     * @see Sam_Core_Lot_LotList_DataSource::getResults()
     */
    public function getResults(): array
    {
        if (!$this->checkInit()) {
            return [];
        }
        $resultQuery = $this->flatten($this->getResultQueries());
        $dbResults = $this->multiQuery($resultQuery);
        $dbResult = current($dbResults);
        $rows = [];
        if ($dbResult) {
            while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /**
     * Get the full MySQL query to get the results
     * depending on filters, pagination, etc
     *
     * @return array
     */
    protected function getResultQueries(): array
    {
        $this->preparePrerequisites();
        $filterQueryParts = $this->getFilterQueryParts();
        $filterQuery = $this->composeQuery($filterQueryParts);
        $queries = [];
        $queries['pre-queries'] = $this->getPreQueries();
        $queries['pre-queries'][] = 'SET @order := 0';
        $queries['lots_tmp'][] = 'DROP TEMPORARY TABLE IF EXISTS lots_tmp';
        $queries['lots_tmp'][] = <<<SQL
CREATE TEMPORARY TABLE lots_tmp (
    `ali_id` INT(11),
    `li_id`  INT(11),
    `order`  INT(11),
    PRIMARY KEY (`ali_id`, `li_id`),
    INDEX `idx_lots_tmp_order` (`order`)
) ENGINE = InnoDB 
SQL;

        $queries['lots_tmp_populate'][] = <<<SQL
REPLACE INTO `lots_tmp` (`ali_id`, `li_id`, `order`)  
    SELECT `ali_id`, `li_id`, @order := @order+1  
    FROM ({$filterQuery}) `lots`;
SQL;

        if ($this->isTableInJoins('imgs_tmp')) {
            $queries['imgs_tmp'][] = 'DROP TEMPORARY TABLE IF EXISTS imgs_tmp';
            $queries['imgs_tmp'][] = <<<SQL
CREATE TEMPORARY TABLE imgs_tmp (
    lot_item_id INT(11) NOT NULL PRIMARY KEY,
    lot_image_id INT(11)
) ENGINE=InnoDB AS (
    SELECT DISTINCT lim.lot_item_id `lot_item_id`, lim.id `lot_image_id`
    FROM lot_image lim
    INNER JOIN (
        SELECT
            lt.li_id `lot_item_id`,
            MIN(CONCAT(LPAD(IFNULL(lim.order,0), 10, 0), "-", LPAD(lim.id, 10, 0))) `order`
        FROM lots_tmp lt
        INNER JOIN lot_image lim ON lim.lot_item_id = lt.li_id
        GROUP BY lim.lot_item_id
    ) io ON io.lot_item_id = lim.lot_item_id
        AND io.order = CONCAT(LPAD(IFNULL(lim.order,0), 10, 0), "-", LPAD(lim.id, 10, 0))
);
SQL;
        }

        if ($this->isTableInJoins('bids_tmp')) {
            $queries['bids_tmp'][] = 'DROP TEMPORARY TABLE IF EXISTS bids_tmp';
            $timed = Constants\Auction::TIMED;
            $queries['bids_tmp'][] = <<<SQL
CREATE TEMPORARY TABLE bids_tmp (
    ali_id INT(11) NOT NULL PRIMARY KEY,
    max_bid DECIMAL(10,2) DEFAULT NULL,
    or_bid INT(10) DEFAULT NULL
) AS (
    SELECT DISTINCT lots.ali_id `ali_id`, bt.max_bid `max_bid`, NULL `or_bid`
    FROM (
        SELECT ali.id ali_id, MAX(bt.id) bt_id
        FROM lots_tmp lt
        INNER JOIN auction_lot_item ali ON ali.id=lt.ali_id
        INNER JOIN auction a ON a.id = ali.auction_id AND a.auction_type = '{$timed}'
        INNER JOIN bid_transaction bt ON bt.lot_item_id = ali.lot_item_id AND bt.auction_id = ali.auction_id AND NOT bt.deleted AND NOT bt.failed
        WHERE bt.user_id = @UserId
        GROUP BY ali.id
    ) lots
    INNER JOIN bid_transaction bt ON bt.id=lots.bt_id
)
SQL;

            $live = Constants\Auction::LIVE;
            $hybrid = Constants\Auction::HYBRID;
            $queries['bids_tmp'][] = <<<SQL
REPLACE INTO bids_tmp (`ali_id`, `max_bid`, `or_bid`)  
    SELECT DISTINCT lots.ali_id `ali_id`, ab.max_bid `max_bid`, ab.or_id `or_bid`   
    FROM (  
        SELECT ali.id ali_id, MAX(ab.id) ab_id  
        FROM lots_tmp lt  
        INNER JOIN auction_lot_item ali ON ali.id=lt.ali_id   
        INNER JOIN auction a ON a.id=ali.auction_id AND a.auction_type IN ('{$live}', '{$hybrid}') 
        INNER JOIN absentee_bid ab ON ab.lot_item_id=ali.lot_item_id AND ab.auction_id=ali.auction_id
        WHERE ab.user_id = @UserId
        GROUP BY ali.id  
    ) lots  
    INNER JOIN absentee_bid ab ON ab.id=lots.ab_id;
SQL;
        }
        $queries['aggregating'][] = $this->getAggregatedListQuery();
        $queries['post-queries'] = $this->getPostQueries();
        return $queries;
    }

    protected function isTableInJoins(string $tableAlias): bool
    {
        foreach ($this->resultSetFields as $field) {
            $joins = $this->resultFieldsMapping[$field]['join'] ?? [];
            if (in_array($tableAlias, $joins, true)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Prepare result set, if they are required to retrieve other dependent fields
     */
    protected function preparePrerequisites(): void
    {
        $prerequisites = [];
        // Add prerequisite fields to current result set from result set fields
        foreach ($this->resultSetFields as $column) {
            if (
                array_key_exists($column, $this->resultFieldsMapping)
                && !empty($this->resultFieldsMapping[$column]['prerequisite'])
            ) {
                foreach ($this->resultFieldsMapping[$column]['prerequisite'] as $field) {
                    if (empty($this->resultSetFields[$field])) {
                        $prerequisites[] = $field;
                    }
                }
            }
        }
        $prerequisites = array_unique($prerequisites);
        $this->addResultSetFields($prerequisites);
    }

    /**
     * Return pre-queries, that define some variables
     * @return array
     */
    protected function getPreQueries(): array
    {
        // $this->preQueries = [];
        // IK: TODO: variable renamed in new (5.7) mysql version
        // $this->preQueries[] = 'SET @transaction_isolation := @@transaction_isolation';
        // Next is old version variable name
        $this->preQueries['@tx_isolation'] = 'SET @tx_isolation := @@tx_isolation';
        // TB: It seemed there were intermittent random instances of the locking the entire auction_cache when someone creates a new lot.
        // I noticed there is a transaction creating and saving the new lot and modified the transaction isolation level
        // from the default to "READ COMMITTED" in app/admin/views/drafts/lot_info_form.php::save()
        $this->preQueries['SESSION tx_isolation'] = 'SET SESSION tx_isolation=IF(@@binlog_format="STATEMENT", @tx_isolation, "READ-UNCOMMITTED")';
        $nowIso = $this->getCurrentDateUtcIso();
        $this->preQueries['@NowUtc'] = 'SET @NowUtc := ' . $this->escape($nowIso);
        $this->preQueries['@NowUtcTs'] = 'SET @NowUtcTs := UNIX_TIMESTAMP(@NowUtc)';
        if ($this->userId) {
            $this->preQueries['@UserId'] = 'SET @UserId := ' . $this->escape($this->userId);
        }
        if ($this->systemAccountId) {
            $this->preQueries['@SysAccountId'] = 'SET @SysAccountId := ' . $this->escape($this->systemAccountId);
        }
        return $this->preQueries;
    }

    /**
     * Return post-queries, that resume initial state
     * @return array
     */
    protected function getPostQueries(): array
    {
        $postQueries = [];
        $postQueries[] = 'SET SESSION tx_isolation = @tx_isolation';
        // $postQueries[] = 'SET SESSION transaction_isolation=@transaction_isolation'; // TODO: rename variable for new mysql version
        return $postQueries;
    }

    /** *****************************
     *  Logic related to base query
     */

    /**
     * Return general conditions, that are used in base query
     * @return string[]
     */
    protected function getBaseConditions(): array
    {
        $conditions = ['li.active'];
        foreach ($this->filter as $column => $value) {
            $conditions[] = $this->getFilterConditionForArray($column, $value);
        }
        $conditions[] = $this->getConditionByOnlyWithHammerPrice();
        $conditions[] = $this->getConditionByLotNum();
        $conditions = array_filter($conditions);
        return $conditions;
    }

    /**
     * @return string
     */
    protected function getBaseConditionClause(): string
    {
        $where = implode("\nAND ", $this->getBaseConditions());
        return $where;
    }

    /**
     * Return array of joins, that are required for base query
     * @return array
     */
    protected function getBaseJoins(): array
    {
        $joins = ['account'];
        $columns = array_keys($this->filter);
        if (array_intersect($columns, ['a.auction_type', 'a.auction_status_id'])) {
            $joins[] = 'auction';
        }
        return $joins;
    }

    /**
     * Return condition for filtering by lot#
     * Note: possibly, we should implement lot# separators
     * @return string
     */
    protected function getConditionByLotNum(): string
    {
        $cond = '';
        if ((string)$this->lotNum !== '') {
            $cond = 'CONCAT(ali.lot_num_prefix, ali.lot_num, ali.lot_num_ext) ';
            if ($this->isFullLotNum) {
                $cond .= '= ' . $this->escape($this->lotNum);
            } else {
                $cond .= 'LIKE ' . $this->escape('%' . $this->lotNum . '%');
            }
        }
        return $cond;
    }

    /**
     * Return condition for filtering by lot with hammer price only
     * @return string
     */
    protected function getConditionByOnlyWithHammerPrice(): string
    {
        $cond = '';
        if ($this->isOnlyWithHammerPrice) {
            $cond = 'li.hammer_price IS NOT NULL';
        }
        return $cond;
    }

    /** *******************************
     *  Logic related to filtering query
     */

    /**
     * Return query parts of filtering query. Initialize if necessary
     * @param array $queryParts
     * @return array
     */
    protected function getFilterQueryParts(array $queryParts = []): array
    {
        if (!is_array($this->filterQueryParts)) {
            $this->addCustomFieldOptionsToMapping();
            $this->checkResultSetFieldsAvailability();  // after addCustomFieldOptionsToMapping(), that completes mapping
            $queryParts = $this->initializeFilterQueryParts($queryParts);
            $queryParts = $this->completeQueryParts($queryParts);
            $this->filterQueryParts = $queryParts;
        }
        return $this->filterQueryParts;
    }

    /**
     * Initialize parts of filtering query
     * @param array $queryParts
     * @return array
     */
    protected function initializeFilterQueryParts(array $queryParts = []): array
    {
        // set defaults if not passed or empty
        if (!isset($queryParts['select'])) {
            $queryParts['select'] = 'SELECT ali.id `ali_id`, ali.lot_item_id `li_id` ';
        }
        if (empty($queryParts['from'])) {
            $queryParts['from'] = [
                'FROM auction_lot_item ali',
                'INNER JOIN lot_item li ON li.id = ali.lot_item_id',
            ];
        }
        if (!isset($queryParts['join'])) {
            $queryParts['join'] = [];
        }
        if (!isset($queryParts['where'])) {
            $queryParts['where'] = [];
        }
        if (!isset($queryParts['group'])) {
            $queryParts['group'] = '';
        }
        if (!isset($queryParts['having'])) {
            $queryParts['having'] = '';
        }
        if (!isset($queryParts['order'])) {
            $queryParts['order'] = '';
        }
        if (!isset($queryParts['limit'])) {
            $queryParts['limit'] = '';
            if ($this->limit) {
                $queryParts['limit'] = 'LIMIT ' . $this->offset . ', ' . $this->limit;
            }
        }

        if (!isset($queryParts['select_count'])) {
            $queryParts['select_count'] = 'SELECT COUNT(DISTINCT ali.id) ';
        }
        if (!isset($queryParts['from_count'])) {
            $queryParts['from_count'] = $queryParts['from'];
        }
        if (!isset($queryParts['where_count'])) {
            $queryParts['where_count'] = [];
        }
        if (!isset($queryParts['having_count'])) {
            $queryParts['having_count'] = '';
        }

        if ($this->like) {
            foreach ($this->like as $condition) {
                $queryParts['where'][] = $condition;
            }
        }

        $queryParts = $this->addAuctionAccessCheckQueryParts($queryParts);
        $queryParts = $this->addAuctionGeneralStatusFilterQueryParts($queryParts);
        $queryParts = $this->addCategoryFilterQueryParts($queryParts);
        $queryParts = $this->addPriceFilterQueryParts($queryParts);
        $queryParts = $this->addAuctionPublishedFilterQueryParts($queryParts);
        $queryParts = $this->addLotPublishedFilterQueryParts($queryParts);
        $queryParts = $this->addSearchTermFilterQueryParts($queryParts);
        $queryParts = $this->addCustomFieldFilterQueryParts($queryParts);
        $queryParts = $this->addTimedItemOptionsFilterQueryParts($queryParts);
        $queryParts = $this->addOverallLotStatusFilterQueryParts($queryParts);
        if ($this->isEnabledOnlyOngoingLots) {
            $queryParts = $this->addOnlyOngoingLotsQueryParts($queryParts);
        }
        if ($this->isEnabledNotShowUpcomingLots) {
            $queryParts = $this->addNotShowUpcomingLotsQueryParts($queryParts);
        }
        if ($this->isEnabledExcludeClosedLots) {
            $queryParts = $this->addExcludeClosedLotsQueryParts($queryParts);
        }
        if ($this->isEnabledConsiderOptionHideUnsoldLots) {
            $queryParts = $this->addConsiderOptionHideUnsoldLotsQueryParts($queryParts);
        }

        $queryParts = $this->addOrderingQueryParts($queryParts);
        $queryParts['join'] = array_unique($queryParts['join']);
        $joins = $this->buildJoins($queryParts['join'], ['auction_lot_item', 'lot_item']);
        $queryParts['from'] = array_merge($queryParts['from'], $joins);
        return $queryParts;
    }

    /**
     * Add select, join and order expressions required for ordering
     * @param array $queryParts
     * @return array
     */
    protected function addOrderingQueryParts(array $queryParts): array
    {
        $n = "\n";
        if ($this->orderFields) {
            $selects = [];    // for additional fields to select in filtering query
            $orders = [];     // for ordering expressions
            $orderKeys = explode(',', $this->orderFields);
            $orderMapping = $this->getOrderFieldsMapping();
            foreach ($orderKeys as $orderKey) {
                $arr = preg_split('/\s+/', trim($orderKey));
                $column = trim($arr[0]);
                $direction = empty($arr[1]) ? 'asc' : trim($arr[1]);
                if (!empty($orderMapping[$column])) {
                    $props = $orderMapping[$column][$direction];
                    if (!empty($props['order'])) {
                        $orders[] = $props['order'];
                    }
                    if (!empty($props['order_callback'])) {
                        $orders[] = $props['order_callback']();
                    }
                    if (!empty($props['prerequisite'])) {
                        $selects = array_merge($selects, $props['prerequisite']);
                    }
                    // Add joins, that are required by ordering expression
                    if (!empty($props['join'])) {
                        $queryParts['join'] = array_merge($queryParts['join'], $props['join']);
                    }
                } elseif (!empty($this->resultFieldsMapping[$column])) {
                    $orders[] = $column . ' ' . $direction;
                    $selects[] = $column;
                } else {    // direct access to column
                    $column = preg_replace('/[^a-z0-9_.`]/i', '', $column);   // sanitize
                    $orders[] = $column . ' ' . $direction;
                }
            }
            $queryParts['order'] = 'ORDER BY ' . implode(', ', $orders);
            if ($selects) {
                $selects = array_unique($selects);
                foreach ($selects as $column) {
                    $alias = empty($this->resultFieldsMapping[$column]['alias'])
                        ? $column : $this->resultFieldsMapping[$column]['alias'];
                    $queryParts['select'] .= ', ' . $n . $this->resultFieldsMapping[$column]['select'] . ' `' . $alias . '`';
                    if (!empty($this->resultFieldsMapping[$column]['join'])) {
                        $queryParts['join'] = array_merge(
                            $queryParts['join'],
                            $this->resultFieldsMapping[$column]['join']
                        );
                    }
                }
            }
        }
        $queryParts = $this->addOrderByRecentAuctionQueryParts($queryParts);
        return $queryParts;
    }

    /**
     * Add conditions for "only ongoing lots" filter, if respective option set for auction
     * @param array $queryParts
     * @return array
     */
    protected function addOnlyOngoingLotsQueryParts(array $queryParts): array
    {
        // @formatter:off
        $queryParts['where'][] =
            'IF(a.only_ongoing_lots, ' .
                'ali.lot_status_id = ' . Constants\Lot::LS_ACTIVE . ' ' .
                'AND IF(a.auction_type = "' . Constants\Auction::TIMED . '", ' .
                    $this->queryBuilderHelper->getTimedLotStartDateExpr() . ' < @NowUtc ' .
                        'AND ' . $this->queryBuilderHelper->getTimedLotEndDateExpr() . ' > @NowUtc, ' .
                    'true' .
                '), ' .
                'true' .
            ')';
        // @formatter:on
        $queryParts['join'][] = 'auction';
        $queryParts['join'][] = 'auction_dynamic';
        $queryParts['join'][] = 'auction_lot_item_cache';
        return $queryParts;
    }

    /**
     * Add conditions for "not show upcoming lots" filter, if respective option set for auction
     * @param array $queryParts
     * @return array
     */
    protected function addNotShowUpcomingLotsQueryParts(array $queryParts): array
    {
        // @formatter:off
        $queryParts['where'][] =
            'IF(a.not_show_upcoming_lots, ' .
                'IF(a.auction_type = "' . Constants\Auction::TIMED . '", ' .
                    $this->queryBuilderHelper->getTimedLotStartDateExpr() . ' < @NowUtc, ' .
                    'true' .
                '), ' .
                'true' .
            ')';
        // @formatter:on
        $queryParts['join'][] = 'auction';
        $queryParts['join'][] = 'auction_lot_item_cache';
        return $queryParts;
    }

    /**
     * Add conditions for "exclude closed lots" filter
     * @param array $queryParts
     * @return array
     */
    protected function addExcludeClosedLotsQueryParts(array $queryParts): array
    {
        $n = "\n";
        // @formatter:off
        $queryParts['where'][] =
            'ali.lot_status_id = ' . Constants\Lot::LS_ACTIVE
            . $n . 'AND a.auction_status_id IN (' . implode(',', Constants\Auction::$openAuctionStatuses) . ')'
            . $n . 'AND IF(a.auction_type = "' . Constants\Auction::TIMED . '", '
                . $this->queryBuilderHelper->getTimedLotEndDateExpr() . ' > @NowUtc, '
                . 'true'
            . ')';
        // @formatter:on
        $queryParts['join'][] = 'auction';
        $queryParts['join'][] = 'auction_dynamic';
        $queryParts['join'][] = 'auction_lot_item_cache';
        return $queryParts;
    }

    /**
     * Add conditions for filtering out unsold lots, if "Hide Unsold Lots" option is On for auction (SAM-2877)
     * @param array $queryParts
     * @return array
     */
    protected function addConsiderOptionHideUnsoldLotsQueryParts(array $queryParts): array
    {
        $lsUnsold = Constants\Lot::LS_UNSOLD;
        $queryParts['where'][] = "IF(a.hide_unsold_lots, ali.lot_status_id != {$lsUnsold}, true)";
        $queryParts['join'][] = 'auction';
        return $queryParts;
    }

    /**
     * Add conditions for auction access permission checking
     * @param array $queryParts
     * @return array
     */
    protected function addAuctionAccessCheckQueryParts(array $queryParts): array
    {
        if ($this->auctionAccessChecks) {
            $conditions = [];
            $auctionAccessCheckerQueryBuilderHelper = $this->createAuctionAccessCheckerQueryBuilderHelper();
            foreach ($this->auctionAccessChecks as $resourceType) {
                $conditions[] = $auctionAccessCheckerQueryBuilderHelper->makeWhereClause($resourceType, $this->userId);
            }
            $where = implode(' AND ', $conditions);
            if ($where) { // @phpstan-ignore-line
                $queryParts['where'][] = $where;
            }
            $queryParts['join'][] = 'auction';
        }
        return $queryParts;
    }

    /**
     * Add query parts for filtering by categories
     * @param array $queryParts
     * @return array
     */
    protected function addCategoryFilterQueryParts(array $queryParts): array
    {
        // Filter by categories
        if (!empty($this->lotCategoryIds)) {
            if ($this->categoryMatch === Constants\MySearch::CATEGORY_MATCH_ALL) {
                foreach ($this->lotCategoryIds as $lotCategoryId) {
                    $ids = $this->getLotCategoryLoader()
                        ->loadCategoryWithDescendantIds([$lotCategoryId], true);
                    $queryParts['join'][] = "INNER JOIN lot_item_category lic{$lotCategoryId} " .
                        "ON lic{$lotCategoryId}.lot_item_id = li.id " .
                        "AND " . $this->getFilterConditionForArray("lic{$lotCategoryId}.lot_category_id", $ids);
                }
            } else {
                $ids = $this->getLotCategoryLoader()
                    ->loadCategoryWithDescendantIds($this->lotCategoryIds, true);
                $queryParts['join'][] = "INNER JOIN lot_item_category lic " .
                    "ON lic.lot_item_id = li.id " .
                    "AND " . $this->getFilterConditionForArray("lic.lot_category_id", $ids);
            }
            $queryParts['group'] = 'ali_id, li_id';
        }
        return $queryParts;
    }

    /**
     * Add query parts for filtering by custom fields
     * or return them in new array
     *
     * @param array $queryParts
     * @return array $queryParts
     */
    public function addCustomFieldFilterQueryParts(array $queryParts): array
    {
        // @formatter:off
        $n = "\n";
        $postalCodeFieldCount = null;
        $dbTransformer = DbTextTransformer::new();

        foreach ($this->lotCustomFields as $lotCustomField) {
            if (!isset($this->customFieldFilters[$lotCustomField->Id])) {
                continue;
            }

            [$roleConds, $isRestricted] = $this->getRoleCondition($lotCustomField);
            if ($isRestricted) {
                // User's role has not enough permissions to access custom data, hence skip filtering
                continue;
            }

            $fieldName = null;
            if ($this->customFieldQueryTypeForFiltering === self::CFQT_JOIN) {
                $fieldName = $dbTransformer->toDbColumn($lotCustomField->Name);
                $alias = $this->getBaseCustomFieldHelper()->makeFieldAlias($fieldName);
                $queryParts['join'][] = $this->joinsMapping[$alias];
                $queryParts['join'][] = 'lot_item';
            }
            $values = $this->customFieldFilters[$lotCustomField->Id];
            $idEscaped = $this->escape($lotCustomField->Id);
            switch ($lotCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                    $minDbValue = $values['min'] ?? null;
                    $maxDbValue = $values['max'] ?? null;
                    $minEscaped = $this->escape($minDbValue);
                    $maxEscaped = $this->escape($maxDbValue);

                    if ($this->customFieldQueryTypeForFiltering === self::CFQT_JOIN) {
                        if ($minDbValue !== null && $maxDbValue === null) {
                            $queryParts['where'][] = "licd{$fieldName}.numeric >= {$minEscaped}";
                        } elseif ($minDbValue !== null && $maxDbValue !== null) {
                            $queryParts['where'][] = "licd{$fieldName}.numeric >= {$minEscaped} " .
                                "AND licd{$fieldName}.numeric <= {$maxEscaped}";
                        } elseif ($minDbValue === null && $maxDbValue !== null) {
                            $queryParts['where'][] = "licd{$fieldName}.numeric <= {$maxEscaped}";
                        }
                    } else {
                        /** @noinspection NestedPositiveIfStatementsInspection */
                        if ($minDbValue !== null && $maxDbValue === null) {
                            $queryParts['where'][] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                    "AND licd.lot_item_cust_field_id = {$idEscaped} " .
                                    "AND licd.numeric >= {$minEscaped} {$roleConds}) > 0)";
                        } elseif ($minDbValue !== null && $maxDbValue !== null) {
                            $queryParts['where'][] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                    "AND licd.lot_item_cust_field_id = {$idEscaped} " .
                                    "AND licd.numeric >= {$minEscaped} " .
                                    "AND licd.numeric <= {$maxEscaped} {$roleConds}) > 0)";
                        } elseif ($minDbValue === null && $maxDbValue !== null) {
                            $queryParts['where'][] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                    "AND licd.lot_item_cust_field_id = {$idEscaped} " .
                                    "AND licd.numeric <= {$maxEscaped} {$roleConds}) > 0)";
                        }
                    }
                    break;

                case Constants\CustomField::TYPE_DECIMAL:
                    $min = $values['min'] ?? null;
                    $max = $values['max'] ?? null;
                    $precision = (int)$lotCustomField->Parameters;
                    $minDbValue = CustomDataDecimalPureCalculator::new()->calcModelValue((float)$min, $precision);
                    $maxDbValue = CustomDataDecimalPureCalculator::new()->calcModelValue((float)$max, $precision);
                    $minEscaped = $this->escape($minDbValue);
                    $maxEscaped = $this->escape($maxDbValue);

                    if ($this->customFieldQueryTypeForFiltering === self::CFQT_JOIN) {
                        if ($min !== null && $max === null) {
                            $queryParts['where'][] = "licd{$fieldName}.numeric >= {$minEscaped}";
                        } elseif ($min !== null && $max !== null) {
                            $queryParts['where'][] = "licd{$fieldName}.numeric >= {$minEscaped} " .
                                "AND licd{$fieldName}.numeric <= {$maxEscaped}";
                        } elseif ($min === null && $max !== null) {
                            $queryParts['where'][] = "licd{$fieldName}.numeric <= {$maxEscaped}";
                        }
                    } else {    // self::CFQT_SUBQUERY;
                        /** @noinspection NestedPositiveIfStatementsInspection */
                        if ($min !== null && $max === null) {
                            $queryParts['where'][] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                    "AND licd.lot_item_cust_field_id = {$idEscaped} " .
                                    "AND licd.numeric >= {$minEscaped} {$roleConds}) > 0)";
                        } elseif ($min !== null && $max !== null) {
                            $queryParts['where'][] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                    "AND licd.lot_item_cust_field_id = {$idEscaped} " .
                                    "AND licd.numeric >= {$minEscaped} " .
                                    "AND licd.numeric <= {$maxEscaped} {$roleConds}) > 0)";
                        } elseif ($min === null && $max !== null) {
                            $queryParts['where'][] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                    "AND licd.lot_item_cust_field_id = {$idEscaped} " .
                                    "AND licd.numeric <= {$maxEscaped} {$roleConds}) > 0)";
                        }
                    }
                    break;

                case Constants\CustomField::TYPE_SELECT:
                    if(!is_array($values)) {
                        $values = ['in' => [trim($values)]];
                    }
                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_FULLTEXT:
                case Constants\CustomField::TYPE_FILE:
                    if(!is_array($values)) {
                        $values = ['contain' => trim($values)];
                    }
                    $contain = $values['contain'] ?? null;
                    $in = $values['in'] ?? null;
                    $notIn = $values['notIn'] ?? null;

                    if ($this->customFieldQueryTypeForFiltering === self::CFQT_JOIN) {
                        $expressionTemplate = "licd{$fieldName}.text %s";
                    } else {
                        $expressionTemplate = "((SELECT COUNT(1) " .
                            "FROM lot_item_cust_data AS licd " .
                            "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                "AND licd.lot_item_cust_field_id = {$idEscaped} " .
                                "AND (licd.text %s){$roleConds}) > 0)";
                    }
                    if($contain) {
                        $queryParts['where'][] = sprintf($expressionTemplate, 'LIKE '. $this->escape('%' . $contain . '%'));
                    }
                    if($in) {
                        $inEscaped = array_map($this->escape(...), $in);
                        $inExpression = implode(',', $inEscaped);
                        $queryParts['where'][] = sprintf($expressionTemplate, "IN ({$inExpression})");
                    }
                    if($notIn) {
                        $notInEscaped = array_map($this->escape(...), $notIn);
                        $notInExpression = implode(',', $notInEscaped);
                        $queryParts['where'][] = sprintf($expressionTemplate, "NOT IN ({$notInExpression})");
                    }
                    break;

                case Constants\CustomField::TYPE_DATE:
                    $minDate = $values['min'] ?? null;
                    $maxDate = $values['max'] ?? null;
                    $minEscaped = $this->escape($minDate);
                    $maxEscaped = $this->escape($maxDate);

                    if ($this->customFieldQueryTypeForFiltering === self::CFQT_JOIN) {
                        if ($minDate !== null && $maxDate === null) {
                            $queryParts['where'][] = "(FROM_UNIXTIME(licd{$fieldName}.numeric, '%Y-%m-%d') " .
                                ">= FROM_UNIXTIME({$minEscaped}, '%Y-%m-%d'))";
                        } elseif ($minDate !== null && $maxDate !== null) {
                            $queryParts['where'][] = "(FROM_UNIXTIME(licd{$fieldName}.numeric, '%Y-%m-%d') " .
                                ">= FROM_UNIXTIME({$minEscaped}, '%Y-%m-%d'))";
                            $queryParts['where'][] = "(FROM_UNIXTIME(licd{$fieldName}.numeric, '%Y-%m-%d') " .
                                "<= FROM_UNIXTIME({$maxEscaped}, '%Y-%m-%d'))";
                        } elseif ($minDate === null && $maxDate !== null) {
                            $queryParts['where'][] = "(FROM_UNIXTIME(licd{$fieldName}.numeric, '%Y-%m-%d') " .
                                "<= FROM_UNIXTIME({$maxEscaped}, '%Y-%m-%d'))";
                        }
                    } else {
                        /** @noinspection NestedPositiveIfStatementsInspection */
                        if ($minDate !== null && $maxDate === null) {
                            $queryParts['where'][] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                    "AND licd.lot_item_cust_field_id = {$idEscaped} " .
                                    "AND (FROM_UNIXTIME(licd.numeric, '%Y-%m-%d') " .
                                        ">= FROM_UNIXTIME({$minEscaped}, '%Y-%m-%d'))" .
                                    "{$roleConds}) > 0)";
                        } elseif ($minDate !== null && $maxDate !== null) {
                            $queryParts['where'][] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                    "AND licd.lot_item_cust_field_id = {$idEscaped} " .
                                    "AND (FROM_UNIXTIME(licd.numeric, '%Y-%m-%d') " .
                                        ">= FROM_UNIXTIME({$minEscaped}, '%Y-%m-%d')) " .
                                    "AND (FROM_UNIXTIME(licd.numeric, '%Y-%m-%d') " .
                                        "<= FROM_UNIXTIME({$maxEscaped}, '%Y-%m-%d'))" .
                                    "{$roleConds}) > 0)";
                        } elseif ($minDate === null && $maxDate !== null) {
                            $queryParts['where'][] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                    "AND licd.lot_item_cust_field_id = {$idEscaped} " .
                                    "AND (FROM_UNIXTIME(licd.numeric, '%Y-%m-%d') " .
                                        "<= FROM_UNIXTIME({$maxEscaped}, '%Y-%m-%d'))" .
                                    "{$roleConds}) > 0)";
                        }
                    }
                    break;

                case Constants\CustomField::TYPE_POSTALCODE:
                    $postalCode = $values['pcode'] ?? null;
                    $radius = $values['radius'] ?? null;
                    if ($radius !== null && $postalCode !== null) {
                        $coordinates = $this->detectPostalCodeCoordinates($postalCode);
                        if ($coordinates) {
                            $distanceQueryBuilder = $this->createPostalCodeDistanceQueryBuilder();
                            $locationTableAlias = 'lig' . $lotCustomField->Id;
                            $distanceAlias = 'distance' . $lotCustomField->Id;
                            $latitude = $coordinates['latitude'];
                            $longitude = $coordinates['longitude'];
                            $join = $distanceQueryBuilder->buildJoinClause('li.id', $locationTableAlias, $lotCustomField->Id);
                            $from = $n . 'INNER JOIN ' . $join;
                            $queryParts['from'][] = $from;
                            $queryParts['from_count'][] = $from;
                            if ($this->orderFields === 'distance') {
                                // we use distance formula in SELECT clause to be possible to ORDER BY distance, filtering by radius in HAVING
                                $select = $distanceQueryBuilder->buildSelectExpression($latitude, $longitude, $locationTableAlias, $distanceAlias);
                                $queryParts['select'] .= ', ' . $n . $select;
                                if (!empty($queryParts['having'])) {
                                    $queryParts['having'] .= 'AND ';
                                }
                                $queryParts['having'] .= $n . $distanceQueryBuilder->buildHavingClause($radius, $distanceAlias);
                                // for COUNT query, we use distance formula in WHERE
                                $where = $distanceQueryBuilder->buildWhereClause($latitude, $longitude, $radius, $locationTableAlias);
                                $queryParts['where_count'][] = $where;
                            } else {    // when there is no ORDERing by distance, query could be optimized a little
                                $where = $distanceQueryBuilder->buildWhereClause($latitude, $longitude, $radius, $locationTableAlias);
                                $queryParts['where'][] = $where;
                            }

                            // we must group results, if there is more than 1 custom field with type of Postal Code
                            if ($postalCodeFieldCount === null) {
                                $postalCodeFieldCount = $this->createLotCustomFieldExistenceChecker()
                                    ->countByType(Constants\CustomField::TYPE_POSTALCODE, true);
                            }
                            if ($postalCodeFieldCount > 1) {
                                $queryParts['group'] = $this->filterQueryBaseTableAlias . '.id';
                                $queryParts['select_count'] =
                                    "SELECT COUNT(1) " . $n .
                                    "FROM `" . $this->filterQueryBaseTable . "` AS " . $this->filterQueryBaseTableAlias . "2 " . $n .
                                    "WHERE EXISTS (" . $n .
                                    "SELECT " . $this->filterQueryBaseTableAlias . ".id " . $n . "%s" . $n . ")";
                                $queryParts['where_count'][] = $this->filterQueryBaseTableAlias . '2.id ' .
                                    '= ' . $this->filterQueryBaseTableAlias . '.id';
                            }
                        }
                    }
            }
        }
        return $queryParts;
        // @formatter:on
    }

    /**
     * Add query parts for filtering by search term
     *
     * @param array $queryParts
     * @return array $queryParts
     */
    public function addSearchTermFilterQueryParts(array $queryParts): array
    {
        $n = "\n";
        if ($this->createSearchIndexManager()->checkSearchKey($this->searchKey)) {
            $searchConds = [];
            if ($this->filterQueryBaseTable === 'lot_item') {
                $whereClauseForNumber = $this->createLotItemEntitySearchQueryBuilder()
                    ->getWhereClauseForItemNumber($this->searchKey);
            } else {
                $whereClauseForNumber = $this->createLotItemEntitySearchQueryBuilder()
                    ->getWhereClauseForLotNumber($this->searchKey);
            }
            if (!empty($whereClauseForNumber)) {
                $searchConds[] = $whereClauseForNumber;
            }
            switch ($this->cfg()->get('core->search->index->type')) {
                case Constants\Search::INDEX_FULLTEXT:
                    $searchConds[] = $this->createFulltextSearchQueryBuilder()
                        ->getWhereClause(
                            $this->searchKey,
                            Constants\Search::ENTITY_LOT_ITEM,
                            $this->isPublic,
                            $this->filterAccountIds
                        );
                    $searchJoin = $n . "INNER JOIN " . $this->createFulltextSearchQueryBuilder()->getJoinClause(
                            Constants\Search::ENTITY_LOT_ITEM,
                            'li.id',
                            $this->filterAccountIds,
                            'li.account_id'
                        );
                    $queryParts['from'][] = $searchJoin;
                    $queryParts['from_count'][] = $searchJoin;
                    break;

                default:    // SearchIndexManager::INDEX_NONE
                    $searchConds[] = $this->createLotItemEntitySearchQueryBuilder()
                        ->getWhereClause($this->searchKey);
                    $whereForCategory = $this->createLotItemEntitySearchQueryBuilder()
                        ->getWhereClauseForCategory($this->searchKey);
                    if (!empty($whereForCategory)) {
                        $searchConds[] = $whereForCategory;
                    }
                    if ($this->filterQueryBaseTable === 'lot_item') {
                        $whereForCustomField = $this->createLotItemEntitySearchQueryBuilder()
                            ->getWhereClauseForCustomFieldsOptimized($this->searchKey);
                    } else {
                        $whereForCustomField = $this->createLotItemEntitySearchQueryBuilder()
                            ->getWhereClauseForCustomFields(
                                $this->searchKey,
                                'li',
                                'ali',
                                $this->userId,
                                $this->lotCustomFields,
                                $this->getUserAccess()[0]
                            );
                    }
                    if (!empty($whereForCustomField)) {
                        $searchConds[] = $whereForCustomField;
                    }
                    break;
            }
            $queryParts['where'][] = "(" . implode($n . ' OR ', $searchConds) . ")";
        }
        return $queryParts;
    }

    /**
     * Add query parts for filtering by auction publish date
     * @param array $queryParts
     * @return array
     */
    public function addAuctionPublishedFilterQueryParts(array $queryParts): array
    {
        if ($this->isAuctionPublished !== null) {
            $currentDateIso = $this->escape($this->getCurrentDateUtcIso());
            if ($this->isAuctionPublished) {
                $queryParts['where'][] = <<<SQL
a.publish_date <= {$currentDateIso}
AND (a.unpublish_date > {$currentDateIso} OR a.unpublish_date IS NULL)
SQL;
            } else {
                $queryParts['where'][] = <<<SQL
(
    a.publish_date > {$currentDateIso} 
    OR a.publish_date IS NULL 
    OR a.unpublish_date <= {$currentDateIso}
)
SQL;
            }
        }
        return $queryParts;
    }

    /**
     * Add query parts for filtering by lot item publish dates
     * uses auction dates if NULL
     * @param array $queryParts
     * @return array
     */
    public function addLotPublishedFilterQueryParts(array $queryParts): array
    {
        if ($this->isLotPublished !== null) {
            $currentDateIso = $this->escape($this->getCurrentDateUtcIso());
            if ($this->isLotPublished) {
                $queryParts['where'][] = <<<SQL
IFNULL(ali.publish_date, a.publish_date) <= {$currentDateIso}
AND (
    IFNULL(ali.unpublish_date, a.unpublish_date) > {$currentDateIso} 
    OR (ali.unpublish_date IS NULL AND a.unpublish_date IS NULL)
)
SQL;
            } else {
                $queryParts['where'][] = <<<SQL
IFNULL(ali.publish_date, a.publish_date) > {$currentDateIso} 
OR (ali.publish_date IS NULL AND a.publish_date IS NULL)
OR IFNULL(ali.unpublish_date, a.unpublish_date) <= {$currentDateIso}
SQL;
            }
        }
        return $queryParts;
    }

    /**
     * Add query parts for filtering by min and max price
     * @param array $queryParts
     * @return array
     */
    public function addPriceFilterQueryParts(array $queryParts): array
    {
        // @formatter:off
        $minPrice = $this->priceFilter['min'];
        $maxPrice = $this->priceFilter['max'];
        if (
            Floating::gt($minPrice, 0)
            && Floating::eq($maxPrice, 0)
        ) {
            $queryParts['where'][] = <<<SQL
(if (li.hammer_price IS NOT NULL,
    (if (li.hammer_price >= {$this->escape($minPrice)}, true, false)),
    (if (bt.bid > 0,
        (if(bt.bid >= {$this->escape($minPrice)}, true, false)),
        (if(li.starting_bid > 0,
            (if (li.starting_bid >= {$this->escape($minPrice)}, true, false)),
            false
        ))
    ))
))
SQL;
        } elseif (
            Floating::gt($minPrice, 0)
            && Floating::gt($maxPrice, 0)
        ) {
            $queryParts['where'][] = <<<SQL
(if (li.hammer_price IS NOT NULL,
    (if (li.hammer_price >= {$this->escape($minPrice)},true, false)),
    (if (bt.bid > 0,
        (if(bt.bid >= {$this->escape($minPrice)}, true, false)),
        (if(li.starting_bid > 0,
            (if (li.starting_bid >= {$this->escape($minPrice)}, true, false)),
            false
        ))
    ))
))
AND (if (li.hammer_price IS NOT NULL,
    (if (li.hammer_price <= {$this->escape($maxPrice)},true, false)),
    (if (bt.bid > 0,
        (if(bt.bid <= {$this->escape($maxPrice)}, true, false)),
        (if(li.starting_bid > 0,
            (if (li.starting_bid <= {$this->escape($maxPrice)}, true, false)),
            false
        ))
    ))
))
SQL;
        } elseif (
            Floating::eq($minPrice, 0)
            && Floating::gt($maxPrice, 0)
        ) {
            $queryParts['where'][] = <<<SQL
(if (li.hammer_price IS NOT NULL,
    (if (li.hammer_price <= {$this->escape($maxPrice)},true,false)),
    (if (bt.bid > 0,
        (if(bt.bid <= {$this->escape($maxPrice)}, true, false)),
        (if(li.starting_bid > 0,
            (if (li.starting_bid <= {$this->escape($maxPrice)}, true, false)),
            false
        ))
    ))
))
SQL;
        }
        if (
            Floating::gt($minPrice, 0)
            || Floating::gt($maxPrice, 0)
        ) {
            $queryParts['join'][] = 'bid_transaction';
        }
        return $queryParts;
        // @formatter:on
    }

    /**
     * Add query parts for filtering by timed online item properties
     * @param array $queryParts
     * @return array
     */
    public function addTimedItemOptionsFilterQueryParts(array $queryParts): array
    {
        $n = "\n";
        if ($this->timedItemOptions) {
            $where = '';
            if (in_array(self::TOIO_REGULAR_BIDDING, $this->timedItemOptions, true)) {
                $where .= ' OR (toi.no_bidding IS NULL OR toi.no_bidding = false) ' . $n;
            }
            if (in_array(self::TOIO_BUY_NOW, $this->timedItemOptions, true)) {
                $where .= ' OR ali.buy_now_amount > 0 ' . $n;
            }
            if (in_array(self::TOIO_MAKE_OFFER, $this->timedItemOptions, true)) {
                $where .= ' OR toi.best_offer = true ' . $n;
            }
            $where = '(SELECT IF(a.auction_type IN ("' . Constants\Auction::LIVE . '", "' . Constants\Auction::HYBRID . '"), ' .
                'true, (toi.id > 0 AND (0 ' . $where . ')))) > 0';
            $queryParts['where'][] = $where;
            $queryParts['join'] = array_merge($queryParts['join'], ['auction', 'timed_online_item']);
        }
        return $queryParts;
    }

    /**
     * @param array $queryParts
     * @return array
     */
    protected function addAuctionGeneralStatusFilterQueryParts(array $queryParts): array
    {
        if ($this->auctionGeneralStatuses) {
            $statusList = $this->toEscapedList($this->auctionGeneralStatuses);
            $queryParts['join'][] = 'auction';
            $queryParts['where'][] = $this->getAuctionGeneralStatusClause() . ' IN (' . $statusList . ')';
        }
        return $queryParts;
    }

    /**
     * Filter by overall lot status, it considers ali.lot_status_id.
     * Attention! This filtering logic may be confusing. Before fixing it search for clarifications in SAM-5603, SAM-6247.
     * Especially for cases, where we perform filtering "from the contrary" way, i.e. for unassigned, unsold statuses, where condition = 0.
     * @param array $queryParts
     * @return array $queryParts
     */
    protected function addOverallLotStatusFilterQueryParts(array $queryParts): array
    {
        $overallLotStatus = $this->filterOverallLotStatus;
        if (!$this->filterOverallLotStatus) {
            return $queryParts;
        }
        $filterAuctionId = $this->getFilterValue('ali.auction_id');
        $auctionCondition = $filterAuctionId ? ' AND ' . $this->getFilterConditionForArray('ali_flt.auction_id', $filterAuctionId) : '';
        if ($overallLotStatus === Constants\MySearch::OLSF_ASSIGNED) {
            // Items that are assigned as a lot to at least one auction
            $queryParts['where'][] = "(SELECT COUNT(1) FROM auction_lot_item ali_flt " .
                "INNER JOIN auction a_flt ON a_flt.id = ali_flt.auction_id " .
                "AND a_flt.auction_status_id IN (" . implode(',', Constants\Auction::$notDeletedAuctionStatuses) . ") " .
                "WHERE ali_flt.lot_item_id = li.id AND ali_flt.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ") " .
                "{$auctionCondition}) > 0 ";
        } elseif ($overallLotStatus === Constants\MySearch::OLSF_UNASSIGNED) {
            if ($filterAuctionId) {
                // Unassigned lot status filter doesn't work together with auction filter
                $queryParts['where'][] = 'false';
            } else {
                // Items that are not assigned as a lot to any single auction (no auction_lot_item record for lot item)
                $queryParts['where'][] = "(SELECT COUNT(1) FROM auction_lot_item ali_flt " .
                    "INNER JOIN auction a_flt ON a_flt.id = ali_flt.auction_id " .
                    "WHERE ali_flt.lot_item_id = li.id " .
                    "AND a_flt.auction_status_id IN (" . implode(', ', Constants\Auction::$notDeletedAuctionStatuses) . ") " .
                    "AND ali_flt.lot_status_id IN (" . implode(', ', Constants\Lot::$availableLotStatuses) . ")) = 0 ";
            }
        } elseif ($overallLotStatus === Constants\MySearch::OLSF_SOLD) {
            // Lots with status sold in at least one auction. Only looks at the lot status
            $queryParts['where'][] = "(SELECT COUNT(1) FROM auction_lot_item ali_flt " .
                "INNER JOIN auction a_flt ON a_flt.id = ali_flt.auction_id " .
                "AND a_flt.auction_status_id IN (" . implode(',', Constants\Auction::$notDeletedAuctionStatuses) . ") " .
                "WHERE ali_flt.lot_item_id = li.id AND lot_status_id = '" . Constants\Lot::LS_SOLD . "'" .
                "{$auctionCondition}) > 0 ";
        } elseif ($overallLotStatus === Constants\MySearch::OLSF_UNSOLD) {
            if ($filterAuctionId) {
                // Active and Unsold lots assigned to filtering auction
                $queryParts['where'][] = "(SELECT COUNT(1) FROM auction_lot_item ali_flt " .
                    "INNER JOIN auction a_flt ON a_flt.id = ali_flt.auction_id " .
                    "AND a_flt.auction_status_id IN (" . implode(', ', Constants\Auction::$notDeletedAuctionStatuses) . ") " .
                    "WHERE ali_flt.lot_item_id = li.id AND ali_flt.lot_status_id IN (" . implode(',', [Constants\Lot::LS_ACTIVE, Constants\Lot::LS_UNSOLD]) . ") " .
                    "{$auctionCondition}) > 0 ";
            } else {
                // Unassigned items and lots that are not of status sold or received in any single auction.
                $queryParts['where'][] = "(SELECT COUNT(1) FROM auction_lot_item ali_flt " .
                    "INNER JOIN auction a_flt ON a_flt.id = ali_flt.auction_id " .
                    "AND a_flt.auction_status_id IN (" . implode(', ', Constants\Auction::$notDeletedAuctionStatuses) . ") " .
                    "WHERE ali_flt.lot_item_id = li.id AND ali_flt.lot_status_id IN (" . implode(',', Constants\Lot::$wonLotStatuses) . ") " .
                    ") = 0 ";
            }
        } elseif ($overallLotStatus === Constants\MySearch::OLSF_RECEIVED) {
            // Lots with status received in at least one auction. Only looks at the lot status
            $queryParts['where'][] = "(SELECT COUNT(1) FROM auction_lot_item ali_flt " .
                "INNER JOIN auction a_flt ON a_flt.id = ali_flt.auction_id " .
                "AND a_flt.auction_status_id IN (" . implode(',', Constants\Auction::$notDeletedAuctionStatuses) . ") " .
                "WHERE ali_flt.lot_item_id = li.id AND ali_flt.lot_status_id = '" . Constants\Lot::LS_RECEIVED . "'" .
                "{$auctionCondition}) > 0 ";
        }
        return $queryParts;
    }

    /**
     * @param array $queryParts
     * @return array
     */
    protected function addOrderByRecentAuctionQueryParts(array $queryParts): array
    {
        if ($this->orderFields && str_contains($this->orderFields, 'recent_auction')) {
            $availableLorStatuses = implode(', ', Constants\Lot::$availableLotStatuses);
            $queryParts['where'][] = <<<SQL
(
    (li.auction_id IS NOT NULL AND ali.auction_id = li.auction_id) 
    OR (
        li.auction_id IS NULL AND 
        ali.id = 
        (
            SELECT ali_ord_ra.id FROM auction_lot_item ali_ord_ra 
            WHERE ali_ord_ra.lot_item_id=li.id AND ali_ord_ra.lot_status_id IN ({$availableLorStatuses})
            ORDER BY ali_ord_ra.created_on DESC LIMIT 1
        )
    )
    OR ali.id IS NULL
)
SQL;
        }
        return $queryParts;
    }

    /**
     * Return clause that calculates general auction status value (Constants\Auction::STATUS_...)
     * @return string
     */
    protected function getAuctionGeneralStatusClause(): string
    {
        $dateNowFormatted = $this->getCurrentDateUtcIso();
        $dateNowFormatted = $this->escape($dateNowFormatted);
        // @formatter:off
        $clause =
            'IF (a.auction_type = "' . Constants\Auction::TIMED . '", ' .
                'IF (a.event_type = ' . Constants\Auction::ET_ONGOING . ',' .
                    Constants\Auction::STATUS_IN_PROGRESS . ', '.
                    'IF (a.start_bidding_date >= ' . $dateNowFormatted . ',' .
                        Constants\Auction::STATUS_UPCOMING . ', ' .
                        'IF (a.end_date > ' . $dateNowFormatted . ', '
                            . Constants\Auction::STATUS_IN_PROGRESS . ', '
                            . Constants\Auction::STATUS_CLOSED
                        . ')' .
                    ')' .
                '),' .
                'IF (a.start_closing_date >= ' . $dateNowFormatted .
                        ' AND a.auction_status_id = ' . Constants\Auction::AS_ACTIVE . ',' .
                    Constants\Auction::STATUS_UPCOMING . ',' .
                    'IF (a.auction_status_id = ' . Constants\Auction::AS_CLOSED . ', '
                        . Constants\Auction::STATUS_CLOSED . ', '
                        . Constants\Auction::STATUS_IN_PROGRESS
                    . ')' .
                ')' .
            ')';
        // @formatter:on
        return $clause;
    }

    /** ****************************************
     *  Logic related to aggregated list query
     */

    /**
     * Return query to aggregate information for a list of lots
     *
     * @param array $queryParts optional array with initial query elements select, from, where, group, having, etc
     * @return string
     */
    protected function getAggregatedListQuery(array $queryParts = []): string
    {
        if (!is_array($this->aggregatedListQueryParts)) {
            $this->initializeAggregatedListQueryParts($queryParts);
        }
        $query = $this->composeQuery($this->aggregatedListQueryParts);
        return $query;
    }

    /**
     * Initialize aggregated list query parts
     *
     * @param array $queryParts
     * @return array
     */
    protected function initializeAggregatedListQueryParts(array $queryParts = []): array
    {
        $n = "\n";
        // set defaults if not passed or empty
        if (!isset($queryParts['select'])) {
            $queryParts['select'] = 'SELECT ';
        }
        if (empty($queryParts['from'])) {
            $queryParts['from'] = [
                'FROM lots_tmp lt',
                'LEFT JOIN auction_lot_item ali ON ali.id = lt.ali_id',
            ];
        }
        if (!isset($queryParts['where'])) {
            $queryParts['where'] = [];
        }
        if (!isset($queryParts['group'])) {
            $queryParts['group'] = 'GROUP BY lt.ali_id ';
        }
        if (!isset($queryParts['having'])) {
            $queryParts['having'] = '';
        }
        if (!isset($queryParts['order'])) {
            $queryParts['order'] = 'ORDER BY lt.order';
        }

        $select = '';
        foreach ($this->resultSetFields as $column) {
            if (array_key_exists($column, $this->resultFieldsMapping)) {
                // Render SELECT query part from fields of $this->resultSetFields
                $alias = empty($this->resultFieldsMapping[$column]['alias'])
                    ? $column : $this->resultFieldsMapping[$column]['alias'];
                $select .= ($select ? ',' . $n : '')
                    . $this->resultFieldsMapping[$column]['select']
                    . ' `' . $alias . '`';
            } else {
                $message = 'Invalid result field mapping key ' . $column;
                log_error($message);
                throw new InvalidArgumentException($message);
            }
        }

        if (!$select) {
            $message = 'No result set selected';
            log_error($message);
            throw new RuntimeException($message);
        }

        // Attach string with list of selected fields to SELECT query part
        $queryParts['select'] .= $select . ' ';
        // Attach string with list of JOINs to FROM query part
        $joins = $this->collectAggregatedListJoins();
        $join = $this->buildJoins($joins);
        $queryParts['from'] = array_merge($queryParts['from'], $join);

        $queryParts = $this->completeQueryParts($queryParts);
        $this->aggregatedListQueryParts = $queryParts;

        return $queryParts;
    }

    /**
     * @return string[]
     */
    protected function collectAggregatedListJoins(): array
    {
        $joins = [];
        foreach ($this->resultSetFields as $column) {
            if (
                array_key_exists($column, $this->resultFieldsMapping)
                && !empty($this->resultFieldsMapping[$column]['join'])
            ) {
                // Collect keys for tables to be joined to the query based on the $this->resultSetFields
                foreach ($this->resultFieldsMapping[$column]['join'] as $join) {
                    $joins[] = $join;
                }
            }
        }
        return $joins;
    }

    /**
     * Set custom fields, that could be used in query (for selecting, filtering or ordering)
     * @param array $lotCustomFields
     * @return static
     * @throws InvalidArgumentException
     */
    public function setMappedLotCustomFields(array $lotCustomFields): static
    {
        foreach ($lotCustomFields as $lotCustomField) {
            if (!$lotCustomField instanceof LotItemCustField) {
                throw new InvalidArgumentException("Not a LotItemCustField");
            }
        }
        $this->lotCustomFields = $lotCustomFields;
        $this->filterQueryParts = null;
        return $this;
    }

    /**
     * Set user access array
     * @param array $userAccesses
     * @return static
     */
    public function setUserAccess(array $userAccesses): static
    {
        $this->userAccesses = $userAccesses;
        $this->filterQueryParts = null;
        return $this;
    }

    /**
     * Return user access array
     * @return array
     */
    public function getUserAccess(): array
    {
        if ($this->userAccesses === null) {
            $this->userAccesses = $this->getUnknownContextAccessChecker()->detectRoles($this->userId);
        }
        return $this->userAccesses;
    }

    /**
     * Set system account.id
     * @param int $systemAccountId
     * @return static
     */
    public function setSystemAccountId(int $systemAccountId): static
    {
        $this->systemAccountId = $systemAccountId;
        $this->filterQueryParts = null;
        return $this;
    }

    /**
     * Set account filter (drop down or sub domain)
     * @param int[] $accountIds
     * @return static
     */
    public function filterAccountIds(array $accountIds): static
    {
        $this->filterAccountIds = $accountIds;
        $this->filterArray("li.account_id", $accountIds);
        return $this;
    }

    /**
     * @param int[] $statuses
     * @return static
     */
    public function filterAuctionGeneralStatuses(array $statuses): static
    {
        $this->auctionGeneralStatuses = $statuses;
        $this->filterQueryParts = null;
        return $this;
    }

    /**
     * @param string $name
     * @return static
     */
    public function likeAuctionName(string $name): static
    {
        if (empty($name)) {
            return $this;
        }
        $this->like[] = 'a.name LIKE ' . $this->escape('%' . $name . '%');
        $this->filterQueryParts = null;
        return $this;
    }

    /**
     * Set auction id filter
     * @param int[]|null[] $auctionIds
     * @return static
     */
    public function filterAuctionIds(array $auctionIds): static
    {
        $this->filterArray("ali.auction_id", $auctionIds);
        return $this;
    }

    /**
     * Set filtering by auction lot ids
     * @param int[] $auctionLotIds
     * @return static
     */
    public function filterAuctionLotIds(array $auctionLotIds): static
    {
        $this->filterArray("ali.id", $auctionLotIds);
        return $this;
    }

    /**
     * Set filtering by auction type
     * @param string[] $auctionTypes
     * @return static
     */
    public function filterAuctionTypes(array $auctionTypes): static
    {
        $this->filterArray("a.auction_type", $auctionTypes);
        return $this;
    }

    /**
     * Set filtering by lot item ids
     * @param int[] $lotItemIds
     * @return static
     */
    public function filterLotItemIds(array $lotItemIds): static
    {
        $this->filterArray("li.id", $lotItemIds);
        return $this;
    }

    /**
     * Set filter by overall lot status, it considers ali.lot_status_id.
     * @param int|null $status
     * @return static
     */
    public function filterOverallLotStatus(?int $status): static
    {
        $this->filterOverallLotStatus = $status;
        return $this;
    }

    /**
     * Set the user.id
     * @param int|null $userId
     * @return static
     */
    public function setUserId(?int $userId): static
    {
        $this->userId = $userId;
        $this->filterQueryParts = null;
        return $this;
    }

    /**
     * Set the limit offset
     * @param int $offset
     * @return static
     */
    public function setOffset(int $offset): static
    {
        $this->offset = $offset;
        $this->filterQueryParts = null;
        return $this;
    }

    /**
     * Set the limit
     * @param int $limit
     * @return static
     */
    public function setLimit(int $limit): static
    {
        $this->limit = $limit;
        $this->filterQueryParts = null;
        return $this;
    }

    /**
     * Set the order key
     * @param string $orderFields
     * @return static
     */
    public function orderBy(string $orderFields): static
    {
        $this->orderFields = strtolower($orderFields);
        $this->filterQueryParts = null;
        return $this;
    }

    /**
     * Set the search key
     * @param string $searchKey
     * @return static
     */
    public function setSearchKey(string $searchKey): static
    {
        $this->searchKey = trim($searchKey);
        $this->filterQueryParts = null;
        return $this;
    }

    /**
     * Set filtering by categories
     * @param int[] $lotCategoryIds
     * @return static
     */
    public function filterLotCategoryIds(array $lotCategoryIds): static
    {
        $this->lotCategoryIds = $lotCategoryIds;
        $this->filterQueryParts = null;
        return $this;
    }

    /**
     * Set category match option used for filtering by categories
     * @param int $categoryMatch
     * @return static
     */
    public function setCategoryMatch(int $categoryMatch): static
    {
        $this->categoryMatch = $categoryMatch;
        $this->filterQueryParts = null;
        return $this;
    }

    /**
     * Enable filtering by ongoing lots for auctions that have respective setting enabled
     * @param bool $isOnlyOngoingLots
     * @return static
     */
    public function enableOnlyOngoingLotsFilter(bool $isOnlyOngoingLots): static
    {
        $this->isEnabledOnlyOngoingLots = $isOnlyOngoingLots;
        $this->filterQueryParts = null;
        return $this;
    }

    /**
     * Enable filtering by not show upcoming lots for auctions that have respective setting enabled
     * @param bool $shouldNotShowUpcomingLots
     * @return static
     */
    public function enableNotShowUpcomingLotsFilter(bool $shouldNotShowUpcomingLots): static
    {
        $this->isEnabledNotShowUpcomingLots = $shouldNotShowUpcomingLots;
        $this->filterQueryParts = null;
        return $this;
    }

    /**
     * Enable filter for excluding closed lots
     * @param bool $isExcludeClosedLots
     * @return static
     */
    public function enableExcludeClosedLotsFilter(bool $isExcludeClosedLots): static
    {
        $this->isEnabledExcludeClosedLots = $isExcludeClosedLots;
        $this->filterQueryParts = null;
        return $this;
    }

    /**
     * Enable considering of "Hide Unsold Lots" option of auction (SAM-2877)
     * @param bool $shouldConsider
     * @return static
     */
    public function considerOptionHideUnsoldLots(bool $shouldConsider): static
    {
        $this->isEnabledConsiderOptionHideUnsoldLots = $shouldConsider;
        $this->filterQueryParts = null;
        return $this;
    }

    /**
     * Drop filtering by ali.lot_status_id (we initialize it by default values in initInstance())
     * @return static
     */
    public function dropFilterLotStatusId(): static
    {
        unset($this->filter['ali.lot_status_id']);
        return $this;
    }

    /**
     * Set filtering by auctioneer id (a.auction_auctioneer_id)
     * @param int[] $auctioneerIds
     * @return static
     */
    public function filterAuctioneerIds(array $auctioneerIds): static
    {
        $this->filterArray('a.auction_auctioneer_id', $auctioneerIds);
        return $this;
    }

    /**
     * Set filtering by auction publish dates
     * @param bool $isPublished
     * @return static
     */
    public function filterAuctionPublished(bool $isPublished): static
    {
        $this->isAuctionPublished = $isPublished;
        return $this;
    }

    /**
     * Set filtering by auction statuses
     * @param int[] $auctionStatusIds
     * @return static
     */
    public function filterAuctionStatusIds(array $auctionStatusIds): static
    {
        $this->filterArray('a.auction_status_id', $auctionStatusIds);
        return $this;
    }

    /**
     * Set filtering by user id of consignor (li.consignor_id fk to u.id)
     * @param int[] $consignorUserIds
     * @return static
     */
    public function filterConsignorIds(array $consignorUserIds): static
    {
        $this->filterArray('li.consignor_id', $consignorUserIds);
        return $this;
    }

    /**
     * Set parameters for filtering by lot item custom fields
     * @param array $filters
     * @return static
     */
    public function filterCustomFields(array $filters): static
    {
        $this->customFieldFilters = $filters;
        return $this;
    }

    /**
     * Set filtering by lot#
     * @param string $lotNum
     * @param bool $isFullLotNum true - filter by full lot#
     * @return static
     */
    public function filterLotNo(string $lotNum, bool $isFullLotNum = false): static
    {
        $this->lotNum = trim($lotNum);
        $this->isFullLotNum = $isFullLotNum;
        return $this;
    }

    /**
     * Set filtering by item num
     * @param int[] $itemNums
     * @return static
     */
    public function filterItemNums(array $itemNums): static
    {
        $this->filterArray('li.item_num', $itemNums);
        return $this;
    }

    /**
     * Set filtering by item num extension
     * @param string[] $itemNumExtensions
     * @return static
     */
    public function filterItemNumExtensions(array $itemNumExtensions): static
    {
        $this->filterArray('li.item_num_ext', $itemNumExtensions);
        return $this;
    }

    /**
     * Set filtering by lot statuses
     * @param int[] $lotStatusIds
     * @return static
     */
    public function filterLotStatusIds(array $lotStatusIds): static
    {
        $this->filterArray('ali.lot_status_id', $lotStatusIds);
        return $this;
    }

    /**
     * Set filtering by min and max prices
     * @param array $prices ('min' => float, 'max' => float)
     * @return static
     */
    public function filterPrices(array $prices): static
    {
        $this->priceFilter = $prices;
        return $this;
    }

    /**
     * Set filtering by lot publish dates
     * @param bool $isPublished
     * @return static
     */
    public function filterPublished(bool $isPublished): static
    {
        $this->isLotPublished = $isPublished;
        return $this;
    }

    /**
     * Set filtering by sample lot
     * @param bool $sampleLot
     * @return static
     */
    public function filterSampleLot(bool $sampleLot): static
    {
        $this->filterArray("ali.sample_lot", [$sampleLot]);
        return $this;
    }

    /**
     * Set filtering by timed online item properties
     * @param int[] $timedItemOptions
     * @return static
     */
    public function filterTimedItemOptions(array $timedItemOptions): static
    {
        $this->timedItemOptions = ArrayCast::makeIntArray($timedItemOptions, self::$availableTimedOnlineOptions);
        return $this;
    }

    // /**
    //  * Set base table name of filtering query
    //  * @param string $table
    //  * @return static
    //  */
    // public function setFilterQueryBaseTable($table)
    // {
    //     $this->filterQueryBaseTable = $table;
    //     if ($table === 'auction_lot_item') {
    //         $this->filterQueryBaseTableAlias = 'ali';
    //     } elseif ($table === 'lot_item') {
    //         $this->filterQueryBaseTableAlias = 'li';
    //     }
    //     return $this;
    // }

    /**
     * Set search index: true (def) - search in public index, false - search in private index (full)
     * @param bool $isPublic
     * @return static
     */
    public function enablePublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomFieldQueryTypeForAggregatedList(): string
    {
        if ($this->customFieldQueryTypeForAggregatedList === self::CFQT_JOIN) {
            // Do not join custom field tables, when there are too many joins. Use sub-queries instead.
            // See, Mysql Error Code: 1116 - Too many tables; MySQL can only use 61 tables in a join
            // TODO: we should adjust logic, so we don't count all possible regular joins, but only required ones
            // That requires changes in addCustomFieldOptionsToMapping()
            $customFieldCount = count($this->lotCustomFields);
            $regularJoinCount = count($this->joinsMapping);
            if ($customFieldCount + $regularJoinCount > 60) {
                $this->customFieldQueryTypeForAggregatedList = self::CFQT_SUBQUERY;
            }
        }
        return $this->customFieldQueryTypeForAggregatedList;
    }

    /**
     * Set type of query for custom fields in aggregated list query
     * @param string $type
     * @return static
     */
    public function setCustomFieldQueryTypeForAggregatedList(string $type): static
    {
        $this->customFieldQueryTypeForAggregatedList = $type;
        return $this;
    }

    /**
     * Set type of query for custom fields in filtering query
     * @param string $type
     * @return static
     */
    public function setCustomFieldQueryTypeForFiltering(string $type): static
    {
        $this->customFieldQueryTypeForFiltering = $type;
        return $this;
    }

    /**
     * Set the result set fields
     * @param array $resultSetFields
     * @return static
     * @throws InvalidArgumentException
     */
    public function setResultSetFields(array $resultSetFields): static
    {
        $this->resultSetFields = $resultSetFields;
        return $this;
    }

    protected function checkResultSetFieldsAvailability(): void
    {
        $noMatches = array_diff($this->resultSetFields, array_keys($this->resultFieldsMapping));
        if (count($noMatches) > 0) {
            throw new InvalidArgumentException('Invalid result set fields: ' . implode(',', $noMatches));
        }
    }

    /**
     * Add result set fields
     * @param array $resultSetFields
     * @return static
     */
    public function addResultSetFields(array $resultSetFields): static
    {
        $this->resultSetFields = array_merge($this->resultSetFields, $resultSetFields);
        $this->resultSetFields = array_unique($this->resultSetFields);
        return $this;
    }

    /**
     * Define auction access checking types
     * @param int[] $auctionAccessChecks
     * @return static
     */
    public function filterAuctionAccessCheck(array $auctionAccessChecks): static
    {
        $this->auctionAccessChecks = $auctionAccessChecks;
        return $this;
    }

    /**
     * Set condition to return only lots, that have hammer price
     * @param bool $isOnlyWithHammerPrice
     * @return static
     */
    public function enableOnlyWithHammerPrice(bool $isOnlyWithHammerPrice): static
    {
        $this->isOnlyWithHammerPrice = $isOnlyWithHammerPrice;
        return $this;
    }

    /** **************************************
     *  Util methods used for query building
     */

    /**
     * Build a query string from array values. Process array recursively
     * @param array $arr
     * @return string
     */
    private function flatten(array $arr): string
    {
        $n = "\n";
        $query = '';
        foreach ($arr as $val) {
            if (is_array($val)) {
                $query .= $this->flatten($val);
            } else {
                $query .= trim($val, ' ;') . '; ' . $n;
            }
        }
        return $query;
    }

    /**
     * Final assembling of clauses
     * @param array $queryParts
     * @return array
     */
    protected function completeQueryParts(array $queryParts): array
    {
        if (
            !empty($queryParts['group'])
            && !preg_match('/group by/i', $queryParts['group'])
        ) {
            $queryParts['group'] = ' GROUP BY ' . $queryParts['group'];
        }
        if (
            !empty($queryParts['having'])
            && false === stripos($queryParts['having'], "having")
        ) {
            $queryParts['having'] = ' HAVING ' . $queryParts['having'];
        }
        if (
            !empty($queryParts['order'])
            && !preg_match('/order by/i', $queryParts['order'])
        ) {
            $queryParts['order'] = ' ORDER BY ' . $queryParts['order'];
        }

        if (!isset($queryParts['where_count'])) {
            $queryParts['where_count'] = [];
        }
        $queryParts['where_count'] = array_merge($queryParts['where'], $queryParts['where_count']);

        if (
            !empty($queryParts['group_count'])
            && !preg_match('/group by/i', $queryParts['group_count'])
        ) {
            $queryParts['group_count'] = ' GROUP BY ' . $queryParts['group_count'];
        }
        if (
            !empty($queryParts['having_count'])
            && false === stripos($queryParts['having_count'], "having")
        ) {
            $queryParts['having_count'] = ' HAVING ' . $queryParts['having_count'];
        }
        $queryParts['from_count'] = $queryParts['from'];
        return $queryParts;
    }

    /**
     * Make query string from query parts
     * @param array $queryParts
     * @param bool $isResultQuery
     * @return string
     */
    protected function composeQuery(array $queryParts, bool $isResultQuery = true): string
    {
        $n = "\n";
        if ($isResultQuery) {
            $select = $queryParts['select'];
            $from = $n . implode($n, $queryParts['from']);
            $where = !empty($queryParts['where']) ? $n . 'WHERE ' . implode($n . "AND ", $queryParts['where']) : '';
            $group = !empty($queryParts['group']) ? $n . $queryParts['group'] : '';
            $having = !empty($queryParts['having']) ? $n . $queryParts['having'] : '';
            $order = !empty($queryParts['order']) ? $n . $queryParts['order'] : '';
            $limit = !empty($queryParts['limit']) ? $n . $queryParts['limit'] : '';
            $query = $select . $from . $where . $group . $having . $order . $limit;
        } else {
            $where = !empty($queryParts['where'])
                ? $n . 'WHERE ' . implode($n . "AND ", $queryParts['where_count']) : '';
            $group = !empty($queryParts['group_count']) ? $n . $queryParts['group_count'] : '';
            $having = !empty($queryParts['having_count']) ? $n . $queryParts['having_count'] . $n : '';
            $query = $n . implode($n, $queryParts['from_count']) . $where . $group . $having;
            if (preg_match('/%s/', $queryParts['select_count'])) {
                $query = sprintf($queryParts['select_count'], $query);
            } else {
                $query = $queryParts['select_count'] . $query;
            }
        }
        return $query;
    }

    /**
     * Build join clause based on passed array
     * @param array $joinTables
     * @param array $excludeTables - tables should be excluded from result
     * @return string[]
     */
    protected function buildJoins(array $joinTables, array $excludeTables = []): array
    {
        $joins = [];
        if ($joinTables) {
            if (in_array('lot_item', $joinTables, true)) {    // let it be the first join
                array_unshift($joinTables, 'lot_item');
            }
            $joinTables = array_unique($joinTables);
            foreach ($joinTables as $joinTable) {
                if (!in_array($joinTable, $excludeTables, true)) {
                    $joins[] = array_key_exists($joinTable, $this->joinsMapping)
                        ? $this->joinsMapping[$joinTable] // take from mapping table or
                        : $joinTable; // take literally
                }
            }
        }
        return $joins;
    }

    /**
     * Initialize the result set and sort mapping arrays for available custom fields
     */
    protected function addCustomFieldOptionsToMapping(): void
    {
        $dbTransformer = DbTextTransformer::new();
        foreach ($this->lotCustomFields as $lotCustomField) {
            $fieldName = $dbTransformer->toDbColumn($lotCustomField->Name);
            $alias = $this->getBaseCustomFieldHelper()->makeFieldAlias($fieldName);
            [$roleConds, $isRestricted] = $this->getRoleCondition($lotCustomField);

            if ($isRestricted) {
                // User's role has not enough permissions to access custom data, hence skip fetching
                $this->resultFieldsMapping[$alias] = ['select' => "NULL"];
                continue;
            }

            $fieldJoins = [];
            $fieldJoins['join'] = [
                "LEFT JOIN lot_item_cust_data licd{$fieldName} ON"
                . " licd{$fieldName}.lot_item_cust_field_id = " . $lotCustomField->Id
                . " AND licd{$fieldName}.lot_item_id = li.id"
                . $roleConds,
                'lot_item',
            ];
            $this->joinsMapping[$alias] = $fieldJoins['join'][0];
            $orderFields = [
                'asc' => ['order' => ''],
                'desc' => ['order' => ''],
            ];

            if ($this->getCustomFieldQueryTypeForAggregatedList() === self::CFQT_JOIN) {
                if ($lotCustomField->isNumeric()) {
                    // SELECT term for numeric custom field types
                    $fieldJoins['select'] = "licd{$fieldName}.numeric";
                    // ORDER BY term for numeric custom field type
                    $orderFields['asc']['order'] = "licd{$fieldName}.numeric ASC";
                    $orderFields['desc']['order'] = "licd{$fieldName}.numeric DESC";
                } else {
                    // SELECT term for text custom field types
                    $fieldJoins['select'] = "licd{$fieldName}.text";
                    // ORDER BY for text custom field types
                    $orderFields['asc']['order'] = "licd{$fieldName}.text ASC";
                    $orderFields['desc']['order'] = "licd{$fieldName}.text DESC";
                }
                // Prepare necessary JOIN for custom field
                $orderFields['asc']['join'] = $orderFields['desc']['join'] = [
                    'auction_lot_item',
                    $fieldJoins['join'][0],
                ];
            } else {
                if ($lotCustomField->isNumeric()) {
                    $fieldJoins['select'] = '(SELECT licd.`numeric` FROM lot_item_cust_data AS licd ' .
                        'WHERE licd.lot_item_id = li_id AND licd.active = true ' .
                        'AND licd.lot_item_cust_field_id = ' . $this->escape($lotCustomField->Id) . ' limit 1)';
                } else {
                    $fieldJoins['select'] = '(SELECT licd.`text` FROM lot_item_cust_data AS licd ' .
                        'WHERE licd.lot_item_id = li_id AND licd.active = true ' .
                        'AND licd.lot_item_cust_field_id = ' . $this->escape($lotCustomField->Id) . ' limit 1)';
                }
                $orderFields['asc']['order'] = $fieldJoins['select'] . " ASC";
                $orderFields['desc']['order'] = $fieldJoins['select'] . " DESC";
                unset($fieldJoins['join']);
            }
            $this->resultFieldsMapping[$alias] = $fieldJoins;
            $this->addOrderFieldsMapping($alias, $orderFields);
        }
    }

    /**
     * Define filter array of values
     * @param string $column
     * @param array $values
     * @return static
     */
    protected function filterArray(string $column, array $values): static
    {
        $this->filter[$column] = $values;
        $this->filterQueryParts = null;
        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return string
     */
    protected function getFilterConditionForArray(string $column, array $values): string
    {
        $cond = '';
        if ($values) {
            $conditions = [];
            // search for "null" value
            foreach ($values as $key => $value) {
                if ($value === null) {
                    $conditions[] = $column . ' IS NULL';
                    unset($values[$key]);
                }
            }
            if ($values) {
                foreach ($values as $i => $value) {
                    $values[$i] = $this->escape($value);
                }
                $list = implode(',', $values);
                $template = count($values) === 1
                    ? "%s = %s"
                    : "%s IN (%s)";
                $conditions[] = sprintf($template, $column, $list);
            }
            $cond = implode(' OR ', $conditions);
            $cond = count($conditions) > 1 ? '(' . $cond . ')' : $cond;
        }
        return $cond;
    }

    /**
     * Return filtering value by column name
     * @param string $column
     * @return mixed
     */
    protected function getFilterValue(string $column): mixed
    {
        $value = $this->filter[$column] ?? null;
        return $value;
    }

    /**
     * @param array $values
     * @return string
     */
    protected function toEscapedList(array $values): string
    {
        foreach ($values as $i => $value) {
            $values[$i] = $this->escape($value);
        }
        $list = implode(',', $values);
        return $list;
    }

    /**
     * @param string $field
     * @return array|null
     */
    public function getObservingProperties(string $field): ?array
    {
        // we allow empty array [] (eg. for 'auction_id')
        $observingProperties = $this->resultFieldsMapping[$field]['observe'] ?? null;
        return $observingProperties;
    }

    /**
     * Detect role permission checking condition and $isRestricted status
     * @param LotItemCustField $lotCustomField
     * @return array [
     *      string $roleConds, - additional role checking condition for custom field fetching
     *      bool $isRestricted - true - we already detected, that custom field access is restricted
     *      ]
     */
    protected function getRoleCondition(LotItemCustField $lotCustomField): array
    {
        $userAccountIdSet = 'SET @UserAccountId := (SELECT account_id FROM `user` WHERE id = ' . $this->escape($this->userId) . ')';
        $sameAccountCond = 'li.account_id = @UserAccountId';
        $consignorOfLotCond = 'li.consignor_id = @UserId';
        $bidderInAuctionCond = "(SELECT COUNT(1) FROM auction_bidder AS aub "
            . "WHERE aub.auction_id = ali.auction_id "
            . "AND " . $this->createAuctionBidderQueryBuilderHelper()->makeApprovedBidderWhereClause() . " "
            . "AND aub.user_id = @UserId) > 0";
        [$definiteRoles, $probableRoles] = $this->getUserAccess();
        $roleConds = '';
        $isRestricted = false;
        if ($lotCustomField->Access === Constants\Role::ADMIN) {
            if (in_array(Constants\Role::ADMIN, $definiteRoles, true)) {
                // We are sure, user is admin (superadmin)
            } elseif (in_array(Constants\Role::ADMIN, $probableRoles, true)) {
                // We need additional check, admin user and lot has the same account
                $this->preQueries['@UserAccountId'] = $userAccountIdSet;
                $roleConds = " AND {$sameAccountCond}";
            } else {
                $isRestricted = true;
            }
        } elseif ($lotCustomField->Access === Constants\Role::CONSIGNOR) {
            if (in_array(Constants\Role::CONSIGNOR, $definiteRoles, true)) {
                // We are sure, user is consignor (currently impossible, we don't know lot)
            } elseif (in_array(Constants\Role::CONSIGNOR, $probableRoles, true)) {
                $conds = [];
                if (in_array(Constants\Role::ADMIN, $probableRoles, true)) {
                    // Admin has consignor's access to resources
                    $this->preQueries['@UserAccountId'] = $userAccountIdSet;
                    $conds[] = $sameAccountCond;
                }
                $conds[] = $consignorOfLotCond;
                $roleConds = ' AND (' . implode(' OR ', $conds) . ')';
            } else {
                $isRestricted = true;
            }
        } elseif ($lotCustomField->Access === Constants\Role::BIDDER) {
            if (in_array(Constants\Role::BIDDER, $definiteRoles, true)) {
                // We are sure, user is bidder (currently impossible, we don't know lot)
            } elseif (in_array(Constants\Role::BIDDER, $probableRoles, true)) {
                $conds = [];
                if (in_array(Constants\Role::ADMIN, $probableRoles, true)) {
                    // Admin has bidder's access to resources
                    $this->preQueries['@UserAccountId'] = $userAccountIdSet;
                    $conds[] = $sameAccountCond;
                }
                if (in_array(Constants\Role::CONSIGNOR, $probableRoles, true)) {
                    // Consignor has bidder's access to resources
                    $conds[] = $consignorOfLotCond;
                }
                $conds[] = $bidderInAuctionCond;
                $roleConds = ' AND (' . implode(' OR ', $conds) . ')';
            } else {
                $isRestricted = true;
            }
        } elseif ($lotCustomField->Access === Constants\Role::USER) {
            if (in_array(Constants\Role::USER, $definiteRoles, true)) {
                // We are sure user is authorizable
            } elseif (in_array(Constants\Role::USER, $probableRoles, true)) {
                // Currently isn't possible
            } else {
                $isRestricted = true;
            }
        }

        $arr = [$roleConds, $isRestricted];
        return $arr;
    }

    /**
     * @param bool $isAsc
     * @return string
     */
    protected function buildDistanceOrderClause(bool $isAsc): string
    {
        $direction = $isAsc ? 'ASC' : 'DESC';
        $orderClause = $this->getOrderByDistanceAlias() . " {$direction}, "
            . $this->createAuctionLotOrderMysqlQueryBuilder()->buildLotOrderClause($isAsc);
        return $orderClause;
    }

    /**
     * Return distance ordering related column alias. It is "postal code"-type custom field.
     * @return string|null
     */
    protected function getOrderByDistanceAlias(): ?string
    {
        if ($this->orderByDistanceAlias === null) {
            $this->orderByDistanceAlias = $this->detectOrderByDistanceAlias();
        }
        return $this->orderByDistanceAlias;
    }

    /**
     * Finds first "postal code"-type custom field with defined filtering requirements by postal code and radius
     * null - when distance alias available for ordering cannot be found
     * @return string|null
     */
    protected function detectOrderByDistanceAlias(): ?string
    {
        foreach ($this->lotCustomFields as $lotCustomField) {
            if (!isset($this->customFieldFilters[$lotCustomField->Id])) {
                continue;
            }
            if ($lotCustomField->Type === Constants\CustomField::TYPE_POSTALCODE) {
                $values = $this->customFieldFilters[$lotCustomField->Id];
                $postalCode = $values['pcode'] ?? null;
                $radius = $values['radius'] ?? null;
                if (
                    $radius !== null
                    && $postalCode !== null
                ) {
                    $coordinates = $this->detectPostalCodeCoordinates($postalCode);
                    if ($coordinates) {
                        return 'distance' . $lotCustomField->Id;
                    }
                }
            }
        }
        return null;
    }

    /**
     * @param string $postalCode
     * @return array|null
     */
    protected function detectPostalCodeCoordinates(string $postalCode): ?array
    {
        if (!isset($this->postalCodeCoordinates[$postalCode])) {
            $coordinates = $this->getPostalCodeSharedServiceClient()->findCoordinates($postalCode);
            if (!$coordinates) {
                $coordinates = null;
            }
            $this->postalCodeCoordinates[$postalCode] = $coordinates;
        }
        return $this->postalCodeCoordinates[$postalCode];
    }
}
