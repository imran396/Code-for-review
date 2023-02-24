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
 * Class SystemParameterUserOptionPasswordSecurityPanelConstants
 */
class SystemParameterUserOptionPasswordSecurityPanelConstants
{
    public const CID_TXT_MINIMUM_LENGTH = 'uof82';
    public const CID_TXT_MIN_LETTERS = 'uof83';
    public const CID_TXT_MIN_NUMBERS = 'uof84';
    public const CID_TXT_MIN_SPECIAL_CHARACTERS = 'uof85';
    public const CID_CHK_REQUIRE_MIXED_CASE = 'uof88';
    public const CID_TXT_MAX_SEQUENTIAL_LETTERS = 'uof89';
    public const CID_TXT_MAX_CONSECUTIVE_LETTERS = 'uof90';
    public const CID_TXT_MAX_SEQUENTIAL_NUMBERS = 'uof91';
    public const CID_TXT_MAX_CONSECUTIVE_NUMBERS = 'uof92';
    public const CID_LST_RENEW_PASSWORD = 'uof93';
    public const CID_LST_HISTORY_REPEAT = 'uof94';
    public const CID_TXT_TMP_TIMEOUT = 'uof95';
    public const CID_TXT_ATT_LOCKOUT_TIMEOUT = 'uof96';
    public const CID_TXT_ATTS_TIME_INCREMENT = 'uof97';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_TXT_MINIMUM_LENGTH => Constants\Setting::PW_MIN_LEN,
        self::CID_TXT_MIN_LETTERS => Constants\Setting::PW_MIN_LETTER,
        self::CID_TXT_MIN_NUMBERS => Constants\Setting::PW_MIN_NUM,
        self::CID_TXT_MIN_SPECIAL_CHARACTERS => Constants\Setting::PW_MIN_SPECIAL,
        self::CID_CHK_REQUIRE_MIXED_CASE => Constants\Setting::PW_REQ_MIXED_CASE,
        self::CID_TXT_MAX_SEQUENTIAL_LETTERS => Constants\Setting::PW_MAX_SEQ_LETTER,
        self::CID_TXT_MAX_CONSECUTIVE_LETTERS => Constants\Setting::PW_MAX_CONSEQ_LETTER,
        self::CID_TXT_MAX_SEQUENTIAL_NUMBERS => Constants\Setting::PW_MAX_SEQ_NUM,
        self::CID_TXT_MAX_CONSECUTIVE_NUMBERS => Constants\Setting::PW_MAX_CONSEQ_NUM,
        self::CID_LST_RENEW_PASSWORD => Constants\Setting::PW_RENEW,
        self::CID_LST_HISTORY_REPEAT => Constants\Setting::PW_HISTORY_REPEAT,
        self::CID_TXT_TMP_TIMEOUT => Constants\Setting::PW_TMP_TIMEOUT,
        self::CID_TXT_ATT_LOCKOUT_TIMEOUT => Constants\Setting::FAILED_LOGIN_ATTEMPT_LOCKOUT_TIMEOUT,
        self::CID_TXT_ATTS_TIME_INCREMENT => Constants\Setting::FAILED_LOGIN_ATTEMPT_TIME_INCREMENT
    ];

    public const CLASS_BLK_INDICATOR = 'indicator';
    public const CLASS_BLK_INDICATOR_TEXT = 'indicator-text';
    public const CLASS_BLK_PASSWORD_INDICATOR = 'password-security';
}
