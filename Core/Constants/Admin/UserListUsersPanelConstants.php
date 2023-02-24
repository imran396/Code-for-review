<?php
/**
 * SAM-6908: Move sections' logic to separate Panel classes at Manage Users page (/admin/manage-users)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 18, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;


class UserListUsersPanelConstants
{
    public const CID_LST_ACCOUNT = 'ulf26';
    public const CID_ICO_WAIT_FOR_BTN_SHOW_ALL = 'ulf2-0';
    public const CID_ICO_WAIT_FOR_BTN_REMOVE_CHECK = 'ulf2-1';
    public const CID_ICO_WAIT_FOR_BTN_EXPORT_MAILS = 'ulf2-2';
    public const CID_TXT_SEARCH_KEY = 'ulf3';
    public const CID_BTN_SEARCH = 'ulf4';
    public const CID_BTN_SHOW_ALL = 'ulf5';
    public const CID_CHK_BIDDERS = 'ulf6';
    public const CID_CHK_CONSIGNORS = 'ulf7';
    public const CID_CHK_ADMINS = 'ulf8';
    public const CID_CHK_NONE = 'ulf801';
    public const CID_TXT_CUSTOMER_FILTER = 'ulf27';
    public const CID_TXT_COMPANY_NAME = 'ulf28';
    public const CID_TXT_POSTAL_CODE = 'ulf29';
    public const CID_TXT_EMAIL = 'ulf30';
    public const CID_DTG_USERS = 'ulf10';
    public const CID_DTG_USERS_PER_PAGE_SELECTOR_ID = 'ulfDtgPerPageSelector';
    public const CID_CHK_CHECK_ALL_IN_PAGE = 'ulf12';
    public const CID_BTN_REMOVE_CHECK = 'ulf14';
    public const CID_BTN_EXPORT_MAILS = 'ulf15';
    public const CID_LBL_USER_REPORT = 'ulf19';
    public const CID_BTN_RESET = 'ulf20';
    public const CID_DLG_USER_EMAIL = 'ulf21';
    public const CID_LBL_REPORT = 'ulf22';
    public const CID_CHK_PENDING_RESELLER_APPROVAL = 'pendResApproval';
    public const CID_CHK_ANY_STATUS_RESELLERS = 'anyStatusRes';
    public const CID_CHK_CHECK_BOX_TPL = '%scha%s';
    public const CID_LNK_RESELLER_CERT_UNAPPROVE_TPL = 'lnkResellerCertUnapprove%s';
    public const CID_BLK_RESELLER_CERT_UNAPPROVE_TPL = 'blkResellerCertUnapprove%s';
    public const CID_LNK_RESELLER_CERT_APPROVE_TPL = 'lnkResellerCertApprove%s';
    public const CID_BLK_RESELLER_CERT_APPROVE_TPL = 'blkResellerCertApprove%s';
    public const CID_BLK_REPORT = 'chkReport';
    public const CID_BLK_USER_CUSTOM_FIELD_TPL = 'ucf%s';

    // Css classes for data grid columns at Added and Available lots
    public const CSS_CLASS_DTG_USERS_COL_SELECT = 'u-select';
    public const CSS_CLASS_DTG_USERS_COL_CUSTOMER_NUM = 'u-customer-num';
    public const CSS_CLASS_DTG_USERS_COL_USERNAME = 'u-username';
    public const CSS_CLASS_DTG_USERS_COL_EMAIL = 'u-email';
    public const CSS_CLASS_DTG_USERS_COL_FIRST_NAME = 'u-first-name';
    public const CSS_CLASS_DTG_USERS_COL_LAST_NAME = 'u-last-name';
    public const CSS_CLASS_DTG_USERS_COL_REGISTRATION_DATE = 'u-registration-date';
    public const CSS_CLASS_DTG_USERS_COL_VERIFIED = 'u-verified';
    public const CSS_CLASS_DTG_USERS_COL_FLAG = 'u-flag';
    public const CSS_CLASS_DTG_USERS_COL_ACCOUNT_NAME = 'u-account-name';
    public const CSS_CLASS_DTG_USERS_COL_RESELLER_CERT = 'u-reseller-cert';
    public const CSS_CLASS_DTG_USERS_COL_ACTIONS = 'u-actions';

    public const CLASS_BLK_ALERT = 'alert';
    public const CLASS_BLK_TABLE_SEARCH = 'tablesearch';
    public const CLASS_BLK_USER_LIST_SEARCH = 'user-list-search';
    public const CLASS_CHK_USER_SELECT = 'user-sel';
    public const CLASS_LNK_ITEMS = 'items';
    public const CLASS_LNK_SEARCH = 'search';

    public const CID_BTN_DELETE_USER_TPL = 'delUserBtn%s';
}
