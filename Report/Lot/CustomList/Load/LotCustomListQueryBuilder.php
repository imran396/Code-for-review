<?php
/**
 * Class implements functionality for building "Custom Lots" report query
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Query.php 20294 2015-01-29 03:07:28Z SWB\bregidor $
 * @since           Sep 27, 2012
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\CustomList\Load;

use LotItemCustField;
use Sam\Core\Auction\Render\AuctionPureRenderer;
use Sam\Core\Constants;
use Sam\Core\CustomField\Decimal\CustomDataDecimalPureCalculator;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\CustomField\Lot\PostalCode\Distance\PostalCodeDistanceQueryBuilderCreateTrait;
use Sam\CustomField\Lot\Validate\LotCustomFieldExistenceCheckerCreateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Report\Lot\CustomList\Template\LotCustomListTemplate;
use Sam\SharedService\PostalCode\PostalCodeSharedServiceClientAwareTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * Class Report_CustomLots_Query
 */
class LotCustomListQueryBuilder extends CustomizableClass
{
    use AccountAwareTrait;
    use AuctionAwareTrait;
    use DateHelperAwareTrait;
    use DbConnectionTrait;
    use FilterDatePeriodAwareTrait;
    use LimitInfoAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotCustomFieldExistenceCheckerCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use PostalCodeDistanceQueryBuilderCreateTrait;
    use PostalCodeSharedServiceClientAwareTrait;
    use SortInfoAwareTrait;

    /**
     * Custom Field Query Types: join is too slow at big lot count
     * Approx. benchmarking: 200000 lots - join: 55s, subquery: 7;
     * 10000 lots, for offset > 1500 - join: 3s, subquery: 4s;
     * 10000 lots, for offset < 1500 - join 0.5s, subquery: 2.5s
     */
    private const CFQT_SUBQUERY = 'subquery';
    private const CFQT_JOIN = 'join';

    /** @var string[][] */
    protected array $orderFieldsMapping = [
        'ItemNumber' => [
            'asc' => 'item_num ASC, item_num_ext ASC',
            'desc' => 'item_num DESC, item_num_ext DESC'
        ],
        'LotNumber' => [
            'asc' => 'lot_num_prefix ASC, lot_num ASC, lot_num_ext ASC',
            'desc' => 'lot_num_prefix DESC, lot_num DESC, lot_num_ext DESC'
        ],
        'AuctionNumber' => [
            'asc' => 'sale_num ASC, sale_num_ext ASC',
            'desc' => 'sale_num DESC, sale_num_ext DESC'
        ]
    ];

    // condition related fields
    protected bool $isIncludeWithoutHammerPrice = false;
    /** @var int[] */
    protected array $lotCategoryIds = [];
    protected ?int $categoryMatch = null;
    protected array $customFieldFilters = [];
    /** @var string */
    protected string $customFieldQueryType = self::CFQT_SUBQUERY;
    /** @var string[] */
    protected array $returnFields = [];
    protected array $postalCodeCoordinates = [];
    protected ?int $postalCodeCustomFieldCount = null;
    /** @var string[] */
    protected array $conds = [];
    /** @var string[] */
    protected array $joins = [];
    /** @var string[] */
    protected array $resultJoins = [];
    /** @var string[] */
    protected array $countJoins = [];
    /** @var string[] */
    protected array $groups = [];
    protected string $select = '';
    protected string $countSelect = 'SELECT COUNT(1) AS %s %s ';
    protected string $countSelectGrouped = 'SELECT COUNT(1) AS %s FROM lot_item AS li2 WHERE EXISTS (SELECT li.id %s)';
    protected string $from = ' FROM lot_item li';
    protected string $where = '';
    protected string $countWhere = '';
    protected string $join = '';
    protected string $countJoin = '';
    protected string $group = '';
    protected string $order = '';
    protected string $limitClause = '';
    protected string $nl = "\n";

    /**
     * Class instantiation method
     * @return static or customized class extending LotCustomListQueryBuilder
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        // @formatter:off
        $n = $this->nl;
        $this->joins['acc']         = 'INNER JOIN account AS acc ON acc.id = li.account_id';
        $this->joins['ali']         = 'LEFT JOIN auction_lot_item ali ON ' . $n .
            'ali.lot_item_id = li.id AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ') AND ' . $n .
            '((li.auction_id IS NOT NULL AND ali.auction_id = li.auction_id) ' . $n .
            'OR (li.auction_id IS NULL AND ali.id = ' . $n .
            '(SELECT ali2.id FROM auction_lot_item ali2 ' . $n .
            'WHERE ali2.lot_item_id=li.id AND ali2.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ') ' . $n .
            'ORDER BY ali2.created_on DESC LIMIT 1)))';
        $this->joins['a']           = 'LEFT JOIN auction a ON a.id = ali.auction_id';
        $this->joins['toi']         = 'LEFT JOIN timed_online_item toi ON toi.auction_id = ali.auction_id AND toi.lot_item_id = ali.lot_item_id';
        $this->joins['uw']          = 'LEFT JOIN user uw ON uw.id = li.winning_bidder_id';
        $this->joins['uiw']         = 'LEFT JOIN user_info uiw ON uiw.user_id = uw.id';
        $this->joins['ubw']         = 'LEFT JOIN user_billing ubw ON ubw.user_id = uw.id';
        $this->joins['usw']         = 'LEFT JOIN user_shipping usw ON usw.user_id = uw.id';
        $this->joins['uc']          = 'LEFT JOIN user uc ON uc.id = li.consignor_id';
        $this->joins['uccf']        = 'LEFT JOIN user_consignor_commission_fee uccf ON uccf.user_id = li.consignor_id';
        $this->joins['luiw']        = 'LEFT JOIN location luiw ON luiw.id = uiw.location_id';
        $this->joins['atz']         = 'LEFT JOIN timezone atz ON atz.id = a.timezone_id';
        $this->joins['acurr']       = 'LEFT JOIN currency acurr ON acurr.id = a.currency';
        $this->joins['aa']          = 'LEFT JOIN auction_auctioneer aa ON aa.id = a.auction_auctioneer_id';
        $this->joins['al']          = 'LEFT JOIN location al ON al.id = a.invoice_location_id';
        $this->joins['alicflop']    = 'LEFT JOIN lot_item_cust_field alicflop ON alicflop.id = a.lot_order_primary_cust_field_id';
        $this->joins['alicflos']    = 'LEFT JOIN lot_item_cust_field alicflos ON alicflos.id = a.lot_order_secondary_cust_field_id';
        $this->joins['alicflot']    = 'LEFT JOIN lot_item_cust_field alicflot ON alicflot.id = a.lot_order_tertiary_cust_field_id';
        $this->joins['alicfloq']    = 'LEFT JOIN lot_item_cust_field alicfloq ON alicfloq.id = a.lot_order_quaternary_cust_field_id';
        $this->joins['ai']          = 'LEFT JOIN auction_image ai ON ai.auction_id=a.id';
        $this->joins['ii']          = 'LEFT JOIN invoice_item ii ON ii.lot_item_id=li.id AND ii.active';
        $invoiceStatusList = implode(',', Constants\Invoice::$availableInvoiceStatuses);
        $this->joins['i']           = "LEFT JOIN invoice i ON ii.invoice_id = i.id AND i.invoice_status_id IN ({$invoiceStatusList})";
        $this->joins['ab']          = 'LEFT JOIN auction_bidder ab ON ab.user_id = li.winning_bidder_id AND ab.auction_id = a.id';
        $this->joins['seta']        = 'LEFT JOIN setting_auction seta ON seta.account_id = li.account_id';
        // @formatter:on
        return $this;
    }

    /**
     * Add filtering by custom field options
     *
     * @param LotItemCustField $lotCustomField
     * @param string|array $mixValues array('min' => min value, 'max' => max value) for types of Integer, Decimal, Date
     *                                      or string for types of Text and Select
     */
    public function addCustomFieldFilter(LotItemCustField $lotCustomField, string|array $mixValues): void
    {
        $this->customFieldFilters[] = ['CustFld' => $lotCustomField, 'Values' => $mixValues];
    }

