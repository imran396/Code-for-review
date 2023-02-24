<?php
/**
 * SAM-9795: Check Printing for Settlements: Implementation of html layout and view layer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           10-26, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Core\Constants\Admin;


/**
 * Class SettlementCheckListFormConstants
 * @package Sam\Core\Constants\Admin
 */
class SettlementCheckListFormConstants
{
    public const CID_BLK_APPLY_PAYMENT = 'blkApplyPayment';
    public const CID_BLK_BUTTON_ACTIONS = 'btn-actions-block';
    public const CID_BLK_EXPORT_CSV = 'blkCsvExport';
    public const CID_BLK_MARK_CLEARED = 'blkMarkCleared';
    public const CID_BLK_MARK_POSTED = 'blkMarkPosted';
    public const CID_BLK_MARK_POSTED_AND_APPLY_AS_PAYMENT = 'blkMarkPostedAndApplyAsPayment';
    public const CID_BLK_MARK_VOIDED = 'blkMarkVoided';
    public const CID_BLK_PRINT = 'blkPrint';
    public const CID_BTN_ADD_CHECK = 'btnAddCheck';
    public const CID_BTN_APPLY_AS_PAYMENT = 'btnApplyAsPayment';
    public const CID_BTN_DELETE_SETTLEMENT_CHECK_TPL = '%s_btnDelSettlementCheck%s';
    public const CID_BTN_EXPORT_CSV = 'btnExportCsv';
    public const CID_BTN_MARK_CLEARED = 'btnMarkCleared';
    public const CID_BTN_MARK_POSTED = 'btnMarkPosted';
    public const CID_BTN_MARK_POSTED_AND_APPLY_AS_PAYMENT = 'btnMarkPostedAndApplyAsPayment';
    public const CID_BTN_MARK_VOID = 'btnMarkVoid';
    public const CID_BTN_PRINT_CHECKS = 'btnPrintChecks';
    public const CID_CHK_CHOOSE_ALL_ON_PAGE = 'chkChooseAllOnPage';
    public const CID_CHK_SETTLEMENT_CHECK_TPL = '%schkChoose%s';
    public const CID_SETTLEMENT_CHECK_ACTIONS_WRAPPER = 'settlementCheckActionsWrapper';
    public const CID_DTG_SETTLEMENT_CHECK_LIST = 'dtgSettlementCheckList';
    public const CID_ICO_WAIT_TPL = 'icoWait_%s';
    public const CID_PNL_FILTER = 'pnlFilter';
    public const CID_SETTLEMENT_CHECK_LIST_FORM = 'SettlementCheckListForm'; // used at JS only! Do not remove! Auto-generated ID by QCodo.
    public const CID_TXT_STARTING_CHECK_NO_FOR_PRINT_CHECKS = 'txtStartingCheckNoForPrintChecks';

    // Css classes
    public const CSS_CHECK_STATUS_CLEARED = 'check-status-cleared';
    public const CSS_CHECK_STATUS_CREATED = 'check-status-created';
    public const CSS_CHECK_STATUS_NONE = 'check-status-none';
    public const CSS_CHECK_STATUS_POSTED = 'check-status-posted';
    public const CSS_CHECK_STATUS_PRINTED = 'check-status-printed';
    public const CSS_CHECK_STATUS_VOIDED = 'check-status-voided';
    public const CSS_CHK_SETTLEMENT_CHECK = 'chk-settlement-check';
    public const CSS_COLUMN_ACTIONS = 'column-actions'; // used at JS
    public const CSS_DISABLED = 'disabled'; // used at JS
    public const CSS_ENABLED = 'enabled'; // used at JS
}
