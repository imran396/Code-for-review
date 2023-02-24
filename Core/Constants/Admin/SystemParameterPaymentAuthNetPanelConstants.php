<?php
/**
 * SAM-6914: Move sections' logic to separate Panel classes at Manage settings system parameters invoicing and paymeynt page (/admin/manage-system-parameter/invoicing-and-payment)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Oct 07, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterPaymentAuthNetPanelConstants
 */
class SystemParameterPaymentAuthNetPanelConstants
{
    public const CID_TXT_AUTH_NET_LOGIN = 'ipf32';
    public const CID_TXT_AUTH_NET_TRAN_KEY = 'ipf33';
    public const CID_RAD_AUTH_NET_MODE = 'ipf34';
    public const CID_RAD_AUTH_NET_TYPE = 'ipf35';
    public const CID_CHK_ACH_PAYMENT = 'ipf36';
    public const CID_CHK_CC_PAYMENT = 'ipf38';
    public const CID_CHK_AUTH_NET_CIM = 'ipf52';
    public const CID_BTN_AUTH_NET_EDIT = 'ipf55';
    public const CID_LBL_AUTH_NET_LOGIN = 'ipf56';
    public const CID_LBL_AUTH_NET_TRAN_KEY = 'ipf57';
    public const CID_CHK_NET_AUTH_USE = 'ipf73';
    public const CID_CHK_NO_AUTO_AUTH_TRANSACTION_AUTH = 'ipf200';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_TXT_AUTH_NET_LOGIN => Constants\Setting::AUTH_NET_LOGIN,
        self::CID_TXT_AUTH_NET_TRAN_KEY => Constants\Setting::AUTH_NET_TRANKEY,
        self::CID_RAD_AUTH_NET_MODE => Constants\Setting::AUTH_NET_MODE,
        self::CID_RAD_AUTH_NET_TYPE => Constants\Setting::AUTH_NET_ACCOUNT_TYPE,
        self::CID_CHK_ACH_PAYMENT => Constants\Setting::ACH_PAYMENT,
        self::CID_CHK_CC_PAYMENT => Constants\Setting::CC_PAYMENT,
        self::CID_CHK_AUTH_NET_CIM => Constants\Setting::AUTH_NET_CIM,
    ];
}
