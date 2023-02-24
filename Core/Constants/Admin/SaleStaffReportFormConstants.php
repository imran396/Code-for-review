<?php
/**
 * SAM-4633:Refactor sales staff report
 * https://bidpath.atlassian.net/browse/SAM-4633
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/10/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class SaleStaffReportFormConstants
 * @package Sam\Core\Constants\Admin
 */
class SaleStaffReportFormConstants
{
    // Control ids
    public const CID_LST_ACCOUNT = 'ssf13';
    public const CID_CAL_DATE_FROM = 'ssf1';
    public const CID_CAL_DATE_TO = 'ssf2';
    public const CID_RAD_DATE_RANGE_TYPE = 'ssf4';
    public const CID_LST_APPLY_PAYOUT_TO = 'ssf5';
    public const CID_LST_APPLY_PAYOUT_IF = 'ssf6';
    public const CID_BTN_CONSOLIDATE = 'ssf7';
    public const CID_DTG_LOT_ITEMS = 'ssf9';
    public const CID_BTN_GENERATE = 'ssf10';
    public const CID_BTN_LOCATION = 'ssf11';
    public const CID_BTN_SHOW_ALL = 'ssf12';
    public const CID_TXT_AUTOCOMPLETE_SALES_STAFF = 'ssf14';
    public const CID_HID_AUTOCOMPLETE_SALES_STAFF = 'ssf15';

    // Consolidation types
    public const CT_NONE = 0;
    public const CT_SALE_STAFF = 1;
    public const CT_LOCATION_OFFICE = 2;
    /** @var int[] */
    public static array $consolidationTypes = [self::CT_NONE, self::CT_SALE_STAFF, self::CT_LOCATION_OFFICE];

    // Date range types
    public const DR_DATE_SOLD = 'DS';
    public const DR_INVOICE_DATE = 'ID';
    public const DR_PAYMENT_DATE = 'PD';
    /** @var string[] */
    public static array $dateRangeTypes = [self::DR_DATE_SOLD, self::DR_INVOICE_DATE, self::DR_PAYMENT_DATE];

    // Payout application type
    public const PAT_HP = 'HP';
    public const PAT_HPBP = 'HPBP';
    public const PAT_TTL = 'TTL';

    // Payout application depend on invoice status
    public const PAIS_SHIPPED = 'SH';
    public const PAIS_PAID_OR_SHIPPED = 'PDSH';
    public const PAIS_ANY = 'AY';

    //Sorting order keys
    public const ORD_SALE_STAFF = 'saleStaff';
    public const ORD_OFFICE_LOCATION = 'officeLocation';
    public const ORD_CONSIGNOR_USER = 'consignorUser';
    public const ORD_CONSIGNOR_COMPANY = 'consignorCompany';
    public const ORD_BUYER_USER = 'buyerUser';
    public const ORD_BUYER_COMPANY = 'buyerCompany';
    public const ORD_ITEM_NO = 'itemNo';
    public const ORD_AUCTION_NAME = 'auctionName';
    public const ORD_LOT_NO = 'lotNo';
    public const ORD_LOT_NAME = 'lotName';
    public const ORD_INVOICE_STATUS = 'invoiceStatus';
    public const ORD_HAMMER_PRICE = 'hammerPrice';
    public const ORD_BUYERS_PREMIUM = 'buyersPremium';
    public const ORD_SALE_TAX = 'saleTax';
    public const ORD_TOTAL = 'total';
    public const ORD_PAYOUT = 'payout';
    public const ORD_DATE_SOLD = 'dateSold';
    public const ORD_INVOICE_DATE = 'invoiceDate';
    public const ORD_PAYMENT_DATE = 'paymentDate';
    public const ORD_REFERRER = 'referrer';
    public const ORD_REFERRER_HOST = 'referrerHost';

    /**
     * @var string[]
     */
    public static array $dateRangeOptionNames = [
        self::DR_DATE_SOLD => 'Use date sold',
        self::DR_INVOICE_DATE => 'Use invoice date',
        self::DR_PAYMENT_DATE => 'Use payment date',
    ];

    /**
     * @var string[]
     */
    public static array $payoutApplicationTypeNames = [
        self::PAT_HP => 'Hammer price only',
        self::PAT_HPBP => 'Hammer price plus BP',
        self::PAT_TTL => 'Total',
    ];

    /**
     * @var string[]
     */
    public static array $payoutApplicationOnInvoiceStatusNames = [
        self::PAIS_ANY => 'Any',
        self::PAIS_SHIPPED => 'Shipped',
        self::PAIS_PAID_OR_SHIPPED => 'Paid or Shipped',
    ];
    /**
     * @var array<string, string[]>
     */
    public static array $orderKeysToColumns = [
        self::ORD_SALE_STAFF => ['ucon.added_by'],
        self::ORD_OFFICE_LOCATION => ['l.address'],
        self::ORD_CONSIGNOR_USER => ['ucon.username'],
        self::ORD_CONSIGNOR_COMPANY => ['uicon.company_name'],
        self::ORD_BUYER_USER => ['ubid.username'],
        self::ORD_BUYER_COMPANY => ['uibid.company_name'],
        self::ORD_ITEM_NO => ['li.item_num'],
        self::ORD_AUCTION_NAME => ['a.name'],
        self::ORD_LOT_NO => ['ali.lot_num'],
        self::ORD_LOT_NAME => ['li.name'],
        self::ORD_INVOICE_STATUS => ['inv_status_name'],
        self::ORD_HAMMER_PRICE => ['ii.hammer_price'],
        self::ORD_BUYERS_PREMIUM => ['buyers_premium'],
        self::ORD_SALE_TAX => ['sales_tax'],
        self::ORD_TOTAL => ['total'],
        self::ORD_DATE_SOLD => ['li.date_sold'],
        self::ORD_INVOICE_DATE => ['i.created_on'],
        self::ORD_PAYMENT_DATE => ['payment_date'],
        self::ORD_REFERRER => ['uibid.referrer'],
        self::ORD_REFERRER_HOST => ['uibid.referrer_host'],
    ];
}
