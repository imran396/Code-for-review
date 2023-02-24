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
 * Class SystemParameterInvoicingTaxesPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class SystemParameterInvoicingTaxesPanelConstants
{
    public const CID_ICO_WAIT_TAX_STATE = 'ipf119';
    public const CID_CHK_TAX_SERVICES = 'ipf117';
    public const CID_LST_TAX_SERVICES_COUNTRY = 'ipf118';
    public const CID_PNL_TAX_STATE = 'ipf120';
    public const CID_BTN_ADD_TAX_STATE = 'ipf121';
    public const CID_TXT_SALES_TAX = 'ipf3';
    public const CID_CHK_DEFAULT_LOT_NO_OOS = 'ipf144';
    public const CID_CHK_SALES_TAX_SERVICES = 'ipf145';
    public const CID_TAX_COUNTRY_STATE_TPL = 'lstcs%s';
    public const CID_BTN_RM_TAX_STATE_TPL = 'btnrcs%s';
    public const CID_PNL_REMOVE_TAX_STATE_TPL = 'pnlbrcs%s';
    public const CID_RAD_TAX_APPLICATION = 'ipf67';
    public const CID_LST_HP_TAX_SCHEMA = 'ipf-hp-tax-schema';
    public const CID_LST_BP_TAX_SCHEMA = 'ipf-bp-tax-schema';
    public const CID_LST_SERVICES_TAX_SCHEMA = 'ipf-services-tax-schema';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_CHK_TAX_SERVICES => Constants\Setting::SAM_TAX,
        self::CID_LST_TAX_SERVICES_COUNTRY => Constants\Setting::SAM_TAX_DEFAULT_COUNTRY,
        self::CID_TXT_SALES_TAX => Constants\Setting::SALES_TAX,
        self::CID_CHK_DEFAULT_LOT_NO_OOS => Constants\Setting::DEFAULT_LOT_ITEM_NO_TAX_OOS,
        self::CID_CHK_SALES_TAX_SERVICES => Constants\Setting::SALES_TAX_SERVICES,
        self::CID_RAD_TAX_APPLICATION => Constants\Setting::INVOICE_ITEM_SALES_TAX_APPLICATION,
        self::CID_LST_HP_TAX_SCHEMA => Constants\Setting::INVOICE_HP_TAX_SCHEMA_ID,
        self::CID_LST_BP_TAX_SCHEMA => Constants\Setting::INVOICE_BP_TAX_SCHEMA_ID,
        self::CID_LST_SERVICES_TAX_SCHEMA => Constants\Setting::INVOICE_SERVICES_TAX_SCHEMA_ID,
    ];

    public const CLASS_BLK_TAX_COUNTRY_STATE = 'tax-country-state';
}
