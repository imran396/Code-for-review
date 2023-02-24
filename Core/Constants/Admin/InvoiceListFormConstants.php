<?php

namespace Sam\Core\Constants\Admin;

/**
 * Class InvoiceListFormConstants
 * @package Sam\Core\Constants\Admin
 */
class InvoiceListFormConstants
{
    // Filter by Invoice Status
    public const IS_ALL = 0;

    // Invoice list actions
    public const LA_EMAIL = 1;
    public const LA_PRINT = 2;
    public const LA_DELETE = 3;
    public const LA_MERGE = 4;
    public const LA_CHARGE_AUTH_NET = 5;
    public const LA_CHARGE_PAY_TRACE = 6;
    public const LA_CHARGE_NMI = 7;
    public const LA_CHARGE_OPAYO = 8;
    public const LA_CHARGE_EWAY = 9;

    public static array $listActionNames = [
        self::LA_EMAIL => 'Send',
        self::LA_PRINT => 'Print',
        self::LA_DELETE => 'Delete Selected',
        self::LA_MERGE => 'Merge Selected',
        self::LA_CHARGE_AUTH_NET => 'Charge via Authorize.net',
        self::LA_CHARGE_PAY_TRACE => 'Charge via PayTrace',
        self::LA_CHARGE_NMI => 'Charge via Nmi',
        self::LA_CHARGE_OPAYO => 'Charge via Opayo',
        self::LA_CHARGE_EWAY => 'Charge via Eway',
    ];

    public const CID_INVOICE_LIST_FORM = 'InvoiceListForm';
    public const CID_BLK_AUCTION_LIST_CONTAINER = 'auction-list-container';
    public const CID_ICO_DTG_WAIT = 'ilf1';
    public const CID_ICON_REFRESH = 'ilf1b';
    public const CID_LST_ACCOUNT = 'ilf36';
    public const CID_LST_STATUS_FILTER = 'ilf2';
    public const CID_CHK_SOLD_DATE = 'ilf47';
    public const CID_CAL_START_DATE = 'ilf37';
    public const CID_LST_START_HOUR = 'aip38';
    public const CID_LST_START_MINUTE = 'aip39';
    public const CID_LST_START_MERIDIEM = 'aip40';
    public const CID_CAL_END_DATE = 'ilf41';
    public const CID_LST_END_HOUR = 'ilf42';
    public const CID_LST_END_MINUTE = 'ilf43';
    public const CID_LST_END_MERIDIEM = 'ilf44';
    public const CID_LST_WINNING_AUCTION = 'ilf45';
    public const CID_BTN_REFRESH_WINNING_BIDDERS_LIST = 'ilf3b';
    public const CID_LST_ITEM_PER_PAGE = 'ilf13';
    public const CID_DTG_INVOICES = 'ilf5';
    public const CID_DTG_INVOICES_PER_PAGE_SELECTOR = 'ilfDtgPerPageSelector';
    public const CID_TXT_SEARCH_KEY = 'ilf6';
    public const CID_HID_SOURCE_PAGE = 'ilf12';
    public const CID_TXT_INVOICE_NO = 'ilf38';
    public const CID_HID_AUCTION_FILTER = 'ilf25';
    public const CID_TXT_BIDDER_FILTER = 'ilf39';
    public const CID_HID_BIDDER_FILTER = 'ilf46';
    public const CID_BTN_SEARCH = 'ilf7';
    public const CID_BTN_RESET_SEARCH = 'ilf50';
    public const CID_CHK_CHECK_ALL = 'ilf9';
    public const CID_LBL_AUCTION_REPORT = 'ilf10';
    public const CID_LST_PRIMARY_SORT = 'ilf16';
    public const CID_LST_SECONDARY_SORT = 'ilf17';
    public const CID_LST_WINNING_USER = 'ilf18';
    public const CID_LST_ACTION = 'ilf29';
    public const CID_BTN_ACTION_GO = 'ilf30';
    public const CID_BTN_GO = 'ilf19';
    public const CID_BTN_GENERATE = 'ilf3';
    public const CID_TXT_GENERATE = 'ilf3m';
    public const CID_BTN_ITEM_SOLD = 'ilf23';
    public const CID_DLG_INVOICE_ITEMS_SOLD = 'ilf24';
    public const CID_DLG_CONFIRM_UNSOLD_REMOVE_INVOICES = 'ilf31';
    public const CID_LST_CURRENCY_FILTER = 'ilf28';
    public const CID_HID_CONFIRM_DELETE = 'ilf35';
    public const CID_TXT_BIDDER_NUMBER = 'ilf48';
    public const CID_TXT_CUSTOMER_NUMBER = 'ilf49';
    public const CID_PNL_INVOICES = 'ilfPnlInvoices';
    public const CID_PNL_INVOICES_REPORTS = 'ilfPnlInvoicesReports';
    public const CID_CHK_INVOICE_TPL = '%scInv%s';
    public const CID_BTN_DELETE_TPL = '%sbdel%s';
    public const CID_BTN_PAID_TPL = '%sbpaid%s';
    public const CID_FOCUS_TPL = 'ilf5row%s';

    public const CLASS_BLK_CLOSE_ALL = 'closeall';
    public const CLASS_BLK_SEARCH_SECTION = 'searchSection';
    public const CLASS_BLK_USER_BULK_UPLOAD = 'user-bulk-upload';
    public const CLASS_CHK_INVOICE = 'invoice';
    public const CLASS_LNK_ITEMS = 'items';
    public const CLASS_LNK_Q_IMPORT = 'qimport';
    public const CLASS_LNK_REPORT = 'report';
    public const CLASS_LNK_SEARCH = 'search';
    public const CLASS_TBL_REPORT = 'tablereport';
}
