<?php
/**
 * SAM-6914: Move sections' logic to separate Panel classes at Manage settings system parameters invoicing and paymeynt page (/admin/manage-system-parameter/invoicing-and-payment)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Oct 08, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterPaymentOpayoPanelConstants
 */
class SystemParameterPaymentOpayoPanelConstants
{
    public const CID_TXT_OPAYO_VENDOR_NAME = 'ipf186';
    public const CID_LBL_OPAYO_VENDOR_NAME = 'ipflbl186';
    public const CID_BTN_OPAYO_EDIT = 'ipfbtn187';
    public const CID_LST_OPAYO_AVSCV2 = 'ipf188';
    public const CID_LST_OPAYO_3D_SECURE = 'ipf189';
    public const CID_RAD_OPAYO_MODE = 'ipf190';
    public const CID_LST_OPAYO_SEND_EMAIL = 'ipf191';
    public const CID_CHK_CC_PAYMENT_OPAYO = 'ipf192';
    public const CID_CHK_ACH_PAYMENT_OPAYO = 'ipf193';
    public const CID_CHK_OPAYO_TOKEN = 'ipf194';
    public const CID_CHK_OPAYO_AUTH_USE = 'ipf195';
    public const CID_TXT_OPAYO_CURRENCY = 'ipf196';
    public const CID_CHK_NO_AUTO_AUTH_TRANSACTION_OPAYO = 'ipf206';
    public const CID_LST_OPAYO_AUTH_TRANSACTION_TYPE = 'ipf207';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_TXT_OPAYO_VENDOR_NAME => Constants\Setting::OPAYO_VENDOR_NAME,
        self::CID_LST_OPAYO_AVSCV2 => Constants\Setting::OPAYO_AVSCV2,
        self::CID_LST_OPAYO_3D_SECURE => Constants\Setting::OPAYO_3DSECURE,
        self::CID_RAD_OPAYO_MODE => Constants\Setting::OPAYO_MODE,
        self::CID_LST_OPAYO_SEND_EMAIL => Constants\Setting::OPAYO_SEND_EMAIL,
        self::CID_CHK_CC_PAYMENT_OPAYO => Constants\Setting::CC_PAYMENT_OPAYO,
        self::CID_CHK_ACH_PAYMENT_OPAYO => Constants\Setting::ACH_PAYMENT_OPAYO,
        self::CID_CHK_OPAYO_TOKEN => Constants\Setting::OPAYO_TOKEN,
        self::CID_TXT_OPAYO_CURRENCY => Constants\Setting::OPAYO_CURRENCY,
        self::CID_LST_OPAYO_AUTH_TRANSACTION_TYPE => Constants\Setting::OPAYO_AUTH_TRANSACTION_TYPE,
    ];
}
