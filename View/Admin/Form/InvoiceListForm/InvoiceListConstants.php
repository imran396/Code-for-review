<?php
/**
 * Invoice List Constants
 *
 * SAM-6092: Refactor Invoice List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 16, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceListForm;

/**
 * Class InvoiceListConstants
 */
class InvoiceListConstants
{
    public const ORD_INVOICE_NO = 'InvoiceNo';
    public const ORD_ISSUED = 'Issued';
    public const ORD_SALE = 'Sale';
    public const ORD_BIDDER_NO = 'BidNo';
    public const ORD_USER = 'User';
    public const ORD_STATE = 'St.';
    public const ORD_ZIP = 'ZIP';
    public const ORD_STATUS = 'Status';
    public const ORD_NAME = 'Name';
    public const ORD_SENT = 'Sent';
    public const ORD_BID_TOTAL = 'BidTotal';
    public const ORD_PREMIUM = 'Premium';
    public const ORD_TAX = 'Tax';
    public const ORD_FEES = 'Fees';
    public const ORD_PAYMENT = 'Payment';
    public const ORD_BALANCE = 'Balance';
    public const ORD_TOTAL = 'Total';
    public const ORD_CURRENCY = 'Currency';
    public const ORD_DEFAULT = 'Id';

    /** @var string[] */
    public static array $invoiceListOrderColumns = [
        self::ORD_INVOICE_NO => "Inv. #",
        self::ORD_ISSUED => "Issued",
        self::ORD_SENT => "Sent",
        self::ORD_USER => "User",
        self::ORD_NAME => "Name",
        self::ORD_STATE => "Buyer St.",
        self::ORD_ZIP => "Buyer ZIP",
        self::ORD_BID_TOTAL => "Bid Total",
        self::ORD_PREMIUM => "Premium",
        self::ORD_TAX => "Tax",
        self::ORD_FEES => "Fees",
        self::ORD_TOTAL => "Total",
        self::ORD_PAYMENT => "Payment",
        self::ORD_BALANCE => "Balance",
        self::ORD_STATUS => "Status",
        self::ORD_CURRENCY => "Currency",
    ];
}
