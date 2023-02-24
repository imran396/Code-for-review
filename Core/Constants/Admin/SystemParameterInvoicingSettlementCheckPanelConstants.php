<?php
/**
 * SAM-9969: Check Printing for Settlements: Implement check settings management
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 23, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterInvoicingSettlementCheckPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class SystemParameterInvoicingSettlementCheckPanelConstants
{
    public const CID_CHK_STLM_CHECK_FEATURE_ENABLED = 'chkStlmCheckFeatureEnabled';
    public const CID_FLA_CHECK_FILE = 'ipf167';
    public const CID_TXT_NAME_COORDINATES_LEFT = 'ipf168';
    public const CID_TXT_NAME_COORDINATES_TOP = 'ipf169';
    public const CID_TXT_AMOUNT_COORDINATES_LEFT = 'ipf170';
    public const CID_TXT_AMOUNT_COORDINATES_TOP = 'ipf171';
    public const CID_TXT_DATE_COORDINATES_LEFT = 'ipf172';
    public const CID_TXT_DATE_COORDINATES_TOP = 'ipf173';
    public const CID_TXT_AMOUNT_SPELLING_COORDINATES_LEFT = 'ipc3';
    public const CID_TXT_AMOUNT_SPELLING_COORDINATES_TOP = 'ipc4';
    public const CID_TXT_MEMO_COORDINATES_LEFT = 'ipc5';
    public const CID_TXT_MEMO_COORDINATES_TOP = 'ipc6';
    public const CID_TXT_ADDRESS_COORDINATES_LEFT = 'ipc7';
    public const CID_TXT_ADDRESS_COORDINATES_TOP = 'ipc8';
    public const CID_TXT_HEIGHT = 'ipc9';
    public const CID_TXT_PER_PAGE = 'ipc10';
    public const CID_TXT_REPEAT_COUNT = 'ipc11';
    public const CID_TXT_CHECK_ADDRESS_TEMPLATE = 'ipf128';
    public const CID_TXT_CHECK_PAYEE_TEMPLATE = 'ipf129';
    public const CID_TXT_CHECK_MEMO_TEMPLATE = 'ipf125';

    public const CID_BTN_APPLY_SAMPLE_CHECK_ADDRESS_TEMPLATE = 'apply-sample-check-address-tpl';
    public const CID_BTN_RESTORE_CONTENT_CHECK_ADDRESS_TEMPLATE = 'restore-default-check-address-tpl';
    public const CID_BTN_APPLY_SAMPLE_CHECK_PAYEE_TEMPLATE = 'apply-sample-check-payee-tpl';
    public const CID_BTN_RESTORE_CONTENT_CHECK_PAYEE_TEMPLATE = 'restore-default-check-payee-tpl';
    public const CID_BTN_APPLY_SAMPLE_CHECK_MEMO_TEMPLATE = 'apply-sample-check-memo-tpl';
    public const CID_BTN_RESTORE_CONTENT_CHECK_MEMO_TEMPLATE = 'restore-default-check-memo-tpl';
    public const CID_BLK_SETTLEMENT_CHECK = 'settlement-check';

    public const CSS_CLASS_FOR_FEATURE_ENABLED = 'for-feature-enabled';

    // If feature enabled
    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_CHK_STLM_CHECK_FEATURE_ENABLED => Constants\Setting::STLM_CHECK_ENABLED,
        self::CID_FLA_CHECK_FILE => Constants\Setting::STLM_CHECK_FILE,
        self::CID_TXT_NAME_COORDINATES_LEFT => Constants\Setting::STLM_CHECK_NAME_COORD_X,
        self::CID_TXT_NAME_COORDINATES_TOP => Constants\Setting::STLM_CHECK_NAME_COORD_Y,
        self::CID_TXT_AMOUNT_COORDINATES_LEFT => Constants\Setting::STLM_CHECK_AMOUNT_COORD_X,
        self::CID_TXT_AMOUNT_COORDINATES_TOP => Constants\Setting::STLM_CHECK_AMOUNT_COORD_Y,
        self::CID_TXT_AMOUNT_SPELLING_COORDINATES_LEFT => Constants\Setting::STLM_CHECK_AMOUNT_SPELLING_COORD_X,
        self::CID_TXT_AMOUNT_SPELLING_COORDINATES_TOP => Constants\Setting::STLM_CHECK_AMOUNT_SPELLING_COORD_Y,
        self::CID_TXT_DATE_COORDINATES_LEFT => Constants\Setting::STLM_CHECK_DATE_COORD_X,
        self::CID_TXT_DATE_COORDINATES_TOP => Constants\Setting::STLM_CHECK_DATE_COORD_Y,
        self::CID_TXT_MEMO_COORDINATES_LEFT => Constants\Setting::STLM_CHECK_MEMO_COORD_X,
        self::CID_TXT_MEMO_COORDINATES_TOP => Constants\Setting::STLM_CHECK_MEMO_COORD_Y,
        self::CID_TXT_ADDRESS_COORDINATES_LEFT => Constants\Setting::STLM_CHECK_ADDRESS_COORD_X,
        self::CID_TXT_ADDRESS_COORDINATES_TOP => Constants\Setting::STLM_CHECK_ADDRESS_COORD_Y,
        self::CID_TXT_HEIGHT => Constants\Setting::STLM_CHECK_HEIGHT,
        self::CID_TXT_PER_PAGE => Constants\Setting::STLM_CHECK_PER_PAGE,
        self::CID_TXT_REPEAT_COUNT => Constants\Setting::STLM_CHECK_REPEAT_COUNT,
        self::CID_TXT_CHECK_ADDRESS_TEMPLATE => Constants\Setting::STLM_CHECK_ADDRESS_TEMPLATE,
        self::CID_TXT_CHECK_PAYEE_TEMPLATE => Constants\Setting::STLM_CHECK_PAYEE_TEMPLATE,
        self::CID_TXT_CHECK_MEMO_TEMPLATE => Constants\Setting::STLM_CHECK_MEMO_TEMPLATE,
    ];

    // If feature disabled
    public const PANEL_TO_PROPERTY_MAP_IF_DISABLED = [
        self::CID_CHK_STLM_CHECK_FEATURE_ENABLED => Constants\Setting::STLM_CHECK_ENABLED,
    ];
}
