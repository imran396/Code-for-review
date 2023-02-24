<?php
/**
 * SAM-6914: Move sections' logic to separate Panel classes at Manage settings system parameters invoicing and paymeynt page (/admin/manage-system-parameter/invoicing-and-payment)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Oct 09, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

class SystemParameterPaymentPayTracePanelConstants
{
    public const CID_TXT_PAY_TRACE_USERNAME = 'ipf146';
    public const CID_TXT_PAY_TRACE_PASSWORD = 'ipf147';
    public const CID_RAD_PAY_TRACE_MODE = 'ipf148';
    public const CID_CHK_PAY_TRACE_CIM = 'ipf149';
    public const CID_CHK_PAY_TRACE_AUTH_USE = 'ipf150';
    public const CID_CHK_CC_PAYMENT_PAY_TRACE = 'ipf151';
    public const CID_BTN_PAY_TRACE_EDIT = 'ipf153';
    public const CID_LBL_PAY_TRACE_USERNAME = 'ipf154';
    public const CID_LBL_PAY_TRACE_PASSWORD = 'ipf155';
    public const CID_CHK_NO_AUTO_AUTH_TRANSACTION_PAY_TRACE = 'ipf203';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_TXT_PAY_TRACE_USERNAME => Constants\Setting::PAY_TRACE_USERNAME,
        self::CID_TXT_PAY_TRACE_PASSWORD => Constants\Setting::PAY_TRACE_PASSWORD,
        self::CID_RAD_PAY_TRACE_MODE => Constants\Setting::PAY_TRACE_MODE,
        self::CID_CHK_PAY_TRACE_CIM => Constants\Setting::PAY_TRACE_CIM,
        self::CID_CHK_CC_PAYMENT_PAY_TRACE => Constants\Setting::CC_PAYMENT_PAY_TRACE,
    ];
}
