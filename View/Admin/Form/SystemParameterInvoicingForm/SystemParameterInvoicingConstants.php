<?php
/**
 * SAM-6914: Move sections' logic to separate Panel classes at Manage settings system parameters invoicing and payment page (/admin/manage-system-parameter/invoicing-and-payment)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           11-10, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\View\Admin\Form\SystemParameterInvoicingForm;


/**
 * Class SystemParameterInvoicingConstants
 * @package Sam\View\Admin\Form\SystemParameterInvoicingForm
 */
class SystemParameterInvoicingConstants
{
    // Constants for Invoice Line Items
    public const ORD_INVOICE_LINE_ITEMS_LABEL = 'label';
    public const ORD_INVOICE_LINE_ITEMS_AMOUNT = 'amount';
    public const ORD_INVOICE_LINE_ITEMS_AUCTION_TYPE = 'auction_type';
    public const ORD_INVOICE_LINE_ITEMS_DEFAULT = self::ORD_INVOICE_LINE_ITEMS_LABEL;
}
