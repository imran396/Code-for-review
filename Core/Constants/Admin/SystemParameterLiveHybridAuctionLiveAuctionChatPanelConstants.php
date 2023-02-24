<?php
/**
 * SAM-10008: Move sections' logic to separate Panel classes at Manage settings system parameters live/hybrid auction page (/admin/manage-system-parameter/live-hybrid-auction)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 03, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterLiveHybridAuctionLiveAuctionChatPanelConstants
 */
class SystemParameterLiveHybridAuctionLiveAuctionChatPanelConstants
{
    public const CID_CHK_LIVE_CHAT = 'uof48';
    public const CID_CHK_LIVE_CHAT_VIEW_ALL = 'uof49';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_CHK_LIVE_CHAT => Constants\Setting::LIVE_CHAT,
        self::CID_CHK_LIVE_CHAT_VIEW_ALL => Constants\Setting::LIVE_CHAT_VIEW_ALL,
    ];
}
