<?php
/**
 * SAM-9991: Move sections' logic to separate Panel classes at Manage settings system parameters admin options page (/admin/manage-system-parameter/admin-option)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           01-09, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterAdminOptionInventoryPagePanelConstants
 * @package Sam\Core\Constants\Admin
 */
class SystemParameterAdminOptionInventoryPagePanelConstants
{
    public const CID_HID_LOT_FIELD_ORDER = 'aof22';
    public const CID_LNK_LOT_DEFAULT_ORDER = 'aof23';
    public const CID_DTG_LOT_FIELD_CONFIG = 'aof24';
    public const CID_CHK_ITEM_NUM_LOCK = 'aoip1';
    public const CID_CHK_VISIBLE_TPL = '%schk%s';
    public const CID_HID_CONFIG_TPL = '%shid%s';
    public const CID_CHK_REQUIRED_TPL = '%schkReq%s';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_CHK_ITEM_NUM_LOCK => Constants\Setting::ITEM_NUM_LOCK,
    ];
}
