<?php
/**
 * SAM-6914: Move sections' logic to separate Panel classes at Manage settings system parameters invoicing and paymeynt page (/admin/manage-system-parameter/invoicing-and-payment)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Oct 16, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class SystemParameterInvoicingAdditionalInvoiceLineItemsPanelConstants
 */
class SystemParameterInvoicingAdditionalInvoiceLineItemsPanelConstants
{
    public const CID_DTG_INVOICE_LINE_ITEMS = 'ipf18';
    public const CID_BTN_INVOICE_LINE_ADD = 'ipf19';
    public const CID_TXT_INVOICE_LINE_LABEL = 'ipf20';
    public const CID_TXT_INV_LINE_AMOUNT = 'ipf21';
    public const CID_RAD_INVOICE_LINE_AUCTION_TYPE = 'ipf22';
    public const CID_BTN_INVOICE_LINE_SAVE = 'ipf23';
    public const CID_BTN_INVOICE_LINE_CANCEL = 'ipf24';
    public const CID_RAD_INVOICE_LINE_BREAK_DOWN = 'ipf99';
    public const CID_CHK_INVOICE_LINE_PER_LOT = 'ipf70';
    public const CID_CHK_INVOICE_LINE_LEU_TAX = 'ipf71';
    public const CID_LST_CATEGORY = 'ipf98';
    public const CID_INVOICE_LINE_BTN_E_TPL = '%sbInvlinee%s';
    public const CID_INVOICE_LINE_BTN_D_TPL = '%sbInvlined%s';
}
