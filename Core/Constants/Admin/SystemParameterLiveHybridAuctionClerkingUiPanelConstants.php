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
 * Class SystemParameterLiveHybridAuctionClerkingUiPanelConstants
 */
class SystemParameterLiveHybridAuctionClerkingUiPanelConstants
{
    public const CID_TXT_LIVE_BIDDING_COUNTDOWN = 'scf129';
    public const CID_LST_ONLINE_BID_BUTTON_INFO = 'aof15';
    public const CID_CHK_AUTO_CREATE_FLOOR_RECORD = 'aof9';
    public const CID_CHK_FLOOR_BIDDER_DROP_DOWN = 'apf120';
    public const CID_CHK_CONDITIONAL_SALES = 'apf126';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_TXT_LIVE_BIDDING_COUNTDOWN => Constants\Setting::LIVE_BIDDING_COUNTDOWN,
        self::CID_LST_ONLINE_BID_BUTTON_INFO => Constants\Setting::ONLINEBID_BUTTON_INFO,
        self::CID_CHK_AUTO_CREATE_FLOOR_RECORD => Constants\Setting::AUTO_CREATE_FLOOR_BIDDER_RECORD,
        self::CID_CHK_FLOOR_BIDDER_DROP_DOWN => Constants\Setting::FLOOR_BIDDERS_FROM_DROPDOWN,
        self::CID_CHK_CONDITIONAL_SALES => Constants\Setting::CONDITIONAL_SALES,
    ];
}
