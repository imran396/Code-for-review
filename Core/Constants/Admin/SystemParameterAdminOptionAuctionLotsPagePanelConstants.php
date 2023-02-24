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
 * Class SystemParameterAdminOptionAuctionLotsPagePanelConstants
 * @package Sam\Core\Constants\Admin
 */
class SystemParameterAdminOptionAuctionLotsPagePanelConstants
{
    public const CID_LST_LOT_STATUS = 'aof27';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_LST_LOT_STATUS => Constants\Setting::LOT_STATUS,
    ];
}
