<?php

namespace Sam\Core\Constants\Admin;

/**
 * Class SettlementListFormConstants
 * @package Sam\Core\Constants\Admin
 */
class SettlementListFormConstants
{
    public const CID_ICO_WAIT = 'slf1';
    public const CID_LST_STATUS_FILTER = 'slf2';
    public const CID_BTN_GENERATE = 'slf3';
    public const CID_DTG_SETTLEMENTS = 'slf5';
    public const CID_CHK_CHOOSE_ALL = 'slf8';
    public const CID_BTN_EXPORT = 'slf10';
    public const CID_CHK_UNPAID_LOTS = 'slf12';
    public const CID_LST_CONSIGNOR = 'slf16';
    public const CID_BTN_LINE_ITEM_EXPORT = 'slf17';
    public const CID_AUTOCOMPLETE_FILTER_AUCTION = 'slf18';
    public const CID_DLG_SETTLEMENT_LINE_ITEM = 'slf19';
    public const CID_LST_ACTION = 'slf20';
    public const CID_BTN_ACTION_GO = 'slf21';
    public const CID_HID_CONFIRM_DELETE = 'slf22';
    public const CID_LST_ACCOUNT_FILTER = 'slf23';
    public const CID_LST_ACCOUNT_GENERATE = 'slf28';
    public const CID_TXT_CONSIGNOR = 'slf24';
    public const CID_HID_CONSIGNOR = 'slf25';
    public const CID_AUTOCOMPLETE_GENERATE_AUCTION = 'slf26';
    public const CID_BTN_SEARCH = 'slf27';
    public const CID_BTN_SEARCH_RESET = 'slf29';
    public const CID_CHK_SETTLEMENT_TPL = '%scSet%s';
    public const CID_BTN_DELETE_TPL = '%sbdel%s';
    public const CID_BTN_PAID_TPL = '%sbpaid%s';
    public const CID_BLK_GENERATE_AUCTION_LIST_CONTAINER = 'auction-list-container';
    public const CID_BLK_FILTER_AUCTION_LIST_CONTAINER = 'auction-list-container-sec';
    public const CID_SETTLEMENT_LIST_FORM = 'SettlementListForm';
    public const CID_BTN_ALL_CHECKS = 'btnAllChecks';

    // Settlement list actions
    public const LA_EMAIL = 1;
    public const LA_PRINT = 2;
    public const LA_DELETE = 3;
    public const LA_MERGE = 4;
    public const LA_CHARGE_AUTH_NET = 5;
    public const LA_CHARGE_PAY_TRACE = 7;
    public const LA_CHARGE_NMI = 8;
    public const LA_CHARGE_OPAYO = 9;
    public const LA_WORK_WITH_CHECKS = 10;
    public const LA_CREATE_CHECKS = 11;

    public static array $listActionNames = [
        self::LA_EMAIL => 'go_action.selector.email.option',
        self::LA_PRINT => 'go_action.selector.print.option',
        self::LA_DELETE => 'go_action.selector.delete.option',
        self::LA_MERGE => 'go_action.selector.merge.option',
        self::LA_CHARGE_AUTH_NET => 'go_action.selector.charge_authorize_net.option',
        self::LA_CHARGE_PAY_TRACE => 'go_action.selector.charge_pay_trace.option',
        self::LA_CHARGE_NMI => 'go_action.selector.charge_nmi.option',
        self::LA_CHARGE_OPAYO => 'go_action.selector.charge_opayo.option',
        self::LA_WORK_WITH_CHECKS => 'go_action.selector.work_with_checks.option',
        self::LA_CREATE_CHECKS => 'go_action.selector.create_checks.option',
    ];

    // Charge actions
    public const SELECT_ACTION = 0;
    public const CHARGE_AUTH_NET = 1;
    public const CHARGE_PAY_TRACE = 4;
    public const CHARGE_EWAY = 5;
    public const CHARGE_NMI = 6;
    public const CHARGE_OPAYO = 7;

    public static array $chargeActionNames = [
        self::CHARGE_AUTH_NET => 'go_action.selector.charge_authorize_net.option',
        self::CHARGE_PAY_TRACE => 'go_action.selector.charge_pay_trace.option',
        self::CHARGE_EWAY => 'go_action.selector.charge_eway.option',
        self::CHARGE_NMI => 'go_action.selector.charge_nmi.option',
        self::CHARGE_OPAYO => 'go_action.selector.charge_opayo.option',
    ];

    public const CLASS_CHK_SETTLEMENT = 'settlement';
}
