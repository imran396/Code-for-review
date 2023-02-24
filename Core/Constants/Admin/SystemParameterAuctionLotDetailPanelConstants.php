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
 * Class SystemParameterAuctionLotDetailPanelConstants
 */
class SystemParameterAuctionLotDetailPanelConstants
{
    public const CID_LST_SHIPPING_INFO_BOX = 'scf38';
    public const CID_CHK_ABSENTEE_BID_NOTIFY = 'scf67';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_LST_SHIPPING_INFO_BOX => Constants\Setting::SHIPPING_INFO_BOX,
        self::CID_CHK_ABSENTEE_BID_NOTIFY => Constants\Setting::ABSENTEE_BID_LOT_NOTIFICATION,
    ];
}
