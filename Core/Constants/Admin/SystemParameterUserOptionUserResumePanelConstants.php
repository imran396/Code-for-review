<?php
/**
 * SAM-9992: Move sections' logic to separate Panel classes at Manage settings system parameters users options page (/admin/manage-system-parameter/user-option)
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
 * Class SystemParameterUserOptionUserResumePanelConstants
 */
class SystemParameterUserOptionUserResumePanelConstants
{
    public const CID_CHK_USER_RESUME = 'uof24';
    public const CID_LST_SHOW_USER_RESUME = 'uof25';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_CHK_USER_RESUME => Constants\Setting::ENABLE_USER_RESUME,
        self::CID_LST_SHOW_USER_RESUME => Constants\Setting::SHOW_USER_RESUME,
    ];

    public const CLASS_BLK_ENABLE_RESUME = 'enable-resume';
}
