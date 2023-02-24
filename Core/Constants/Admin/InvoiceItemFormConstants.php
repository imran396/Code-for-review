<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/14/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 =415 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class InvoiceItemFormConstants
 */
class InvoiceItemFormConstants
{
    public const CHARGE_OPTION_CC_ON_PROFILE = 'P';
    public const CHARGE_OPTION_OTHER_CC = 'O';

    public const CHARGETYPE_EXTRA = 1; // Default extra charges
    public const CHARGETYPE_SURCHARGE = 2; // CC payment related additional charges

    public const CID_ICO_PAYMENT_WAIT = 'iid01';
    public const CID_ICO_CHARGE_WAIT = 'iid02';
    public const CID_ICO_SHIPPING_CALC_WAIT = 'iid89';
    public const CID_RAD_CHARGE_OPTION = 'iid26';
    public const CID_LST_STATUS = 'iid2';
    public const CID_RAD_DISCOUNT = 'iid3';
    public const CID_TXT_NOTE = 'iid5';
    public const CID_BTN_SEND_EMAIL = 'iid6';
    public const CID_BTN_BOTTOM_SAVE = 'iid7';
    public const CID_TXT_SHIPPING = 'iid8';
    public const CID_TXT_SHIPPING_NOTE = 'iid84';
    public const CID_BTN_SHIPPING_CALC = 'iid85';
    public const CID_PNL_PAYMENT_INFO_GENERAL = 'iid9';
    public const CID_PNL_PAYMENT_AMOUNT_GENERAL = 'iid10';
    public const CID_PNL_PAYMENT_REMOVE_GENERAL = 'iid11';
    public const CID_BTN_ADD_PAYMENT = 'iid12';
    public const CID_PNL_CHARGE_INFO_GENERAL = 'iid13';
    public const CID_PNL_CHARGE_AMOUNT_GENERAL = 'iid14';
    public const CID_PNL_CHARGE_REMOVE_GENERAL = 'iid15';
    public const CID_BTN_ADD_CHARGE = 'iid16';
    public const CID_BTN_RECALCULATE = 'iid17';
    public const CID_CHK_EXCLUDE_IN_THRESHOLD = 'iid18';
    public const CID_CHK_LST_PAYMENT_METHODS = 'iid21';
    public const CID_DLG_CONFIRM_UNSOLD_REMOVE = 'iid23';
    public const CID_TXT_CHARGE_AMOUNT = 'iid24';
    public const CID_BTN_CHARGE_AMOUNT_VIA_AUTHORIZE_NET = 'iid25';
    public const CID_DLG_CHARGE_INVOICE_CC_INFO = 'iid27';
    public const CID_LBL_CHARGE_ERROR = 'iid28';
    public const CID_DLG_CONFIRM_UNSOLD_REMOVE_INVOICE = 'iid29';
    public const CID_DLG_SELECT_SHIPPING_CALC_METHOD_INVOICE = 'iid88';
    public const CID_BTN_CHARGE_AMOUNT_VIA_PAY_TRACE = 'iid90';
    public const CID_BTN_CHARGE_AMOUNT_VIA_EWAY = 'iid91';
    public const CID_BTN_CHARGE_AMOUNT_VIA_NMI = 'iid92';
    public const CID_BTN_CHARGE_AMOUNT_VIA_OPAYO = 'iid93';
    public const CID_BTN_REMOVE_TAXES = 'iid32';
    public const CID_DLG_CONFIRM_RELEASE_LOT = 'iid33';
    public const CID_BTN_TOP_WAIT = 'iid36';
    public const CID_BTN_TOP_EDIT = 'iid34';
    public const CID_BTN_TOP_SAVE = 'iid35';
    public const CID_BTN_TOP_CANCEL = 'iid43';
    public const CID_TXT_INVOICE_NO = 'iid38';
    public const CID_CAL_INVOICE_DATE = 'iid40';
    public const CID_DLG_INVOICE_PAYMENT = 'iid41';
    public const CID_DLG_INVOICE_STATUS = 'iid42';
    public const CID_BTN_ADD_LOTS = 'iid77';
    public const CID_LBL_ERROR_ADD_PAYMENT_METHOD = 'iid78';
    public const CID_LBL_BALANCE_DUE_WARNING = 'iid86';
    public const CID_TXT_INTERNAL_NOTE = 'iid79';
    public const CID_TXT_TAX_CHARGE_RATE = 'iid82';
    public const CID_TXT_TAX_FEES_RATE = 'iid83';
    public const CID_PNL_BILLING_PHONE = 'ibp';
    public const CID_PNL_BILLING_FAX = 'ibf';
    public const CID_LST_BILLING_COUNTRY = 'billcountry';
    public const CID_TXT_BILLING_STATE = 'billstatetxt';
    public const CID_LST_BILLING_STATE = 'billstatelst';
    public const CID_LST_BILLING_STATE_CANADA = 'billstatelstcanada';
    public const CID_LST_BILLING_STATE_MEXICO = 'billstatelstmexico';
    public const CID_PNL_SHIPPING_PHONE = 'isp';
    public const CID_PNL_SHIPPING_FAX = 'isf';
    public const CID_LST_SHIPPING_COUNTRY = 'shipcountry';
    public const CID_TXT_SHIPPING_STATE = 'shipstatetxt';
    public const CID_LST_SHIPPING_STATE = 'shipstatelst';
    public const CID_LST_SHIPPING_STATE_CANADA = 'shipstatelstcanada';
    public const CID_LST_SHIPPING_STATE_MEXICO = 'shipstatelstmexico';
    public const CID_CAL_SALE_DATE_TPL = 'csaledate%s%s';
    public const CID_BTN_WAIT_TPL = 'bwait%s';
    public const CID_BTN_EDIT_TPL = 'bedi%s';
    public const CID_CHK_RELEASE_TPL = 'chkRel%s';
    public const CID_BTN_SAVE_TPL = 'bsave%s';
    public const CID_BTN_CANCEL_TPL = 'bcancel%s';
    public const CID_TXT_TAX_PER_TPL = 'ttaxper%s';
    public const CID_LST_TAX_APPLICATION_TPL = 'ltaxapp%s';
    public const CID_BTN_REMOVE_TPL = 'bremi%s';
    public const CID_TXT_LOT_NAME_TPL = 'tlotname%s';
    public const CID_TXT_HAMMER_TPL = 'thammer%s';
    public const CID_TXT_PREMIUM_TPL = 'tpremium%s';
    public const CID_CAL_INV_DATE = 'inv_date';
    public const CID_PNL_BILLING_VIEW = 'billing-view';
    public const CID_PNL_BILLING_EDIT = 'billing-edit';
    public const CID_PNL_SHIPPING_VIEW = 'shipping-view';
    public const CID_PNL_SHIPPING_EDIT = 'shipping-edit';
    public const CID_LST_SALE_DATE_TPL = 'lsaledate%s';
    public const CID_LST_LOT_NAME_TPL = 'llotname%s';
    public const CID_LST_HAMMER_TPL = 'lhammer%s';
    public const CID_LST_PREMIUM_TPL = 'lpremium%s';
    public const CID_LBL_SALES_TAX = 'lblSalesTax';
    public const CID_PNL_PAYMENT_INFO_TPL = 'tppiid%s';
    public const CID_HID_PAYMENT_ID_TPL = 'iif_payment_id_%s';
    public const CID_LST_PAYMENT_METHOD_TPL = 'tpniid%s';
    public const CID_LST_CREDIT_CARD_TPL = 'tpcciid%s';
    public const CID_TXT_NOTE_TPL = 'tptiid%s';
    public const CID_CAL_DATE_TPL = 'tpciid%s';
    public const CID_PNL_PAYMENT_AMOUNT_TPL = 'tpziid%s';
    public const CID_TXT_PAYMENT_AMOUNT_TPL = 'tpaiid%s';
    public const CID_PNL_PAYMENT_REMOVE_TPL = 'tpxiid%s';
    public const CID_BTN_PAYMENT_RM_ADD_TPL = 'bpriid%s';
    public const CID_PNL_CHARGE_INFO_TPL = 'tcpiid%s';
    public const CID_TXT_CHARGE_NAME_TPL = 'tcniid%s';
    public const CID_TXT_COUPON_ID_TPL = 'hciid%s';
    public const CID_TXT_COUPON_CODE_TPL = 'hccid%s';
    public const CID_HID_CHARGE_ID_TPL = 'iif_charge_id_%s';
    public const CID_PNL_CHARGE_AMOUNT_TPL = 'tcziid%s';
    public const CID_TXT_CHARGE_AMOUNT_TPL = 'tcaiid%s';
    public const CID_HID_CHARGE_TYPE_TPL = 'hctiid%s';
    public const CID_PNL_CHARGE_REMOVE_TPL = 'tcxiid%s';
    public const CID_BTN_CHARGE_REMOVE_TPL = 'bcriid%s';
    public const CID_INVOICE_ITEM_FORM = 'InvoiceItemForm';
    public const CID_BLK_INVOICE_DATE = 'inv_date';
    public const CID_BLK_DATA_GRID = 'c2';
    public const CID_BLK_DATA_GRID_ROW_TPL = 'c2row%s';
    public const CID_BLK_MESSENGER = 'messenger';

