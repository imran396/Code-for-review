<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/30/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class TaxReportFormConstants
 * @package Sam\Core\Constants\Admin
 */
class TaxReportFormConstants
{
    public const CID_LST_ACCOUNT = 'trf14';
    public const CID_LST_LOCATION = 'trf05';
    public const CID_LST_ITEM_PER_PAGE = 'trf10';
    public const CID_LST_CURRENCY = 'trf13';
    public const CID_TXT_USER_ID = 'trf01c';
    public const CID_CHK_FILTER_DATE = 'trf02';
    public const CID_BTN_GENERATE = 'trf07';
    public const CID_DTG_INVOICE_ITEM = 'trf09';
    public const CID_CAL_DATE_FROM = 'trf11';
    public const CID_CAL_DATE_TO = 'trf12';
    public const CID_AUTOCOMPLETE_AUCTION = 'trf01';
    public const CID_TXT_USERS_LIST = 'users-list';

    //sorting order keys
    public const ORD_CREATED_ON = 'createdOn';
    public const ORD_USERNAME = 'username';
    public const ORD_BIDDER_NUM = 'bidderNum';
    public const ORD_SALE_NO = 'saleNo';
    public const ORD_LOT_NO = 'lotNo';
    public const ORD_INVOICE_NO = 'invoiceNo';
    public const ORD_NAME = 'name';
    public const ORD_SALES_TAX = 'salesTax';
    public const ORD_HAMMER_PRICE = 'hammerPrice';
    public const ORD_BUYERS_PREMIUM = 'buyersPremium';
    public const ORD_SUB_TOTAL = 'subTotal';
    public const ORD_TAX = 'tax';
    public const ORD_TOTAL = 'total';
    public const CID_BLK_AUCTION_LIST_CONTAINER = 'auction-list-container';

    /**
     * @var array<string, string[]>
     */
    public static array $orderKeysToColumns = [
        self::ORD_CREATED_ON => ['i.created_on'],
        self::ORD_USERNAME => ['u.username'],
        self::ORD_BIDDER_NUM => ['ab.bidder_num'],
        self::ORD_SALE_NO => ['a.sale_num'],
        self::ORD_LOT_NO => ['ali.lot_num'],
        self::ORD_INVOICE_NO => ['i.invoice_no'],
        self::ORD_NAME => ['li.name'],
        self::ORD_SALES_TAX => ['sales_tax'],
        self::ORD_HAMMER_PRICE => ['ii.hammer_price'],
        self::ORD_BUYERS_PREMIUM => ['ii.buyers_premium'],
        self::ORD_SUB_TOTAL => ['sub_total'],
        self::ORD_TAX => ['tax'],
        self::ORD_TOTAL => ['total'],
    ];
}
