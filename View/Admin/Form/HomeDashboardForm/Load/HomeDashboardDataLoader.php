<?php
/**
 * Home Dashboard Data Loader
 *
 * SAM-5599: Refactor data loader for Home Dashboard at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 20, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\HomeDashboardForm\Load;

use DateTime;
use QMySqli5DatabaseResult;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterCurrencyAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;

/**
 * Class HomeDashboardDataLoader
 */
class HomeDashboardDataLoader extends CustomizableClass
{
    use CurrencyLoaderAwareTrait;
    use DbConnectionTrait;
    use EditorUserAwareTrait;
    use FilterAccountAwareTrait;
    use FilterCurrencyAwareTrait;
    use LimitInfoAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $isReadOnlyDb
     * @return HomeDashboardAuctionDto|null - return values for active auctions panel
     */
    public function loadActiveAuctionValues(bool $isReadOnlyDb = false): ?HomeDashboardAuctionDto
    {
        $this->enableReadOnlyDb($isReadOnlyDb);
        $whereConditions = '';
        $filterAccountId = $this->getFilterAccountId();
        if (is_array($filterAccountId)) {
            $filterAccountId = reset($filterAccountId);
        }
        if ($filterAccountId > 0) {
            $whereConditions = " AND a.account_id = " . $this->escape($filterAccountId);
        }
        $whereConditions .= " AND a.currency = " . $this->escape($this->getFilterCurrencyId());

        if (!$this->getEditorUserAdminPrivilegeChecker()->hasSubPrivilegeForManageAllAuctions()) {
            $whereConditions .= " AND a.created_by = " . $this->escape($this->getEditorUserId());
        }

        $openAuctionStatusList = implode(',', Constants\Auction::$openAuctionStatuses);
        $timed = Constants\Auction::TIMED;
        $live = Constants\Auction::LIVE;
        $hybrid = Constants\Auction::HYBRID;
        $asStarted = Constants\Auction::AS_STARTED;
        $sql = <<<SQL
SELECT
    ac.*,
    a.id AS id,
    a.sale_num AS sale_num,
    a.sale_num_ext AS sale_num_ext,
    a.account_id AS account_id,
    a.name AS name,
    a.auction_type AS auction_type,
    ac.bids AS max_bid_count,
    a.event_type AS event_type,
    a.auction_status_id AS auction_status_id,
    a.end_date AS end_date,
    a.start_closing_date AS start_closing_date,
    CASE WHEN auction_type = '{$timed}'
        THEN a.end_date
        ELSE a.start_closing_date
    END AS 'auction_end_date',
    atz.location AS timezone_location,
    IF(a.auction_type IN ('{$live}', '{$hybrid}')
        AND a.auction_status_id = {$asStarted} ,
        0, IFNULL(a.end_date, 1)) AS order_by_date_primary,
    IF(a.auction_type IN ('{$live}', '{$hybrid}'),
        a.start_closing_date, a.end_date) AS order_by_date_secondary
FROM auction_cache AS ac
INNER JOIN auction AS a ON a.id = ac.auction_id
INNER JOIN `account` acc ON acc.id = a.account_id AND acc.active
LEFT JOIN timezone atz ON atz.id = a.timezone_id
WHERE
    a.auction_status_id IN ({$openAuctionStatusList})
    AND ac.total_lots > 0
    {$whereConditions}
ORDER BY order_by_date_primary, order_by_date_secondary DESC
{$this->buildLimitClause()}
SQL;

        $dbResult = $this->query($sql);
        $dto = null;
        while ($row = $dbResult->fetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $auctionSettlement = $this->getAuctionSettlement($row['id']);
            $row['total_commission'] = $auctionSettlement['total_commission'];
            $row['total_commission_settled'] = $auctionSettlement['total_commission_settled'];
            $row['total_settlement_fee'] = $auctionSettlement['total_settlement_fee'];
            $row['total_settlement_fee_settled'] = $auctionSettlement['total_settlement_fee_settled'];
            $dto = HomeDashboardAuctionDto::new()->fromDbRow($row);
        }
        return $dto;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return HomeDashboardAuctionDto|null - values for closed auctions panel
     */
    public function loadClosedAuctionValues(bool $isReadOnlyDb = false): ?HomeDashboardAuctionDto
    {
        $this->enableReadOnlyDb($isReadOnlyDb);
        $whereConditions = '';
        $filterAccountId = $this->getFilterAccountId();
        if (is_array($filterAccountId)) {
            $filterAccountId = reset($filterAccountId);
        }
        if ($filterAccountId > 0) {
            $whereConditions = " AND a.account_id = " . $this->escape($filterAccountId);
        }
        $whereConditions .= " AND a.currency = " . $this->escape($this->getFilterCurrencyId());

        if (!$this->getEditorUserAdminPrivilegeChecker()->hasSubPrivilegeForManageAllAuctions()) {
            $whereConditions .= " AND a.created_by = " . $this->escape($this->getEditorUserId());
        }

        $timed = Constants\Auction::TIMED;
        $asClosed = Constants\Auction::AS_CLOSED;
        $sql = <<<SQL
SELECT ac.*,
    a.id AS id,
    a.sale_num AS sale_num,
    a.sale_num_ext AS sale_num_ext,
    a.account_id AS account_id,
    ac.bids AS max_bid_count, 
    a.name AS name,
    a.auction_type AS auction_type,
    CASE
        WHEN auction_type = '{$timed}' THEN a.end_date
        ELSE a.start_closing_date
    END AS 'end_date',
    atz.location AS timezone_location
FROM auction_cache ac
INNER JOIN auction a ON a.id = ac.auction_id
INNER JOIN account acc ON acc.id = a.account_id AND acc.active
LEFT JOIN timezone atz ON atz.id = a.timezone_id
WHERE
    a.auction_status_id = '{$asClosed}'
    AND ac.total_lots > 0
    {$whereConditions}
ORDER BY IF (auction_type = '{$timed}', a.end_date, a.start_closing_date) DESC
{$this->buildLimitClause()}
SQL;
        $dbResult = $this->query($sql);
        $dto = null;
        while ($row = $dbResult->fetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $auctionSettlement = $this->getAuctionSettlement($row['id']);
            $row['total_commission'] = $auctionSettlement['total_commission'];
            $row['total_commission_settled'] = $auctionSettlement['total_commission_settled'];
            $row['total_settlement_fee'] = $auctionSettlement['total_settlement_fee'];
            $row['total_settlement_fee_settled'] = $auctionSettlement['total_settlement_fee_settled'];
            $dto = HomeDashboardAuctionDto::new()->fromDbRow($row);
        }
        return $dto;
    }

    /**
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return array - values for auctions commission
     */
    protected function getAuctionSettlement(int $auctionId, bool $isReadOnlyDb = false): array
    {
        $this->enableReadOnlyDb($isReadOnlyDb);
        $availableSettlementStatusList = implode(',', Constants\Settlement::$availableSettlementStatuses);
        $sql = <<<SQL
SELECT
    @total_commission := SUM(IFNULL(si.commission,0)) AS total_commission,
    @total_settlement_fee := SUM(IFNULL(si.fee,0)) AS total_settlement_fee
FROM settlement s
INNER JOIN account acc ON acc.id = s.account_id AND acc.active
INNER JOIN settlement_item si ON si.settlement_id = s.id AND si.active
WHERE
    s.settlement_status_id IN ({$availableSettlementStatusList})
    AND si.auction_id = {$this->escape($auctionId)}
SQL;
        $this->query($sql);
        $returnRows = $this->fetchAssoc();
        $isPaid = Constants\Invoice::IS_PAID;
        $isShipped = Constants\Invoice::IS_SHIPPED;
        $sql = <<<SQL
SELECT
    @total_commission_settled := SUM(IFNULL(si.commission,0)) AS total_commission_settled,
    @total_settlement_fee_settled := SUM(IFNULL(si.fee,0)) AS total_settlement_fee_settled
FROM settlement s
INNER JOIN account acc ON acc.id = s.account_id AND acc.active
INNER JOIN settlement_item si ON si.settlement_id = s.id AND si.active
INNER JOIN invoice_item ii ON ii.lot_item_id = si.lot_item_id AND ii.auction_id = si.auction_id AND ii.release = false
INNER JOIN invoice i ON i.id = ii.invoice_id AND i.invoice_status_id IN ({$isPaid}, {$isShipped}) 
WHERE
    s.settlement_status_id IN ({$availableSettlementStatusList})
    AND si.auction_id = {$this->escape($auctionId)}
SQL;
        $this->query($sql);
        return array_merge($returnRows, $this->fetchAssoc());
    }

    /**
     * @param int|null $auctionId null means without filter by auction
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param bool $isReadOnlyDb
     * @return HomeDashboardInvoiceOverviewDto - values for invoice overview panel
     */
    public function loadInvoiceOverviewValues(
        ?int $auctionId,
        DateTime $startDate,
        DateTime $endDate,
        bool $isReadOnlyDb = false
    ): HomeDashboardInvoiceOverviewDto {
        $this->enableReadOnlyDb($isReadOnlyDb);
        $accountCond = '';
        $accountId = $this->getFilterAccountId();
        if (is_array($accountId)) {
            $accountId = reset($accountId);
        }
        if ($accountId > 0) {
            $accountCond = " AND i.account_id = " . $this->escape($accountId);
        }
        // filter by auction
        $auctionCond = '';
        if ($auctionId > 0) {
            $auctionCond = " AND a.id = " . $this->escape($auctionId);
        }
        // filter by currency
        $currencyCond = '';
        $currencyId = $this->getFilterCurrencyId();
        if ($currencyId > 0) {
            $currencyCond = " AND a.currency = " . $this->escape($currencyId);
        }

        $dateCond = " AND (i.created_on BETWEEN"
            . " " . $this->escape($startDate->format(Constants\Date::ISO))
            . " AND " . $this->escape($endDate->format(Constants\Date::ISO)) . ")";
        $isPaid = Constants\Invoice::IS_PAID;
        $isShipped = Constants\Invoice::IS_SHIPPED;
        $invoiceStatusList = implode(',', Constants\Invoice::$availableInvoiceStatuses);
        if ($auctionId > 0) {
            $taHpBp = Constants\User::TAX_HP_BP;
            $taHp = Constants\User::TAX_HP;
            $taBp = Constants\User::TAX_BP;
            $tdsLegacy = Constants\Invoice::TDS_LEGACY;
            $sql = <<<SQL
SELECT
    @total_hp := SUM(ii.hammer_price) AS total_hp,
    @total_bp := SUM(ii.buyers_premium) AS total_bp,
    @total_tax := SUM(
        IF (i.tax_designation = {$tdsLegacy},
            CASE ii.tax_application
                WHEN {$taHpBp} THEN (ii.hammer_price + ii.buyers_premium) * (ii.sales_tax / 100)
                WHEN {$taHp} THEN (ii.hammer_price) * (ii.sales_tax / 100)
                WHEN {$taBp} THEN (ii.buyers_premium) * (ii.sales_tax / 100)
                ELSE 0
            END,
            ii.hp_tax_amount + ii.bp_tax_amount
        )
    ) AS total_tax,
    @total_hp_collected := SUM(IF(i.invoice_status_id IN ({$isPaid}, {$isShipped}), ii.hammer_price, 0)) AS total_hp_collected,
    @total_bp_collected := SUM(IF(i.invoice_status_id IN ({$isPaid}, {$isShipped}), ii.buyers_premium, 0)) AS total_bp_collected,
    @total_tax_collected := SUM(
        IF(i.invoice_status_id IN ({$isPaid}, {$isShipped}),
            IF (i.tax_designation = {$tdsLegacy},
                CASE ii.tax_application
                    WHEN {$taHpBp} THEN (ii.hammer_price + ii.buyers_premium) * (ii.sales_tax / 100)
                    WHEN {$taHp} THEN (ii.hammer_price) * (ii.sales_tax / 100)
                    WHEN {$taBp} THEN (ii.buyers_premium) * (ii.sales_tax / 100)
                    ELSE 0
                END,
                ii.hp_tax_amount + ii.bp_tax_amount
            ),
            0
        )
    ) AS total_tax_collected
FROM invoice i
INNER JOIN account acc ON acc.id = i.account_id AND acc.active
INNER JOIN invoice_item ii ON i.id = ii.invoice_id AND ii.active
INNER JOIN auction a ON ii.auction_id = a.id
WHERE
    i.invoice_status_id IN ({$invoiceStatusList})
    {$dateCond}
    {$auctionCond}
    {$accountCond}
    {$currencyCond}
SQL;
            $this->query($sql);
            $results = $this->fetchAssoc();

            $sql = <<<SQL
SELECT
    @total_fees := SUM(IFNULL(i.shipping_fees, 0) + i.extra_charges) AS total_fees,
    @total_fees_collected := SUM(IF(i.invoice_status_id IN ({$isPaid}, {$isShipped}), IFNULL(i.shipping_fees, 0), 0)
        + IF(i.invoice_status_id IN ({$isPaid}, {$isShipped}), i.extra_charges, 0)) AS total_fees_collected
FROM invoice i
INNER JOIN account acc ON acc.id = i.account_id AND acc.active
WHERE i.id IN (
    SELECT i.id FROM invoice i
    INNER JOIN account acc ON acc.id = i.account_id AND acc.active
    INNER JOIN invoice_item ii ON i.id = ii.invoice_id AND ii.active
    INNER JOIN auction a ON ii.auction_id = a.id
    WHERE
        i.invoice_status_id IN ({$invoiceStatusList})
        {$dateCond}
        {$auctionCond}
        {$accountCond}
        {$currencyCond}
    GROUP BY i.id
)
SQL;
            $this->query($sql);
            $results = array_merge($this->fetchAssoc(), $results);
        } else {
            $currencyLoader = $this->getCurrencyLoader();
            $currency = $currencyLoader->load($currencyId);
            $currencySign = $currency->Sign ?? '';
            // TODO: change to condition using i.currency_id, when the column will be implemented
            $primaryCurrencySign = $currencyLoader->findPrimaryCurrencySign();
            $sql = <<<SQL
SELECT
    @total_hp := SUM(i.bid_total) AS total_hp,
    @total_bp := SUM(i.buyers_premium) AS total_bp,
    @total_tax := SUM(i.tax) AS total_tax,
    @total_fees := SUM(IFNULL(i.shipping_fees, 0) + i.extra_charges) AS total_fees,
    @total_hp_collected := SUM(IF(i.invoice_status_id IN ({$isPaid}, {$isShipped}), i.bid_total, 0)) AS total_hp_collected,
    @total_bp_collected := SUM(IF(i.invoice_status_id IN ({$isPaid}, {$isShipped}), i.buyers_premium, 0)) AS total_bp_collected,
    @total_tax_collected := SUM(IF(i.invoice_status_id IN ({$isPaid}, {$isShipped}), i.tax, 0)) AS total_tax_collected,
    @total_fees_collected := SUM(IF(i.invoice_status_id IN ({$isPaid}, {$isShipped}), IFNULL(i.shipping_fees, 0), 0)
        + IF(i.invoice_status_id IN ({$isPaid}, {$isShipped}), i.extra_charges, 0)) AS total_fees_collected
FROM invoice i
INNER JOIN account acc ON acc.id = i.account_id AND acc.active
WHERE
    i.invoice_status_id IN ({$invoiceStatusList})
    {$dateCond}
    {$accountCond}
    AND IF(i.currency_sign <> '', i.currency_sign, '{$primaryCurrencySign}')
        LIKE {$this->escape($currencySign)}
SQL;
            $this->query($sql);
            $results = $this->fetchAssoc();
        }

        // High bid total & High bid above reserve
        // TODO: currently we exclude reverse auctions
        $accountCond = '';
        if ($accountId > 0) {
            $accountCond = " AND a.account_id = " . $this->escape($accountId);
        }
        $availableLotStatusList = implode(',', Constants\Lot::$availableLotStatuses);
        $sql = <<<SQL
SELECT
    SUM(IF(li.hammer_price IS NOT NULL, li.hammer_price, alic.current_bid)) AS total_high_bid,
    SUM(IF(!a.reverse AND alic.current_bid >= IFNULL(li.reserve_price, 0), alic.current_bid, 0)) AS total_high_bid_above_reserve
FROM auction_lot_item_cache alic
INNER JOIN auction_lot_item ali ON ali.id = alic.auction_lot_item_id AND ali.lot_status_id IN ({$availableLotStatusList})
INNER JOIN auction a ON a.id = ali.auction_id AND a.auction_status_id IN ({$availableLotStatusList})
INNER JOIN account acc ON acc.id = a.account_id AND acc.active
INNER JOIN lot_item li ON li.id = ali.lot_item_id AND li.active
WHERE
    (alic.current_bid_placed BETWEEN {$this->escape($startDate->format(Constants\Date::ISO))}
        AND {$this->escape($endDate->format(Constants\Date::ISO))})
    {$accountCond}
    {$auctionCond}
    {$currencyCond}
SQL;
        $this->query($sql);
        $rows = $this->fetchAssoc();
        $dto = HomeDashboardInvoiceOverviewDto::new()->fromDbRow(array_merge($results, $rows));
        return $dto;
    }

    /**
     * Fetch data for settlement overview panel
     * @param int|null $auctionId null means without filter by auction
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param bool $isReadOnlyDb
     * @return HomeDahsboardSettlementOverviewDto - values for settlement overview panel
     */
    public function loadSettlementOverviewValues(?int $auctionId, DateTime $startDate, DateTime $endDate, bool $isReadOnlyDb = false): HomeDahsboardSettlementOverviewDto
    {
        $this->enableReadOnlyDb($isReadOnlyDb);
        $accountCond = '';
        $accountId = $this->getFilterAccountId();
        if (is_array($accountId)) {
            $accountId = reset($accountId);
        }
        if ($accountId > 0) {
            $accountCond = " AND s.account_id = " . $this->escape($accountId);
        }
        // filter by auction
        $auctionCond = '';
        if ($auctionId > 0) {
            $auctionCond = " AND si.auction_id = " . $this->escape($auctionId);
        }
        // filter by currency
        $currencyCond = '';
        $currencyId = $this->getFilterCurrencyId();
        if ($currencyId > 0) {
            $currencyCond = " AND a.currency = " . $this->escape($currencyId);
        }
        // filter by date range
        $dateCond = " AND (s.created_on BETWEEN"
            . " " . $this->escape($startDate->format(Constants\Date::ISO))
            . " AND " . $this->escape($endDate->format(Constants\Date::ISO)) . ")";
        $ssPaid = Constants\Settlement::SS_PAID;
        $availableSettlementStatusList = implode(',', Constants\Settlement::$availableSettlementStatuses);
        if ($auctionId > 0) {
            $sql = <<<SQL
SELECT
    @total_hp := SUM(si.hammer_price) AS total_hp,
    @total_commission := SUM(si.commission) AS total_commission,
    @total_hp_settled := SUM(IF(s.settlement_status_id = {$ssPaid}, si.hammer_price, 0)) AS total_hp_settled,
    @total_commission_settled := SUM(IF(s.settlement_status_id = {$ssPaid}, si.commission, 0)) AS total_commission_settled
FROM settlement s
INNER JOIN account acc ON acc.id = s.account_id AND acc.active
INNER JOIN settlement_item si ON si.settlement_id = s.id AND si.active
INNER JOIN auction a ON si.auction_id = a.id
WHERE
    s.settlement_status_id IN ({$availableSettlementStatusList})
    {$dateCond}
    {$auctionCond}
    {$accountCond}
    {$currencyCond}
SQL;
            $this->query($sql);
            $results = $this->fetchAssoc();

            $sql = <<<SQL
SELECT
    @total_tax := SUM(s.tax_exclusive + s.tax_services) AS total_tax,
    @total_fee := SUM(s.extra_charges) AS total_fee,
    @total_tax_settled := SUM(IF(s.settlement_status_id = {$ssPaid}, s.tax_exclusive, 0)
        + IF(s.settlement_status_id = {$ssPaid}, s.tax_services, 0)) AS total_tax_settled,
    @total_fee_settled := SUM(IF(s.settlement_status_id = {$ssPaid}, s.extra_charges, 0)) AS total_fee_settled
FROM settlement s
INNER JOIN account acc ON acc.id = s.account_id AND acc.active
WHERE EXISTS (
    SELECT s2.id FROM settlement s2
    INNER JOIN account acc ON acc.id = s2.account_id AND acc.active
    INNER JOIN settlement_item si ON si.settlement_id = s2.id AND si.active
    INNER JOIN auction a ON si.auction_id = a.id
    WHERE
        s2.id = s.id
        AND s2.settlement_status_id IN ({$availableSettlementStatusList})
        {$dateCond}
        {$auctionCond}
        {$accountCond}
        {$currencyCond}
    GROUP BY s2.id
)
SQL;
            $this->query($sql);
            $results = array_merge($this->fetchAssoc(), $results);
        } else {
            $sql = <<<SQL
SELECT
    @total_hp := SUM(s.hp_total) AS total_hp,
    @total_commission := SUM(s.comm_total) AS total_commission,
    @total_tax := SUM(s.tax_exclusive + s.tax_services) AS total_tax,
    @total_fee := SUM(s.extra_charges) AS total_fee,
    @total_hp_settled := SUM(IF(s.settlement_status_id = {$ssPaid}, s.hp_total, 0)) AS total_hp_settled,
    @total_commission_settled := SUM(IF(s.settlement_status_id = {$ssPaid}, s.comm_total, 0)) AS total_commission_settled,
    @total_tax_settled := SUM(IF(s.settlement_status_id = {$ssPaid}, s.tax_exclusive, 0)
        + IF(s.settlement_status_id = {$ssPaid}, s.tax_services, 0)) AS total_tax_settled,
    @total_fee_settled := SUM(IF(s.settlement_status_id = {$ssPaid}, s.extra_charges, 0)) AS total_fee_settled
FROM settlement s
INNER JOIN account acc ON acc.id = s.account_id AND acc.active
WHERE EXISTS (
    SELECT s2.id FROM settlement s2
    INNER JOIN account acc ON acc.id = s2.account_id AND acc.active
    INNER JOIN settlement_item si ON si.settlement_id = s2.id AND si.active
    INNER JOIN auction a ON si.auction_id = a.id
    WHERE
        s2.id = s.id
        AND s2.settlement_status_id IN ({$availableSettlementStatusList})
        {$dateCond}
        {$auctionCond}
        {$accountCond}
        {$currencyCond}
    GROUP BY s2.id
)
SQL;
            $this->query($sql);
            $results = $this->fetchAssoc();
        }
        $dto = HomeDahsboardSettlementOverviewDto::new()->fromDbRow($results);
        return $dto;
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
}
