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
 * Class SystemParameterUserOptionAdditionalConfirmationsPanelConstants
 */
class SystemParameterUserOptionAdditionalConfirmationsPanelConstants
{
    public const CID_BTN_SIGNUP_CONFIRMATION_ADD = 'uof39';
    public const CID_DTG_SIGNUP_CONFIRMATIONS = 'uof43';
    public const CID_TXT_SIGNUP_CONFIRMATION_CONTENT = 'uof40';
    public const CID_BTN_SIGNUP_CONFIRMATION_SAVE = 'uof41';
    public const CID_BTN_SIGNUP_CONFIRMATION_CANCEL = 'uof42';
    public const CID_CHK_NEWS_LETTER = 'uof50';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_TXT_SIGNUP_CONFIRMATION_CONTENT => Constants\Setting::REG_CONFIRM_PAGE_CONTENT,
        self::CID_CHK_NEWS_LETTER => Constants\Setting::NEWSLETTER_OPTION,
    ];
}
