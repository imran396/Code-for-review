<?php
/**
 * SAM-6914: Move sections' logic to separate Panel classes at Manage settings system parameters invoicing and paymeynt page (/admin/manage-system-parameter/invoicing-and-payment)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Oct 06, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParametersInvoicingAndPaymentPayPalConstants
 */
class SystemParameterPaymentPayPalPanelConstants
{
    public const CID_CHK_PAY_PAL_PAYMENTS = 'ipf30';
    public const CID_TXT_PAY_PAL_EMAIL = 'ipf31';
    public const CID_LBL_PAY_PAL_EMAIL = 'ipflbl31';
    public const CID_TXT_PAY_PAL_TOKEN = 'ipf68';
    public const CID_LBL_PAY_PAL_TOKEN = 'ipflbl68';
    public const CID_BTN_PAY_PAL_EDIT = 'ipfbtn68';
    public const CID_TXT_PAY_PAL_NOTE = 'ipf69';
    public const CID_RAD_PAY_PAL_TYPE = 'ipf72';
    public const CID_TXT_PAY_PAL_BN_CODE = 'ipf174';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_CHK_PAY_PAL_PAYMENTS => Constants\Setting::ENABLE_PAYPAL_PAYMENTS,
        self::CID_TXT_PAY_PAL_EMAIL => Constants\Setting::PAYPAL_EMAIL,
        self::CID_TXT_PAY_PAL_TOKEN => Constants\Setting::PAYPAL_IDENTITY_TOKEN,
        self::CID_RAD_PAY_PAL_TYPE => Constants\Setting::PAYPAL_ACCOUNT_TYPE,
        self::CID_TXT_PAY_PAL_BN_CODE => Constants\Setting::PAYPAL_BN_CODE,
    ];

    public const CLASS_BLK_ENABLE_PAYPAL = 'enable-paypal';
}
