<?php
/**
 * SAM-6914: Move sections' logic to separate Panel classes at Manage settings system parameters invoicing and paymeynt page (/admin/manage-system-parameter/invoicing-and-payment)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Oct 11, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterPaymentSmartPayPanelConstants
 */
class SystemParameterPaymentSmartPayPanelConstants
{
    public const CID_RAD_SMART_TYPE = 'ipf103';
    public const CID_RAD_SMART_MERCHANT_MODE = 'ipf116';
    public const CID_TXT_SMART_SKIN_CODE = 'ipf104';
    public const CID_RAD_SMART_MODE = 'ipf105';
    public const CID_LBL_SMART_LOGIN = 'ipf107';
    public const CID_CHK_SMART_PAYMENTS = 'ipf115';
    public const CID_TXT_SMART_MERCHANT_ACCOUNT = 'ipf108';
    public const CID_TXT_SMART_SHARED_SECRET = 'ipf109';
    public const CID_LBL_SMART_MERCHANT_ACCOUNT = 'ipflbl108';
    public const CID_LBL_SMART_SHARED_SECRET = 'ipflbl109';
    public const CID_BTN_SMART_EDIT = 'ipfbtn109';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_RAD_SMART_TYPE => Constants\Setting::SMART_PAY_ACCOUNT_TYPE,
        self::CID_RAD_SMART_MERCHANT_MODE => Constants\Setting::SMART_PAY_MERCHANT_MODE,
        self::CID_TXT_SMART_SKIN_CODE => Constants\Setting::SMART_PAY_SKIN_CODE,
        self::CID_RAD_SMART_MODE => Constants\Setting::SMART_PAY_MODE,
        self::CID_CHK_SMART_PAYMENTS => Constants\Setting::ENABLE_SMART_PAYMENTS,
        self::CID_TXT_SMART_MERCHANT_ACCOUNT => Constants\Setting::SMART_PAY_MERCHANT_ACCOUNT,
        self::CID_TXT_SMART_SHARED_SECRET => Constants\Setting::SMART_PAY_SHARED_SECRET,
    ];
}