    /**
     * Return query of report result
     *
     * @return string
     */
    public function getResultQuery(): string
    {
        $this->buildQueryParts();
        $resultQuery = $this->select . $this->from . $this->join . $this->where . $this->group . $this->order . $this->limitClause;
        return $resultQuery;
    }

    /**
     * Return query for counting
     *
     * @param string $alias count result alias
     * @return string
     */
    public function getCountQuery(string $alias): string
    {
        $this->buildQueryParts();
        $countSelect = empty($this->group) ? $this->countSelect : $this->countSelectGrouped;
        $countQuery = sprintf($countSelect, $alias, $this->from . $this->countJoin . $this->countWhere . $this->group);
        return $countQuery;
    }

    /**
     * Return ORDER BY clause for field index
     *
     * @param string $index
     * @param bool $isAscending
     * @return string
     */
    public function getOrderClause(string $index, bool $isAscending): string
    {
        if (!LotCustomListTemplate::new()->isSortOrderField($index)) {
            $index = 'ItemNumber';
        }

        if (array_key_exists($index, $this->orderFieldsMapping)) {
            $direction = $isAscending ? 'asc' : 'desc';
            return $this->orderFieldsMapping[$index][$direction];
        }

        return $index . ($isAscending ? ' ASC' : ' DESC');
    }

    /**
     * Return value of isIncludeWithoutHammerPrice property
     * @return bool
     */
    public function isIncludeWithoutHammerPrice(): bool
    {
        return $this->isIncludeWithoutHammerPrice;
    }

    /**
     * Set isIncludeWithoutHammerPrice property value and normalize boolean value
     * @param bool $isIncludeWithoutHammerPrice
     * @return static
     */
    public function enableIncludeWithoutHammerPrice(bool $isIncludeWithoutHammerPrice): static
    {
        $this->isIncludeWithoutHammerPrice = $isIncludeWithoutHammerPrice;
        return $this;
    }

    /**
     * Return value of categoryIds property
     * @return int[]
     */
    public function getFilterLotCategoryIds(): array
    {
        return $this->lotCategoryIds;
    }

    /**
     * Set categoryIds property value and normalize to integer positive value
     * @param int[] $lotCategoryIds
     * @return static
     */
    public function filterLotCategoryIds(array $lotCategoryIds): static
    {
        $this->lotCategoryIds = ArrayCast::makeIntArray($lotCategoryIds);
        return $this;
    }

    /**
     * Return value of categoryMatch property
     * @return int|null
     */
    public function getCategoryMatch(): ?int
    {
        return $this->categoryMatch;
    }

    /**
     * Set categoryMatch property value
     * @param int $categoryMatch
     * @return static
     */
    public function setCategoryMatch(int $categoryMatch): static
    {
        $this->categoryMatch = $categoryMatch;
        return $this;
    }

    /**
     * Return value of returnFields property
     * @return string[]
     */
    public function getReturnFields(): array
    {
        return $this->returnFields;
    }

    /**
     * Set returnFields property value and normalize to string value
     * @param string[] $returnFields
     * @return static
     */
    public function setReturnFields(array $returnFields): static
    {
        $this->returnFields = ArrayCast::makeStringArray($returnFields);
        return $this;
    }

    /**
     * Return value of customFieldQueryType property
     * @return string|null
     */
    public function getCustomFieldQueryType(): ?string
    {
        return $this->customFieldQueryType;
    }

    /**
     * Set customFieldQueryType property value and normalize to string value
     * @param string $customFieldQueryType
     * @return static
     */
    public function setCustomFieldQueryType(string $customFieldQueryType): static
    {
        $this->customFieldQueryType = $customFieldQueryType;
        return $this;
    }

    /**
     * Assemble query parts using current options. Save them in $this properties
     * ($_strSelect, $_where, $_strJoin, $_strCountJoin, $_strOrder, $_strLimit)
     */
    protected function buildQueryParts(): void
    {
        $n = $this->nl;
        $this->buildSelect();
        $this->buildWhere();
        $this->join = ' ' . implode(' ' . $n, $this->resultJoins);
        $this->countJoin = ' ' . implode(' ' . $n, $this->countJoins);

        $this->countWhere = $this->where;
        if (!empty($this->groups)) {
            $this->group = ' GROUP BY ' . implode(', ', $this->groups);
            $this->countWhere = empty($this->where) ? ' WHERE ' : $this->where . ' AND';
            $this->countWhere .= ' li2.id = li.id';
        }

        if ($this->getSortColumn()) {
            $this->order = $n . ' ORDER BY ' . $this->getOrderClause($this->getSortColumn(), $this->isAscendingOrder());
        }

        if ($this->getLimit() > 0) {
            $limitClause = $this->getLimit();
            if ($this->getOffset() > 0) {
                $limitClause = $this->getOffset() . ', ' . $limitClause;
            }
            $this->limitClause = $n . ' LIMIT ' . $limitClause;
        }
    }

