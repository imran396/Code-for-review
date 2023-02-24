<?php
/**
 * SAM-4624 : Refactor invoice item sold report
 * https://bidpath.atlassian.net/browse/SAM-4624
 *
 * @author        Imran Rahman
 * @version       SVN: $Id: $
 * @since         Dec 16, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 */

namespace Sam\Report\Invoice\Legacy\ItemSold;

use LotItemCustData;
use LotItemCustField;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\CustomField\User\Load\UserCustomDataLoaderAwareTrait;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceAdditional\InvoiceAdditionalReadRepositoryCreateTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Timezone;
use UserCustData;
use UserCustField;

/**
 * Class DataLoader
 * @package Sam\Report\Invoice\Legacy\ItemSold
 */
class DataLoader extends CustomizableClass
{
    use DateHelperAwareTrait;
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterDatePeriodAwareTrait;
    use InvoiceAdditionalReadRepositoryCreateTrait;
    use LotCustomDataLoaderCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use NumberFormatterAwareTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;
    use TimezoneLoaderAwareTrait;
    use UserCustomDataLoaderAwareTrait;
    use UserCustomFieldLoaderAwareTrait;

    /** @var string */
    private const DATE_FORMAT = 'm/d/Y';

    protected ?Timezone $systemTimezone = null;
    protected bool $isAccountFiltering = false;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function enableAccountFiltering(bool $enable): static
    {
        $this->isAccountFiltering = $enable;
        return $this;
    }

    /**
     * @return Timezone
     */
    public function getSystemTimezone(): Timezone
    {
        if ($this->systemTimezone === null) {
            $this->systemTimezone = $this->getTimezoneLoader()->loadSystemTimezone($this->getSystemAccountId());
        }
        return $this->systemTimezone;
    }

    /**
     * @param Timezone $systemTimezone
     * @return static
     */
    public function setSystemTimezone(Timezone $systemTimezone): static
    {
        $this->systemTimezone = $systemTimezone;
        return $this;
    }

    /**
     * @return array
     */
    public function load(): array
    {
        $query = $this->buildResultQuery();
        $this->query($query);
        $rows = $this->fetchAllAssoc();
        return $rows;
    }

