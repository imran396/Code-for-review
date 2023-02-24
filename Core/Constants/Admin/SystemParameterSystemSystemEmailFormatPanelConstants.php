<?php
/**
 * SAM-9990: Move sections' logic to separate Panel classes at Manage settings system parameters system configuration page (/admin/manage-system-parameter/system)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Nov 27, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterSystemSystemEmailFormatPanelConstants
 */
class SystemParameterSystemSystemEmailFormatPanelConstants
{
    public const CID_RAD_EMAIL_FORMAT = 'scf19';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_RAD_EMAIL_FORMAT => Constants\Setting::EMAIL_FORMAT,
    ];
}
