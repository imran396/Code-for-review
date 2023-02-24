<?php

namespace Sam\Core\Constants\Admin;

/**
 * Class SettlementItemFormConstants
 * @package Sam\Core\Constants\Admin
 */
class SettlementItemFormConstants
{
    public const CID_LST_STATUS = 'sid2';
    public const CID_TXT_NOTE = 'sid4';
    public const CID_BTN_SAVE = 'sid5';
    public const CID_BTN_RECALCULATE = 'sid6';
    public const CID_PNL_PAYMENT_NAME = 'sid';
    public const CID_PNL_PAYMENT_AMOUNT = 'sid8';
    public const CID_PNL_REMOVE_PAYMENT = 'sid9';
    public const CID_BTN_ADD_PAYMENT = 'sid10';
    public const CID_PNL_CHARGE_NAME = 'sid11';
    public const CID_PNL_CHARGE_AMOUNT = 'sid12';
    public const CID_PNL_REMOVE_CHARGE = 'sid13';
    public const CID_BTN_ADD_CHARGE = 'sid14';
    public const CID_BTN_SEND_EMAIL = 'sid15';
    public const CID_BTN_ADD_LOTS = 'sid77';
    public const CID_BTN_CHARGE_CONSIGNOR = 'sid16';
    public const CID_BTN_MANAGE_SETTLEMENT_CHECK = 'btnManageSettlementCheck';
    public const CID_TXT_CC_CVN = 'sid35';
    public const CID_LST_ACTION = 'sid23';
    public const CID_ICO_SET_WAIT = 'sid17';
    public const CID_BTN_SET_EDIT = 'sid18';
    public const CID_BTN_SET_SAVE = 'sid19';
    public const CID_BTN_SET_CANCEL = 'sid20';
    public const CID_TXT_SETTLEMENT_NO = 'sid21';
    public const CID_CAL_SETTLEMENT_DATE = 'sid22';
    public const CID_ICO_PAYMENT_WAIT = 'sid26';
    public const CID_ICO_CHARGE_WAIT = 'sid27';
    public const CID_TXT_TAX = 'sid28';
    public const CID_CHK_TAX_HP = 'sid29';
    public const CID_RAD_TAX_HP_EXCLUSIVE = 'sid30';
    public const CID_RAD_TAX_HP_INCLUSIVE = 'sid31';
    public const CID_LBL_TAX_HP_ERROR = 'sid32';
    public const CID_CHK_TAX_COMMISSION = 'sid33';
    public const CID_CHK_TAX_SERVICES = 'sid34';
    public const CID_LBL_SETTLEMENT_NO = 'set_no';
    public const CID_LBL_SETTLEMENT_DATE = 'set_date';
    public const CID_TXT_TAX_CONTROL = 'tax-control';
    public const CID_LBL_TAX_AMOUNT = 'tax-amount';
    public const CID_TXT_LOT_NAME_TPL = 'tlotname%s';
    public const CID_TXT_HAMMER_PRICE_TPL = 'thammer%s';
    public const CID_TXT_FEE_TPL = 'tfee%s';
    public const CID_TXT_COMMISSION_TPL = 'tcommission%s';
    public const CID_BTN_REMOVE_TPL = 'brem%s';
    public const CID_TXT_L_LOT_NAME_TPL = 'llotname%s';
    public const CID_TXT_HAMMER_TPL = 'lhammer%s';
    public const CID_TXT_L_FEE_TPL = 'lfee%s';
    public const CID_TXT_L_COMMISSION_TPL = 'lcommission%s';
    public const CID_LST_PAYMENT_NAME_TPL = 'tpniid%s';
    public const CID_TXT_PAYMENT_NOTE_TPL = 'paymentNote%s';
    public const CID_PNL_PAYMENT_METHOD_CONTROLS_TPL = 'paymentMethodControlsPnl%s';
    public const CID_TXT_AMOUNT_TPL = 'tpaiid%s';
    public const CID_HID_PAYMENT_ID_TPL = 'tpiiid%s';
    public const CID_PNL_CHARGE_AMOUNT_TPL = 'tcziid%s';
    public const CID_PNL_CHARGE_NAME_TPL = 'tcniid%s';
    public const CID_LBL_SALE_DATE_TPL = 'lsaledate%s';
    public const CID_BTN_REMOVE_PAYMENT_TPL = 'bpriid%s';
    public const CID_BTN_REMOVE_CHARGE_TPL = 'bcriid%s';
    public const CID_BLK_DATA_GRID = 'c1';
    public const CID_BLK_DATA_GRID_ROW_TPL = 'c1row%s';
    public const CID_SETTLEMENT_ITEM_FORM = 'SettlementItemForm';

    // Group name
    public const GN_TAX_HP = 'taxhp';

