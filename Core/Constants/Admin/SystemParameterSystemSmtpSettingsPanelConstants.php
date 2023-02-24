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
 * Class SystemParameterSystemSmtpSettingsPanelConstants
 */
class SystemParameterSystemSmtpSettingsPanelConstants
{
    public const CID_TXT_SMTP_SERVER = 'scf5';
    public const CID_TXT_SMTP_PORT = 'scf6';
    public const CID_TXT_SMTP_USERNAME = 'scf7';
    public const CID_TXT_SMTP_PASSWORD = 'scf8';
    public const CID_BTN_SMTP_PASSWORD_EDIT = 'scf10';
    public const CID_LBL_SMTP_PASSWORD = 'scf11';
    public const CID_CHK_SMTP_AUTH = 'scf9';
    public const CID_LST_SMTP_SSL_TYPE = 'scf27';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_TXT_SMTP_SERVER => Constants\Setting::SMTP_SERVER,
        self::CID_TXT_SMTP_PORT => Constants\Setting::SMTP_PORT,
        self::CID_TXT_SMTP_USERNAME => Constants\Setting::SMTP_USERNAME,
        self::CID_TXT_SMTP_PASSWORD => Constants\Setting::SMTP_PASSWORD,
        self::CID_CHK_SMTP_AUTH => Constants\Setting::SMTP_AUTH,
        self::CID_LST_SMTP_SSL_TYPE => Constants\Setting::SMTP_SSL_TYPE,
    ];
}
