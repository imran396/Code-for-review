<?php
/**
 * SAM-9795: Check Printing for Settlements: Implementation of html layout and view layer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           10-31, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Core\Constants\Admin;


/**
 * Class SettlementCheckEditFormConstants
 * @package Sam\Core\Constants\Admin
 */
class SettlementCheckEditFormConstants
{
    public const CID_TXT_CHECK_NO = 'txtCheckNo';
    public const CID_TXT_PAYEE = 'txtPayee';
    public const CID_TXT_AMOUNT = 'txtAmount';
    public const CID_TXT_AMOUNT_SPELLING = 'txtAmountSpelling';
    public const CID_TXT_MEMO = 'txtMemo';
    public const CID_TXT_NOTE = 'txtNote';
    public const CID_TXT_ADDRESS = 'txtAddress';
    public const CID_LBL_CREATED_DATE = 'lblCreatedDate';
    public const CID_LBL_PRINTED_DATE = 'lblPrintedDate';
    public const CID_LBL_VOIDED_DATE = 'lblVoidedDate';
    public const CID_CAL_POSTED_DATE = 'calPostedDate';
    public const CID_CAL_CLEARED_DATE = 'calClearedDate';
    public const CID_BTN_APPLY_AS_PAYMENT = 'btnApplyAsPayment';
    public const CID_BTN_SAVE = 'btnSave';
    public const CID_BTN_SAVE_AND_EXIT = 'btnSaveAndExit';
    public const CID_BTN_SAVE_AND_ADD_MORE = 'btnSaveAndAddMore';
    public const CID_BTN_SAVE_AND_PRINT = 'btnSaveAndPrint';
    public const CID_BTN_CANCEL = 'btnCancel';
    public const CID_BTN_POPULATE_PAYEE = 'btnPopulatePayee';
    public const CID_BTN_POPULATE_AMOUNT = 'btnPopulateAmount';
    public const CID_BTN_POPULATE_AMOUNT_SPELLING = 'btnPopulateAmountSpelling';
    public const CID_BTN_POPULATE_MEMO = 'btnPopulateMemo';
    public const CID_BTN_POPULATE_ADDRESS = 'btnPopulateAddress';
    public const CID_BTN_CLEAR_PRINTED_DATE = 'btnClearPrintedDateAndSave';
    public const CID_BTN_CLEAR_VOIDED_DATE = 'btnClearVoidedDateAndSave';
    public const CID_BTN_MARK_VOIDED = 'btnMarkVoided';
    public const CID_BTN_DELETE = 'btnDelete';
    public const CID_BLK_BUTTON_ACTIONS_ONE = 'btn-actions-one-block';
    public const CID_BLK_BUTTON_ACTIONS_TWO = 'btn-actions-two-block';
    public const CID_ICO_WAIT_TPL = 'icoWait_%s';
    public const CID_ICO_WAIT_SAVE = 'icoWaitSave';
    public const CID_ICO_WAIT_SAVE_TWO = 'icoWaitSaveTwo';

    public const CSS_BTN_POPULATE_PAYEE = 'btn-populate-payee';
    public const CSS_BTN_POPULATE_AMOUNT = 'btn-populate-amount';
    public const CSS_BTN_POPULATE_AMOUNT_SPELLING = 'btn-populate-amount-spelling';
    public const CSS_BTN_POPULATE_MEMO = 'btn-populate-memo';
    public const CSS_BTN_POPULATE_ADDRESS = 'btn-populate-address';
    public const CSS_BTN_CLEAR_DATE_PRINTED = 'btn-clear-date-printed';
    public const CSS_BTN_CLEAR_DATE_VOIDED = 'btn-clear-date-voided';
    public const CSS_DISABLED = 'disabled';
}
