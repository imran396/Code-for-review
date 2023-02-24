<?php

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterInvoicingSettlementGenerationPanelConstants
 */
class SystemParameterInvoicingSettlementGenerationPanelConstants
{
    public const CID_CHK_DISPLAY_SETTLEMENT_QTY = 'ipf63';
    public const CID_CHK_SETT_GENERATE_PER_USER = 'ipf127';
    public const CID_CHK_LOT_ITEM_CUSTOM_FIELDS_RENDER_IN_SEPARATE_ROW = 'ipf103';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_CHK_DISPLAY_SETTLEMENT_QTY => Constants\Setting::QUANTITY_IN_SETTLEMENT,
        self::CID_CHK_SETT_GENERATE_PER_USER => Constants\Setting::MULTIPLE_SALE_SETTLEMENT,
        self::CID_CHK_LOT_ITEM_CUSTOM_FIELDS_RENDER_IN_SEPARATE_ROW => Constants\Setting::RENDER_LOT_CUSTOM_FIELDS_IN_SEPARATE_ROW_FOR_SETTLEMENT
    ];
}
