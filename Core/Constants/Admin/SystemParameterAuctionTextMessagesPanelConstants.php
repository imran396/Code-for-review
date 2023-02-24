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
 * Class SystemParameterAuctionTextMessagesPanelConstants
 */
class SystemParameterAuctionTextMessagesPanelConstants
{
    public const CID_CHK_TEXT_MSG_ENABLED = 'c1';
    public const CID_TXT_TEXT_MSG_API_URL = 'c2';
    public const CID_TXT_TEXT_MSG_API_POST_VAR = 'c3';
    public const CID_TXT_TEXT_MSG_API_NOTIFICATION = 'c4';
    public const CID_TXT_TEXT_MSG_API_OUTBID_NOTIFICATION = 'c5';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_CHK_TEXT_MSG_ENABLED => Constants\Setting::TEXT_MSG_ENABLED,
        self::CID_TXT_TEXT_MSG_API_URL => Constants\Setting::TEXT_MSG_API_URL,
        self::CID_TXT_TEXT_MSG_API_POST_VAR => Constants\Setting::TEXT_MSG_API_POST_VAR,
        self::CID_TXT_TEXT_MSG_API_NOTIFICATION => Constants\Setting::TEXT_MSG_API_NOTIFICATION,
        self::CID_TXT_TEXT_MSG_API_OUTBID_NOTIFICATION => Constants\Setting::TEXT_MSG_API_OUTBID_NOTIFICATION,
    ];
}
