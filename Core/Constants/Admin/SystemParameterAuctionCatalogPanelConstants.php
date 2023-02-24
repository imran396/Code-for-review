<?php
/**
 * SAM-10007: Move sections' logic to separate Panel classes at Manage settings system parameters auction page (/admin/manage-system-parameter/auction)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Nov 28, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterAuctionCatalogPanelConstants
 */
class SystemParameterAuctionCatalogPanelConstants
{
    public const CID_CHK_DISPLAY_QUANTITY = 'scf45';
    public const CID_LST_ITEMS_PER_PAGE = 'scf39';
    public const CID_CHK_HAMMER_BP = 'uof46';
    public const CID_CHK_LST_AUCTION_STATUSES = 'spbf3';
    public const CID_LST_AUCTION_LINK_DESTINATION = 'spbf71';
    public const CID_CHK_USE_ALTERNATE_PDF_CATALOG = 'scf88';
    public const CID_CHK_ADD_DESCRIPTION_IN_LOT_NUM = 'scf102';
    public const CID_CHK_SHOW_WINNER_IN_CATALOG = 'scf68';
    public const CID_CHK_RESERVE_NOT_MET_NOTICE = 'uof123';
    public const CID_CHK_RESERVE_MET_NOTICE = 'uof124';
    public const CID_TXT_QUANTITY_DIGITS = 'uof125';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_CHK_DISPLAY_QUANTITY => Constants\Setting::DISPLAY_QUANTITY,
        self::CID_TXT_QUANTITY_DIGITS => Constants\Setting::QUANTITY_DIGITS,
        self::CID_LST_ITEMS_PER_PAGE => Constants\Setting::ITEMS_PER_PAGE,
        self::CID_CHK_HAMMER_BP => Constants\Setting::HAMMER_PRICE_BP,
        self::CID_CHK_LST_AUCTION_STATUSES => Constants\Setting::VISIBLE_AUCTION_STATUSES,
        self::CID_LST_AUCTION_LINK_DESTINATION => Constants\Setting::AUCTION_LINKS_TO,
        self::CID_CHK_USE_ALTERNATE_PDF_CATALOG => Constants\Setting::USE_ALTERNATE_PDF_CATALOG,
        self::CID_CHK_ADD_DESCRIPTION_IN_LOT_NUM => Constants\Setting::ADD_DESCRIPTION_IN_LOT_NAME_COLUMN,
        self::CID_CHK_SHOW_WINNER_IN_CATALOG => Constants\Setting::SHOW_WINNER_IN_CATALOG,
        self::CID_CHK_RESERVE_NOT_MET_NOTICE => Constants\Setting::RESERVE_NOT_MET_NOTICE,
        self::CID_CHK_RESERVE_MET_NOTICE => Constants\Setting::RESERVE_MET_NOTICE,
    ];
}
