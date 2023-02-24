<?php
/**
 * SAM-10078: Move sections' logic to separate Panel classes at Manage settings system parameters hybrid auction page (/admin/manage-system-parameter/hybrid-auction)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 04, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterHybridAuctionOptionsPanelConstants
 */
class SystemParameterHybridAuctionOptionsPanelConstants
{
    public const CID_TXT_GAP_TIME = 'sphaGapTime';
    public const CID_TXT_EXTEND_TIME = 'spha3';
    public const CID_TXT_FAIR_WARNINGS = 'spha4';
    public const CID_TXT_PENDING_ACTION_TIMEOUT = 'spha5';
    public const CID_CHK_RESET_TIMER_ON_UNDO = 'spha7';
    public const CID_CHK_ALLOW_BIDDING_DURING_START_GAP_HYBRID = 'spha8';
    public const CID_TXT_EXP_SECT = 'spha6';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_TXT_GAP_TIME => Constants\Setting::LOT_START_GAP_TIME_HYBRID,
        self::CID_TXT_EXTEND_TIME => Constants\Setting::EXTEND_TIME_HYBRID,
        self::CID_TXT_FAIR_WARNINGS => Constants\Setting::FAIR_WARNINGS_HYBRID,
        self::CID_TXT_PENDING_ACTION_TIMEOUT => Constants\Setting::PENDING_ACTION_TIMEOUT_HYBRID,
        self::CID_CHK_RESET_TIMER_ON_UNDO => Constants\Setting::RESET_TIMER_ON_UNDO_HYBRID,
        self::CID_CHK_ALLOW_BIDDING_DURING_START_GAP_HYBRID => Constants\Setting::ALLOW_BIDDING_DURING_START_GAP_HYBRID
    ];
}
