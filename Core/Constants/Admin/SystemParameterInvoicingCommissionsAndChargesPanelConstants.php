<?php
/**
 * SAM-6914: Move sections' logic to separate Panel classes at Manage settings system parameters invoicing and paymeynt page (/admin/manage-system-parameter/invoicing-and-payment)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           01-25, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;


/**
 * Class SystemParameterInvoicingCommissionsAndChargesPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class SystemParameterInvoicingCommissionsAndChargesPanelConstants
{
    public const CID_TXT_CASH_DISCOUNT = 'ipf4';
    public const CID_TXT_DEFAULT_POST_AUC_IMPORT_PREMIUM = 'ipf124';
    public const CID_CHK_CHARGE_CONSIGN_COM = 'ipf37';
    public const CID_TXT_SHIPPING_CHARGE = 'ipf5';
    public const CID_TXT_PROCESSING_CHARGE = 'ipf6';
    public const CID_CHK_AUTO_INVOICE = 'ipf8';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_TXT_CASH_DISCOUNT => Constants\Setting::CASH_DISCOUNT,
        self::CID_TXT_DEFAULT_POST_AUC_IMPORT_PREMIUM => Constants\Setting::DEFAULT_POST_AUC_IMPORT_PREMIUM,
        self::CID_CHK_CHARGE_CONSIGN_COM => Constants\Setting::CHARGE_CONSIGNOR_COMMISSION,
        self::CID_TXT_SHIPPING_CHARGE => Constants\Setting::SHIPPING_CHARGE,
        self::CID_TXT_PROCESSING_CHARGE => Constants\Setting::PROCESSING_CHARGE,
        self::CID_CHK_AUTO_INVOICE => Constants\Setting::AUTO_INVOICE,
    ];
}
