<?php

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterInvoicingInvoiceGenerationPanelConstants
 */
class SystemParameterInvoicingInvoiceGenerationPanelConstants
{
    public const CID_CHK_DISPLAY_CATEGORY = 'ipf45';
    public const CID_CHK_ONE_SALE_GROUPED = 'ipf126';
    public const CID_CHK_MULTIPLE_SALE = 'ipf53';
    public const CID_CHK_QUANTITY_IN_INVOICE = 'ipf54';
    public const CID_CHK_INCLUDE_IDENTIFICATION = 'ipf101';
    public const CID_CHK_INV_ITEM_DESC = 'ipf65';
    public const CID_CHK_INV_ITEM_TAX = 'ipf66';
    public const CID_CHK_INV_ITEM_GO_SE = 'ipf166';
    public const CID_CHK_LOT_ITEM_CUSTOM_FIELDS_RENDER_IN_SEPARATE_ROW = 'ipf102';
    public const CID_RAD_TAX_DESIGNATION_STRATEGY = 'ipf46';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_CHK_DISPLAY_CATEGORY => Constants\Setting::CATEGORY_IN_INVOICE,
        self::CID_CHK_ONE_SALE_GROUPED => Constants\Setting::ONE_SALE_GROUPED_INVOICE,
        self::CID_CHK_MULTIPLE_SALE => Constants\Setting::MULTIPLE_SALE_INVOICE,
        self::CID_CHK_QUANTITY_IN_INVOICE => Constants\Setting::QUANTITY_IN_INVOICE,
        self::CID_CHK_INCLUDE_IDENTIFICATION => Constants\Setting::INVOICE_IDENTIFICATION,
        self::CID_CHK_INV_ITEM_DESC => Constants\Setting::INVOICE_ITEM_DESCRIPTION,
        self::CID_CHK_INV_ITEM_TAX => Constants\Setting::INVOICE_ITEM_SALES_TAX,
        self::CID_CHK_INV_ITEM_GO_SE => Constants\Setting::INVOICE_ITEM_SEPARATE_TAX,
        self::CID_CHK_LOT_ITEM_CUSTOM_FIELDS_RENDER_IN_SEPARATE_ROW => Constants\Setting::RENDER_LOT_CUSTOM_FIELDS_IN_SEPARATE_ROW_FOR_INVOICE,
        self::CID_RAD_TAX_DESIGNATION_STRATEGY => Constants\Setting::INVOICE_TAX_DESIGNATION_STRATEGY
    ];

    public const CLASS_BLK_SEP_TAX = 'sep_tax';
}
