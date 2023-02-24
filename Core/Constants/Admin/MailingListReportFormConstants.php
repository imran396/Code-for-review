<?php
/**
 *
 * SAM-4748: Mailing List Template management classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-07
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class MailingListReportFormConstants
 * @package Sam\Core\Constants\Admin
 */
class MailingListReportFormConstants
{
    public const CID_LST_ACCOUNT = 'mlrf1';
    public const CID_CAL_DATE_FROM = 'mlrf2';
    public const CID_CAL_DATE_TO = 'mlrf3';
    public const CID_RAD_USER_TYPE = 'mlrf4';
    public const CID_AUTOCOMPLETE_AUCTION = 'mlrf5';
    public const CID_TXT_MONEY_SPENT_FROM = 'mlrf6';
    public const CID_TXT_MONEY_SPENT_TO = 'mlrf7';
    public const CID_BTN_SAVE = 'mlrf8';
    public const CID_TXT_NAME = 'mlrf9';
    public const CID_DTG_MAILING_LISTS = 'mlrf10';
    public const CID_CHKT_CATEGORIES = 'mlrf11'; // don't use underscore, it will break \QTreeNav::GetItem()
    public const CID_BTN_CANCEL_EDIT = 'mlrf12';
    public const CID_BTN_CREATE_NEW = 'mlrf13';
    public const CID_LBL_MONEY_SPENT_ERROR = 'mlrf14';
    public const CID_DTG_USERS = 'mlvr1';
    public const CID_BTN_ACTION_D_TPL = 'btnD%s';
    public const CID_BLK_AUCTION_LIST_CONTAINER = 'auction-list-container';
}
