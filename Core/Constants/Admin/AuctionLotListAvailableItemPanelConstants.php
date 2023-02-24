<?php
/**
 * SAM-6780 : Move sections' logic to separate Panel classes at Manage auction lots page (/admin/manage-auctions/lots/id/%s)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           01-05, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Core\Constants\Admin;


/**
 * Class AuctionLotListAvailableItemPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class AuctionLotListAvailableItemPanelConstants
{
    public const CID_BLK_SEARCH_AUCTION_LIST_CONTAINER = 'search-auction-list-container';
    public const CID_BTN_ASSIGN = 'alf24';
    public const CID_BTN_ASSIGN_ON_TOP = 'alf175';
    public const CID_BTN_RESET_UNNASIGNED_LOT_FILTER = 'alf122';
    public const CID_BTN_SEARCH_UNASSIGNED_LOT = 'al121';
    public const CID_BTN_SECTION_SEARCH = 'section_search2';
    public const CID_CHK_CHOOSE_ITEM_TPL = 'availableChooseLotChk%s';
    public const CID_CHK_UNASSIGNED_CHOOSE_ALL = 'alf23';
    public const CID_CUSTOM_FIELD_GROUP_FOR_AVAILABLE_LOTS = 'alf88';
    public const CID_DLG_CONFIRM_AUCTION_LOT_DATETIME_TWO = 'alf57_2';
    public const CID_DTG_UNASSIGNED_ITEMS = 'alf19';
    public const CID_HID_AUCTION_AVAILABLE = 'alf176';
    public const CID_HID_UNASSIGNED_LOT_CONSIGNOR_FILTER = 'alf92';
    public const CID_LBL_UNASSIGNED_CHOOSE_ALL = 'alf22';
    public const CID_LBL_UNASS_REPORT = 'alf29';
    public const CID_LNK_OPEN_UNASSIGNED_LOT_SECTION = 'sectionItems2';
    public const CID_LST_UNASSIGNED_LOT_BILLING_STATUS = 'alf80';
    public const CID_LST_UNASSIGNED_LOT_CATEGORY_FILTER = 'alf16';
    public const CID_LST_UNASSIGNED_LOT_STATUS = 'alf18';
    public const CID_TXT_HIDDEN = 'alf74';
    public const CID_TXT_SEARCH_KEY = 'alf14';
    public const CID_TXT_UNASSIGNED_LOT_CONSIGNOR_FILTER = 'alf91';
    public const CID_UNASSIGNED_ITEMS_PAGINATOR_ID = 'avDtgPaginator';
    public const CID_UNASSIGNED_ITEMS_PER_PAGE_SELECTOR_ID = 'avPerPageSelector';
    public const DTG_UNASSIGNED_ITEMS_URL_QUERY_PREFIX = 'av';
    public const CID_ICO_WAIT_ASSIGN_LOTS = 'icoWaitAssignLots';
    public const CID_ICO_WAIT_ASSIGN_ON_TOP_LOTS = 'icoWaitAssignOnTopLots';

    // Css classes for data grid columns at Available lots
    public const CSS_CLASS_DTG_LOTS_COL_CREATED_ON = 'li-created-on';
    public const CSS_CLASS_DTG_LOTS_COL_RECENT_AUCTION = 'li-recent-auction';

    public const CLASS_BLK_AUCTION_LOT_LIST = 'auction-lot-list';
    public const CLASS_BLK_AUCTION_LOT_LIST_2 = 'auction-lot-list2';
    public const CLASS_CHK_UNASSIGNED = 'unassigned';
}
