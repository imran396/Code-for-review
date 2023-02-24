<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/13/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 =415 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class AuctionListFormConstants
 */
class AuctionListFormConstants
{
    public const CID_LST_ACCOUNT = 'alf14';
    public const CID_BTN_CREATE = 'alf2';
    public const CID_LST_AUCTION_STATUS = 'alf3';
    public const CID_DTG_AUCTIONS = 'alf4';
    public const CID_LST_AUCTION_TYPE = 'alf8';
    public const CID_LST_PUBLISHED = 'alf9';
    public const CID_BTN_DOWNLOAD_BIDS = 'alf10';
    public const CID_DLG_AUCTION_LIST = 'alf11';
    public const CID_TXT_SEARCH_SALE_NO = 'alf12';
    public const CID_BTN_SEARCH_SALE_NO = 'alf13';
    public const CID_TXT_SEARCH_KEYWORD = 'alf15';
    public const CID_BTN_SEARCH_KEYWORD = 'alf16';
    public const CID_BTN_DELETE_TPL = 'btnD%s';
    public const CID_BTN_ARCHIVE_TPL = 'btnA%s';
    public const CID_BTN_UPDATE_TPL = 'btnU%s';
    public const CID_BTN_RESET_TPL = 'btnR%s';
    public const CID_BTN_REOPEN_TPL = 'btnRO%s';
    public const CID_PNL_AUCTIONS_REPORTS = 'alfPnlAuctionsReports';
    public const CID_BLK_SECTION_REPORTS = 'section_reports';
    public const CID_BLK_REPORTS = 'reports';
    public const CID_PNL_AUCTIONS_SEARCH = 'alfPnlAuctionsSearch';
    public const CID_BTN_SECTION_SEARCH = 'section_search1';
    public const CID_BLK_SECTION_SEARCH = 'section_search1_div';
    public const CID_AUCTION_LIST_FORM = 'AuctionListForm';
    public const CID_BLK_AUCTION_LIST_CONTAINER = 'auction-list-container';
    public const CID_DIALOG_MESSAGE = 'dialog-message';

    public const SAS_ACTIVE = 'active';
    public const SAS_CLOSED = 'closed';
    public const SAS_ARCHIVED = 'archived';
    public const SAS_ALL = 'all';
    public const SAS_DEFAULT = self::SAS_ACTIVE;

    /** @var string[] */
    public static array $showAuctionStatuses = [
        self::SAS_ACTIVE,
        self::SAS_CLOSED,
        self::SAS_ARCHIVED,
        self::SAS_ALL,
    ];

    /** @var string[] */
    public static array $showAuctionStatusNames = [
        self::SAS_ACTIVE => 'Active',
        self::SAS_CLOSED => 'Closed',
        self::SAS_ARCHIVED => 'Archived',
        self::SAS_ALL => 'All',
    ];

    public const PF_ALL = 1;
    public const PF_PUBLISHED = 2;
    public const PF_UNPUBLISHED = 3;
    public const PF_DEFAULT = self::PF_ALL;

    /** @var int[] */
    public static array $publishedFilters = [
        self::PF_ALL,
        self::PF_PUBLISHED,
        self::PF_UNPUBLISHED,
    ];

    /** @var string[] */
    public static array $publishedFilterNames = [
        self::PF_ALL => 'All',
        self::PF_PUBLISHED => 'Published only',
        self::PF_UNPUBLISHED => 'Unpublished only',
    ];

    // Css classes for data grid columns at Added and Available lots
    public const CSS_CLASS_DTG_AUCTIONS_COL_CUSTOM_FIELDS_PREFIX = 'a-cf-';
    public const CSS_CLASS_DTG_AUCTIONS_COL_START_DATE = 'a-start-date';
    public const CSS_CLASS_DTG_AUCTIONS_COL_END_DATE = 'a-end-date';
    public const CSS_CLASS_DTG_AUCTIONS_COL_LOTS = 'a-total-lots';
    public const CSS_CLASS_DTG_AUCTIONS_COL_SALE_NUM = 'a-sale-num';
    public const CSS_CLASS_DTG_AUCTIONS_COL_NAME = 'a-name';
    public const CSS_CLASS_DTG_AUCTIONS_COL_STATUS = 'a-status';
    public const CSS_CLASS_DTG_AUCTIONS_COL_TYPE = 'a-type';
    public const CSS_CLASS_DTG_AUCTIONS_COL_PUBLISHED = 'a-published';
    public const CSS_CLASS_DTG_AUCTIONS_COL_ACCOUNT_NAME = 'a-account-name';
    public const CSS_CLASS_DTG_AUCTIONS_COL_ACTIONS = 'a-actions';

    public const CLASS_LNK_RESET = 'resetlink';
}
