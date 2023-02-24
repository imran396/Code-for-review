<?php
/**
 * SAM-11127: Stacked Tax. New Invoice Edit page: Payment Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 28, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class InvoicePaymentEditFormConstants
 * @package Sam\Core\Constants\Admin
 */
class InvoicePaymentEditFormConstants
{
    public const CID_BTN_DELETE = 'ipef-delete';
    public const CID_BTN_EDIT_INVOICE_ADDITIONAL = 'ipef-edit-inv-additional';
    public const CID_BTN_SAVE = 'ipef-save';
    public const CID_BTN_CHARGE_AND_SAVE = 'ipef-charge-and-save';
    public const CID_CAL_DATE = 'ipef-date';
    public const CID_DLG_INVOICE_STATUS = 'ipef-edit-status-dialog';
    public const CID_DLG_CHARGE_INVOICE_CC_INFO = 'ipef-c1234';
    public const CID_LBL_INVOICE_ADDITIONAL_AMOUNT = 'ipef-inv-additional-amount';
    public const CID_LBL_INVOICE_ADDITIONAL_NAME = 'ipef-inv-additional-name';
    public const CID_LBL_INVOICE_ADDITIONAL_TAX_AMOUNT = 'ipef-inv-additional-tax-amount';
    public const CID_LBL_TOTAL_AMOUNT = 'ipef-total-amount';
    public const CID_LST_CREDIT_CARD = 'ipef-credit-card';
    public const CID_LST_INVOICE_ADDITIONAL_TAX_SCHEMA = 'ipef-inv-additional-tax-schema';
    public const CID_LST_METHOD = 'ipef-method';
    public const CID_LST_PAYMENT_GATEWAY = 'ipef-payment-gateway';
    public const CID_TXT_AMOUNT = 'ipef-amount';
    public const CID_TXT_NOTE = 'ipef-note';
    public const CID_CHK_APPLY_CASH_DISCOUNT = 'ipef-apply-cash-discount';

    public const ID_BLK_CREDIT_CARD_INPUT = 'credit-card-input';
    public const ID_BLK_INVOICE_ADDITIONAL_AMOUNT = 'inv-additional-amount';
    public const ID_BLK_INVOICE_ADDITIONAL_TAX_AMOUNT = 'inv-additional-tax-amount';
    public const ID_BLK_INVOICE_ADDITIONAL_TAX_SCHEMA = 'inv-additional-tax-schema';
    public const ID_BLK_APPLY_CASH_DISCOUNT = 'apply-cash-discount';
    public const ID_BLK_PAYMENT_GATEWAY = 'payment-gateway';
}
