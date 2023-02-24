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
 * Class SystemParameterAdminOptionDateTimezonePanelConstants
 * @package Sam\Core\Constants\Admin
 */
class SystemParameterAdminOptionDateTimezonePanelConstants
{
    public const CID_TXT_TIMEZONE = 'aof2';
    public const CID_LST_DEFAULT_COUNTRY = 'aof3';
    public const CID_LST_ADMIN_DATE_FORMAT = 'aof28';
    public const CID_LST_LOCALE = 'aof5';
    public const CID_LST_ADMIN_LANGUAGE = 'aof6';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_TXT_TIMEZONE => Constants\Setting::TIMEZONE_ID,
        self::CID_LST_DEFAULT_COUNTRY => Constants\Setting::DEFAULT_COUNTRY,
        self::CID_LST_ADMIN_DATE_FORMAT => Constants\Setting::ADMIN_DATE_FORMAT,
        self::CID_LST_LOCALE => Constants\Setting::LOCALE,
        self::CID_LST_ADMIN_LANGUAGE => Constants\Setting::ADMIN_LANGUAGE
    ];
}