    /**
     * @return string
     */
    public function buildResultQuery(): string
    {
        $this->getNumberFormatter()->construct($this->getSystemAccountId());
        $invoiceStatusList = implode(',', Constants\Invoice::$availableInvoiceStatuses);
        $n = "\n";
        $startDateUtcIso = $this->escape($this->getFilterStartDateUtcIso());
        $endDateUtcIso = $this->escape($this->getFilterEndDateUtcIso());
        $taHpBp = Constants\User::TAX_HP_BP;
        $taHp = Constants\User::TAX_HP;
        $taBp = Constants\User::TAX_BP;

        // @formatter:off
        $query =
            "SELECT " . $n .
                "DISTINCT i.id AS inv_id, " . $n .
                "i.invoice_no AS inv_no, " . $n .
                "IFNULL(i.invoice_date, i.created_on) AS inv_date, " . $n .
                "i.invoice_status_id AS invoice_status_id, " . $n .
                "@total := (SELECT SUM(IFNULL(hammer_price,0) + IFNULL(buyers_premium,0)) FROM invoice_item WHERE invoice_id = i.id AND active) AS total, " . $n .
                "@cash_discount := (IF(i.cash_discount = true, CAST((@total * ((select IFNULL(cash_discount,0) from setting_invoice where account_id = i.account_id) / 100)) AS DECIMAL(14,4)) ,0)) AS cash_discount, " . $n .
                "@shipping_fee := IFNULL(i.shipping,0) AS shipping_fee, " . $n .
                "@total_charge := (SELECT IFNULL(SUM(amount), 0) FROM invoice_additional WHERE invoice_id = i.id AND active) AS total_charge, " . $n .
                "@total_sales_tax := (SELECT CAST(SUM( " . $n .
                    "CASE tax_application " . $n .
                        "WHEN {$taHpBp} THEN (hammer_price + buyers_premium)  * (sales_tax / 100) " . $n .
                        "WHEN {$taHp} THEN (hammer_price)  * (sales_tax / 100) " . $n .
                        "WHEN {$taBp} THEN (buyers_premium)  * (sales_tax / 100) " . $n .
                        "ELSE 0 " . $n .
                    "END) AS DECIMAL(12,4)) " . $n .
                    "FROM invoice_item " . $n .
                    "WHERE " . $n .
                        "invoice_id = i.id " . $n .
                        "AND active) AS total_sales_tax, " . $n .
                "@grand_total := CAST(((@total + @shipping_fee + @total_charge + @total_sales_tax) - @cash_discount) AS DECIMAL(12,2)) AS grand_total, " . $n .
                    "u.id AS id, " . $n .
                "u.username AS username, " . $n .
                "u.customer_no AS customer_no, " . $n .
                "ui.first_name AS first_name, " . $n .
                "ui.last_name AS last_name, " . $n .
                "u.email AS email, " . $n .
                "ui.phone AS phone, " . $n .
                "iub.company_name AS billing_company_name, " . $n .
                "iub.first_name AS billing_first_name, " . $n .
                "iub.last_name AS billing_last_name, " . $n .
                "iub.phone AS billing_phone, " . $n .
                "iub.address AS billing_address, " . $n .
                "iub.address2 AS billing_address2, " . $n .
                "iub.address3 AS billing_address3, " . $n .
                "iub.city AS billing_city, " . $n .
                "iub.state AS billing_state, " . $n .
                "iub.zip AS billing_zip, " . $n .
                "iub.country AS billing_country, " . $n .
                "ius.company_name AS shipping_company_name, " . $n .
                "ius.first_name AS shipping_first_name, " . $n .
                "ius.last_name AS shipping_last_name, " . $n .
                "ius.phone AS shipping_phone, " . $n .
                "ius.address AS shipping_address, " . $n .
                "ius.address2 AS shipping_address2, " . $n .
                "ius.address3 AS shipping_address3, " . $n .
                "ius.city AS shipping_city, " . $n .
                "ius.state AS shipping_state, " . $n .
                "ius.zip AS shipping_zip, " . $n .
                "ius.country AS shipping_country, " . $n .
                "ui.referrer AS referrer, " . $n .
                "ui.referrer_host AS referrer_host " . $n .
            "FROM invoice AS i " . $n .
            "LEFT JOIN invoice_item AS ii ON ii.invoice_id=i.id" . $n .
            "LEFT JOIN user AS u ON u.id = i.bidder_id " . $n .
            "LEFT JOIN user_info AS ui ON ui.user_id = i.bidder_id " . $n .
            "LEFT JOIN invoice_user_billing AS iub ON iub.invoice_id = i.id " . $n .
            "LEFT JOIN invoice_user_shipping AS ius ON ius.invoice_id = i.id " . $n;
        // @formatter:on


        if ($this->isAccountFiltering) {
            $accountCond = $this->getFilterAccountId()
                ? "AND i.account_id = " . $this->escape($this->getFilterAccountId()) . " "
                : "AND i.account_id > 0 ";
        } else { //In case sam portal has been disabled again
            $accountCond = "AND i.account_id = " . $this->escape($this->getSystemAccountId()) . " ";
        }

        if ($this->getFilterAuctionId()) {
            $accountCond .= " AND ii.auction_id = " . $this->escape($this->getFilterAuctionId());
        }

        if ((int)$this->getSortColumn() === 1) { // Invoice date
            $query .=
                // @formatter:off
                "WHERE " . $n .
                    "i.tax_designation = " . Constants\Invoice::TDS_LEGACY . " " .
                    "AND (IFNULL(i.invoice_date, i.created_on)) >= {$startDateUtcIso} " .
                    "AND (IFNULL(i.invoice_date, i.created_on)) <= {$endDateUtcIso} " .
                    "AND i.invoice_status_id IN ({$invoiceStatusList}) " . $n .
                    $accountCond . $n .
                "ORDER BY i.invoice_no " . $n;
                // @formatter:on
        } elseif ((int)$this->getSortColumn() === 2) { // Payment date
            $query .=
                // @formatter:off
                "WHERE " . $n .
                    "(SELECT COUNT(1) " . $n .
                    "FROM payment AS p " . $n .
                    "WHERE p.tran_id = i.id AND p.tran_type = " . $this->escape(Constants\Payment::TT_INVOICE) . $n .
                        "AND (" . $n .
                            "(IFNULL(p.paid_on, p.created_on)) >= {$startDateUtcIso} " . $n .
                            "AND (IFNULL(p.paid_on, p.created_on)) <= {$endDateUtcIso}) " . $n .
                            "AND p.active = true " . $n .
                        ") > 0 " . $n .
                        "AND i.invoice_status_id IN ({$invoiceStatusList}) " . $n .
                        $accountCond . $n .
                    "ORDER BY  " .
                        "(SELECT IFNULL(p.paid_on, p.created_on) " . $n .
                        "FROM payment AS p " . $n .
                        "WHERE p.tran_id = i.id AND p.tran_type = " . $this->escape(Constants\Payment::TT_INVOICE) . $n .
                            " AND p.active = true " . $n .
                        "ORDER BY " . $n .
                            "IFNULL(p.paid_on, p.created_on)" . $n .
                        "LIMIT 1), "
                        . "i.invoice_no ";
                // @formatter:on
        }

        return $query;
    }

