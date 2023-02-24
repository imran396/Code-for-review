<?php
/**
 * SAM-9991: Move sections' logic to separate Panel classes at Manage settings system parameters admin options page (/admin/manage-system-parameter/admin-option)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           01-11, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterAdminOptionUserInfoPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class SystemParameterAdminOptionUserInfoPanelConstants
{
    public const CID_LST_SHARE_USER_INFO = 'aof25';
    public const CID_CHK_SHARE_USER_STATS = 'aof26';
    public const CID_CHK_ALLOW_ADD_FLOOR_BIDDER = 'chkAllowAddFloorBidder';
    public const CID_CHK_ALLOW_MAKE_BIDDER_PREFERRED = 'chkAllowMakeBidderPreferred';
    public const CID_BLK_ALLOW_MAKE_BIDDER_PREFERRED = 'blkAllowMakeBidderPreferred';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_LST_SHARE_USER_INFO => Constants\Setting::SHARE_USER_INFO,
        self::CID_CHK_SHARE_USER_STATS => Constants\Setting::SHARE_USER_STATS,
        self::CID_CHK_ALLOW_ADD_FLOOR_BIDDER => Constants\Setting::ALLOW_ACCOUNT_ADMIN_ADD_FLOOR_BIDDER,
        self::CID_CHK_ALLOW_MAKE_BIDDER_PREFERRED => Constants\Setting::ALLOW_ACCOUNT_ADMIN_MAKE_BIDDER_PREFERRED,
    ];
}
