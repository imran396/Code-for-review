<?php
/**
 * SAM-4696: Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @author        Oleg Kovalov
 * @since         Jan 15, 2019
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Core\Constants\Responsive;

/**
 * Class MyAlertListConstants
 * @package Sam\Core\Constants\Responsive
 */
class MyAlertListConstants
{
    public const CID_TXT_KEY = 'mal01';
    public const CID_HID_CATEGORIES = 'mal02a';
    public const CID_LST_SORT_CRITERIA = 'mal03';
    public const CID_TXT_TITLE = 'mal10';
    public const CID_BTN_RESET_FIELDS = 'mal11';
    public const CID_BTN_SAVE_SEARCH = 'mal13';
    public const CID_LST_CAT_MATCH = 'mal14';
    public const CID_LST_AUCTIONEER = 'mal15';
    public const CID_HID_SELECTED_AUCTION_TYPES = 'maSelAucTypes';

    public const SORT_CRITERIA_NEWEST = 'newest';
    public const SORT_CRITERIA_TIMELEFT = 'timeleft';
    public const SORT_CRITERIA_HIGHEST = 'highest';
    public const SORT_CRITERIA_LOWEST = 'lowest';
    public const SORT_CRITERIA_DISTANCE = 'distance';

    public const RETURN_TYPE_LINK_ONLY = 'link-only';
    public const RETURN_TYPE_BUTTON = 'button';
    public const CID_BTN_SEARCH_TPL = 'btnDoSearch%s';

    public const CID_CHK_HYBRID = 'advsHybrid';
    public const CID_CHK_MY_SEARCH_LIVE = 'myhSearchLive';
    public const CID_CHK_MY_SEARCH_TIMED = 'mySearchTimed';
    public const CID_CHK_MY_SEARCH_REGULAR = 'mySearchRegular';
    public const CID_CHK_MY_SEARCH_BUY_NOW = 'mySearchBuyNow';
    public const CID_CHK_MY_SEARCH_MAKE_OFFER = 'mySearchMakeOffer';
    public const CID_CHK_MY_SEARCH_EXCLUSIVE_CLOSED = 'mySearchExclusiveClosed';
    public const CID_BLK_SCN_OPEN = 'scnopen';
    public const CID_BLK_MY_SEARCH_AUCTION_TYPES = 'mysearch-auc-types';
    public const CID_BLK_SIGN = 'sign_fst';
    public const CID_MY_ALERTS_LIST = 'MyAlertsList';
    public const CID_LST_MY_SEARCH_ITEM_TPL = 'mysearch-item-%s';

    public const CLASS_BTN_HIDE_BTN = 'hidebtn';
    public const CLASS_BTN_SHOW = 'show';
    public const CLASS_BTN_SHOW_1 = 'show1';
    public const CLASS_BTN_SHOW_BTN = 'showbtn';
    public const CLASS_BLK_MAX_LIMIT_MESSAGE = 'maxLimitMessage';
    public const CLASS_BLK_MORE_SEC = 'moresec';
    public const CLASS_LST_COMBOBOX = 'combobox';
    public const CLASS_LST_CUSTOM_SELECT = 'customselect';
}
