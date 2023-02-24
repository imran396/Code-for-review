<?php
/**
 * SAM-11000: Stacked Tax. New Invoice Edit page: Payments section
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class InvoiceEditPaymentsPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class InvoiceEditPaymentsPanelConstants
{
    public const CID_DLG_CONFIRM_PAYMENT_REMOVE = 'iepp-confirm-payment-remove';
    public const CID_DLG_CONFIRM_RELATED_SERVICE_FEE_REMOVE = 'iepp-confirm-related-service-fee-remove';

    public const CLASS_COLUMN_METHOD = 'method';
    public const CLASS_COLUMN_DATE = 'date';
    public const CLASS_COLUMN_NOTE = 'payment-note';
    public const CLASS_COLUMN_AMOUNT = 'amount';
    public const CLASS_COLUMN_ACTIONS = 'actions';
    public const CLASS_COLUMN_SUBTOTAL = 'subtotal';
    public const CLASS_COLUMN_BALANCE_DUE = 'balance-due';

    public const CID_BTN_ADD = 'iepp-add';

    public const CID_BTN_EDIT_TPL = 'iepp-edit-%s';
    public const CID_BTN_DELETE_TPL = 'iepp-delete-%s';
}