    /**
     * Build and return select clause which used to get report result fields.
     * It is saved in $this->_strSelect
     *
     * @return string
     */
    protected function buildSelect(): string
    {
        // @formatter:off
        $n = $this->nl;
        $selects = [];
        $selects['id'] = 'li.id';
        $selects['account_id'] = 'li.account_id';
        $this->resultJoins['acc'] = $this->joins['acc'];
        $this->countJoins['acc'] = $this->joins['acc'];
        $templateManager = LotCustomListTemplate::new();
        foreach($this->returnFields as $field) {

            if ($templateManager->isAuctionField($field)) {
                $this->resultJoins['ali'] = $this->joins['ali'];
                $this->resultJoins['a'] = $this->joins['a'];
                $selects['auction_type'] = 'a.auction_type';
                $selects['auction_id'] = 'a.id';
            }

            switch($field) {
                case 'LotStatus':
                    $this->resultJoins['ali'] = $this->joins['ali'];
                    $this->resultJoins['a'] = $this->joins['a'];
                    $selects[$field] = 'ali.lot_status_id';
                    $selects['reverse'] = 'IF(a.reverse,1,0)';
                    break;

                case 'ItemNumber':
                    $selects[$field] = 'li.item_num';
                    $selects['item_num_ext'] = 'li.item_num_ext';
                    break;

                case 'ItemNumberExtension':
                    $selects[$field] = 'li.item_num_ext';
                    break;

                case 'LotNumber':
                    $this->resultJoins['ali'] = $this->joins['ali'];
                    $this->resultJoins['a'] = $this->joins['a'];
                    $selects['auction_id'] = 'a.id';
                    $selects['lot_num_prefix'] = "ali.lot_num_prefix";
                    $selects['lot_num'] = "ali.lot_num";
                    $selects['lot_num_ext'] = "ali.lot_num_ext";
                    break;

                case 'Quantity':
                    $this->resultJoins['ali'] = $this->joins['ali'];
                    $this->resultJoins['a'] = $this->joins['a'];
                    $this->resultJoins['seta'] = $this->joins['seta'];
                    $selects[$field] = 'ali.quantity';
                    $selects['quantity_scale'] = 'COALESCE(
                        ali.quantity_digits, 
                        li.quantity_digits, 
                        (SELECT lc.quantity_digits
                         FROM lot_category lc
                           INNER JOIN lot_item_category lic ON lc.id = lic.lot_category_id
                         WHERE lic.lot_item_id = li.id
                           AND lc.active = 1
                         ORDER BY lic.id
                         LIMIT 1), 
                        seta.quantity_digits)';
                    break;

                case 'QxM':
                    $this->resultJoins['ali'] = $this->joins['ali'];
                    $this->resultJoins['a'] = $this->joins['a'];
                    $selects[$field] = 'IF(ali.quantity_x_money,1,0)';
                    break;

                case 'LotName':
                    $selects[$field] = 'li.name';
                    break;

                case 'LotDescription':
                    $selects[$field] = 'li.description';
                    break;

                case 'Changes':
                    $selects[$field] = 'li.changes';
                    break;

                case 'Warranty':
                    $selects[$field] = 'li.warranty';
                    break;

                case 'SpecialTerms':
                    $this->resultJoins['ali'] = $this->joins['ali'];
                    $this->resultJoins['a'] = $this->joins['a'];
                    $selects[$field] = 'ali.terms_and_conditions';
                    break;

                case 'LowEst':
                    $selects[$field] = 'li.low_estimate';
                    break;

                case 'HighEst':
                    $selects[$field] = 'li.high_estimate';
                    break;

                case 'StartingBid':
                    $selects[$field] = 'li.starting_bid';
                    break;

                case 'BuyNowPrice':
                    $this->resultJoins['ali'] = $this->joins['ali'];
                    $this->resultJoins['a'] = $this->joins['a'];
                    $selects[$field] = "ali.buy_now_amount";
                    break;

                case 'ListingOnly':
                    $this->resultJoins['ali'] = $this->joins['ali'];
                    $this->resultJoins['a'] = $this->joins['a'];
                    $selects[$field] = 'ali.listing_only';
                    break;

                case 'Cost':
                    $selects[$field] = 'li.cost';
                    break;

                case 'Reserve':
                    $selects[$field] = 'li.reserve_price';
                    break;

                case 'Consignor':
                    $this->resultJoins['uc'] = $this->joins['uc'];
                    $selects['consignor_id'] = 'li.consignor_id';
                    $selects['consignor_account_id'] = 'uc.account_id';
                    $selects[$field] = 'uc.username';
                    break;

                case 'Commission':
                case 'CommissionPercent':
                    $this->resultJoins['ali'] = $this->joins['ali'];
                    $this->resultJoins['a'] = $this->joins['a'];
                    $this->resultJoins['uccf'] = $this->joins['uccf'];

                    $selects['HammerPrice'] = 'li.hammer_price';
                    $selects['consignor_commission_id'] = 'COALESCE(li.consignor_commission_id, ali.consignor_commission_id, a.consignor_commission_id, uccf.commission_id)';
                    break;

                case 'HammerPrice':
                    $selects[$field] = 'li.hammer_price';
                    break;

                case 'BuyersPremium':
                    $this->resultJoins['ali'] = $this->joins['ali'];
                    $this->resultJoins['a'] = $this->joins['a'];
                    $selects['auction_id'] = 'a.id';
                    $selects['winning_bidder_id'] = 'li.winning_bidder_id';
                    break;

                case 'AuctionId':
                    $this->resultJoins['ali'] = $this->joins['ali'];
                    $this->resultJoins['a'] = $this->joins['a'];
                    $selects['AuctionId'] = 'a.id';
                    break;

                case 'AuctionNumber':
                    $this->resultJoins['ali'] = $this->joins['ali'];
                    $this->resultJoins['a'] = $this->joins['a'];
                    $selects['auction_id'] = 'a.id';
                    $selects['sale_num'] = 'a.sale_num';
                    $selects['sale_num_ext'] = 'a.sale_num_ext';
                    break;

                case 'DateSold':
                    $selects[$field] = 'li.date_sold';
                    break;

                case 'WinningBidder':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $selects['winning_bidder_id'] = 'li.winning_bidder_id';
                    $selects['winning_bidder_account_id'] = 'uw.account_id';
                    $selects[$field] = 'uw.username';
                    break;

                case 'WinningBidderNum':
                    $this->resultJoins['ali'] = $this->joins['ali'];
                    $this->resultJoins['a'] = $this->joins['a'];
                    $this->resultJoins['ab'] = $this->joins['ab'];
                    $selects['winning_bidder_id'] = 'li.winning_bidder_id';
                    $selects['winning_bidder_number'] = 'ab.bidder_num';
                    $selects['winning_bidder_account_id'] = 'uw.account_id';
                    $selects[$field] = 'ab.bidder_num';
                    break;

                case 'InternetBid':
                    $selects[$field] = 'IF(li.internet_bid,1,0)';
                    break;

                case 'OnlyTaxBp':
                    $selects[$field] = 'IF(li.only_tax_bp,1,0)';
                    break;

                case 'TaxPercent':
                    $this->resultJoins['ali'] = $this->joins['ali'];
                    $this->resultJoins['a'] = $this->joins['a'];
                    $selects['auction_id'] = 'a.id';
                    $selects['winning_bidder_id'] = 'li.winning_bidder_id';
                    break;

                case 'Tax':
                    $this->resultJoins['ali'] = $this->joins['ali'];
                    $this->resultJoins['a'] = $this->joins['a'];
                    $selects['auction_id'] = 'a.id';
                    $selects['winning_bidder_id'] = 'li.winning_bidder_id';
                    $selects['HammerPrice'] = 'li.hammer_price';
                    break;

                case 'TaxInvoicePercent':
                    $this->resultJoins['ii'] = $this->joins['ii'];
                    $this->resultJoins['i'] = $this->joins['i'];
                    $selects[$field] = 'ii.sales_tax';
                    $selects['invoice_tax_designation'] = 'i.tax_designation';
                    break;

                case 'TaxInvoice':
                    $this->resultJoins['ii'] = $this->joins['ii'];
                    $this->resultJoins['i'] = $this->joins['i'];
                    $taHpBp = Constants\User::TAX_HP_BP;
                    $taHp = Constants\User::TAX_HP;
                    $taBp = Constants\User::TAX_BP;
                    $tdsLegacy = Constants\Invoice::TDS_LEGACY;
                    $selects[$field] =
                        "IF(i.tax_designation = " . $tdsLegacy . "," . $n .
                            "CASE ii.tax_application " . $n .
                                "WHEN {$taHpBp} THEN (ROUND((ii.sales_tax / 100) * (ii.hammer_price + ii.buyers_premium),2)) " . $n .
                                "WHEN {$taHp} THEN (ROUND((ii.sales_tax / 100) * ii.hammer_price,2)) " . $n .
                                "WHEN {$taBp} THEN (ROUND((ii.sales_tax / 100) * ii.buyers_premium,2)) " . $n .
                                "ELSE 0 " . $n .
                            "END," . $n .
                            "ii.hp_tax_amount + ii.bp_tax_amount" . $n .
                        ")";
                    break;

                case 'NoTaxOutside':
                    $selects[$field] = 'IF(li.no_tax_oos,1,0)';
                    break;

                case 'Returned':
                    $selects[$field] = 'IF(li.returned,1,0)';
                    break;

                case 'Featured':
                    $this->resultJoins['ali'] = $this->joins['ali'];
                    $this->resultJoins['a'] = $this->joins['a'];
                    $selects[$field] = 'IF(ali.sample_lot,1,0)';
                    break;

                case 'ClerkNote':
                    $this->resultJoins['ali'] = $this->joins['ali'];
                    $this->resultJoins['a'] = $this->joins['a'];
                    $selects[$field] = 'ali.note_to_clerk';
                    break;

                case 'GeneralNote':
                    $this->resultJoins['ali'] = $this->joins['ali'];
                    $this->resultJoins['a'] = $this->joins['a'];
                    $selects[$field] = 'ali.general_note';
                    break;

                case 'BulkControl':
                    $this->resultJoins['ali'] = $this->joins['ali'];
                    $this->resultJoins['a'] = $this->joins['a'];
                    $selects['is_bulk_master'] = 'ali.is_bulk_master';
                    $selects['bulk_master_id'] = 'ali.bulk_master_id';
                    $selects['ItemNumber'] = 'li.item_num';
                    break;

                case 'WinningBidDistribution':
                    $this->resultJoins['ali'] = $this->joins['ali'];
                    $this->resultJoins['a'] = $this->joins['a'];
                    $selects[$field] = 'ali.bulk_master_win_bid_distribution';
                    break;

                case 'WinnerUsername':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $selects[$field] = 'uw.username';
                    break;

                case 'WinnerEmail':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $selects[$field] = 'uw.email';
                    break;

                case 'WinnerCustomerNumber':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $selects[$field] = 'uw.customer_no';
                    break;

                case 'WinnerPermanentBidderNumber':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $selects[$field] = 'IF(uw.id, IF(uw.use_permanent_bidderno,1,0), "")';
                    break;

                case 'WinnerFirstName':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['uiw'] = $this->joins['uiw'];
                    $selects[$field] = 'uiw.first_name';
                    break;

                case 'WinnerLastName':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['uiw'] = $this->joins['uiw'];
                    $selects[$field] = 'uiw.last_name';
                    break;

                case 'WinnerPhone':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['uiw'] = $this->joins['uiw'];
                    $selects[$field] = 'uiw.phone';
                    break;

                case 'WinnerPhoneType':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['uiw'] = $this->joins['uiw'];
                    $selects[$field] = 'uiw.phone_type';
                    break;

                case 'WinnerIdentification':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['uiw'] = $this->joins['uiw'];
                    $selects[$field] = 'uiw.identification';
                    break;

                case 'WinnerIdentificationType':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['uiw'] = $this->joins['uiw'];
                    $selects[$field] = 'uiw.identification_type';
                    break;

                case 'WinnerCompanyName':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['uiw'] = $this->joins['uiw'];
                    $selects[$field] = 'uiw.company_name';
                    break;

                case 'WinnerLocation':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['uiw'] = $this->joins['uiw'];
                    $this->resultJoins['luiw'] = $this->joins['luiw'];
                    $selects[$field] = 'luiw.name';
                    break;

                case 'WinnerFlag':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $selects[$field] = 'uw.flag';
                    break;

                case 'WinnerReferrer':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['uiw'] = $this->joins['uiw'];
                    $selects[$field] = 'uiw.referrer';
                    break;

                case 'WinnerReferrerHost':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['uiw'] = $this->joins['uiw'];
                    $selects[$field] = 'uiw.referrer_host';
                    break;

                case 'WinnerBillingContactType':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['ubw'] = $this->joins['ubw'];
                    $selects[$field] = 'ubw.contact_type';
                    break;

                case 'WinnerBillingFirstName':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['ubw'] = $this->joins['ubw'];
                    $selects[$field] = 'ubw.first_name';
                    break;

                case 'WinnerBillingLastName':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['ubw'] = $this->joins['ubw'];
                    $selects[$field] = 'ubw.last_name';
                    break;

                case 'WinnerBillingCompanyName':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['ubw'] = $this->joins['ubw'];
                    $selects[$field] = 'ubw.company_name';
                    break;

                case 'WinnerBillingPhone':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['ubw'] = $this->joins['ubw'];
                    $selects[$field] = 'ubw.phone';
                    break;

                case 'WinnerBillingFax':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['ubw'] = $this->joins['ubw'];
                    $selects[$field] = 'ubw.fax';
                    break;

                case 'WinnerBillingCountry':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['ubw'] = $this->joins['ubw'];
                    $selects[$field] = 'ubw.country';
                    break;

                case 'WinnerBillingAddress':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['ubw'] = $this->joins['ubw'];
                    $selects[$field] = 'ubw.address';
                    break;

                case 'WinnerBillingAddress2':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['ubw'] = $this->joins['ubw'];
                    $selects[$field] = 'ubw.address2';
                    break;

                case 'WinnerBillingAddress3':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['ubw'] = $this->joins['ubw'];
                    $selects[$field] = 'ubw.address3';
                    break;

                case 'WinnerBillingCity':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['ubw'] = $this->joins['ubw'];
                    $selects[$field] = 'ubw.city';
                    break;

                case 'WinnerBillingState':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['ubw'] = $this->joins['ubw'];
                    $selects[$field] = 'ubw.state';
                    $selects['WinnerBillingCountry'] = 'ubw.country';
                    break;

                case 'WinnerBillingZip':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['ubw'] = $this->joins['ubw'];
                    $selects[$field] = 'ubw.zip';
                    break;

                case 'WinnerBillingAuthNetCim':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['ubw'] = $this->joins['ubw'];
                    $selects[$field] = 'ubw.auth_net_cpi';
                    break;

                case 'WinnerBillingCcType':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['ubw'] = $this->joins['ubw'];
                    $selects[$field] = 'ubw.cc_type';
                    break;

                case 'WinnerBillingCcNumber':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['ubw'] = $this->joins['ubw'];
                    $selects[$field] = 'ubw.cc_number';
                    break;

                case 'WinnerBillingExpDate':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['ubw'] = $this->joins['ubw'];
                    $selects[$field] = 'ubw.cc_exp_date';
                    break;

                case 'WinnerShippingContactType':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['usw'] = $this->joins['usw'];
                    $selects[$field] = 'usw.contact_type';
                    break;

                case 'WinnerShippingFirstName':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['usw'] = $this->joins['usw'];
                    $selects[$field] = 'usw.first_name';
                    break;

                case 'WinnerShippingLastName':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['usw'] = $this->joins['usw'];
                    $selects[$field] = 'usw.last_name';
                    break;

                case 'WinnerShippingCompanyName':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['usw'] = $this->joins['usw'];
                    $selects[$field] = 'usw.company_name';
                    break;

                case 'WinnerShippingPhone':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['usw'] = $this->joins['usw'];
                    $selects[$field] = 'usw.phone';
                    break;

                case 'WinnerShippingFax':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['usw'] = $this->joins['usw'];
                    $selects[$field] = 'usw.fax';
                    break;

                case 'WinnerShippingCountry':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['usw'] = $this->joins['usw'];
                    $selects[$field] = 'usw.country';
                    break;

                case 'WinnerShippingAddress':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['usw'] = $this->joins['usw'];
                    $selects[$field] = 'usw.address';
                    break;

                case 'WinnerShippingAddress2':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['usw'] = $this->joins['usw'];
                    $selects[$field] = 'usw.address2';
                    break;

                case 'WinnerShippingAddress3':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['usw'] = $this->joins['usw'];
                    $selects[$field] = 'usw.address3';
                    break;

                case 'WinnerShippingCity':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['usw'] = $this->joins['usw'];
                    $selects[$field] = 'usw.city';
                    break;

                case 'WinnerShippingState':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['usw'] = $this->joins['usw'];
                    $selects[$field] = 'usw.state';
                    $selects['WinnerShippingCountry'] = 'usw.country';
                    break;

                case 'WinnerShippingZip':
                    $this->resultJoins['uw'] = $this->joins['uw'];
                    $this->resultJoins['usw'] = $this->joins['usw'];
                    $selects[$field] = 'usw.zip';
                    break;

                case 'AuctionType':
                    $auctionPureRenderer = AuctionPureRenderer::new();
                    $timedName = $auctionPureRenderer->makeAuctionType(Constants\Auction::TIMED);
                    $liveName = $auctionPureRenderer->makeAuctionType(Constants\Auction::LIVE);
                    $hybridName = $auctionPureRenderer->makeAuctionType(Constants\Auction::HYBRID);
                    $selects[$field] =
                        "IF(a.auction_type='" . Constants\Auction::TIMED . "', " .
                        "'" . $timedName . "', " .
                        "IF(a.auction_type='" . Constants\Auction::LIVE . "', " .
                        "'" . $liveName . "', " .
                        "IF(a.auction_type='" . Constants\Auction::HYBRID . "', " .
                        "'" . $hybridName . "', " .
                        "''" .
                        ")))";
                    break;

                case 'AuctionStartDate':
                    $this->resultJoins['atz'] = $this->joins['atz'];
                    $selects[$field] = 'a.start_date';
                    $selects['auction_timezone_location'] = 'atz.location';
                    break;

                case 'AuctionStartClosingDate':
                    $this->resultJoins['atz'] = $this->joins['atz'];
                    $selects[$field] = 'a.start_closing_date';
                    $selects['auction_timezone_location'] = 'atz.location';
                    break;

                case 'AuctionEndDate':
                    $this->resultJoins['atz'] = $this->joins['atz'];
                    $selects[$field] = 'a.end_date';
                    $selects['auction_timezone_location'] = 'atz.location';
                    break;

                case 'AuctionClerkingStyle':
                    $selects[$field] =
                        "IF(a.auction_type='" . Constants\Auction::LIVE . "', " .
                        "IF(a.clerking_style='" . Constants\Auction::CS_SIMPLE . "', " .
                        "'Simple', " .
                        "'Advanced'), " .
                        "IF(a.auction_type='" . Constants\Auction::HYBRID . "', " .
                        "'Simple', " .
                        "''" .
                        ")" .
                        ")";
                    break;

                case 'AuctionName':
                    $selects[$field] = 'a.name';
                    break;

                case 'AuctionDescription':
                    $selects[$field] = 'a.description';
                    break;

                case 'AuctionTerms':
                    $selects[$field] = 'a.terms_and_conditions';
                    break;

                case 'AuctionInvoiceNotes':
                    $selects[$field] = 'a.invoice_notes';
                    break;

                case 'AuctionShippingInfo':
                    $selects[$field] = 'a.shipping_info';
                    break;

                case 'AuctionStreamDisplay':
                    $selects[$field] =
                        "IF(a.auction_type IN ('" . Constants\Auction::LIVE . "', '" . Constants\Auction::HYBRID . "'), " .
                        "IF(a.stream_display='" . Constants\Auction::SD_NONE . "', " . "'No', ''), ''" .
                        ")";
                    break;

                //                case 'AuctionStreamServer':
                //                    $selects[$field] =
                //                        "IF(a.auction_type IN ('" . Constants\Auction::LIVE . "', '" . Constants\Auction::HYBRID . "'), " .
                //                            "a.stream_server, " .
                //                            "'')";
                //                    break;
                //
                //                case 'AuctionStreamName':
                //                    $selects[$field] =
                //                        "IF(a.auction_type IN ('" . Constants\Auction::LIVE . "', '" . Constants\Auction::HYBRID . "'), " .
                //                            "a.stream_name, " .
                //                            "'')";
                //                    break;

                case 'AuctionParcelChoice':
                    $selects[$field] =
                        "IF(a.auction_type IN ('" . Constants\Auction::LIVE . "', '" . Constants\Auction::HYBRID . "'), " .
                        "IF(a.parcel_choice, 1, 0), " .
                        "'')";
                    break;

                case 'AuctionImage':
                    $this->resultJoins['ai'] = $this->joins['ai'];
                    $selects['auction_image_id'] = "ai.id";
                    $selects['auction_image_link'] = "ai.image_link";
                    break;

                case 'AuctionDefaultPostalCode':
                    $selects[$field] = 'a.default_lot_postal_code';
                    break;

                case 'AuctionPublished':
                    $currentDateIso = $this->escape($this->getDateHelper()->getCurrentDateUtcIso());
                    $selects[$field] = "IF(a.id > 0, IF(a.publish_date <= {$currentDateIso} AND (a.unpublish_date > {$currentDateIso} OR a.unpublish_date IS NULL), 1, 0), '')";
                    break;

                case 'AuctionTest':
                    $selects[$field] = "IF(a.id > 0, IF(a.test_auction, 1, 0), '')";
                    break;

                case 'AuctionReverse':
                    $selects[$field] = "IF(a.auction_type='" . Constants\Auction::TIMED . "', IF(a.reverse, 1, 0), '')";
                    break;

                case 'AuctionEventType':
                    $selects[$field] = "IF(a.auction_type='" . Constants\Auction::TIMED . "', a.event_type, '')";
                    break;

                case 'AuctionStaggerClosing':
                    $selects[$field] = "IF(a.auction_type='" . Constants\Auction::TIMED . "', a.stagger_closing, '')";
                    break;

                case 'AuctionLotsPerInterval':
                    $selects[$field] = "IF(a.auction_type='" . Constants\Auction::TIMED . "', a.lots_per_interval, '')";
                    break;

                case 'AuctionExcludeClosedLots':
                    $selects[$field] = "IF(a.auction_type='" . Constants\Auction::TIMED . "', IF(a.exclude_closed_lots, 1, 0), '')";
                    break;

                case 'AuctionOnlyOngoingLots':
                    $selects[$field] = "IF(a.auction_type='" . Constants\Auction::TIMED . "', IF(a.only_ongoing_lots, 1, 0), '')";
                    break;

                case 'AuctionDateAssignmentStrategy':
                    $selects[$field] = "IF(a.auction_type='" . Constants\Auction::TIMED . "' AND a.extend_all = false, a.date_assignment_strategy, '')";
                    break;

                case 'AuctionSaleGroup':
                    $selects[$field] = 'a.sale_group';
                    break;

                case 'AuctionCcThresholdDom':
                    $selects[$field] = 'a.cc_threshold_domestic';
                    break;

                case 'AuctionCcThresholdInt':
                    $selects[$field] = 'a.cc_threshold_international';
                    break;

                case 'AuctionAuthAmount':
                    $selects[$field] = 'a.authorization_amount';
                    break;

                case 'AuctionHeldIn':
                    $selects[$field] = 'a.auction_held_in';
                    break;

                case 'AuctionCurrency':
                    $this->resultJoins['acurr'] = $this->joins['acurr'];
                    $selects[$field] = "CONCAT(acurr.name, '(', acurr.sign, ')')";
                    break;

                case 'AuctionAddCurrencies':
                    break;

                case 'AuctionAuctioneer':
                    $this->resultJoins['aa'] = $this->joins['aa'];
                    $selects[$field] = 'aa.name';
                    break;

                case 'AuctionEmail':
                    $selects[$field] = 'a.email';
                    break;

                case 'AuctionLocation':
                    $this->resultJoins['al'] = $this->joins['al'];
                    $selects[$field] = 'al.name';
                    break;

                case 'AuctionTaxPercent':
                    $selects[$field] = 'a.tax_percent';
                    break;

                case 'AuctionPaymentTrackCode':
                    $selects[$field] = 'a.payment_tracking_code';
                    break;

                case 'AuctionLotOrderPri':
                    $this->resultJoins['alicflop'] = $this->joins['alicflop'];
                    $selects['lot_order_primary_type'] = 'a.lot_order_primary_type';
                    $selects['lot_order_primary_cust_field_name'] = 'alicflop.name';
                    break;

                case 'AuctionLotOrderSec':
                    $this->resultJoins['alicflos'] = $this->joins['alicflos'];
                    $selects['lot_order_secondary_type'] = 'a.lot_order_secondary_type';
                    $selects['lot_order_secondary_cust_field_name'] = 'alicflos.name';
                    break;

                case 'AuctionLotOrderTer':
                    $this->resultJoins['alicflot'] = $this->joins['alicflot'];
                    $selects['lot_order_tertiary_type'] = 'a.lot_order_tertiary_type';
                    $selects['lot_order_tertiary_cust_field_name'] = 'alicflot.name';
                    break;

                case 'AuctionLotOrderQua':
                    $this->resultJoins['alicfloq'] = $this->joins['alicfloq'];
                    $selects['lot_order_quaternary_type'] = 'a.lot_order_quaternary_type';
                    $selects['lot_order_quaternary_cust_field_name'] = 'alicfloq.name';
                    break;

                case 'AuctionLotOrderPriIgnStpWrd':
                    $selects[$field] = 'IF(a.id, IF(a.lot_order_primary_ignore_stop_words, 1, 0), "")';
                    break;

                case 'AuctionLotOrderSecIgnStpWrd':
                    $selects[$field] = 'IF(a.id, IF(a.lot_order_secondary_ignore_stop_words, 1, 0), "")';
                    break;

                case 'AuctionLotOrderTerIgnStpWrd':
                    $selects[$field] = 'IF(a.id, IF(a.lot_order_tertiary_ignore_stop_words, 1, 0), "")';
                    break;

                case 'AuctionLotOrderQuaIgnStpWrd':
                    $selects[$field] = 'IF(a.id, IF(a.lot_order_quaternary_ignore_stop_words, 1, 0), "")';
                    break;

                case 'AuctionBlacklist':
                    $selects[$field] = 'a.blacklist_phrase';
                    break;

                case 'AuctionLotChangeConfirm':
                    $selects[$field] = "IF(a.id > 0, IF(a.require_lot_change_confirmation, 1, 0), '')";
                    break;

                case 'AuctionPopLotFromCategory':
                    $selects[$field] = "IF(a.id > 0, IF(a.auto_populate_lot_from_category, 1, 0), '')";
                    break;

                case 'AuctionPopEmptyLotNum':
                    $selects[$field] = "IF(a.id > 0, IF(a.auto_populate_empty_lot_num, 1, 0), '')";
                    break;

                case 'AuctionDefaultLotPeriod':
                    $selects[$field] = "IF(a.auction_type='" . Constants\Auction::TIMED . "', a.default_lot_period, '')";
                    break;

                case 'InvoiceNumber':
                    $this->resultJoins['ii'] = $this->joins['ii'];
                    $this->resultJoins['i'] = $this->joins['i'];
                    $selects[$field] = 'i.invoice_no';
                    break;

                case 'Name':
                    $selects[$field] = 'acc.name';
                    break;

                case 'CompanyName':
                    $selects[$field] = 'acc.company_name';
                    break;

                case 'UrlDomain':
                    $selects[$field] = 'acc.url_domain';
                    $selects['account_id'] = 'acc.id';
                    $selects['account_name'] = 'acc.name';
                    break;

                case 'AuctionWavebidGuid':
                    $selects[$field] = 'a.wavebid_auction_guid';
                    break;

                default:
                    if (preg_match('/^fc(\d+)$/', $field, $matches)) {
                        $customFieldId = (int)$matches[1];
                        $lotCustomField = $this->createLotCustomFieldLoader()->load($customFieldId);
                        if ($lotCustomField) {
                            $this->resultJoins['ali'] = $this->joins['ali'];
                            $this->resultJoins['a'] = $this->joins['a'];
                            $selects['auction_id'] = 'a.id';
                            if ($this->getCustomFieldQueryType() === self::CFQT_JOIN) {
                                $tableAlias = 'licd' . $customFieldId;
                                $this->resultJoins[$tableAlias] = 'LEFT JOIN lot_item_cust_data '.$tableAlias .
                                    ' ON ' . $tableAlias . '.lot_item_cust_field_id = ' . $this->escape($customFieldId) .
                                    ' AND ' . $tableAlias . '.active = true' .
                                    ' AND ' . $tableAlias . '.lot_item_id = li.id';
                                $selects[$field] = $lotCustomField->isNumeric()
                                    ? $tableAlias . '.numeric'
                                    : $tableAlias . '.text';
                            } else {
                                if ($lotCustomField->isNumeric()) {
                                    $selects[$field] = '(SELECT licd.`numeric` FROM lot_item_cust_data AS licd ' .
                                        'WHERE licd.lot_item_id = li.id AND licd.active = true ' .
                                        'AND licd.lot_item_cust_field_id = ' . $this->escape($lotCustomField->Id) . ' limit 1)';
                                } else {
                                    $selects[$field] = '(SELECT licd.`text` FROM lot_item_cust_data AS licd ' .
                                        'WHERE licd.lot_item_id = li.id AND licd.active = true ' .
                                        'AND licd.lot_item_cust_field_id = ' . $this->escape($lotCustomField->Id) . ' limit 1)';
                                }
                            }
                        }
                    } elseif (preg_match('/^Category/', $field, $matches)) {  // for Category, CategoryTree, CategoryLevelX
                        // we use li.id
                    } else {
                        $selects[$field] = "'-'";
                    }
                    break;
            }
        }

