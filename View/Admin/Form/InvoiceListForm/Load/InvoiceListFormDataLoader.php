<?php
/**
 * Invoice List Form Data Loader
 *
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

namespace Sam\View\Admin\Form\InvoiceListForm\Load;

use QMySqli5DatabaseResult;
use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Constants;
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
use Sam\View\Admin\Form\InvoiceListForm\InvoiceListConstants;

/**
 * Class InvoiceListFormDataLoader
 */
class InvoiceListFormDataLoader extends CustomizableClass
{
    use ApplicationAccessCheckerCreateTrait;
    use BidderNumPaddingAwareTrait;
    use CurrencyLoaderAwareTrait;
    use DbConnectionTrait;
    use EditorUserAwareTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;
    use LimitInfoAwareTrait;

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
    protected string $sortOrderDefaultIndex = InvoiceListConstants::ORD_DEFAULT;

    /** @var string[][] */
    protected array $orderFieldsMapping = [
        InvoiceListConstants::ORD_INVOICE_NO => [
            'asc' => 'i.invoice_no ASC',
            'desc' => 'i.invoice_no DESC',
        ],
        InvoiceListConstants::ORD_ISSUED => [
            'asc' => "DATE_FORMAT(IFNULL(i.invoice_date, i.created_on), '%Y-%m-%d %H:%i') ASC",
            'desc' => "DATE_FORMAT(IFNULL(i.invoice_date, i.created_on), '%Y-%m-%d %H:%i') DESC",
        ],
        InvoiceListConstants::ORD_SALE => [
            'asc' => 'sale_name ASC',
            'desc' => 'sale_name DESC',
        ],
        InvoiceListConstants::ORD_BIDDER_NO => [
            'asc' => 'bidder_number ASC',
            'desc' => 'bidder_number DESC',
        ],
        InvoiceListConstants::ORD_USER => [
            'asc' => 'u.username ASC',
            'desc' => 'u.username DESC',
        ],
        InvoiceListConstants::ORD_STATE => [
            'asc' => 'iub.state ASC',
            'desc' => 'iub.state DESC',
        ],
        InvoiceListConstants::ORD_ZIP => [
            'asc' => 'iub.zip ASC',
            'desc' => 'iub.zip DESC',
        ],
        InvoiceListConstants::ORD_STATUS => [
            'asc' => 'invoice_status_id ASC',
            'desc' => 'invoice_status_id DESC',
        ],
        InvoiceListConstants::ORD_NAME => [
            'asc' => 'ui.last_name ASC, ui.first_name ASC',
            'desc' => 'ui.last_name DESC, ui.first_name DESC',
        ],
        InvoiceListConstants::ORD_SENT => [
            'asc' => 'DATE_FORMAT(sent, \'%Y-%m-%d %H:%i\') ASC',
            'desc' => 'DATE_FORMAT(sent, \'%Y-%m-%d %H:%i\') DESC',
        ],
        InvoiceListConstants::ORD_BID_TOTAL => [
            'asc' => 'bid_total ASC',
            'desc' => 'bid_total DESC',
        ],
        InvoiceListConstants::ORD_PREMIUM => [
            'asc' => 'premium ASC',
            'desc' => 'premium DESC',
        ],
        InvoiceListConstants::ORD_TAX => [
            'asc' => 'tax ASC',
            'desc' => 'tax DESC',
        ],
        InvoiceListConstants::ORD_FEES => [
            'asc' => 'fees ASC',
            'desc' => 'fees DESC',
        ],
        InvoiceListConstants::ORD_PAYMENT => [
            'asc' => 'total_payment ASC',
            'desc' => 'total_payment DESC',
        ],
        InvoiceListConstants::ORD_BALANCE => [
            'asc' => 'balance ASC',
            'desc' => 'balance DESC',
        ],
        InvoiceListConstants::ORD_TOTAL => [
            'asc' => 'total ASC',
            'desc' => 'total DESC',
        ],
        InvoiceListConstants::ORD_CURRENCY => [
            'asc' => 'curr_sign ASC',
            'desc' => 'curr_sign DESC',
        ],
        InvoiceListConstants::ORD_DEFAULT => [
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
     * @return array - return values for Invoices
     */
    public function load(): array
    {
        $resultQuery = $this->buildResultQuery();
        $dbResult = $this->query($resultQuery);
        $dtos = [];
        while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $dtos[] = InvoiceListFormDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * Build query for count
     * @return string
     */
    public function buildCountQuery(): string
    {
        $count = "SELECT COUNT(1) AS invoice_count FROM ( SELECT i.id %s ) AS cnt";
        $query = sprintf($count, $this->buildFromClause() . $this->buildWhereClause() . $this->buildGroupClause());
        return $query;
    }

    /**
     * Build query for result
     * @return string
     */
    public function buildResultQuery(): string
    {
        $exRate = (float)$this->getCurrencyLoader()->loadExRateBySign();
        $primaryCurrencySign = $this->getCurrencyLoader()->findPrimaryCurrencySign();
        $taHpBp = Constants\User::TAX_HP_BP;
        $taHp = Constants\User::TAX_HP;
        $taBp = Constants\User::TAX_BP;

        // @formatter:off
        $select = "SELECT"
            . " i.id AS id,"
            . " i.invoice_no AS invoice_no,"
            . " i.invoice_date AS invoice_date,"
            . " i.created_on AS created_on,"
            . " i.invoice_status_id AS invoice_status_id,"
            . " u.username AS username,"
            . " ANY_VALUE(ui.first_name) AS first_name," // SAM-10501
            . " ANY_VALUE(ui.last_name) AS last_name,"
            . " ANY_VALUE(ui.phone) AS iphone,"
            . " ANY_VALUE(iub.phone) AS bphone,"
            . " ANY_VALUE(ius.phone) AS sphone,"
            . " @bid_total := ROUND(i.bid_total, 2) AS bid_total,"
            . " @premium := ROUND(i.buyers_premium, 2) AS premium,"
            . " @tax := ROUND(i.tax, 2) AS tax,"
            . " @shipping_fees := IFNULL(ROUND(i.shipping_fees, 2), 0) AS shipping_fees,"
            . " @extra_charges := IFNULL(ROUND(i.extra_charges, 2), 0) AS extra_charges,"
            . " @total_payment := IFNULL(ROUND(i.total_payment, 2), 0) AS total_payment,"
            . " i.first_sent_on AS sent,"
            . " @cash_discount := IF(i.cash_discount = true,"
                . " (@bid_total + @premium) *"
                . " (SELECT IFNULL(cash_discount,0) FROM setting_invoice WHERE account_id = i.account_id) / 100, 0) AS cash_discount,"
            . " @total := CAST((@bid_total + @premium + @tax + @shipping_fees + @extra_charges)"
                . " - @cash_discount AS DECIMAL(12,2)) AS total,"
            . " @balance := CAST((@total  - @total_payment) AS DECIMAL(12,2)) AS balance,"
            . " @none_taxable := SUM(IF((ii.sales_tax = 0"
                    . " OR ii.sales_tax IS NULL"
                    . " OR ii.tax_application = {$taBp})"
                    . " AND (ius.country = '' OR setsys.default_country = ius.country),"
                . " IFNULL(ii.hammer_price,0), 0 )) AS none_taxable ,"
            . " @export := SUM(IF(ius.country != ''"
                . " AND setsys.default_country != ius.country,"
                . " IFNULL(ii.hammer_price, 0), 0 )) AS export ,"
            . " @taxable := SUM(IF((ii.tax_application = {$taHp}"
                    . " OR ii.tax_application = {$taHpBp})"
                    . " AND ii.sales_tax != 0"
                    . " AND (ius.country = '' OR setsys.default_country = ius.country),"
                . " IFNULL(ii.hammer_price,0),0 )) AS taxable,"
            . " IF(i.currency_sign <> '', i.currency_sign, " . $this->escape($primaryCurrencySign) . ") AS curr_sign,"
            . " IF(i.currency_sign = " . $this->escape($primaryCurrencySign)
                . " OR i.currency_sign = '', 1,"
                . " IFNULL(i.ex_rate, " . $this->escape($exRate) . ")) AS curr_ex_rate,";

        if (!$this->isMultipleSale()) {
            $select .= " IFNULL(i.bidder_number, '---') as `bidder_number`,";
        }

        if (!$this->isMultipleSale()) {
            $select .= " a.test_auction,"
                . " a.name AS sale_name,"
                . " a.sale_num,"
                . " a.sale_num_ext,";
        }

        $select .= " iub.state AS state,"
            . " iub.zip AS zip";
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
            . " LEFT JOIN user_info AS ui ON ui.user_id = i.bidder_id"
            . " LEFT JOIN invoice_user_billing AS iub ON iub.invoice_id = i.id"
            . " LEFT JOIN invoice_user_shipping AS ius ON ius.invoice_id = i.id"
            . " LEFT JOIN invoice_item AS ii ON ii.invoice_id = i.id AND ii.active"
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
        $where .= " i.tax_designation = " . Constants\Invoice::TDS_LEGACY;

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
                    $where .= " AND (u.customer_no like " . $qi
                        . " OR u.username like " . $qi
                        . " OR u.email like " . $qi
                        . " OR ui.first_name like " . $qi
                        . " OR ui.last_name like " . $qi
                        . " OR u.email like " . $qi . ")";
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
            $auctionCond = $auctionId ? " AND aub.auction_id = " . $this->escape($auctionId) : '';
            // @formatter:off
            $where .= " AND ("
                . " i.bidder_number = " . $bidderNumPadEscaped
                . " OR ("
                    . " SELECT COUNT(1) FROM invoice_item AS ii"
                    . " INNER JOIN auction_bidder AS aub ON aub.auction_id = ii.auction_id"
                    . " WHERE ii.invoice_id = i.id"
                        . " AND ii.winning_bidder_id = aub.user_id"
                        . " AND aub.bidder_num = " . $bidderNumPadEscaped
                        . $auctionCond
                . " )"
                . ")";
            // @formatter:on
        }

        if ($this->getFilterCustomerNo() > 0) {
            $where .= " AND u.customer_no = " . $this->getFilterCustomerNo();
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
    public function isAccountFiltering(): bool
    {
        return $this->createApplicationAccessChecker()->isCrossDomainAdminOnMainAccountForMultipleTenant(
            $this->getEditorUserId(),
            $this->getSystemAccountId(),
            true
        );
    }
}
