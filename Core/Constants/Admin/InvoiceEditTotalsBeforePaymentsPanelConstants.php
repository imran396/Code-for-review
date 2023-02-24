<?php
/**
 * SAM-10999: Stacked Tax. New Invoice Edit page: Totals Before Payments section
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 21, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class InvoiceEditFormConstants
 * @package Sam\Core\Constants\Admin
 */
class InvoiceEditTotalsBeforePaymentsPanelConstants
{
    public const CLASS_TOTALS_BEFORE_PAYMENTS = 'totals-before-payments';
    public const CLASS_TOTALS_BEFORE_PAYMENTS_CONTAINER = 'totals-before-payments-container';
    public const CLASS_GRAND_TOTAL_BEFORE_DISCOUNT = 'grand-total-before-discount';
    public const CLASS_GRAND_TOTAL_BEFORE_PAYMENT = 'grand-total-before-discount';
    public const CLASS_COLUMN_AMOUNT = 'amount';
    public const CLASS_COLUMN_TAX = 'tax';
    public const CLASS_COLUMN_AMOUNT_WITH_TAX = 'amount-with-tax';
}
