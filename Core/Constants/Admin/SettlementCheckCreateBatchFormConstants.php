<?php
/**
 * SAM-9888: Check Printing for Settlements: Bulk Checks Processing - Account level, Settlements List level (Part 2)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class SettlementCheckCreateBatchFormConstants
 * @package Sam\Core\Constants\Admin
 */
class SettlementCheckCreateBatchFormConstants
{
    public const CID_DTG_SETTLEMENT_CHECK_LIST = 'scbc_checkList';
    public const CID_TXT_CHECK_NO_TPL = 'scbc_checkNo_%s';
    public const CID_TXT_PAYEE_TPL = 'scbc_payee_%s';
    public const CID_TXT_AMOUNT_TPL = 'scbc_amount_%s';
    public const CID_TXT_AMOUNT_SPELLING_TPL = 'scbc_amount_spelling_%s';
    public const CID_TXT_MEMO_TPL = 'scbc_memo_%s';
    public const CID_TXT_NOTE_TPL = 'scbc_note_%s';
    public const CID_BTN_POPULATE_AMOUNT_SPELLING_TPL = 'scbc_populate_amount_spelling_%s';
    public const CSS_BTN_POPULATE_AMOUNT_SPELLING = 'btn-populate-amount-spelling';
    public const CID_BTN_SAVE = 'scbc_save';
    public const CID_ICO_WAIT_TPL = 'icoWait_%s';

    public const CID_TXT_ADDRESS_TPL = 'scbc_address_%s';
}
