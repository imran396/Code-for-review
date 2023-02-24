<?php
/**
 * SAM-6857: Move sections' logic to separate Panel classes at Manage auction bidders page (/admin/manage-auctions/bidders/id/%s)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 12, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class AuctionBidderRemainingUsersPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class AuctionBidderRemainingUsersPanelConstants
{
    public const CID_ICO_WAIT = 'ab10';
    public const CID_TXT_UNASSIGNED_SEARCH_KEY = 'ab11';
    public const CID_TXT_CUSTOMER = 'ab27';
    public const CID_TXT_COMPANY_NAME = 'ab29';
    public const CID_TXT_POSTAL_CODE = 'ab30';
    public const CID_TXT_EMAIL = 'ab31';
    public const CID_BTN_UNASSIGNED_SEARCH = 'ab12';
    public const CID_DTG_UNASSIGNED_USERS = 'ab13';
    public const CID_DTG_UNASSIGNED_USERS_PER_PAGE_SELECTOR_ID = 'usrDtgPerPageSelector';
    public const CID_CHK_UNASSIGNED_CHOOSE_ALL = 'ab14';
    public const CID_BTN_UNASSIGNED_USER_REGISTER = 'ab17';
    public const CID_LBL_UNASSIGNED_CHOOSE_ALL = 'ab119';
    public const CID_BTN_UNASSIGNED_SEARCH_RESET = 'ab121';
    public const CID_DLG_AUTO_APPROVE_CONFIRMATION = 'ab25';
    public const CID_CHK_TPL = '%schk%s';
    public const CID_LNK_BTN_UNASSIGNED_USER_SECTION = 'unassignedUserSection';
    public const DTG_UNASSIGNED_USERS_URL_QUERY_PREFIX = 'ru';

    public const CLASS_CHK_USERS_LIST = 'usr-list';
    public const CLASS_BLK_AUCTION_USERS_LIST = 'auction-user-list';
}
