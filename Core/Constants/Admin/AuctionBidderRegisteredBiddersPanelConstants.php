<?php
/**
 * SAM-6857: Move sections' logic to separate Panel classes at Manage auction bidders page (/admin/manage-auctions/bidders/id/%s)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           01-11, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Core\Constants\Admin;


use Sam\View\Admin\Form\AuctionBidderForm\Assigned\AuctionBidderAssignedConstants;

/**
 * Class AuctionBidderRegisteredBiddersPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class AuctionBidderRegisteredBiddersPanelConstants
{
    public const CID_ICO_WAIT_REG = 'ab1';
    public const CID_TXT_ASSIGNED_SEARCH_KEY = 'ab2';
    public const CID_BTN_ASSIGNED_SEARCH = 'ab3';
    public const CID_DTG_AUCTION_BIDDERS = 'ab4';
    public const CID_CHK_ASSIGNED_CHOOSE_ALL = 'ab5';
    public const CID_BTN_ASSIGNED_REMOVE = 'ab6';
    public const CID_BTN_ADD_NEW_BIDDER = 'ab16';
    public const CID_LBL_ASSIGNED_CHOOSE_ALL = 'ab18';
    public const CID_LBL_ASSIGNED_SEARCH_RESET = 'ab120';
    public const CID_BTN_EMAIL_USER = 'ab22';
    public const CID_BTN_WAVE_BID_POST = 'abWaveBidPost';
    public const CID_DLG_EMAIL_ASSIGNED_USERS = 'ab23';
    public const CID_LBL_ERROR = 'ab24';
    public const CID_LST_BIDDER = 'ab33';
    public const CID_BTN_AUTHORIZE_USER = 'ab28';
    public const DTG_AUCTION_BIDDERS_URL_QUERY_PREFIX = 'rb';
    public const CID_DTG_AUCTION_BIDDERS_PER_PAGE_SELECTOR_ID = 'abDtgPerPageSelector';
    public const CID_CHK_REGISTERED_BIDDER_TPL = 'chkRegisteredBidder%s';
    public const CID_BTN_EDIT_BIDDER_NO_TPL = '%sbte%s';
    public const CID_BTN_APPROVE_TPL = '%sbta%s';
    public const CID_BTN_SAVE = 'ab8';
    public const CID_BTN_CANCEL = 'ab9';
    public const CID_TXT_BIDDER_NUM = 'ab7';
    public const CID_BTN_REGISTERED_BIDDER_SECTION = 'registeredBidderSection';

    public const CLASS_CHK_REG_LIST = 'reg-list';
    public const CLASS_BLK_REG_BIDDER_ACTION = 'reg_bidder_actions';
    public const CLASS_BLK_AUCTION_BIDDER_LIST = 'auction-bidder-list';

    // Approve status filters
    public const ASF_ALL = AuctionBidderAssignedConstants::ASF_ALL;
    public const ASF_APPROVED = AuctionBidderAssignedConstants::ASF_APPROVED;
    public const ASF_UNAPPROVED = AuctionBidderAssignedConstants::ASF_UNAPPROVED;
    public const ASF_DEFAULT = self::ASF_ALL;
    /** @var string[] */
    public const APPROVE_STATUS_FILTER_NAMES = [
        self::ASF_ALL => 'All',
        self::ASF_APPROVED => 'Approved',
        self::ASF_UNAPPROVED => 'Unapproved',
    ];
}
