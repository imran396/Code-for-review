<?php

namespace Sam\Auction\AuctionList;

use Account;
use Auction;
use AuctionAuctioneer;
use AuctionCache;
use AuctionCustField;
use AuctionDetailsCache;
use Currency;
use InvalidArgumentException;
use Location;
use QMySqli5DatabaseResult;
use Sam\Application\Access\Auction\AuctionAccessCheckerQueryBuilderHelperCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DataSourceInterface;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use SamTaxCountryStates;
use SettingAuction;
use SettingSystem;

/**
 * Class DataSourceMysql
 * @package Sam\Auction\AuctionList
 */
class DataSourceMysql
    extends CustomizableClass
    implements DataSourceInterface
{
    use AuctionAccessCheckerQueryBuilderHelperCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;

    // It isn't for filtering, but for detecting AuctionParameters record
    use UserAwareTrait;

    // User, who request data. Used to check auction accesses

    public const DEF = 1;
    public const BIDDING_UPCOMING = 2;
    public const BIDDING = 3;
    public const UPCOMING = 4;
    public const CLOSED = 5;

    /**
     * array of auction access checking resource types
     * @var int[]
     */
    protected array $auctionAccessChecks = [];

    /**
     * filtering options by auction custom fields
     * @var array<int, array{field: AuctionCustField, value: mixed}>
     */
    protected array $auctionCustomFieldFilters = [];

    /**
     * selected auction custom fields
     * @var AuctionCustField[]
     */
    protected array $auctionCustomFields = [];

    /**
     * @var int[]
     */
    protected array $auctionIds = [];

    /**
     * filter by auctioneer
     * @var int|null
     */
    protected ?int $auctioneerId = null;

    /**
     * filter by auction statuses (auction.auction_status_id)
     * @var int[]
     */
    protected array $auctionStatuses = [];

    /**
     * filter by auction type
     * @var string[]
     */
    protected array $filterAuctionTypes = [];

    /**
     * filter by auction general status (In progress, Upcoming, Closed)
     * @var int[]
     */
    protected array $filterGeneralStatus = [];

    /**
     * Records per page
     * @var int|null
     */
    protected ?int $limit = null;

    /**
     * result offset
     * @var int|null
     */
    protected ?int $offset = null;

    /**
     * string with order key index and direction separated by a space
     * @var string
     */
    protected string $orderKey = '';

    /**
     * filter by published
     * @var bool|null
     */
    protected ?bool $published = null;

    /**
     * filter by user registered in auction
     * @var bool
     */
    protected bool $isRegisteredInAuction = false;

    /**
     * Fetched fields defined by client
     * @var string[]
     */
    protected array $resultSetFields = [];

    /**
     * filter by sale no
     * @var string
     */
    protected string $filterSaleNo = '';

    /**
     * filter by auction state (bidding & upcoming, bidding, upcoming, closed, def)
     * Special for Auction List page
     */
    protected ?int $filterVisibleAuctionStatus = null;

    /**
     * Array mapping result fields with the query needed to retrieve ('select')
     * and tables needed to join to retrieve the information ('join')
     * and prerequisite fields, that are required to calculate result field ('prerequisite')
     * @var array<string, array{
     *     select: string,
     *     alias?: string,
     *     join?: string[]|string,
     *     observe?: array<array{class: string, properties: string[]}>
     *     }>
     */
    protected array $resultFieldsMapping = [];

    /**
     * array mapping fields available for sorting the filtered result, the necessary order string and
     * tables needed to be joined to retrieve that information
     * @var array<string, array{
     *     asc: array{
     *          order: string,
     *          prerequisite?: string[],
     *          order_callback?: string,
     *          join?: string[]
     *          }
     *      }>
     */
    protected array $orderFieldsMapping = [];

    /**
     * array mapping table names with the join necessary for the query
     * @var string[]
     */
    protected array $joinsMapping = [];

    /**
     * Query parts for result list
     * We keep here composed query parts, so we don't need to build them repeatedly for result and count query.
     * @var array|null
     */
    protected ?array $queryParts = null;

    /**
     * Return instance of self
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Initialize query building parts
     * @return static
     */
    public function initInstance(): static
    {
        $this->auctionAccessChecks = [];
        $this->auctionCustomFieldFilters = [];
        $this->auctionCustomFields = [];
        $this->auctionIds = [];
        $this->filterAuctionTypes = [];
        $this->resultSetFields = [];
        $this->resultFieldsMapping = [];
        $this->orderFieldsMapping = [];
        $this->joinsMapping = [];
        $this->queryParts = [];
        $this->initResultFieldsMapping();
        $this->initJoinsMapping();
        $this->initOrderFieldsMapping();
        return $this;
    }

    // @formatter:off
    /**
     * Define mapping for result fields
     * @return void
     *
     * Note: Keys are alphabetically ordered
     */
    protected function initResultFieldsMapping(): void
    {
        $this->resultFieldsMapping = [
            'account_company_name' => [
                'select' => 'acc.company_name',
                'join' => ['account'],
                'observe' => [
                    ['class' => Account::class, 'properties' => ['CompanyName']],
                ],
            ],
            'account_id' => [
                'select' => 'a.account_id',
                'observe' => [
                    ['class' => Account::class, 'properties' => ['Id']],
                ],
            ],
            'account_name' => [
                'select' => 'acc.name',
                'join' => 'account',
                'observe' => [
                    ['class' => Account::class, 'properties' => ['Name']],
                ],
            ],
            'account_site_url' => [
                'select' => 'acc.site_url',
                'join' => 'account',
                'observe' => [
                    ['class' => Account::class, 'properties' => ['SiteUrl']],
                ],
            ],
            'account_phone' => [
                'select' => 'acc.phone',
                'join' => 'account',
                'observe' => [
                    ['class' => Account::class, 'properties' => ['Phone']],
                ],
            ],
            'account_email' => [
                'select' => 'acc.email',
                'join' => 'account',
                'observe' => [
                    ['class' => Account::class, 'properties' => ['Email']],
                ],
            ],
            'account_public_support_contact_name' => [
                'select' => 'acc.public_support_contact_name',
                'join' => 'account',
                'observe' => [
                    ['class' => Account::class, 'properties' => ['PublicSupportContactName']],
                ],
            ],
            'ap_show_auction_starts_ending' => [
                'select' => 'seta.show_auction_starts_ending',
                'join' => ['setting_auction'],
                'observe' => [
                    ['class' => SettingAuction::class, 'properties' => ['ShowAuctionStartsEnding']],
                ]
            ],
            'auctioneer_id' => [
                'select' => 'a.auction_auctioneer_id',
            ],
            'auctioneer_name' => [
                'select' => 'aa.name',
                'join' => 'auction_auctioneer',
                'observe' => [
                    ['class' => AuctionAuctioneer::class, 'properties' => ['Name', 'Active']],
                    ['class' => Auction::class, 'properties' => ['AuctionAuctioneerId']],
                ],
            ],
            'auction_bidder_id' => [
                'select' => 'aub.id',
                'join' => 'auction_bidder',
            ],
            'auction_date' => [
                'select' => 'IF(a.auction_type = "' . Constants\Auction::TIMED . '" AND a.event_type = "'.Constants\Auction::ET_SCHEDULED.'", ' .
                    'a.end_date, ' .
                    'a.start_closing_date)',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['EndDate', 'StartDate']],
                ],
            ],
            'auction_held_in' => [
                'select' => 'a.auction_held_in',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['AuctionHeldIn']],
                ],
            ],
            'auction_info_link' => [
                'select' => 'a.auction_info_link',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['AuctionInfoLink']],
                ],
            ],
            'auction_seo_url' => [
                'select' => 'adc_by_su.value',
                'join' => ['auction_details_cache_by_seo_url'],
                'observe' => [
                    ['class' => AuctionDetailsCache::class, 'properties' => ['Value']],
                ],
            ],
            'auction_status_id' => [
                'select' => 'a.auction_status_id',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['AuctionStatusId']],
                ],
            ],
            'auction_type' => [
                'select' => 'a.auction_type',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['AuctionType']],
                ],
            ],
            'bidder_num' => [
                'select' => 'aub.bidder_num',
                'join' => 'auction_bidder',
            ],
            'clerking_style' => [
                'select' => 'a.clerking_style',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['ClerkingStyle']],
                ],
            ],
            'created_on' => [
                'select' => 'a.created_on',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['ClerkingStyle']],
                ],
            ],
            'currency_sign' => [
                'select' =>
                    'IF (a.currency IS NULL,'
                        . '(SELECT curr.sign FROM currency curr'
                            . ' LEFT JOIN setting_system setsys ON setsys.primary_currency_id = curr.id'
                            . ' WHERE setsys.account_id = ' . $this->cfg()->get('core->portal->mainAccountId') . '),'
                        . '(SELECT sign FROM currency WHERE id = a.currency)'
                    . ')',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['Currency']],
                    ['class' => Currency::class, 'properties' => ['Sign']],
                    ['class' => SettingSystem::class, 'properties' => ['PrimaryCurrency']],
                ],
            ],
            'description' => [
                'select' => 'a.description',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['Description']],
                ],
            ],
            'email' => [
                'select' => 'a.email',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['Email']],
                ],
            ],
            'end_date' => [
                'select' => 'IF(a.auction_type = "' . Constants\Auction::TIMED . '" AND a.event_type = "' . Constants\Auction::ET_ONGOING . '", NULL, a.end_date)',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['EndDate']],
                ],
            ],
            'event_id' => [
                'select' => 'a.event_id',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['EventId']],
                ],
            ],
            'event_type' => [
                'select' => 'a.event_type',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['EventType']],
                ],
            ],
            'id' => [
                'select' => 'a.id',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['Id']],
                ],
            ],
            'image_id' => [
                'select' => '(SELECT id FROM auction_image WHERE auction_id = a.id ORDER BY id LIMIT 1)',
            ],
            'listing_only' => [
                'select' => 'a.listing_only',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['ListingOnly']],
                ],
            ],
            'invoice_location_address' => [
                'select' => 'l.address',
                'join' => 'invoice_location',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['LocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'Address']],
                ],
            ],
            'invoice_location_logo' => [
                'select' => 'l.logo',
                'join' => 'invoice_location',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['LocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'Logo']],
                ],
            ],
            'invoice_location_id' => [
                'select' => 'l.id',
                'join' => 'invoice_location',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['LocationId']],
                ],
            ],
            'invoice_location_name' => [
                'select' => 'l.name',
                'join' => 'invoice_location',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['LocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'Name']],
                ],
            ],
            'invoice_location_country' => [
                'select' => 'l.country',
                'join' => 'invoice_location',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['LocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'Country']],
                ],
            ],
            'invoice_location_county' => [
                'select' => 'l.county',
                'join' => 'invoice_location',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['LocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'County']],
                ],
            ],
            'invoice_location_state' => [
                'select' => 'l.state',
                'join' => 'invoice_location',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['LocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'State']],
                ],
            ],
            'invoice_location_city' => [
                'select' => 'l.city',
                'join' => 'invoice_location',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['LocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'City']],
                ],
            ],
            'invoice_location_zip' => [
                'select' => 'l.zip',
                'join' => 'invoice_location',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['LocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'Zip']],
                ],
            ],
            'event_location_address' => [
                'select' => 'el.address',
                'join' => 'event_location',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['EventLocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'Address']],
                ],
            ],
            'event_location_logo' => [
                'select' => 'el.logo',
                'join' => 'event_location',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['EventLocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'Logo']],
                ],
            ],
            'event_location_id' => [
                'select' => 'el.id',
                'join' => 'event_location',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['EventLocationId']],
                ],
            ],
            'event_location_name' => [
                'select' => 'el.name',
                'join' => 'event_location',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['EventLocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'Name']],
                ],
            ],
            'event_location_country' => [
                'select' => 'el.country',
                'join' => 'event_location',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['EventLocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'Country']],
                ],
            ],
            'event_location_county' => [
                'select' => 'el.county',
                'join' => 'event_location',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['EventLocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'County']],
                ],
            ],
            'event_location_state' => [
                'select' => 'el.state',
                'join' => 'event_location',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['EventLocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'State']],
                ],
            ],
            'event_location_city' => [
                'select' => 'el.city',
                'join' => 'event_location',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['EventLocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'City']],
                ],
            ],
            'event_location_zip' => [
                'select' => 'el.zip',
                'join' => 'event_location',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['EventLocationId']],
                    ['class' => Location::class, 'properties' => ['Active', 'Zip']],
                ],
            ],
            'modified_on' => [
                'select' => 'a.modified_on',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['ModifiedOn']],
                ],
            ],
            'name' => [
                'select' => 'a.name',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['Name']],
                ],
            ],
            'sale_num' => [
                'select' => 'a.sale_num',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['SaleNum']],
                ],
            ],
            'sale_num_ext' => [
                'select' => 'a.sale_num_ext',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['SaleNumExt']],
                ],
            ],
            'seconds_before' => [
                'select' => 'UNIX_TIMESTAMP(IF (a.auction_type = "' . Constants\Auction::TIMED . '", a.start_bidding_date, a.start_closing_date)) - @NowUtcTs',
            ],
            'seconds_left' => [
                'select' => 'UNIX_TIMESTAMP(a.end_date) - @NowUtcTs',
            ],
            'seo_meta_description' => [
                'select' => 'a.seo_meta_description',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['SeoMetaDescription']],
                ],
            ],
            'seo_meta_keywords' => [
                'select' => 'a.seo_meta_keywords',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['SeoMetaKeywords']],
                ],
            ],
            'seo_meta_title' => [
                'select' => 'a.seo_meta_title',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['SeoMetaTitle']],
                ],
            ],
            'shipping_info' => [
                'select' => 'a.shipping_info',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['ShippingInfo']],
                ],
            ],
            'stagger_closing' => [
                'select' => 'a.stagger_closing',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['StaggerClosing']],
                ],
            ],
            'start_closing_date' => [
                'select' =>  'IF(a.auction_type = "' . Constants\Auction::TIMED . '" AND a.event_type = "' . Constants\Auction::ET_ONGOING . '", NULL, a.start_closing_date)',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['StartClosingDate']],
                ],
            ],
            'start_bidding_date' => [
                'select' => 'a.start_bidding_date',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['StartBiddingDate']],
                ],
            ],
            'start_ending_timezone_location' => [
                'select' => 'atz.location',
                'join' => 'timezone',
                'observe' => [
                    ['class' => AuctionCache::class, 'properties' => ['TimezoneId']],
                ],
            ],
            'start_date' => [
                'select' => 'a.start_date',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['StartDate']],
                ],
            ],
            'start_register_date' => [
              'select' => 'a.start_register_date',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['StartRegisterDate']],
                ],
            ],
            'end_register_date' => [
                'select' => 'a.end_register_date',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['EndRegisterDate']],
                ],
            ],
            'timezone_location' => [
                'select' => 'atz.location',
                'join' => 'timezone',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['TimezoneId']],
                ],
            ],
            'timezone_id' => [
                'select' => 'a.timezone_id',
            ],
            'status' => [
                'select' => $this->getGeneralStatusClause(),
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['EventType', 'StartDateGmt', 'EndDateGmt', 'AuctionStatusId']],
                ],
            ],
            'stream_display' => [
                'select' => 'a.stream_display',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['StreamDisplay']],
                ],
            ],
            'tax_default_country' => [
                'select' => 'a.tax_default_country',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['TaxDefaultCountry']],
                ],
            ],
            'tax_percent' => [
                'select' => 'a.tax_percent',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['TaxPercent']],
                ],
            ],
            'tax_states' => [
                'select' => '(SELECT GROUP_CONCAT(stcs.state) FROM sam_tax_country_states AS stcs ' .
                    'WHERE stcs.auction_id = a.id group by stcs.auction_id)',
                'observe' => [
                    ['class' => SamTaxCountryStates::class, 'properties' => ['AuctionId', 'State', 'Active']],
                ],
            ],
            'terms_and_conditions' => [
                'select' => 'a.terms_and_conditions',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['TermsAndConditions']],
                ],
            ],
            'test_auction' => [
                'select' => 'a.test_auction',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['TestAuction']],
                ],
            ],
            'date_assignment_strategy' => [
                'select' => 'a.date_assignment_strategy',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['DateAssignmentStrategy']],
                ],
            ],
            'total_lots' => [
                'select' => 'IF(a.auction_type = "' . Constants\Auction::TIMED . '" AND a.only_ongoing_lots, ' .
                    'ac.total_active_lots, ' .
                    'ac.total_lots)',
                'join' => 'auction_cache',
                'observe' => [
                    ['class' => AuctionCache::class, 'properties' => ['TotalActiveLots', 'TotalLots']],
                ],
            ],
            'wavebid_auction_guid' => [
                'select' => 'a.wavebid_auction_guid',
                'observe' => [
                    ['class' => Auction::class, 'properties' => ['WavebidAuctionGuid']],
                ],
            ],
        ];
    }

    /**
     * Define mappings for join clauses
     * @return void
     */
    protected function initJoinsMapping(): void
    {
        $this->joinsMapping = [
            'account' =>
                'INNER JOIN account acc ' .
                    'ON acc.id = a.account_id',

            'auction_auctioneer' =>
                'LEFT JOIN auction_auctioneer AS aa ' .
                    'ON aa.id = a.auction_auctioneer_id ' .
                    'AND aa.active',

            'auction_bidder' =>
                'LEFT JOIN auction_bidder aub ' .
                    'ON aub.auction_id = a.id ' .
                    'AND aub.user_id = @UserId',

            'auction_cache' =>
                'LEFT JOIN auction_cache ac ' .
                    'ON ac.auction_id = a.id',

            'auction_details_cache_by_seo_url' =>
                'LEFT JOIN auction_details_cache adc_by_su ' .
                    'ON adc_by_su.auction_id = a.id and adc_by_su.`key` = '
                        . $this->escape(Constants\AuctionDetailsCache::SEO_URL),

            'auction_image' =>
                'LEFT JOIN auction_image ai ' .
                    'ON ai.auction_id = a.id',

            'setting_auction' =>
                'LEFT JOIN setting_auction seta ' .
                    'ON seta.account_id = a.account_id',

            'setting_system' =>
                'LEFT JOIN setting_system setsys ' .
                'ON setsys.account_id = a.account_id',

            'invoice_location' =>
                'LEFT JOIN location AS l ' .
                    'ON (l.id = a.invoice_location_id ' .
                        ' OR (l.entity_id = a.id AND l.entity_type = '
                            . $this->escape(Constants\Location::TYPE_AUCTION_INVOICE) . ')) ' .
                    'AND l.active',

            'event_location' =>
                'LEFT JOIN location AS el ' .
                    'ON (el.id = a.event_location_id ' .
                        ' OR (el.entity_id = a.id AND el.entity_type = '
                            . $this->escape(Constants\Location::TYPE_AUCTION_EVENT) . ')) ' .
                    'AND el.active',

            'start_ending_timezone' =>
                'LEFT JOIN timezone setz ' .
                    'ON setz.id = ac.start_ending_timezone_id ' .
                    'AND setz.active',

            'timezone' =>
                'LEFT JOIN timezone atz ' .
                    'ON atz.id = a.timezone_id ' .
                    'AND atz.active',
        ];
    }

    /**
     * Define mappings for complex ORDER BY expressions
     * @return void
     */
    protected function initOrderFieldsMapping(): void
    {
        $this->orderFieldsMapping = [
            'name' => [
                'asc' => [
                    'order' =>
                        'a.name ASC',
                ],
            ],
            'id' => [
                'asc' => [
                    'order' =>
                        'a.id ASC',
                ],
            ],
            'sale_no' => [
                'asc' => [
                    'order' =>
                        'a.sale_num, a.sale_num_ext ASC',
                ],
            ],
            'status' => [
                'asc' => [
                    'order' =>
                        'status, ' .
                        'CASE status WHEN 1 THEN auction_date END ASC, ' .
                        'CASE status WHEN 2 THEN start_closing_date END ASC, ' .
                        'CASE status WHEN 3 THEN auction_date END DESC, ' .
                        'sale_num ASC, sale_num_ext ASC',
                    'prerequisite' => [
                        'auction_date',
                        'status',
                    ],
                ],
            ],
            'type' => [
                'asc' => [
                    'order' =>
                        'a.auction_type ASC',
                ],
            ],
        ];
    }
    // @formatter:on

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
        if ($dbResult) {
            $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_NUM);
            if (isset($row[0])) {
                $count = (int)$row[0];
            }
        }
        return $count;
    }

    /**
     * @return array
     */
    protected function getCountQueries(): array
    {
        if (!$this->checkInit()) {
            return [];
        }
        $queryParts = $this->getQueryParts();
        $countQuery = $this->composeCountQuery($queryParts);
        $queries = [
            'pre-queries' => $this->getPreQueries(),
            'count-query' => $countQuery,
        ];
        return $queries;
    }

    /**
     * Get fetched data in an array
     * @return array
     */
    public function getResults(): array
    {
        if (!$this->checkInit()) {
            return [];
        }
        $resultQuery = $this->flatten($this->getResultQuery());
        $dbResults = $this->multiQuery($resultQuery);
        $dbResult = current($dbResults);
        $results = [];
        if ($dbResult) {
            while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
                $results[] = $row;
            }
        }
        return $results;
    }

    /**
     * @return array
     */
    protected function getResultQuery(): array
    {
        if (!$this->checkInit()) {
            return [];
        }
        $queryParts = $this->getQueryParts();
        $queries['pre-queries'] = $this->getPreQueries();
        $queries['result-query'] = $this->composeResultQuery($queryParts);
        return $queries;
    }

    /**
     * Check initialization settings
     * @return bool
     */
    protected function checkInit(): bool
    {
        if (!$this->getSystemAccountId()) {
            throw new InvalidArgumentException('System Account Id not defined');
        }
        return true;
    }

    /**
     * Return query parts. Initialize if necessary
     * @param array $queryParts
     * @return array
     */
    protected function getQueryParts(array $queryParts = []): array
    {
        if (!is_array($this->queryParts)) {
            $queryParts = $this->initializeQueryParts($queryParts);
            $queryParts = $this->completeQueryParts($queryParts);
            $this->queryParts = $queryParts;
        }
        return $this->queryParts;
    }

    /**
     * Return pre-queries, that define some variables
     * @return array
     */
    protected function getPreQueries(): array
    {
        $preQueries = [];
        if ($this->getUserId()) {
            $preQueries[] = 'SET @UserId := ' . $this->escape($this->getUserId());
        }
        $preQueries[] = 'SET @NowUtc := ' . $this->escape($this->getCurrentDateUtcIso());
        $preQueries[] = 'SET @NowUtcTs := UNIX_TIMESTAMP(@NowUtc)';
        return $preQueries;
    }

    /**
     * @param array $queryParts
     * @return array
     */
    protected function initializeQueryParts(array $queryParts = []): array
    {
        // set defaults if not passed or empty
        if (!isset($queryParts['select'])) {
            $queryParts['select'] = 'SELECT ';
        }
        if (!isset($queryParts['from'])) {
            $queryParts['from'] = 'FROM auction a ';
        }
        if (!isset($queryParts['join'])) {
            $queryParts['join'] = [];
        }
        if (!isset($queryParts['where'])) {
            $queryParts['where'] = 'WHERE 1 ';
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
            if (
                $this->limit !== null
                && $this->offset !== null
            ) {
                $queryParts['limit'] = 'LIMIT ' . $this->offset . ', ' . $this->limit;
            }
        }

        if (!isset($queryParts['select_count'])) {
            $queryParts['select_count'] = 'SELECT COUNT(a.id) ';
        }
        if (!isset($queryParts['from_count'])) {
            $queryParts['from_count'] = $queryParts['from'];
        }
        if (!isset($queryParts['where_count'])) {
            $queryParts['where_count'] = '';
        }
        if (!isset($queryParts['having_count'])) {
            $queryParts['having_count'] = '';
        }

        $queryParts = $this->addFilterQueryParts($queryParts);

        $selects = [];
        foreach ($this->resultSetFields as $column) {
            if (array_key_exists($column, $this->resultFieldsMapping)) {
                $map = $this->resultFieldsMapping[$column];
                // Render SELECT query part from fields of resultSetFields
                $alias = empty($map['alias']) ? $column : $map['alias'];
                $selects[] = $map['select'] . ' `' . $alias . '`';
                if (!empty($map['join'])) {
                    $joins = (array)$map['join'];
                    // Collect keys for tables to be joined to the query based on the resultSetFields
                    foreach ($joins as $join) {
                        $queryParts['join'][] = $join;
                    }
                }
            }
        }

        // Render string with of JOINs necessary to select selected result set fields
        $joins = $this->buildJoinClause($queryParts['join']);

        // Attach string with list of selected fields to SELECT query part
        $queryParts['select'] .= implode(",\n", $selects) . ' ';
        // Attach string with list of JOINs to FROM query part
        $queryParts['from'] .= $joins . ' ';

        $queryParts = $this->addOrderingQueryParts($queryParts);

        return $queryParts;
    }

    /**
     * @param array $queryParts
     * @return array
     */
    protected function addFilterQueryParts(array $queryParts): array
    {
        $queryParts = $this->addRegisteredInAuctionQueryParts($queryParts);
        $queryParts = $this->addStateQueryParts($queryParts);
        $queryParts = $this->addCategoryFilterQueryParts($queryParts);
        $queryParts = $this->addPublishedFilterQueryParts($queryParts);
        $queryParts = $this->addAccountFilterQueryParts($queryParts);
        $queryParts = $this->addAuctionAccessFilterQueryParts($queryParts);
        $queryParts = $this->addAuctionTypeFilterQueryParts($queryParts);
        $queryParts = $this->addAuctioneerFilterQueryParts($queryParts);
        $queryParts = $this->addAuctionStatusFilterQueryParts($queryParts);
        $queryParts = $this->addAuctionIdFilterQueryParts($queryParts);
        $queryParts = $this->addSaleNoFilterQueryParts($queryParts);
        $queryParts = $this->addGeneralStatusFilterQueryParts($queryParts);
        $queryParts = $this->addActiveAccountFilterQueryParts($queryParts);
        return $queryParts;
    }

    /**
     * Extended version of addRegularStatusFilterQueryParts()
     * @param array $queryParts
     * @return array
     */
    protected function addStateQueryParts(array $queryParts): array
    {
        switch ($this->filterVisibleAuctionStatus) {
            case self::BIDDING_UPCOMING:
                $stat = '<= ' . Constants\Auction::STATUS_UPCOMING;
                break;
            case self::BIDDING:
                $stat = '= ' . Constants\Auction::STATUS_IN_PROGRESS;
                break;
            case self::UPCOMING:
                $stat = '= ' . Constants\Auction::STATUS_UPCOMING;
                break;
            case self::CLOSED:
                $stat = '= ' . Constants\Auction::STATUS_CLOSED;
                break;
            default:
                $visibleAuctionStatus = (int)$this->getSettingsManager()
                    ->get(Constants\Setting::VISIBLE_AUCTION_STATUSES, $this->getSystemAccountId());
                $stat = ' <= ' . Constants\Auction::STATUS_CLOSED;
                if (in_array($visibleAuctionStatus, [1, 3, 6, 7], true)) {
                    $stat = '<= ' . Constants\Auction::STATUS_UPCOMING;
                } elseif ($visibleAuctionStatus === 2) {
                    $stat = '= ' . Constants\Auction::STATUS_IN_PROGRESS;
                } elseif ($visibleAuctionStatus === 4) {
                    $stat = '= ' . Constants\Auction::STATUS_UPCOMING;
                } elseif ($visibleAuctionStatus === 8) {
                    $stat = '= ' . Constants\Auction::STATUS_CLOSED;
                } elseif ($visibleAuctionStatus === 15) {
                    $stat = '<= ' . Constants\Auction::STATUS_CLOSED;
                } elseif ($visibleAuctionStatus === 10) {
                    $stat = '!= ' . Constants\Auction::STATUS_UPCOMING;
                } elseif ($visibleAuctionStatus === 12) {
                    $stat = '!= ' . Constants\Auction::STATUS_IN_PROGRESS;
                }
                break;
        }
        $queryParts['where'] .= ' AND ' . $this->getGeneralStatusClause() . ' ' . $stat;
        return $queryParts;
    }

    /**
     * @param array $queryParts
     * @return array
     */
    protected function addGeneralStatusFilterQueryParts(array $queryParts): array
    {
        if ($this->filterGeneralStatus) {
            $generalStatusList = $this->toEscapedList($this->filterGeneralStatus);
            $queryParts['where'] .= ' AND ' . $this->getGeneralStatusClause()
                . ' IN (' . $generalStatusList . ')' . "\n";
        }
        return $queryParts;
    }

    /**
     * Return clause that calculates general auction status value (Constants\Auction::STATUS_...)
     * @return string
     */
    protected function getGeneralStatusClause(): string
    {
        $currentDateIso = $this->getCurrentDateUtcIso();
        $currentDateIsoEscaped = $this->escape($currentDateIso);
        // @formatter:off
        $clause =
            'IF (a.auction_type = "' . Constants\Auction::TIMED . '", ' .
                'IF (a.event_type = ' . Constants\Auction::ET_ONGOING . ',' .
                    Constants\Auction::STATUS_IN_PROGRESS . ', '.
                    'IF (a.start_bidding_date >= ' . $currentDateIsoEscaped . ',' .
                        Constants\Auction::STATUS_UPCOMING . ', ' .
                        'IF (a.end_date > ' . $currentDateIsoEscaped . ', '
                            . Constants\Auction::STATUS_IN_PROGRESS . ', '
                            . Constants\Auction::STATUS_CLOSED
                        . ')' .
                    ')' .
                '),' .
                'IF (a.auction_status_id = ' . Constants\Auction::AS_ACTIVE . ',' .
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

    /**
     * @param array $queryParts
     * @return array
     */
    protected function addRegisteredInAuctionQueryParts(array $queryParts): array
    {
        if ($this->isRegisteredInAuction) {
            $queryParts['where'] .= ' AND aub.user_id = ' . $this->escape($this->getUserId());
            $queryParts['join'][] = 'auction_bidder';
        }
        return $queryParts;
    }

    /**
     * @param array $queryParts
     * @return array
     */
    protected function addCategoryFilterQueryParts(array $queryParts): array
    {
        if ($this->auctionCustomFieldFilters) {
            $dbTransformer = DbTextTransformer::new();
            $n = "\n";
            foreach ($this->auctionCustomFieldFilters as $arr) {
                /** @var AuctionCustField $auctionCustomField */
                $auctionCustomField = $arr['field'];
                $id = $auctionCustomField->Id;
                $column = 'c' . $dbTransformer->toDbColumn($auctionCustomField->Name);
                $queryParts['join'][] = $this->joinsMapping[$column];
                $col = $auctionCustomField->isNumeric() ? '`numeric`' : '`text`';
                $queryParts['where'] .= "AND acd{$id}.{$col} = " . $this->escape($arr['value']) . " " . $n;
            }
        }
        return $queryParts;
    }

    /**
     * @param array $queryParts
     * @return array
     */
    protected function addAccountFilterQueryParts(array $queryParts): array
    {
        if ($this->getFilterAccountId()) {
            $queryParts['where'] .= ' AND a.account_id = ' . $this->escape($this->getFilterAccountId());
        }
        return $queryParts;
    }

    /**
     * @param array $queryParts
     * @return array
     */
    protected function addPublishedFilterQueryParts(array $queryParts): array
    {
        if ($this->published !== null) {
            $currentDateIso = $this->escape($this->getCurrentDateUtcIso());
            $queryParts['where'] .= ' ';
            if ($this->published) {
                $queryParts['where'] .= <<<SQL
AND a.publish_date <= {$currentDateIso}
AND (a.unpublish_date > {$currentDateIso} OR a.unpublish_date IS NULL)
SQL;
            } else {
                $queryParts['where'] .= <<<SQL
AND (
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
     * @param array $queryParts
     * @return array
     */
    protected function addAuctionAccessFilterQueryParts(array $queryParts): array
    {
        if ($this->auctionAccessChecks) {
            $aucAccessCond = [];
            $auctionAccessCheckerQueryBuilderHelper = $this->createAuctionAccessCheckerQueryBuilderHelper();
            foreach ($this->auctionAccessChecks as $resourceType) {
                $where = $auctionAccessCheckerQueryBuilderHelper->makeWhereClause($resourceType, $this->getUserId());
                if ($where) {
                    $aucAccessCond[] = $where;
                }
            }
            if ($aucAccessCond) {
                $queryParts['where'] .= ' AND ' . implode(' AND ', $aucAccessCond);
            }
        }
        return $queryParts;
    }

    /**
     * @param array $queryParts
     * @return array
     */
    protected function addAuctionTypeFilterQueryParts(array $queryParts): array
    {
        if ($this->filterAuctionTypes) {
            $auctionTypeList = $this->toEscapedList($this->filterAuctionTypes);
            $queryParts['where'] .= " AND a.auction_type IN ({$auctionTypeList})";
        }
        return $queryParts;
    }

    /**
     * @param array $queryParts
     * @return array
     */
    protected function addAuctioneerFilterQueryParts(array $queryParts): array
    {
        if ($this->auctioneerId) {
            $queryParts['where'] .= ' AND a.auction_auctioneer_id = ' . $this->escape($this->auctioneerId);
        }
        return $queryParts;
    }

    /**
     * @param array $queryParts
     * @return array
     */
    protected function addAuctionIdFilterQueryParts(array $queryParts): array
    {
        if ($this->auctionIds) {
            $auctionIdList = $this->toEscapedList($this->auctionIds);
            $queryParts['where'] .= " AND a.id IN ({$auctionIdList})";
        }
        return $queryParts;
    }

    /**
     * @param array $queryParts
     * @return array
     */
    protected function addSaleNoFilterQueryParts(array $queryParts): array
    {
        if ($this->filterSaleNo) {
            $saleNo = $this->escape($this->filterSaleNo);
            $queryParts['where'] .= " AND CONCAT(IFNULL(a.sale_num, ''), IFNULL(a.sale_num_ext, '')) = {$saleNo}";
        }
        return $queryParts;
    }

    /**
     * @param array $queryParts
     * @return array
     */
    protected function addAuctionStatusFilterQueryParts(array $queryParts): array
    {
        if ($this->auctionStatuses) {
            $auctionStatusList = $this->toEscapedList($this->auctionStatuses);
            $queryParts['where'] .= " AND a.auction_status_id IN ({$auctionStatusList})";
        }
        return $queryParts;
    }

    /**
     * Define auction access checking types
     * @param int[] $auctionAccessChecks
     * @return static
     */
    public function filterAuctionAccessCheck(array $auctionAccessChecks): static
    {
        $this->auctionAccessChecks = ArrayCast::castInt($auctionAccessChecks);
        return $this;
    }

    /**
     * Set filtering by auction statuses
     * @param int[] $auctionIds
     * @return static
     */
    public function filterIds(array $auctionIds): static
    {
        $this->auctionIds = ArrayCast::castInt($auctionIds);
        return $this;
    }

    /**
     * Set filtering by auction statuses
     * @param int[] $statuses
     * @return static
     */
    public function filterAuctionStatuses(array $statuses): static
    {
        $this->auctionStatuses = $statuses;
        return $this;
    }

    /**
     * Set filtering by auctioneer id (auction.auction_auctioneer_id)
     * @param int|null $auctioneerId null means do not filter by auctioneer
     * @return static
     */
    public function filterAuctioneerId(?int $auctioneerId): static
    {
        $this->auctioneerId = $auctioneerId;
        return $this;
    }

    /**
     * Set custom fields mapped for using in list (for selecting, ordering, filtering)
     * @param array $auctionCustomFields
     * @return static
     * @throws InvalidArgumentException
     */
    public function setMappedCustomFields(array $auctionCustomFields): static
    {
        foreach ($auctionCustomFields as $auctionCustomField) {
            if (!$auctionCustomField instanceof AuctionCustField) {
                throw new InvalidArgumentException("Not instance of AuctionCustField");
            }
        }
        $this->auctionCustomFields = $auctionCustomFields;
        foreach ($this->auctionCustomFields as $auctionCustomField) {
            $this->addCustomFieldOptionsToMapping($auctionCustomField);
        }
        $this->queryParts = null;
        return $this;
    }

    /**
     * Define filtering conditions by auction custom fields
     * @param array<int, array{field: AuctionCustField, value: mixed}> $auctionCustomFieldFilters
     * @return static
     */
    public function setCustomFieldFilters(array $auctionCustomFieldFilters): static
    {
        $this->auctionCustomFieldFilters = $auctionCustomFieldFilters;
        if ($this->auctionCustomFieldFilters) {
            foreach ($this->auctionCustomFieldFilters as $row) {
                $this->addCustomFieldOptionsToMapping($row['field']);
            }
        }
        return $this;
    }

    /**
     * @param bool $isRegisteredInAuction
     * @return static
     */
    public function enableRegisteredInAuction(bool $isRegisteredInAuction): static
    {
        $this->isRegisteredInAuction = $isRegisteredInAuction;
        return $this;
    }

    /**
     * @param int[] $regularStatus
     * @return static
     */
    public function filterRegularStatus(array $regularStatus): static
    {
        $this->filterGeneralStatus = $regularStatus;
        return $this;
    }

    /**
     * @param string $saleNo
     * @return static
     */
    public function filterSaleNo(string $saleNo): static
    {
        $this->filterSaleNo = $saleNo;
        return $this;
    }

    /**
     * @param int $filterVisibleAuctionStatus
     * @return static
     */
    public function filterVisibleAuctionStatus(int $filterVisibleAuctionStatus): static
    {
        $this->filterVisibleAuctionStatus = $filterVisibleAuctionStatus;
        return $this;
    }

    /**
     * Set filtering by auction type
     * @param string[] $auctionTypes
     * @return static
     */
    public function filterAuctionTypes(array $auctionTypes): static
    {
        $this->filterAuctionTypes = $auctionTypes;
        return $this;
    }

    /**
     * Define filtering by publish & unpublish dates
     * @param bool $isPublished
     * @return static
     */
    public function filterPublished(bool $isPublished): static
    {
        $this->published = $isPublished;
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
        $notMatchedFields = array_diff($resultSetFields, array_keys($this->resultFieldsMapping));
        if (count($notMatchedFields) > 0) {
            throw new InvalidArgumentException('Invalid result set fields: ' . implode(',', $notMatchedFields));
        }
        $this->resultSetFields = $resultSetFields;
        return $this;
    }

    /**
     * Add result set fields
     * @param array $resultSetFields
     * @return static
     * @noinspection PhpUnused
     */
    public function addResultSetFields(array $resultSetFields): static
    {
        $this->resultSetFields = array_merge($this->resultSetFields, $resultSetFields);
        $this->resultSetFields = array_unique($this->resultSetFields);
        return $this;
    }

    /**
     * Set the limit offset
     * @param int|null $offset
     * @return static
     */
    public function setOffset(?int $offset): static
    {
        $this->offset = $offset;
        $this->queryParts = null;
        return $this;
    }

    /**
     * Set the limit
     * @param int|null $limit
     * @return static
     */
    public function setLimit(?int $limit): static
    {
        $this->limit = Cast::toInt($limit, Constants\Type::F_INT_POSITIVE);
        $this->queryParts = null;
        return $this;
    }

    /**
     * Set the order key
     * @param string $orderKey
     * @return static
     */
    public function setOrder(string $orderKey): static
    {
        $this->orderKey = strtolower($orderKey);
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
            && false === stripos($queryParts['order'], "order by")
        ) {
            $queryParts['order'] = ' ORDER BY ' . $queryParts['order'];
        }
        if (
            !isset($queryParts['where_count'])) {
            $queryParts['where_count'] = '';
        }
        $queryParts['where_count'] = $queryParts['where'] . $queryParts['where_count'];
        if (
            !empty($queryParts['where'])
            && false === stripos($queryParts['where'], "where")
        ) {
            $queryParts['where'] = ' WHERE 1 ' . $queryParts['where'];
        }
        if (
            !empty($queryParts['where_count'])
            && false === stripos($queryParts['where_count'], "where")
        ) {
            $queryParts['where_count'] = ' WHERE 1 ' . $queryParts['where_count'];
        }
        if (
            !empty($queryParts['group_count'])
            && false === stripos($queryParts['group_count'], "group by")
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
     * Make count query string from query parts
     * @param array $queryParts
     * @return string
     */
    protected function composeCountQuery(array $queryParts): string
    {
        $n = "\n";
        $query = trim($queryParts['from_count']) . $n .
            trim($queryParts['where_count']) . $n .
            (isset($queryParts['group_count']) ? trim($queryParts['group_count']) . $n : '') .
            (isset($queryParts['having_count']) ? trim($queryParts['having_count']) . $n : '');
        if (str_contains($queryParts['select_count'], '%s')) {
            $query = sprintf($queryParts['select_count'], $query);
        } else {
            $query = trim($queryParts['select_count']) . $n . $query;
        }
        $query = trim($query);
        return $query;
    }

    /**
     * Make result query string from query parts
     * @param array $queryParts
     * @return string
     */
    protected function composeResultQuery(array $queryParts): string
    {
        $n = "\n";
        $query = $queryParts['select'] . $n . $queryParts['from'];
        if (!empty($queryParts['where'])) {
            $query .= $n . $queryParts['where'];
        }
        if (!empty($queryParts['group'])) {
            $query .= $n . $queryParts['group'];
        }
        if (!empty($queryParts['having'])) {
            $query .= $n . $queryParts['having'];
        }
        if (!empty($queryParts['order'])) {
            $query .= $n . $queryParts['order'];
        }
        if (!empty($queryParts['limit'])) {
            $query .= $n . $queryParts['limit'];
        }
        return $query;
    }

    /**
     * Build join clause based on passed array
     * @param array $joins
     * @param array $excludeTables - tables should be excluded from result
     * @return string
     */
    protected function buildJoinClause(array $joins, array $excludeTables = []): string
    {
        $n = "\n";
        $join = '';
        if ($joins) {
            $joins = array_unique($joins);
            foreach ($joins as $joinTable) {
                if (!in_array($joinTable, $excludeTables, true)) {
                    $join .= array_key_exists($joinTable, $this->joinsMapping)
                        ? $this->joinsMapping[$joinTable] // take from mapping table or
                        : $joinTable; // take literally
                    $join .= $n;
                }
            }
        }
        return $join;
    }

    /**
     * Initialize the result set and sort mapping arrays for available custom fields
     * @param AuctionCustField $auctionCustomField
     */
    protected function addCustomFieldOptionsToMapping(AuctionCustField $auctionCustomField): void
    {
        // get "safe" field name of custom field (remove spaces, special characters, etc)
        $id = $auctionCustomField->Id;
        $column = 'c' . DbTextTransformer::new()->toDbColumn($auctionCustomField->Name);
        if (!isset($this->resultFieldsMapping[$column])) {
            $queryParts = [];
            $queryParts['join'] = [
                "LEFT JOIN auction_cust_data acd{$id}"
                . " ON acd{$id}.auction_cust_field_id = " . $auctionCustomField->Id
                . " AND acd{$id}.auction_id = a.id"
                . " AND acd{$id}.active"
            ];
            $this->joinsMapping[$column] = $queryParts['join'][0];
            $orderParts = [
                'asc' => ['order' => ''],
                'desc' => ['order' => ''],
            ];

            if ($auctionCustomField->isNumeric()) {
                // SELECT term for numeric custom field types
                $queryParts['select'] = "acd{$id}.numeric";
                // ORDER BY term for numeric custom field type
                $orderParts['asc']['order'] = "acd{$id}.numeric ASC";
                $orderParts['desc']['order'] = "acd{$id}.numeric DESC";
            } else {
                // SELECT term for text custom field types
                $queryParts['select'] = "acd{$id}.text";
                // ORDER BY for text custom field types
                $orderParts['asc']['order'] = "acd{$id}.text ASC";
                $orderParts['desc']['order'] = "acd{$id}.text DESC";
            }
            // Prepare necessary JOIN for custom field
            $orderParts['asc']['join'] = $orderParts['desc']['join'] = [$queryParts['join'][0]];

            $this->resultFieldsMapping[$column] = $queryParts;
            $this->orderFieldsMapping[$column] = $orderParts;
        }
    }

    /**
     * Add select, join and order expressions required for ordering
     * @param array $queryParts
     * @return array
     */
    protected function addOrderingQueryParts(array $queryParts): array
    {
        $n = "\n";
        if ($this->orderKey) {
            $selects = [];    // for additional fields to select in filtering query
            $orders = [];     // for ordering expressions
            $orderKeys = explode(',', $this->orderKey);
            foreach ($orderKeys as $orderKey) {
                $arr = preg_split('/\s+/', trim($orderKey));
                $column = trim($arr[0]);
                $direction = empty($arr[1]) ? 'asc' : trim($arr[1]);
                if (!empty($this->orderFieldsMapping[$column])) {
                    $orderMap = $this->orderFieldsMapping[$column][$direction];
                    if (!empty($orderMap['order'])) {
                        $orders[] = $orderMap['order'];
                    }
                    if (!empty($orderMap['order_callback'])) {
                        $orders[] = eval($orderMap['order_callback']);
                    }
                    if (!empty($orderMap['prerequisite'])) {
                        $selects = array_merge($selects, $orderMap['prerequisite']);
                    }
                    // Add joins, that are required by ordering expression
                    if (!empty($orderMap['join'])) {
                        $queryParts['join'] = array_merge($queryParts['join'], $orderMap['join']);
                    }
                } elseif (!empty($this->resultFieldsMapping[$column])) {
                    $orders[] = $column . ' ' . $direction;
                    $selects[] = $column;
                } else {    // direct access to column
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
        return $queryParts;
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
        $observingProperties = null;
        if (!empty($this->resultFieldsMapping[$field]['observe'])) {
            $observingProperties = $this->resultFieldsMapping[$field]['observe'];
        }
        return $observingProperties;
    }

    /**
     * Unconditionally filter by active account.
     * @param array $queryParts
     * @return array
     */
    protected function addActiveAccountFilterQueryParts(array $queryParts): array
    {
        $queryParts['where'] .= ' AND acc.active';
        $queryParts['join'][] = 'account';
        return $queryParts;
    }
}
