<?php
/**
 * SAM-9795: Check Printing for Settlements: Implementation of html layout and view layer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           10-27, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\View\Admin\Form\SettlementCheckListForm;


/**
 * Class SettlementCheckListConstants
 * @package Sam\View\Admin\Form\SettlementCheckListForm
 */
class SettlementCheckListConstants
{
    public const ORD_ADDRESS = 'address';
    public const ORD_AMOUNT = 'amount';
    public const ORD_AMOUNT_SPELLING = 'amountSpelling';
    public const ORD_CHECK_NO = 'settlementCheckNo';
    public const ORD_CLEARED_ON = 'clearedOn';
    public const ORD_CREATED_ON = 'createdOn';
    public const ORD_DEFAULT = self::ORD_CHECK_NO;
    public const ORD_MEMO = 'memo';
    public const ORD_NOTE = 'note';
    public const ORD_PAYEE = 'payee';
    public const ORD_POSTED_ON = 'postedOn';
    public const ORD_PRINTED_ON = 'printedOn';
    public const ORD_SETTLEMENT_NO = 'settlementNo';
    public const ORD_STATUS = 'status';
    public const ORD_APPLIED_PAYMENT = 'appliedPayment';
    public const ORD_VOIDED_ON = 'voidedOn';
}