    /**
     * @param int $invoiceId
     * @return string
     */
    public function getRecentPaymentDate(int $invoiceId): string
    {
        $n = "\n";
        $recentPaymentDate = '';
        $recentPaymentQuery =
            // @formatter:off
            "SELECT " . $n .
                "IFNULL(p.paid_on, p.created_on) AS paid_date " . $n .
            "FROM payment AS p " . $n .
            "WHERE " . $n .
                "p.tran_id = " . $this->escape($invoiceId) . " " . $n .
                "AND p.tran_type = " . $this->escape(Constants\Payment::TT_INVOICE) . " " . $n .
                "AND p.active = true " . $n .
            "ORDER BY " . $n .
                "IFNULL(p.paid_on, p.created_on) ASC " . $n .
            "LIMIT 1";
            // @formatter:on

        $this->query($recentPaymentQuery);
        $paymentRow = $this->fetchAssoc();
        if (count($paymentRow) > 0) {
            $recentPaymentDate = $this->getDateHelper()->convertUtcToSysByDateIso($paymentRow['paid_date']);
            $recentPaymentDate = $recentPaymentDate->format(self::DATE_FORMAT);
        }
        return $recentPaymentDate;
    }

    /**
     * @param int $invoiceId
     * @return string
     */
    public function invoiceItemQuery(int $invoiceId): string
    {
        $n = "\n";
        $auctionCond = '';
        if ($this->getFilterAuctionId()) {
            $auctionCond = "AND a.id = " . $this->escape($this->getFilterAuctionId()) . " " . $n;
        }

        $taHpBp = Constants\User::TAX_HP_BP;
        $taHp = Constants\User::TAX_HP;
        $taBp = Constants\User::TAX_BP;

        $invItemQuery =
            // @formatter:off
            "SELECT " . $n .
                "ali.lot_num AS lot_num, " . $n .
                "ali.lot_num_prefix AS lot_num_prefix, " . $n .
                "ali.lot_num_ext AS lot_num_ext, " . $n .
                "ali.quantity AS quantity, " . $n .
                "COALESCE(
                    ali.quantity_digits, 
                    li.quantity_digits, 
                    (SELECT lc.quantity_digits
                     FROM lot_category lc
                       INNER JOIN lot_item_category lic ON lc.id = lic.lot_category_id
                     WHERE lic.lot_item_id = li.id
                       AND lc.active = 1
                     ORDER BY lic.id
                     LIMIT 1), 
                    seta.quantity_digits
                ) as quantity_scale, " . $n .
                "ii.lot_item_id AS lot_item_id, " . $n .
                "ii.auction_id AS auction_id, " . $n .
                "a.name AS sale_name, " . $n .
                "a.test_auction AS test_auction, " . $n .
                "a.sale_num AS sale_num, " . $n .
                "a.sale_num_ext AS sale_num_ext, " . $n .
                "li.item_num AS item_num, " . $n .
                "li.item_num_ext AS item_num_ext, " . $n .
                "li.name AS lot_name, " . $n .
                "ii.hammer_price AS hammer_price, " . $n .
                "ii.buyers_premium AS buyers_premium, " . $n .
                "ii.subtotal AS subtotal, " . $n .
                "ii.sales_tax AS sales_tax, " . $n .
                "CAST( " . $n .
                    "CASE ii.tax_application " . $n .
                        "WHEN {$taHpBp} THEN (ii.hammer_price + ii.buyers_premium)  * (ii.sales_tax / 100) " . $n .
                        "WHEN {$taHp} THEN (ii.hammer_price)  * (ii.sales_tax / 100) " . $n .
                        "WHEN {$taBp} THEN (ii.buyers_premium)  * (ii.sales_tax / 100) " . $n .
                        "ELSE 0 " . $n .
                    "END AS DECIMAL(12,4)) AS computed_sales_tax, " . $n .
                "ii.tax_application AS tax_application " . $n .
            "FROM invoice_item AS ii " . $n .
            "LEFT JOIN lot_item AS li ON li.id = ii.lot_item_id " . $n .
            "LEFT JOIN auction AS a ON a.id = ii.auction_id " . $n .
            "LEFT JOIN auction_lot_item AS ali ON ali.auction_id = ii.auction_id " . $n .
                "AND ali.lot_item_id = ii.lot_item_id AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ") " . $n .
            "LEFT JOIN setting_auction seta ON seta.account_id = li.account_id ". $n .
            "WHERE " . $n .
                "ii.active = true " . $n .
                "AND ii.invoice_id = " . $this->escape($invoiceId) . " " . $n .
                $auctionCond .
            "ORDER BY " . $n .
                "ali.lot_num_prefix, ali.lot_num, ali.lot_num_ext ";
            // @formatter:on
        return $invItemQuery;
    }

    /**
     * @param int $invoiceId
     * @return array[]
     */
    public function loadInvoiceItem(int $invoiceId): array
    {
        $query = $this->invoiceItemQuery($invoiceId);
        $this->query($query);
        $rows = $this->fetchAllAssoc();
        return $rows;
    }

    /**
     * @param int $invoiceId
     * @return array
     */
    public function loadInvoiceAdditionalByInvoiceId(int $invoiceId): array
    {
        $rows = $this->createInvoiceAdditionalReadRepository()
            ->select(['iadd.name', 'iadd.amount'])
            ->filterInvoiceId($invoiceId)
            ->orderById()
            ->loadRows();
        return $rows;
    }

    /**
     * @param int $invoiceId
     * @return string
     */
    public function loadPaymentQuery(int $invoiceId): string
    {
        $n = "\n";
        $paymentsQuery =
            // @formatter:off
            "SELECT " . $n .
                "p.payment_method_id, " . $n .
                "p.amount AS amount, " . $n .
                "p.note AS note, " . $n .
                "IFNULL(p.paid_on, p.created_on) AS paid_date " . $n .
            "FROM payment AS p " . $n .
            "WHERE " . $n .
                "p.tran_id = " . $this->escape($invoiceId) . " " . $n .
                "AND p.tran_type = " . $this->escape(Constants\Payment::TT_INVOICE) . " " . $n .
                "AND p.active = true " . $n .
            "ORDER BY " . $n .
                "IFNULL(p.paid_on, p.created_on) ASC " . $n;
            // @formatter:on
        return $paymentsQuery;
    }

    /**
     * @param int $invoiceId
     * @return array
     */
    public function loadPayments(int $invoiceId): array
    {
        $query = $this->loadPaymentQuery($invoiceId);
        $this->query($query);
        $rows = $this->fetchAllAssoc();
        return $rows;
    }

    /**
     * @return LotItemCustField[]
     */
    public function loadLotCustomFields(): array
    {
        $lotCustomFields = $this->createLotCustomFieldLoader()->loadInInvoices(true);
        return $lotCustomFields;
    }

    /**
     * @return UserCustField[]
     */
    public function loadUserCustomFields(): array
    {
        $userCustomFields = $this->getUserCustomFieldLoader()->loadInInvoices(true);
        return $userCustomFields;
    }

    /**
     * @param int $lotCustomFieldId
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return LotItemCustData|null
     */
    public function loadLotCustomData(int $lotCustomFieldId, int $lotItemId, bool $isReadOnlyDb = false): ?LotItemCustData
    {
        $lotCustomData = $this->createLotCustomDataLoader()->load($lotCustomFieldId, $lotItemId, $isReadOnlyDb);
        return $lotCustomData;
    }

    /**
     * @param UserCustField $userCustomField
     * @param int $userId
     * @param bool $isReadOnlyDb
     * @return UserCustData
     */
    public function loadUserCustomDataOrCreate(
        UserCustField $userCustomField,
        int $userId,
        bool $isReadOnlyDb = false
    ): UserCustData {
        $userCustomData = $this->getUserCustomDataLoader()->loadOrCreate(
            $userCustomField,
            $userId,
            false,
            $isReadOnlyDb
        );
        return $userCustomData;
    }
}
