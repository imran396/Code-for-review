<?php
/**
 * Settlement List Constants
 *
 * SAM-6279: Refactor Settlement List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 10, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementListForm;

/**
 * Class SettlementListConstants
 */
class SettlementListConstants
{
    public const ORD_SETTLEMENT_DATE = 'settlementDate';
    public const ORD_USERNAME = 'userName';
    public const ORD_COST_TOTAL = 'costTotal';
    public const ORD_TAXABLE_TOTAL = 'taxableTotal';
    public const ORD_NON_TAXABLE_TOTAL = 'nonTaxableTotal';
    public const ORD_EXPORT_TOTAL = 'exportTotal';
    public const ORD_TAX_EXCLUSIVE = 'taxExclusive';
    public const ORD_TAX_INCLUSIVE = 'taxInclusive';
    public const ORD_TAX_SERVICES = 'taxServices';
    public const ORD_SETTLEMENT_NO = 'settlementNo';
    public const ORD_FEES_COMMISSION_TOTAL = 'feesCommissionTotal';
    public const ORD_DEFAULT = self::ORD_SETTLEMENT_NO;
}