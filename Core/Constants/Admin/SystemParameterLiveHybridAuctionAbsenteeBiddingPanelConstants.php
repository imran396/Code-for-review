<?php
/**
 * SAM-10008: Move sections' logic to separate Panel classes at Manage settings system parameters live/hybrid auction page (/admin/manage-system-parameter/live-hybrid-auction)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 02, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterLiveHybridAuctionAbsenteeBiddingPanelConstants
 */
class SystemParameterLiveHybridAuctionAbsenteeBiddingPanelConstants
{
    public const CID_RAD_LIVE_ABSENTEE_BID = 'scf42';
    public const CID_CHK_ABOVE_STARTING_BID = 'uof50';
    public const CID_CHK_ABOVE_RESERVE = 'uof44';
    public const CID_CHK_NOTIFY_ABSENTEE_BIDDERS = 'uof21';
    public const CID_CHK_SUGGESTED_STARTING_BID = 'apf124';
    public const CID_CHK_NO_LOWER_MAX_BID = 'apf123';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_RAD_LIVE_ABSENTEE_BID => Constants\Setting::ABSENTEE_BIDS_DISPLAY,
        self::CID_CHK_ABOVE_STARTING_BID => Constants\Setting::ABOVE_STARTING_BID,
        self::CID_CHK_ABOVE_RESERVE => Constants\Setting::ABOVE_RESERVE,
        self::CID_CHK_NOTIFY_ABSENTEE_BIDDERS => Constants\Setting::NOTIFY_ABSENTEE_BIDDERS,
        self::CID_CHK_SUGGESTED_STARTING_BID => Constants\Setting::SUGGESTED_STARTING_BID,
        self::CID_CHK_NO_LOWER_MAX_BID => Constants\Setting::NO_LOWER_MAXBID
    ];
}
