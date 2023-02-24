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
 * Class SystemParameterLiveHybridAuctionOtherPanelConstants
 */
class SystemParameterLiveHybridAuctionOtherPanelConstants
{
    public const CID_CHK_SHOW_PORT_NOTICE = 'scf11';
    public const CID_TXT_SIGNUP_TRACK_CODE = 'scf12';
    public const CID_TXT_DELAY_AFTER_BID_ACCEPTED = 'scf130';
    public const CID_TXT_DELAY_SOLD_ITEM = 'apf117';
    public const CID_TXT_DELAY_BLOCK_SELL = 'apf118';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_CHK_SHOW_PORT_NOTICE => Constants\Setting::SHOW_PORT_NOTICE,
        self::CID_TXT_SIGNUP_TRACK_CODE => Constants\Setting::SIGNUP_TRACKING_CODE,
        self::CID_TXT_DELAY_AFTER_BID_ACCEPTED => Constants\Setting::DELAY_AFTER_BID_ACCEPTED,
        self::CID_TXT_DELAY_SOLD_ITEM => Constants\Setting::DELAY_SOLD_ITEM,
        self::CID_TXT_DELAY_BLOCK_SELL => Constants\Setting::DELAY_BLOCK_SELL,
    ];
}