    // Css classes
    public const CSS_SETTLEMENT_ADDRESS = 'settlement-address ';
    public const CSS_SETTLEMENT_CONSIGNOR_ADDRESS = 'settlement-consignor-address ';
    public const CSS_SETTLEMENT_CONSIGNOR_NAME = 'settlement-consignor-name ';
    public const CSS_SETTLEMENT_CONTROLS = 'settlement-controls ';
    public const CSS_SETTLEMENT_CONTROL_BTN_ADD_LOTS = 'settlement-control-btn-add-lots ';
    public const CSS_SETTLEMENT_CONTROL_BTN_MANAGE_SETTLEMENT_CHECK = 'settlement-control-btn-manage-settlement-check ';
    public const CSS_SETTLEMENT_CONTROL_BTN_CHARGE_CONSIGNOR = 'settlement-control-btn-charge-consignor ';
    public const CSS_SETTLEMENT_CONTROL_BTN_SAVE = 'settlement-control-btn-save ';
    public const CSS_SETTLEMENT_CONTROL_BTN_SEND_EMAIL = 'settlement-control-btn-send-email ';
    public const CSS_SETTLEMENT_CONTROL_LIST_ACTION = 'settlement-control-list-action ';
    public const CSS_SETTLEMENT_DATE = 'settlement-date ';
    public const CSS_SETTLEMENT_GENERAL_INFO = 'settlement-general-info ';
    public const CSS_SETTLEMENT_ITEMS_WRAPPER = 'settlement-items-wrapper ';
    public const CSS_SETTLEMENT_LBL_STATUS = 'settlement-lbl-status ';
    public const CSS_SETTLEMENT_LIST_STATUS = 'settlement-list-status ';
    public const CSS_SETTLEMENT_LOTS_BALANCE_DUE_WRAPPER = 'settlement-lots-balance_due-wrapper ';
    public const CSS_SETTLEMENT_LOTS_EXTRA_CHARGE_WRAPPER = 'settlement-lots-extra-charge-wrapper ';
    public const CSS_SETTLEMENT_LOTS_GRAND_TOTAL_WRAPPER = 'settlement-lots-grand-total-wrapper ';
    public const CSS_SETTLEMENT_LOTS_PAYMENTS_MADE_WRAPPER = 'settlement-lots-payments-made-wrapper ';
    public const CSS_SETTLEMENT_LOTS_SUBTOTAL_PAID_WRAPPER = 'settlement-lots-subtotal-paid-wrapper ';
    public const CSS_SETTLEMENT_LOTS_SUBTOTAL_UNPAID_WRAPPER = 'settlement-lots-subtotal-unpaid-wrapper ';
    public const CSS_SETTLEMENT_LOTS_SUBTOTAL_WRAPPER = 'settlement-lots-subtotal-wrapper ';
    public const CSS_SETTLEMENT_LOTS_TAX_EXCLUSIVE_WRAPPER = 'settlement-lots-tax-exclusive-wrapper ';
    public const CSS_SETTLEMENT_LOTS_TAX_INCLUSIVE_WRAPPER = 'settlement-lots-tax-inclusive-wrapper ';
    public const CSS_SETTLEMENT_LOTS_TOTAL_COMMISSION = 'settlement-lots-total-commission ';
    public const CSS_SETTLEMENT_LOTS_TOTAL_CONTROL_WRAPPER = 'settlement-lots-total-control-wrapper ';
    public const CSS_SETTLEMENT_LOTS_TOTAL_FEE = 'settlement-lots-total-fee ';
    public const CSS_SETTLEMENT_LOTS_TOTAL_HP = 'settlement-lots-total-hp ';
    public const CSS_SETTLEMENT_LOTS_TOTAL_LABEL = 'settlement-lots-total-label ';
    public const CSS_SETTLEMENT_LOTS_TOTAL_SUBTOTAL = 'settlement-lots-total-subtotal ';
    public const CSS_SETTLEMENT_LOTS_TOTAL_TAX_ON_COMMISSION = 'settlement-lots-total-tax-on-commission ';
    public const CSS_SETTLEMENT_LOTS_TOTAL_TAX_ON_HP = 'settlement-lots-total-tax-on-hp ';
    public const CSS_SETTLEMENT_LOTS_TOTAL_WRAPPER = 'settlement-lots-total-wrapper ';
    public const CSS_SETTLEMENT_LOT_ITEM_WRAPPER = 'settlement-lot-item-wrapper ';
    public const CSS_SETTLEMENT_MAIN_BOTTOM_CONTROLS = 'settlement-main-bottom-controls ';
    public const CSS_SETTLEMENT_MAIN_TOP_CONTROLS = 'settlement-main-top-controls ';
    public const CSS_SETTLEMENT_NOTE_TEXT = 'settlement-note-text ';
    public const CSS_SETTLEMENT_NOTE_WRAPPER = 'settlement-note-wrapper ';
    public const CSS_SETTLEMENT_NUMBER = 'settlement-number ';
    public const CSS_SETTLEMENT_PRINT_LINK = 'settlement-print-link ';
    public const CSS_TR_MAIN_TOP = 'tr-main-top ';
    public const CSS_TR_MAIN_AFTER_TOP = 'tr-main-after-top ';
    public const CSS_TR_MAIN_MIDDLE = 'tr-main-middle ';
    public const CSS_TR_MAIN_AFTER_MIDDLE = 'tr-main-after-middle ';
    public const CSS_TR_MAIN_BOTTOM = 'tr-main-bottom ';
    public const CSS_HR_DIVIDER = 'hr-divider ';
    public const CSS_RECALCULATE = 'recalculate ';
    /** SAM-7976  */
    public const CSS_CLASS_USER_CF_MAIN_WRAPPER = 'user-custom-fields';
    public const CSS_CLASS_USER_CF_TITLE = 'user-cust-title';

    public const CLASS_BLK_CC_CVN = 'cc-cvn';
}
