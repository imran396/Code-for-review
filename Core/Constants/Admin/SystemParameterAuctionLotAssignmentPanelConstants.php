<?php
/**
 * SAM-10007: Move sections' logic to separate Panel classes at Manage settings system parameters auction page (/admin/manage-system-parameter/auction)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 01, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterAuctionLotAssignmentPanelConstants
 */
class SystemParameterAuctionLotAssignmentPanelConstants
{
    public const CID_CHK_HIDE_MOVE_TO_SALE = 'scf84';
    public const CID_RAD_ASSIGNED_LOTS_RESTRICTION = 'scf85';
    public const CID_LBL_RESTRICT_LOTS = 'scf86';
    public const CID_CHK_BLOCK_SOLD_LOTS = 'scf87';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_CHK_HIDE_MOVE_TO_SALE => Constants\Setting::HIDE_MOVETOSALE,
        self::CID_RAD_ASSIGNED_LOTS_RESTRICTION => Constants\Setting::ASSIGNED_LOTS_RESTRICTION,
        self::CID_CHK_BLOCK_SOLD_LOTS => Constants\Setting::BLOCK_SOLD_LOTS,
    ];
}
