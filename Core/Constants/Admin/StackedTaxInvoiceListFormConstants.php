<?php

namespace Sam\Core\Constants\Admin;

class StackedTaxInvoiceListFormConstants
{
    // Filter by Invoice Status
    public const IS_ALL = 0;

    // Invoice list actions
    public const LA_NONE = 0;
    public const LA_EMAIL = 1;
    public const LA_PRINT = 2;
    public const LA_DELETE = 3;
    public const LA_MERGE = 4;
    public const LA_CHARGE_AUTH_NET = 5;
    public const LA_CHARGE_PAY_TRACE = 8;
    public const LA_CHARGE_NMI = 9;
    public const LA_CHARGE_OPAYO = 10;
    public const LA_CHARGE_EWAY = 11;

    // TODO: translate
    public static array $listActionNames = [
        self::LA_EMAIL => 'go_action.selector.email.option',
        self::LA_PRINT => 'go_action.selector.print.option',
        self::LA_DELETE => 'go_action.selector.delete.option',
        self::LA_MERGE => 'go_action.selector.merge.option',
        self::LA_CHARGE_AUTH_NET => 'go_action.selector.charge_authorize_net.option',
        self::LA_CHARGE_PAY_TRACE => 'go_action.selector.charge_pay_trace.option',
        self::LA_CHARGE_NMI => 'go_action.selector.charge_nmi.option',
        self::LA_CHARGE_OPAYO => 'go_action.selector.charge_opayo.option',
        self::LA_CHARGE_EWAY => 'go_action.selector.charge_eway.option',
    ];

    public const CID_INVOICE_LIST_FORM = 'StackedTaxInvoiceListForm';
    public const CID_BLK_AUCTION_LIST_CONTAINER = 'auction-list-container';
    public const CID_ICO_DTG_WAIT = 'stilf1';
    public const CID_ICON_REFRESH = 'stilf1b';
    public const CID_LST_ACCOUNT = 'stilf36';
    public const CID_LST_STATUS_FILTER = 'stilf2';
    public const CID_CHK_SOLD_DATE = 'stilf47';
    public const CID_CAL_START_DATE = 'stilf37';
    public const CID_LST_START_HOUR = 'staip38';
    public const CID_LST_START_MINUTE = 'staip39';
    public const CID_LST_START_MERIDIEM = 'staip40';
    public const CID_CAL_END_DATE = 'stilf41';
    public const CID_LST_END_HOUR = 'stilf42';
    public const CID_LST_END_MINUTE = 'stilf43';
    public const CID_LST_END_MERIDIEM = 'stilf44';
    public const CID_LST_WINNING_AUCTION = 'stilf45';
    public const CID_BTN_REFRESH_WINNING_BIDDERS_LIST = 'stilf3b';
    public const CID_LST_ITEM_PER_PAGE = 'stilf13';
    public const CID_DTG_INVOICES = 'stilf5';
    public const CID_DTG_INVOICES_PER_PAGE_SELECTOR = 'stilfDtgPerPageSelector';
    public const CID_TXT_SEARCH_KEY = 'stilf6';
    public const CID_HID_SOURCE_PAGE = 'stilf12';
    public const CID_TXT_INVOICE_NO = 'stilf38';
    public const CID_HID_AUCTION_FILTER = 'stilf25';
    public const CID_TXT_BIDDER_FILTER = 'stilf39';
    public const CID_HID_BIDDER_FILTER = 'stilf46';
    public const CID_BTN_SEARCH = 'stilf7';
    public const CID_BTN_RESET_SEARCH = 'stilf50';
    public const CID_CHK_CHECK_ALL = 'stilf9';
    public const CID_LST_PRIMARY_SORT = 'stilf16';
    public const CID_LST_SECONDARY_SORT = 'stilf17';
    public const CID_LST_WINNING_USER = 'stilf18';
    public const CID_LST_ACTION = 'stilf29';
    public const CID_BTN_ACTION_GO = 'stilf30';
    public const CID_BTN_SORT = 'stilf19';
    public const CID_BTN_GENERATE = 'stilf3';
    public const CID_TXT_GENERATE = 'stilf3m';
    public const CID_BTN_ITEM_SOLD = 'stilf23';
    public const CID_DLG_INVOICE_ITEMS_SOLD = 'stilf24';
    public const CID_DLG_CONFIRM_UNSOLD_REMOVE_INVOICES = 'stilf31';
    public const CID_LST_CURRENCY_FILTER = 'stilf28';
    public const CID_HID_CONFIRM_DELETE = 'stilf35';
    public const CID_TXT_BIDDER_NUMBER = 'stilf48';
    public const CID_TXT_CUSTOMER_NUMBER = 'stilf49';
    public const CID_PNL_INVOICES = 'stilfPnlInvoices';
    public const CID_PNL_INVOICES_REPORTS = 'stilfPnlInvoicesReports';
    public const CID_CHK_INVOICE_TPL = '%scInv%s';
    public const CID_BTN_DELETE_TPL = '%sbdel%s';
    public const CID_BTN_PAID_TPL = '%sbpaid%s';
    public const CID_FOCUS_TPL = 'stilf5row%s';

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
