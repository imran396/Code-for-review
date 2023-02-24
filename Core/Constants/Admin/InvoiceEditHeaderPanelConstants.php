<?php
/**
 * SAM-10995: Stacked Tax. New Invoice Edit page: Initial layout and header section
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class InvoiceEditHeaderPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class InvoiceEditHeaderPanelConstants
{
    public const CID_LST_STATUS = 'iid2';
    public const CID_CHK_EXCLUDE_IN_THRESHOLD = 'iid18';
    public const CID_CHK_LST_PAYMENT_METHODS = 'iid21';
    public const CID_BTN_EDIT = 'iid34';
    public const CID_BTN_SAVE = 'iid35';
    public const CID_BTN_CANCEL = 'iid43';
    public const CID_TXT_INVOICE_NO = 'iid38';
    public const CID_CAL_INV_DATE = 'inv_date';
    public const CID_DLG_CONFIRM_UNSOLD_REMOVE_INVOICE = 'iid29';
    public const CID_CAL_AUCTION_DATE_TPL = 'iehp-auction-date-%s';

    public const CID_BLK_HEADER = 'invoice-header';
    public const CLASS_BLK_Q_DATE_TIME_PICKER = 'qdatetimepicker-ctl';
    public const CID_BLK_SALE_LIST = 'sale-list';
}
