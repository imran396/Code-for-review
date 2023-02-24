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
 * Class LotItemsPanelConstants
 */
class LotItemsPanelConstants
{
    public const CID_LST_ACCOUNT = 'al29';
    public const CID_ICO_WAIT_SEARCH = 'al14';
    public const CID_TXT_SEARCH_KEY = 'al15';
    public const CID_BTN_SEARCH = 'al16';
    public const CID_LST_CATEGORY = 'al17';
    public const CID_LST_ACCOUNT_FILTER = 'al34';
    public const CID_HID_AUCTION_FILTER = 'al35';
    public const CID_TXT_CONSIGNOR = 'al30';
    public const CID_HID_CONSIGNOR = 'al31';
    public const CID_LST_STATUS_FILTER = 'al19';
    public const CID_DTG_UNASSIGNED_ITEMS = 'al20';
    public const CID_CHK_CHOOSE_ALL = 'al21';
    public const CID_PER_PAGE_SELECTOR = 'al23';
    public const CID_BTN_RESET = 'al25';
    public const CID_BTN_DELETE = 'al26';
    public const CID_LBL_CHOOSE_ALL = 'alf29';
    public const CID_LST_SORT = 'al32';
    public const CID_LST_SORT_DIRECTION = 'al33';
    public const CID_BTN_ADD_ITEM = 'al36';
    public const CID_CHK_CHOOSE_ITEMS_TPL = '%schk%s';
    public const CID_BTN_ACTIVE_ITEMS = 'section_items';
    public const CID_BTN_SEARCH_FILTER = 'section_search';
    public const CID_BLK_AUCTION_LIST_CONTAINER = 'auction-list-container';

    public const CLASS_BLK_AUCTION_LOT_LIST = 'auction-lot-list';
    public const CLASS_BLK_SEARCH_FILTERS = 'searchfilters';
    public const CLASS_BLK_SEARCH_OPTIONS = 'search-options';
}
