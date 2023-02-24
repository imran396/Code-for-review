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
 * Class SystemParameterLiveHybridAuctionSoundSettingsPanelConstants
 */
class SystemParameterLiveHybridAuctionSoundSettingsPanelConstants
{
    public const CID_FLA_PLACE_BID_SOUND = 'apf107';
    public const CID_FLA_BID_ACCEPTED_SOUND = 'apf108';
    public const CID_FLA_OUT_BID_SOUND = 'apf109';
    public const CID_FLA_SOLD_WON_SOUND = 'apf110';
    public const CID_FLA_SOLD_NOT_WON_SOUND = 'apf111';
    public const CID_FLA_PASSED_SOUND = 'apf112';
    public const CID_FLA_FAIR_WARNING_SOUND = 'apf113';
    public const CID_FLA_ONLINE_BID_INCOMING_ON_ADMIN_SOUND = 'apf127';
    public const CID_FLA_BID_SOUND = 'apf128';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_FLA_PLACE_BID_SOUND => Constants\Setting::PLACE_BID_SOUND,
        self::CID_FLA_BID_ACCEPTED_SOUND => Constants\Setting::BID_ACCEPTED_SOUND,
        self::CID_FLA_OUT_BID_SOUND => Constants\Setting::OUT_BID_SOUND,
        self::CID_FLA_SOLD_WON_SOUND => Constants\Setting::SOLD_WON_SOUND,
        self::CID_FLA_SOLD_NOT_WON_SOUND => Constants\Setting::SOLD_NOT_WON_SOUND,
        self::CID_FLA_PASSED_SOUND => Constants\Setting::PASSED_SOUND,
        self::CID_FLA_FAIR_WARNING_SOUND => Constants\Setting::FAIR_WARNING_SOUND,
        self::CID_FLA_ONLINE_BID_INCOMING_ON_ADMIN_SOUND => Constants\Setting::ONLINE_BID_INCOMING_ON_ADMIN_SOUND,
        self::CID_FLA_BID_SOUND => Constants\Setting::BID_SOUND,
    ];
}
