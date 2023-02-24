<?php
/**
 * SAM-10079: Move sections' logic to separate Panel classes at Manage settings system parameters integrations page (/admin/manage-system-parameter/integration)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 04, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterIntegrationWavebidPanelConstants
 */
class SystemParameterIntegrationWavebidPanelConstants
{
    public const CID_TXT_WAVEBID_ENDPOINT = 'aiWbEndpointTxt';
    public const CID_BTN_WAVEBID_UAT_EDIT = 'aiWbUatEditBtn';
    public const CID_BTN_WAVE_BID_UAT_TEST = 'aiWbUatTestBtn';
    public const CID_LBL_WAVEBID_UAT = 'aiWbUatLbl';
    public const CID_TXT_WAVEBID_UAT = 'aiWbUatTxt';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_TXT_WAVEBID_ENDPOINT => Constants\Setting::WAVEBID_ENDPOINT,
        self::CID_TXT_WAVEBID_UAT => Constants\Setting::WAVEBID_UAT
    ];
}
