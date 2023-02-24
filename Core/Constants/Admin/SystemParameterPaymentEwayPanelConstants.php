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

/**
 * Class SystemParameterPaymentEwayPanelConstants
 */
class SystemParameterPaymentEwayPanelConstants
{
    public const CID_TXT_E_WAY_API_KEY = 'ipf157';
    public const CID_TXT_E_WAY_PASSWORD = 'ipf158';
    public const CID_RAD_E_WAY_ACCOUNT_TYPE = 'ipf159';
    public const CID_CHK_E_WAY_AUTH_USE = 'ipf161';
    public const CID_CHK_CC_PAYMENT_E_WAY = 'ipf162';
    public const CID_BTN_E_WAY_EDIT = 'ipf163';
    public const CID_LBL_E_WAY_API_KEY = 'ipf164';
    public const CID_LBL_E_WAY_PASSWORD = 'ipf165';
    public const CID_LBL_E_WAY_ENCRYPTION_KEY = 'ipflbl197';
    public const CID_TXT_E_WAY_ENCRYPTION_KEY = 'ipf197';
    public const CID_CHK_NO_AUTO_AUTH_TRANSACTION_E_WAY = 'ipf204';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_TXT_E_WAY_API_KEY => Constants\Setting::EWAY_API_KEY,
        self::CID_TXT_E_WAY_PASSWORD => Constants\Setting::EWAY_PASSWORD,
        self::CID_RAD_E_WAY_ACCOUNT_TYPE => Constants\Setting::EWAY_ACCOUNT_TYPE,
        self::CID_CHK_CC_PAYMENT_E_WAY => Constants\Setting::CC_PAYMENT_EWAY,
        self::CID_TXT_E_WAY_ENCRYPTION_KEY => Constants\Setting::EWAY_ENCRYPTION_KEY
    ];
}
