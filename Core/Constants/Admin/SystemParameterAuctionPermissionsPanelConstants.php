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
 * Class SystemParameterAuctionPermissionsPanelConstants
 */
class SystemParameterAuctionPermissionsPanelConstants
{
    public const CID_LST_AUCTION_VISIBILITY_ACCESS = 'scf69';
    public const CID_LST_AUCTION_INFO_ACCESS = 'scf70';
    public const CID_LST_AUCTION_CATALOG_ACCESS = 'scf71';
    public const CID_LST_LIVE_VIEW_ACCESS = 'scf72';
    public const CID_LST_LOT_DETAILS_ACCESS = 'scf73';
    public const CID_LST_LOT_BIDDING_HISTORY_ACCESS = 'scf74';
    public const CID_LST_LOT_BIDDING_INFO_ACCESS = 'scf76';
    public const CID_LST_LOT_WINNING_BID_ACCESS = 'scf101';
    public const CID_LST_LOT_STARTING_BID_ACCESS = 'scf105';
    public const CID_CHK_HIDE_UNSOLD_LOTS = 'scf111';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_LST_AUCTION_VISIBILITY_ACCESS => Constants\Setting::AUCTION_VISIBILITY_ACCESS,
        self::CID_LST_AUCTION_INFO_ACCESS => Constants\Setting::AUCTION_INFO_ACCESS,
        self::CID_LST_AUCTION_CATALOG_ACCESS => Constants\Setting::AUCTION_CATALOG_ACCESS,
        self::CID_LST_LIVE_VIEW_ACCESS => Constants\Setting::LIVE_VIEW_ACCESS,
        self::CID_LST_LOT_DETAILS_ACCESS => Constants\Setting::LOT_DETAILS_ACCESS,
        self::CID_LST_LOT_BIDDING_HISTORY_ACCESS => Constants\Setting::LOT_BIDDING_HISTORY_ACCESS,
        self::CID_LST_LOT_BIDDING_INFO_ACCESS => Constants\Setting::LOT_BIDDING_INFO_ACCESS,
        self::CID_LST_LOT_WINNING_BID_ACCESS => Constants\Setting::LOT_WINNING_BID_ACCESS,
        self::CID_LST_LOT_STARTING_BID_ACCESS => Constants\Setting::LOT_STARTING_BID_ACCESS,
        self::CID_CHK_HIDE_UNSOLD_LOTS => Constants\Setting::HIDE_UNSOLD_LOTS,
    ];
}
