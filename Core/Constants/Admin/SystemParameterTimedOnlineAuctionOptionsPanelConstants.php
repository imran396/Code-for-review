<?php
/**
 * SAM-10077: Move sections' logic to separate Panel classes at Manage settings system parameters timed online auction page (/admin/manage-system-parameter/timed-online-auction)
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
 * Class SystemParameterTimedOnlineAuctionOptionsPanelConstants
 */
class SystemParameterTimedOnlineAuctionOptionsPanelConstants
{
    public const CID_CHK_NEXT_BID = 'sptoa3';
    public const CID_CHK_SHOW_AUCTION_START_ENCODING = 'aof11';
    public const CID_TXT_EXTEND_TIME = 'aof13';
    public const CID_CHK_TIMED_ABOVE_STARTING_BID = 'aof15';
    public const CID_CHK_TIMED_ABOVE_RESERVE = 'aof16';
    public const CID_CHK_ALLOW_FORCE_BID = 'aof17';
    public const CID_CHK_TAKE_MAX_BIDS_UNDER_RESERVE = 'aof18';
    public const CID_TXT_EXP_SECT = 'aof14';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_CHK_NEXT_BID => Constants\Setting::NEXT_BID_BUTTON,
        self::CID_CHK_SHOW_AUCTION_START_ENCODING => Constants\Setting::SHOW_AUCTION_STARTS_ENDING,
        self::CID_TXT_EXTEND_TIME => Constants\Setting::EXTEND_TIME_TIMED,
        self::CID_CHK_TIMED_ABOVE_STARTING_BID => Constants\Setting::TIMED_ABOVE_STARTING_BID,
        self::CID_CHK_TIMED_ABOVE_RESERVE => Constants\Setting::TIMED_ABOVE_RESERVE,
        self::CID_CHK_ALLOW_FORCE_BID => Constants\Setting::ALLOW_FORCE_BID,
        self::CID_CHK_TAKE_MAX_BIDS_UNDER_RESERVE => Constants\Setting::TAKE_MAX_BIDS_UNDER_RESERVE
    ];
}
