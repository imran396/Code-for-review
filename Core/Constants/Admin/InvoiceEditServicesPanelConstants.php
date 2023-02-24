<?php
/**
 * SAM-10998: Stacked Tax. New Invoice Edit page: Services section
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class InvoiceEditServicesPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class InvoiceEditServicesPanelConstants
{
    public const CID_DLG_CONFIRM_SERVICE_FEE_REMOVE = 'iesp-confirm-service-fee-remove';
    public const CID_DLG_CONFIRM_RELATED_PAYMENT_REMOVE = 'iesp-confirm-related-payment-remove';

    public const CLASS_COLUMN_AMOUNT = 'amount';
    public const CLASS_COLUMN_NAME = 'name';
    public const CLASS_COLUMN_NOTES = 'notes';
    public const CLASS_COLUMN_COUNTRY_TAX_AMOUNT = 'country-tax-amount';
    public const CLASS_COLUMN_STATE_TAX_AMOUNT = 'state-tax-amount';
    public const CLASS_COLUMN_COUNTY_TAX_AMOUNT = 'county-tax-amount';
    public const CLASS_COLUMN_CITY_TAX_AMOUNT = 'city-tax-amount';
    public const CLASS_COLUMN_TYPE = 'type';
    public const CLASS_COLUMN_AMOUNT_WITH_TAX = 'amount-with-tax';
    public const CLASS_COLUMN_ACTIONS = 'actions';

    public const CID_BTN_ADD_TPL = 'iesp-add';
    public const CID_BTN_EDIT_TPL = 'iesp-edit-%s';
    public const CID_BTN_DELETE_TPL = 'iesp-delete-%s';
}