    public const CLASS_BLK_BALANCE_DUE = 'balance-due';
    public const CLASS_BLK_Q_DATE_TIME_PICKER = 'qdatetimepicker-ctl';
    public const CLASS_TBL_INVOICE_DETAIL = 'invoice-detail';
    public const CLASS_TXT_RESIZE_TEXTAREA = 'resize-textarea';
    public const CLASS_TXT_RESIZE_TEXTAREA_ACTIVE = 'resize-textarea-active';
    public const CLASS_TXT_PAYMENT_AMOUNT = 'amt-text';
    public const CLASS_BTN_PAYMENT_REMOVE = 'remove-add';
    public const CLASS_PNL_CHARGE_INFO_GENERAL = 'charge-con';
    public const CLASS_LST_PAYMENT_METHOD = 'amt-name-sel';
    public const CLASS_LST_PAYMENT_CREDIT_CARD = 'amt-name-sel';
    public const CLASS_TXT_PAYMENT_NOTE = 'amt-note';
    public const CLASS_CAL_PAYMENT_DATE = 'amt-date';
    public const CLASS_TXT_CHARGE_NAME = 'amt-name';
    public const CLASS_TXT_CHARGE_AMOUNT = 'amt-text';
    public const CLASS_BTN_CHARGE_REMOVE = 'remove-add';
    public const CLASS_BTN_INVOICE_ITEM_SAVE = 'savelink';
    public const CLASS_BTN_INVOICE_ITEM_CANCEL = 'cancellink';
    public const CLASS_TXT_INVOICE_ITEM_TAX_PERCENT = 'textbox ttaxper';
    public const CLASS_LST_INVOICE_ITEM_TAX_APPLICATION = 'listbox ltaxapp';
    public const CLASS_TXT_INVOICE_ITEM_LOT_NAME = 'textbox tlotname';
    public const CLASS_TXT_INVOICE_ITEM_HAMMER_PRICE = 'textbox thammer';
    public const CLASS_TXT_INVOICE_ITEM_BUYERS_PREMIUM = 'textbox tpremium';
}
