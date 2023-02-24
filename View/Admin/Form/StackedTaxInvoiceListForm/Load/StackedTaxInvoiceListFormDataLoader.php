<?php
/**
 * Invoice List Form Data Loader
 *
 * SAM-11079: Stacked Tax. Tax aggregation. Admin Invoice List page
 * SAM-6092: Refactor Invoice List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 16, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\StackedTaxInvoiceListForm\Load;

use QMySqli5DatabaseResult;
use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Model\Invoice\Status\InvoiceStatusPureChecker;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceAuction\InvoiceAuctionReadRepositoryCreateTrait;
use Sam\View\Admin\Form\StackedTaxInvoiceListForm\StackedTaxInvoiceListConstants;

/**
 * Class InvoiceListFormDataLoader
 */
class StackedTaxInvoiceListFormDataLoader extends CustomizableClass
{
    use ApplicationAccessCheckerCreateTrait;
    use BidderNumPaddingAwareTrait;
    use CurrencyLoaderAwareTrait;
    use DbConnectionTrait;
    use EditorUserAwareTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use InvoiceAuctionReadRepositoryCreateTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;

    protected ?string $filterPrimarySort = null;
    protected ?string $filterSecondarySort = null;
    protected ?string $filterCurrencyFilter = null;
    protected string $filterSearchKey = '';
    protected string $filterInvoiceNo = '';
    protected string $filterBidderUserId = '';
    protected string $filterAuctionFilter = '';
    protected string $filterBidderNo = '';
    protected string $filterBidderKey = '';
    protected int $filterCustomerNo = 0;
    protected ?int $filterStatus = null;
    protected bool $isMultipleSale = false;
    protected string $sortOrderDefaultIndex = StackedTaxInvoiceListConstants::ORD_DEFAULT;