        // Assemble queries
        $selectClause = 'SELECT ';
        foreach($selects as $field => $clause)
            $selectClause .= $clause . ' AS ' . $field . ', ';
        $this->select = rtrim($selectClause, ', ');
        return $this->select;
        // @formatter:on
    }

    /**
     * Build and return where clause, which is used in result and count queries.
     * It is saved in $this->_where
     *
     * @return string
     */
    protected function buildWhere(): string
    {
        $n = $this->nl;
        $this->conds = [];
        // exclude deleted items
        $this->conds[] = 'li.active';
        // exclude items from deleted accounts
        $this->conds[] = 'acc.active';

        // Filtering by date
        if ($this->getFilterStartDateSysIso() && $this->getFilterEndDateSysIso()) {
            $this->conds[] = "((li.date_sold IS NOT NULL AND li.date_sold BETWEEN " . $this->escape($this->getFilterStartDateSysIso()) .
                " AND " . $this->escape($this->getFilterEndDateSysIso()) . ") OR (date_sold IS NULL AND li.created_on BETWEEN " .
                $this->escape($this->getFilterStartDateSysIso()) . " AND " . $this->escape($this->getFilterEndDateSysIso()) . "))";
        }

        /**
         * Filtering by auction
         * Can accept -1 @see Constants\AuctionListAutocomplete::UNASSIGNED_AUCTION_ID
         * if we need to display only lots that unassigned to auctions
         */
        $auctionId = $this->getAuctionId();
        if ($auctionId) {
            if ($auctionId === Constants\AuctionListAutocomplete::UNASSIGNED_AUCTION_ID) {
                $this->conds[] = "a.id IS NULL";
            } else {
                $this->conds[] = "a.id = " . $this->escape($auctionId);
            }
            $this->resultJoins['ali'] = $this->joins['ali'];
            $this->resultJoins['a'] = $this->joins['a'];
            $this->countJoins['ali'] = $this->joins['ali'];
            $this->countJoins['a'] = $this->joins['a'];
        }

        // Filtering by including lots without hammer price
        if (!$this->isIncludeWithoutHammerPrice()) {
            $this->conds[] = "li.hammer_price IS NOT NULL AND li.hammer_price IS NOT NULL";
        }

        // Filtering by categories
        if ($this->getFilterLotCategoryIds()) {
            if ($this->getCategoryMatch() === Constants\MySearch::CATEGORY_MATCH_ALL) {
                foreach ($this->getFilterLotCategoryIds() as $lotCategoryId) {
                    $lotCategoryIds = $this->getLotCategoryLoader()
                        ->loadCategoryWithDescendantIds([$lotCategoryId], true);
                    $lotCategoryIdList = implode(',', $lotCategoryIds);
                    $this->conds[] = "(SELECT COUNT(1) FROM lot_item_category WHERE lot_item_id = li.id AND lot_category_id IN (" . $lotCategoryIdList . ")) > 0";
                }
            } else {
                $lotCategoryIds = $this->getLotCategoryLoader()
                    ->loadCategoryWithDescendantIds($this->getFilterLotCategoryIds(), true);
                $lotCategoryIdList = implode(',', $lotCategoryIds);
                $this->conds[] = "(SELECT COUNT(1) FROM lot_item_category WHERE lot_item_id = li.id AND lot_category_id IN (" . $lotCategoryIdList . ")) > 0";
            }
        }

        // Filtering by custom fields
        $this->buildCustomFieldConditions();

        if ($this->getAccountId()) {
            $this->conds[] = 'li.account_id=' . $this->escape($this->getAccountId());
        }

        if (!empty($this->conds)) {
            $this->where = $n . ' WHERE ' . implode($n . ' AND ', $this->conds);
        }

        return $this->where;
    }

    /**
     * Build where and join clauses for custom fields selection
     * Result query could be of two types:
     * self::CFQT_JOIN - use joins to custom field data tables
     * self::CFQT_SUBQUERY - use sub-queries to select custom field data
     */
    protected function buildCustomFieldConditions(): void
    {
        foreach ($this->customFieldFilters as $customFieldFilters) {
            /** @var LotItemCustField $lotCustomField */
            $lotCustomField = $customFieldFilters['CustFld'];
            $mixValues = $customFieldFilters['Values'];
            $tableAlias = $isConditionAdded = $customFieldJoin = null;
            // create join clause for custom field
            if ($this->getCustomFieldQueryType() === self::CFQT_JOIN) {
                $tableAlias = 'licd' . $lotCustomField->Id;
                $customFieldJoin = 'LEFT JOIN lot_item_cust_data ' . $tableAlias .
                    ' ON ' . $tableAlias . '.lot_item_cust_field_id = ' . $this->escape($lotCustomField->Id) .
                    ' AND ' . $tableAlias . '.active = true' .
                    ' AND ' . $tableAlias . '.lot_item_id = li.id';
                $isConditionAdded = false;
            }

            switch ($lotCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                    $min = $mixValues['min'];
                    $max = $mixValues['max'];
                    if ($min !== null && $max === null) {
                        if ($this->getCustomFieldQueryType() === self::CFQT_JOIN) {
                            $this->conds[] = $tableAlias . ".numeric >= " . $this->escape($min);
                            $isConditionAdded = true;
                        } else {
                            $this->conds[] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                                "AND licd.numeric >= " . $this->escape($min) . ") > 0)";
                        }
                    } elseif ($min !== null && $max !== null) {
                        if ($this->getCustomFieldQueryType() === self::CFQT_JOIN) {
                            $this->conds[] = $tableAlias . ".numeric >= " . $this->escape($min);
                            $this->conds[] = $tableAlias . ".numeric <= " . $this->escape($max);
                            $isConditionAdded = true;
                        } else {
                            $this->conds[] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                                "AND licd.numeric >= " . $this->escape($min) . ") > 0)";
                            $this->conds[] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                                "AND licd.numeric <= " . $this->escape($max) . ") > 0)";
                        }
                    } elseif ($min === null && $max !== null) {
                        if ($this->getCustomFieldQueryType() === self::CFQT_JOIN) {
                            $this->conds[] = $tableAlias . ".numeric <= " . $this->escape($max);
                            $isConditionAdded = true;
                        } else {
                            $this->conds[] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                                "AND licd.numeric <= " . $this->escape($max) . ") > 0)";
                        }
                    }
                    if ($this->getCustomFieldQueryType() === self::CFQT_JOIN) {
                        if ($isConditionAdded) {
                            $this->resultJoins[$tableAlias] = $customFieldJoin;
                            $this->countJoins[$tableAlias] = $customFieldJoin;
                        }
                    }
                    break;

                case Constants\CustomField::TYPE_DECIMAL:
                    $minVal = $mixValues['min'];
                    $maxVal = $mixValues['max'];
                    $precision = (int)$lotCustomField->Parameters;
                    if ($minVal !== null && $maxVal === null) {
                        $min = CustomDataDecimalPureCalculator::new()->calcModelValue((float)$minVal, $precision);
                        if ($this->getCustomFieldQueryType() === self::CFQT_JOIN) {
                            $this->conds[] = $tableAlias . ".numeric >= " . $this->escape($min);
                            $isConditionAdded = true;
                        } else {
                            $this->conds[] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                                "AND licd.numeric >= " . $this->escape($min) . ") > 0)";
                        }
                    } elseif ($minVal !== null && $maxVal !== null) {
                        $min = CustomDataDecimalPureCalculator::new()->calcModelValue((float)$minVal, $precision);
                        $max = CustomDataDecimalPureCalculator::new()->calcModelValue((float)$maxVal, $precision);
                        if ($this->getCustomFieldQueryType() === self::CFQT_JOIN) {
                            $this->conds[] = $tableAlias . ".numeric >= " . $this->escape($min);
                            $this->conds[] = $tableAlias . ".numeric <= " . $this->escape($max);
                            $isConditionAdded = true;
                        } else {
                            $this->conds[] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                                "AND licd.numeric >= " . $this->escape($min) . ") > 0)";
                            $this->conds[] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                                "AND licd.numeric <= " . $this->escape($max) . ") > 0)";
                        }
                    } elseif ($minVal === null && $maxVal !== null) {
                        $max = CustomDataDecimalPureCalculator::new()->calcModelValue((float)$maxVal, $precision);
                        if ($this->getCustomFieldQueryType() === self::CFQT_JOIN) {
                            $this->conds[] = $tableAlias . ".numeric <= " . $this->escape($max);
                            $isConditionAdded = true;
                        } else {
                            $this->conds[] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                                "AND (licd.numeric <= " . $this->escape($max) . ")) > 0)";
                        }
                    }
                    if ($this->getCustomFieldQueryType() === self::CFQT_JOIN) {
                        if ($isConditionAdded) {
                            $this->resultJoins[$tableAlias] = $customFieldJoin;
                            $this->countJoins[$tableAlias] = $customFieldJoin;
                        }
                    }
                    break;

                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_FULLTEXT:
                    $searchKey = $this->escape('%' . $mixValues . '%');
                    if ($this->getCustomFieldQueryType() === self::CFQT_JOIN) {
                        $this->conds[] = $tableAlias . ".text LIKE '" . $searchKey . "'";
                        $this->resultJoins[$tableAlias] = $customFieldJoin;
                        $this->countJoins[$tableAlias] = $customFieldJoin;
                    } else {
                        $this->conds[] = "((SELECT COUNT(1) " .
                            "FROM lot_item_cust_data AS licd " .
                            "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                            "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                            "AND licd.text LIKE " . $searchKey . ") > 0)";
                    }
                    break;

                case Constants\CustomField::TYPE_SELECT:
                    if ($this->getCustomFieldQueryType() === self::CFQT_JOIN) {
                        $this->conds[] = $tableAlias . ".text = " . $this->escape($mixValues);
                        $this->resultJoins[$tableAlias] = $customFieldJoin;
                        $this->countJoins[$tableAlias] = $customFieldJoin;
                    } else {
                        $this->conds[] = "((SELECT COUNT(1) " .
                            "FROM lot_item_cust_data AS licd " .
                            "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                            "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                            "AND licd.text = " . $this->escape($mixValues) . ") > 0)";
                    }
                    break;

                case Constants\CustomField::TYPE_DATE:
                    $min = $mixValues['min'];
                    $max = $mixValues['max'];
                    if ($min !== null && $max === null) {
                        if ($this->getCustomFieldQueryType() === self::CFQT_JOIN) {
                            $this->conds[] = "FROM_UNIXTIME(" . $tableAlias . ".numeric, '%Y-%m-%d') >= FROM_UNIXTIME(" . $this->escape($min) . ", '%Y-%m-%d')";
                            $isConditionAdded = true;
                        } else {
                            $this->conds[] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                                "AND FROM_UNIXTIME(licd.numeric, '%Y-%m-%d') >= FROM_UNIXTIME(" . $this->escape($min) . ", '%Y-%m-%d')) > 0)";
                        }
                    } elseif ($min !== null && $max !== null) {
                        if ($this->getCustomFieldQueryType() === self::CFQT_JOIN) {
                            $this->conds[] = "FROM_UNIXTIME(" . $tableAlias . ".numeric, '%Y-%m-%d') >= FROM_UNIXTIME(" . $this->escape($min) . ", '%Y-%m-%d')";
                            $this->conds[] = "FROM_UNIXTIME(" . $tableAlias . ".numeric, '%Y-%m-%d') <= FROM_UNIXTIME(" . $this->escape($max) . ", '%Y-%m-%d')";
                            $isConditionAdded = true;
                        } else {
                            $this->conds[] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                                "AND FROM_UNIXTIME(licd.numeric, '%Y-%m-%d') >= FROM_UNIXTIME(" . $this->escape($min) . ", '%Y-%m-%d')) > 0)";
                            $this->conds[] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                                "AND FROM_UNIXTIME(licd.numeric, '%Y-%m-%d') <= FROM_UNIXTIME(" . $this->escape($max) . ", '%Y-%m-%d')) > 0)";
                        }
                    } elseif ($min === null && $max !== null) {
                        if ($this->getCustomFieldQueryType() === self::CFQT_JOIN) {
                            $this->conds[] = "FROM_UNIXTIME(" . $tableAlias . ".numeric, '%Y-%m-%d') <= FROM_UNIXTIME(" . $this->escape($max) . ", '%Y-%m-%d')";
                            $isConditionAdded = true;
                        } else {
                            $this->conds[] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_data AS licd " .
                                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                                "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                                "AND FROM_UNIXTIME(licd.numeric, '%Y-%m-%d') <= FROM_UNIXTIME(" . $this->escape($max) . ", '%Y-%m-%d')) > 0)";
                        }
                    }
                    if ($this->getCustomFieldQueryType() === self::CFQT_JOIN) {
                        if ($isConditionAdded) {
                            $this->resultJoins[$tableAlias] = $customFieldJoin;
                            $this->countJoins[$tableAlias] = $customFieldJoin;
                        }
                    }
                    break;

                case Constants\CustomField::TYPE_POSTALCODE:
                    $postalCode = $mixValues['pcode'];
                    $radius = $mixValues['radius'];
                    if ($radius !== null && $postalCode !== null) {
                        if (!isset($this->postalCodeCoordinates[$postalCode])) {
                            $this->postalCodeCoordinates[$postalCode] = $this->getPostalCodeSharedServiceClient()
                                ->findCoordinates($postalCode);
                        }
                        if ($this->postalCodeCoordinates[$postalCode]) {
                            $locationTableAlias = 'lig' . $lotCustomField->Id;
                            $latitude = $this->postalCodeCoordinates[$postalCode]['latitude'];
                            $longitude = $this->postalCodeCoordinates[$postalCode]['longitude'];
                            $distanceQueryBuilder = $this->createPostalCodeDistanceQueryBuilder();
                            $join = 'INNER JOIN ' . $distanceQueryBuilder->buildJoinClause(
                                    'li.id',
                                    $locationTableAlias,
                                    $lotCustomField->Id
                                );
                            $this->resultJoins[$locationTableAlias] = $join;
                            $this->countJoins[$locationTableAlias] = $join;
                            $this->conds[] = $distanceQueryBuilder->buildWhereClause(
                                $latitude,
                                $longitude,
                                $radius,
                                $locationTableAlias
                            );
                            // we must group results, if there is more than 1 custom field with type of Postal Code
                            if ($this->postalCodeCustomFieldCount === null) {
                                $this->postalCodeCustomFieldCount = $this->createLotCustomFieldExistenceChecker()
                                    ->countByType(Constants\CustomField::TYPE_POSTALCODE, true);
                            }
                            if ($this->postalCodeCustomFieldCount > 1) {
                                $this->groups['li.id'] = 'li.id';
                            }
                        }
                    }
                    break;
            }
        }
    }
}
