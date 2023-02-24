<?php

namespace Sam\Core\Constants\Admin;

/**
 * Class SystemParameterInvoicingFormConstants
 */
class SystemParameterInvoicingFormConstants
{
    public const CID_PNL_SYSTEM_PARAMETER_INVOICING_COMMISSIONS_AND_CHARGES = 'spInvoicingCommissionAndCharges';
    public const CID_PNL_SYSTEM_PARAMETER_INVOICING_INVOICE_GENERATION = 'spInvoicingInvoiceGeneration';
    public const CID_PNL_SYSTEM_PARAMETER_INVOICING_TAXES = 'spInvoicingTaxes';
    public const CID_PNL_SYSTEM_PARAMETER_INVOICING_SETTLEMENT_GENERATION = 'spInvoicingSettlementGeneration';
    public const CID_PNL_SYSTEM_PARAMETER_INVOICING_SETTLEMENT_CHECK = 'spInvoicingSettlementCheck';
    public const CID_PNL_SYSTEM_PARAMETER_INVOICING_ADDITIONAL_INVOICE_LINE_ITEMS = 'spInvoicingAdditionalInvoiceLineItems';
    public const CID_PNL_SYSTEM_PARAMETER_INVOICING_AUCTION_INC_SHIPPING_CALCULATOR = 'spInvoicingAuctionIncShippingCalculator';
    public const CID_BTN_SAVE_CHANGES = 'if1';
    public const CID_BTN_CANCEL_CHANGES = 'if62';

    public const CID_BLK_SETTLEMENT_GENERATION = 'settlement-generation';
    public const CID_TXT_EXP_SECT = 'ipf111';
    public const CID_INVOICING_FORM = 'SystemParameterInvoicingForm'; // Used in JS only. Should be the same as form class name. Generated internally by QCodo (see \QFormBase::RenderBegin).

    public const CLASS_BLK_LEG_ALL = 'legall';
    public const CLASS_BLK_LEGEND = 'legend_div';
    public const CLASS_LNK_CLOSE = 'close';
    public const CLASS_LNK_OPEN = 'open';
}