    /** @var string[][] */
    protected array $orderFieldsMapping = [
        StackedTaxInvoiceListConstants::ORD_INVOICE_NO => [
            'asc' => 'i.invoice_no ASC',
            'desc' => 'i.invoice_no DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_ISSUED_DATE => [
            'asc' => "DATE_FORMAT(IFNULL(i.invoice_date, i.created_on), '%Y-%m-%d %H:%i') ASC",
            'desc' => "DATE_FORMAT(IFNULL(i.invoice_date, i.created_on), '%Y-%m-%d %H:%i') DESC",
        ],
        StackedTaxInvoiceListConstants::ORD_SENT_DATE => [
            'asc' => 'DATE_FORMAT(i.first_sent_on, \'%Y-%m-%d %H:%i\') ASC',
            'desc' => 'DATE_FORMAT(i.first_sent_on, \'%Y-%m-%d %H:%i\') DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_SALE => [
            'asc' => 'ia.name ASC',
            'desc' => 'ia.name DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_USER => [
            'asc' => 'iu.username ASC',
            'desc' => 'iu.username DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_NAME => [
            'asc' => 'iu.last_name ASC, iu.first_name ASC',
            'desc' => 'iu.last_name DESC, iu.first_name DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_STATE => [
            'asc' => 'iub.state ASC',
            'desc' => 'iub.state DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_ZIP => [
            'asc' => 'iub.zip ASC',
            'desc' => 'iub.zip DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_STATUS => [
            'asc' => 'i.invoice_status_id ASC',
            'desc' => 'i.invoice_status_id DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_HP => [
            'asc' => 'i.bid_total ASC',
            'desc' => 'i.bid_total DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_HP_TAX => [
            'asc' => 'i.hp_tax_total ASC',
            'desc' => 'i.hp_tax_total DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_HP_WITH_TAX => [
            'asc' => '(i.bid_total + i.hp_tax_total) ASC',
            'desc' => '(i.bid_total + i.hp_tax_total) DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_BP => [
            'asc' => 'i.buyers_premium ASC',
            'desc' => 'i.buyers_premium DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_BP_TAX => [
            'asc' => 'i.bp_tax_total ASC',
            'desc' => 'i.bp_tax_total DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_BP_WITH_TAX => [
            'asc' => '(i.buyers_premium + i.bp_tax_total) ASC',
            'desc' => '(i.buyers_premium + i.bp_tax_total) DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_COUNTRY_TAX => [
            'asc' => '(i.hp_country_tax_total + i.bp_country_tax_total + i.services_country_tax_total) ASC',
            'desc' => '(i.hp_country_tax_total + i.bp_country_tax_total + i.services_country_tax_total) DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_STATE_TAX => [
            'asc' => '(i.hp_state_tax_total + i.bp_state_tax_total) ASC',
            'desc' => '(i.hp_state_tax_total + i.bp_state_tax_total) DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_COUNTY_TAX => [
            'asc' => '(i.hp_county_tax_total + i.bp_county_tax_total) ASC',
            'desc' => '(i.hp_county_tax_total + i.bp_county_tax_total) DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_CITY_TAX => [
            'asc' => '(i.hp_city_tax_total + i.bp_city_tax_total) ASC',
            'desc' => '(i.hp_city_tax_total + i.bp_city_tax_total) DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_SERVICES => [
            'asc' => 'i.extra_charges ASC',
            'desc' => 'i.extra_charges DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_SERVICES_TAX => [
            'asc' => 'i.services_tax_total ASC',
            'desc' => 'i.services_tax_total DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_SERVICES_WITH_TAX => [
            'asc' => '(i.extra_charges + i.services_tax_total) ASC',
            'desc' => '(i.extra_charges + i.services_tax_total) DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_PAYMENT_TOTAL => [
            'asc' => 'i.total_payment ASC',
            'desc' => 'i.total_payment DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_BALANCE_DUE => [
            'asc' => 'balance_due ASC',
            'desc' => 'balance_due DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_INVOICE_TOTAL => [
            'asc' => 'invoice_total ASC',
            'desc' => 'invoice_total DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_CURRENCY => [
            'asc' => 'curr_sign ASC',
            'desc' => 'curr_sign DESC',
        ],
        StackedTaxInvoiceListConstants::ORD_DEFAULT => [
            'asc' => 'i.id ASC',
            'desc' => 'i.id DESC',
        ],
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string|null $primarySort null means that there primary sort is not selected
     * @return static
     */
    public function filterPrimarySort(?string $primarySort): static
    {
        $this->filterPrimarySort = $primarySort;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterPrimarySort(): ?string
    {
        return $this->filterPrimarySort;
    }

    /**
     * @param string|null $secondarySort null means that there secondary sort is not selected
     * @return static
     */
    public function filterSecondarySort(?string $secondarySort): static
    {
        $this->filterSecondarySort = $secondarySort;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterSecondarySort(): ?string
    {
        return $this->filterSecondarySort;
    }

    /**
     * @param string $searchKey
     * @return static
     */
    public function filterSearchKey(string $searchKey): static
    {
        $this->filterSearchKey = $searchKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilterSearchKey(): string
    {
        return $this->filterSearchKey;
    }

    /**
     * @param string $invoiceNo
     * @return static
     */
    public function filterInvoiceNo(string $invoiceNo): static
    {
        $this->filterInvoiceNo = $invoiceNo;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilterInvoiceNo(): string
    {
        return $this->filterInvoiceNo;
    }

    /**
     * @param string $bidderUserId
     * @return static
     */
    public function filterBidderUserId(string $bidderUserId): static
    {
        $this->filterBidderUserId = $bidderUserId;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilterBidderUserId(): string
    {
        return $this->filterBidderUserId;
    }

    /**
     * @param string|null $currencyFilter null means that currency is not selected
     * @return static
     */
    public function filterCurrencyFilter(?string $currencyFilter): static
    {
        $this->filterCurrencyFilter = Cast::toString($currencyFilter);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterCurrencyFilter(): ?string
    {
        return $this->filterCurrencyFilter;
    }

    /**
     * @param string $auctionFilter
     * @return static
     */
    public function filterAuctionFilter(string $auctionFilter): static
    {
        $this->filterAuctionFilter = $auctionFilter;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilterAuctionFilter(): string
    {
        return $this->filterAuctionFilter;
    }

    /**
     * @param int|null $status null means that there is passed status='all'
     * @return static
     */
    public function filterStatus(?int $status): static
    {
        $this->filterStatus = (int)$status;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFilterStatus(): ?int
    {
        return $this->filterStatus;
    }

    /**
     * @param string $bidderNo
     * @return static
     */
    public function filterBidderNo(string $bidderNo): static
    {
        $this->filterBidderNo = $bidderNo;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilterBidderNo(): string
    {
        return $this->filterBidderNo;
    }

    /**
     * @param int $customerNo
     * @return static
     */
    public function filterCustomerNo(int $customerNo): static
    {
        $this->filterCustomerNo = $customerNo;
        return $this;
    }

    /**
     * @return int
     */
    public function getFilterCustomerNo(): int
    {
        return $this->filterCustomerNo;
    }

    /**
     * @param string $bidderKey
     * @return static
     */
    public function filterBidderKey(string $bidderKey): static
    {
        $this->filterBidderKey = $bidderKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilterBidderKey(): string
    {
        return $this->filterBidderKey;
    }

    /**
     * @param bool $isMultipleSale
     * @return static
     */
    public function enableMultipleSale(bool $isMultipleSale): static
    {
        $this->isMultipleSale = $isMultipleSale;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMultipleSale(): bool
    {
        return $this->isMultipleSale;
    }

    /**
     * @return int - return value of Invoices count
     */
    public function count(): int
    {
        $query = $this->buildCountQuery();
        $this->query($query);
        $row = $this->fetchAssoc();
        return $row['invoice_count'];
    }

    /**
     * @return StackedTaxInvoiceListFormDto[] - return values for Invoices
     */
    public function load(): array
    {
        $resultQuery = $this->buildResultQuery();
        $dbResult = $this->query($resultQuery);
        $dtos = [];
        while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $dtos[] = StackedTaxInvoiceListFormDto::new()->fromDbRow($row);
        }
        $dtos = $this->fillWithAuctionData($dtos);
        return $dtos;
    }

    /**
     * @param StackedTaxInvoiceListFormDto[] $dtos
     * @return StackedTaxInvoiceListFormDto[]
     */
    protected function fillWithAuctionData(array $dtos): array
    {
        $invoiceIds = ArrayHelper::toArrayByProperty($dtos, 'invoiceId');
        $rows = $this->createInvoiceAuctionReadRepository()
            ->filterInvoiceId($invoiceIds)
            ->select([
                'invoice_id',
                'auction_id',
                'sale_no',
                'name'
            ])
            ->loadRows();
        foreach ($dtos as $dto) {
            foreach ($rows as $row) {
                if ((int)$row['invoice_id'] === $dto->invoiceId) {
                    $dto->auctionRows[] = $row;
                }
            }
        }
        return $dtos;
    }

    /**
     * Build query for count
     * @return string
     */
    protected function buildCountQuery(): string
    {
        $count = "SELECT COUNT(1) AS invoice_count FROM ( SELECT i.id %s ) AS cnt";
        $query = sprintf($count, $this->buildFromClause() . $this->buildWhereClause() . $this->buildGroupClause());
        return $query;
    }

    /**
     * Build query for result
     * @return string
     */
    protected function buildResultQuery(): string
    {
        $exRate = (float)$this->getCurrencyLoader()->loadExRateBySign();
        $primaryCurrencySign = $this->getCurrencyLoader()->findPrimaryCurrencySign();

        // @formatter:off

        // Invoice info
        $select = "SELECT"
            . " i.id AS id,"
            . " i.invoice_status_id AS invoice_status_id,"
            . " i.invoice_no AS invoice_no,"
            . " i.invoice_date AS invoice_date,"
            . " i.created_on AS created_on,"
            . " i.first_sent_on,";

        // Invoice amounts
        $select .=
            " i.bid_total,"
            . " i.buyers_premium,"
            . " i.hp_tax_total,"
            . " i.bp_tax_total,"
            . " (i.hp_country_tax_total + i.bp_country_tax_total + i.services_country_tax_total) AS country_tax_total,"
            . " (i.hp_state_tax_total + i.bp_state_tax_total + i.services_state_tax_total) AS state_tax_total,"
            . " (i.hp_county_tax_total + i.bp_county_tax_total + i.services_county_tax_total) AS county_tax_total,"
            . " (i.hp_city_tax_total + i.bp_city_tax_total + i.services_city_tax_total) AS city_tax_total,"
            . " i.extra_charges,"
            . " i.services_tax_total,"
            . " i.total_payment,"
            . " @invoice_total := (i.bid_total + i.hp_tax_total + i.buyers_premium + i.bp_tax_total + i.extra_charges + i.services_tax_total) AS invoice_total,"
            . " (@invoice_total - i.total_payment) AS balance_due,"
            . " IF(i.currency_sign <> '', i.currency_sign, " . $this->escape($primaryCurrencySign) . ") AS curr_sign,"
            . " IF(i.currency_sign = " . $this->escape($primaryCurrencySign)
                . " OR i.currency_sign = '', 1,"
                . " IFNULL(i.ex_rate, " . $this->escape($exRate) . ")) AS curr_ex_rate,";

        $select .= " (SELECT GROUP_CONCAT(DISTINCT iauc.bidder_num) FROM invoice_auction iauc WHERE iauc.invoice_id = i.id) AS bidder_num_list,";

        // Winning user info
        $select .=
            " iu.username AS username,"
            . " iub.state AS state,"
            . " iub.zip AS zip,"
            . " ANY_VALUE(iu.first_name) AS first_name," // SAM-10501
            . " ANY_VALUE(iu.last_name) AS last_name,"
            . " ANY_VALUE(iu.phone) AS iphone,"
            . " ANY_VALUE(iub.phone) AS bphone,"
            . " ANY_VALUE(ius.phone) AS sphone";

        // @formatter:on

        $resultQuery = $select
            . $this->buildFromClause()
            . $this->buildWhereClause()
            . $this->buildGroupClause()
            . $this->buildOrderClause()
            . $this->buildLimitClause();

        return $resultQuery;
    }

    /**
     * @return string
     */
    protected function buildFromClause(): string
    {
        $from = " FROM"
            . " invoice AS i"
            . " INNER JOIN account AS acc ON i.account_id = acc.id AND acc.active"
            . " INNER JOIN user AS u ON u.id = i.bidder_id"
            . " LEFT JOIN invoice_user AS iu ON iu.invoice_id = i.id"
            . " LEFT JOIN invoice_user_billing AS iub ON iub.invoice_id = i.id"
            . " LEFT JOIN invoice_user_shipping AS ius ON ius.invoice_id = i.id"
            . " LEFT JOIN invoice_item AS ii ON ii.invoice_id = i.id AND ii.active"
            . " LEFT JOIN invoice_auction AS ia ON ii.auction_id = ia.auction_id AND ia.invoice_id = i.id"
            . " LEFT JOIN setting_system AS setsys ON setsys.account_id = i.account_id";

        if (!$this->isMultipleSale() || $this->getFilterAuction()) {
            $from .= " LEFT JOIN auction AS a ON a.id = ii.auction_id AND ii.auction_id > 0";
        }

        if ($this->getFilterSearchKey() !== '') {
            $invoiceEntityType = Constants\Search::ENTITY_INVOICE;
            $from .= " INNER JOIN search_index_fulltext AS sif ON sif.entity_type={$invoiceEntityType} AND sif.entity_id = i.id";
        }

        return $from;
    }

    /**
     * @return string
     */
    protected function buildWhereClause(): string
    {
        $primaryCurrencySign = $this->getCurrencyLoader()->findPrimaryCurrencySign();

        $where = " WHERE";
        $where .= " i.tax_designation = " . Constants\Invoice::TDS_STACKED_TAX;

        if ($this->isAccountFiltering()) {
            if ($this->getFilterAccountId()) {
                $where .= " AND i.account_id = " . $this->escape($this->getFilterAccountId());
            } else {
                $where .= " AND i.account_id > 0";
            }
        } else { //In case sam portal has been disabled again
            $where .= " AND i.account_id = " . $this->escape($this->getSystemAccountId());
        }

        if ($this->getFilterSearchKey() !== '') {
            $key = $this->escape('%' . $this->getFilterSearchKey() . '%');
            $where .= " AND (0 OR sif.full_content LIKE {$key}";
            $keys = preg_split("/[\s,]+/", $this->getFilterSearchKey());
            if (count($keys) > 1) {
                foreach ($keys as $key) {
                    if (ctype_alnum($key) === false) {
                        continue;
                    }
                    $key = $this->escape('%' . $key . '%');
                    $where .= " OR sif.full_content LIKE {$key}";
                }
            }
            $where .= ')';
        }

        if ($this->getFilterInvoiceNo() !== '') {
            $invoice = $this->escape('%' . $this->getFilterInvoiceNo() . '%');
            $where .= " AND i.invoice_no LIKE {$invoice}";
        }

        if ($this->getFilterBidderUserId() !== '') {
            $bidder = $this->escape($this->getFilterBidderUserId());
            $where .= " AND u.id = " . $bidder;
        } else {
            $searchTerm = $this->getFilterBidderKey();
            if ($searchTerm) {
                $searchTerms = explode(' ', $searchTerm);
                foreach ($searchTerms as $term) {
                    $qi = '%' . $term . '%';
                    $qi = $this->escape($qi);
                    $where .= " AND (iu.customer_no like " . $qi
                        . " OR iu.username like " . $qi
                        . " OR iu.email like " . $qi
                        . " OR iu.first_name like " . $qi
                        . " OR iu.last_name like " . $qi
                        . " OR iu.email like " . $qi . ")";
                }
            }
        }

        if ($this->getFilterCurrencyFilter()) {
            $currencyFilter = $this->escape($this->getFilterCurrencyFilter());
            $where .= " AND IF(i.currency_sign <> '', i.currency_sign, '{$primaryCurrencySign}') LIKE {$currencyFilter}";
        }

        if ($this->getFilterAuction()) {
            $where .= " AND a.id =" . $this->escape($this->getFilterAuctionId());
        }

        $auctionId = $this->getFilterAuctionFilter();
        if ($auctionId > 0) {
            $auctionStatusList = implode(',', Constants\Auction::$availableAuctionStatuses);
            $where .= " AND (SELECT COUNT(1) FROM invoice_item AS ii"
                . " INNER JOIN auction AS a ON a.id = ii.auction_id AND a.auction_status_id IN (" . $auctionStatusList . ")"
                . " WHERE ii.invoice_id = i.id"
                . " AND ii.active = true"
                . " AND a.id = " . $this->escape($auctionId) . ")";
        }

        $invoiceStatusPureChecker = InvoiceStatusPureChecker::new();
        if ($invoiceStatusPureChecker->isAmongAllStatuses($this->getFilterStatus())) {
            $where .= " AND i.invoice_status_id = {$this->getFilterStatus()}";
        } else {
            $invoiceStatusList = implode(',', Constants\Invoice::$availableInvoiceStatuses);
            $where .= " AND i.invoice_status_id IN (" . $invoiceStatusList . ")";
        }

        if ($this->getFilterBidderNo() !== '') {
            $bidderNumPad = $this->getBidderNumberPadding()->add($this->getFilterBidderNo());
            $bidderNumPadEscaped = $this->escape($bidderNumPad);
            $auctionCond = $auctionId ? " AND iauc.auction_id = " . $this->escape($auctionId) : '';
            // @formatter:off
            $where .= " AND ("
                . " SELECT COUNT(1) FROM invoice_auction iauc"
                    . " WHERE iauc.invoice_id = i.id"
                    . " AND iauc.bidder_num = " . $bidderNumPadEscaped
                    . $auctionCond
                . " )";
            // @formatter:on
        }

        if ($this->getFilterCustomerNo() > 0) {
            $where .= " AND iu.customer_no = " . $this->getFilterCustomerNo();
        }

        return $where;
    }

    /**
     * @return string
     */
    protected function buildGroupClause(): string
    {
        $group = " GROUP BY i.id";
        return $group;
    }

    /**
     * @return string
     */
    protected function buildOrderClause(): string
    {
        $sort = '';
        $primarySort = $this->getFilterPrimarySort();
        $secondarySort = $this->getFilterSecondarySort();
        $ascOrDesc = $this->isAscendingOrder() ? 'asc' : 'desc';

        if (
            $this->getSortColumn()
            && $this->getSortColumn() !== $this->sortOrderDefaultIndex
        ) {
            $sort .= $this->orderFieldsMapping[$this->getSortColumn()][$ascOrDesc];
        } elseif (
            $primarySort
            || $secondarySort
        ) {
            //primary sort
            if ($primarySort) {
                $sort .= $this->orderFieldsMapping[$primarySort]['asc'];
            }

            if (
                $primarySort
                && $secondarySort
            ) {
                $sort .= ", ";
            }

            //secondary sort
            if ($secondarySort) {
                $sort .= $this->orderFieldsMapping[$secondarySort]['asc'];
            }
        } else {
            $sort = $this->orderFieldsMapping[$this->sortOrderDefaultIndex][$ascOrDesc];
        }

        $order = sprintf(" ORDER BY %s", $sort);

        return $order;
    }

    /**
     * @return string
     */
    protected function buildLimitClause(): string
    {
        $limit = $this->getLimit();
        if ($limit === null) {
            return '';
        }
        $query = $limit;

        $offset = $this->getOffset();
        if ($offset) {
            $query = "{$offset}, {$limit}";
        }
        return sprintf(' LIMIT %s', $query);
    }

    /**
     * @return bool
     */
    protected function isAccountFiltering(): bool
    {
        return $this->createApplicationAccessChecker()->isCrossDomainAdminOnMainAccountForMultipleTenant(
            $this->getEditorUserId(),
            $this->getSystemAccountId(),
            true
        );
    }
}
