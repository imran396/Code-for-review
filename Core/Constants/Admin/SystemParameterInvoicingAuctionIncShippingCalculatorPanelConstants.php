<?php
/**
 * SAM-6914: Move sections' logic to separate Panel classes at Manage settings system parameters invoicing and paymeynt page (/admin/manage-system-parameter/invoicing-and-payment)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Oct 17, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterInvoicingAuctionIncShippingCalculatorPanelConstants
 */
class SystemParameterInvoicingAuctionIncShippingCalculatorPanelConstants
{
    public const CID_TXT_AUC_INC_ACCOUNT_ID = 'ipf113';
    public const CID_LBL_AUC_INC_ACCOUNT_ID = 'ipflbl113';
    public const CID_TXT_AUC_INC_BUSINESS_ID = 'ipf135';
    public const CID_LBL_AUC_INC_BUSINESS_ID = 'ipflbl135';
    public const CID_BTN_AUC_INC_EDIT = 'ipfbtn135';
    public const CID_RAD_AUC_INC_METHOD = 'ipf114';
    public const CID_CHK_AUC_INC_CARRIER_PICKUP = 'ipf142';
    public const CID_CHK_AUC_INC_CARRIER_UPS = 'ipf130';
    public const CID_CHK_AUC_INC_CARRIER_DHL = 'ipf131';
    public const CID_CHK_AUC_INC_CARRIER_FEDEX = 'ipf143';
    public const CID_CHK_AUC_INC_CARRIER_USPS = 'ipf132';
    public const CID_TXT_AUC_INC_DHL_ACCESS_KEY = 'ipf133';
    public const CID_TXT_AUC_INC_DHL_POSTAL_CODE = 'ipf134';
    public const CID_LST_AUC_INC_WEIGHT_CUST_FILD = 'ipf136';
    public const CID_LST_AUC_INC_WIDTH_CUST_FILD = 'ipf137';
    public const CID_LST_AUC_INC_HEIGHT_CUST_FILD = 'ipf138';
    public const CID_LST_AUC_INC_LENGTH_CUST_FILD = 'ipf139';
    public const CID_LST_AUC_INC_WEIGHT_TYPE = 'ipf140';
    public const CID_LST_AUC_INC_DIMENSION_TYPE = 'ipf141';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_TXT_AUC_INC_ACCOUNT_ID => Constants\Setting::AUC_INC_ACCOUNT_ID,
        self::CID_TXT_AUC_INC_BUSINESS_ID => Constants\Setting::AUC_INC_BUSINESS_ID,
        self::CID_RAD_AUC_INC_METHOD => Constants\Setting::AUC_INC_METHOD,
        self::CID_CHK_AUC_INC_CARRIER_PICKUP => Constants\Setting::AUC_INC_PICKUP,
        self::CID_CHK_AUC_INC_CARRIER_UPS => Constants\Setting::AUC_INC_UPS,
        self::CID_CHK_AUC_INC_CARRIER_DHL => Constants\Setting::AUC_INC_DHL,
        self::CID_CHK_AUC_INC_CARRIER_FEDEX => Constants\Setting::AUC_INC_FEDEX,
        self::CID_CHK_AUC_INC_CARRIER_USPS => Constants\Setting::AUC_INC_USPS,
        self::CID_TXT_AUC_INC_DHL_ACCESS_KEY => Constants\Setting::AUC_INC_DHL_ACCESS_KEY,
        self::CID_TXT_AUC_INC_DHL_POSTAL_CODE => Constants\Setting::AUC_INC_DHL_POSTAL_CODE,
        self::CID_LST_AUC_INC_WEIGHT_CUST_FILD => Constants\Setting::AUC_INC_WEIGHT_CUST_FIELD_ID,
        self::CID_LST_AUC_INC_WIDTH_CUST_FILD => Constants\Setting::AUC_INC_WIDTH_CUST_FIELD_ID,
        self::CID_LST_AUC_INC_HEIGHT_CUST_FILD => Constants\Setting::AUC_INC_HEIGHT_CUST_FIELD_ID,
        self::CID_LST_AUC_INC_LENGTH_CUST_FILD => Constants\Setting::AUC_INC_LENGTH_CUST_FIELD_ID,
        self::CID_LST_AUC_INC_WEIGHT_TYPE => Constants\Setting::AUC_INC_WEIGHT_TYPE,
        self::CID_LST_AUC_INC_DIMENSION_TYPE => Constants\Setting::AUC_INC_DIMENSION_TYPE,
    ];
}
