<?php
/**
 * Mailing Lists Report Constants
 *
 * SAM-6278: Refactor Mailing Lists Report page at admin side
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

namespace Sam\View\Admin\Form\MailingListsReportForm;

/**
 * Class MailingListsReportConstants
 */
class MailingListsReportConstants
{
    public const ORD_NAME = 'name';
    public const ORD_PERIOD_START = 'period_start';
    public const ORD_PERIOD_END = 'period_end';
    public const ORD_MONEY_SPENT_FROM = 'money_spent_from';
    public const ORD_MONEY_SPENT_TO = 'money_spent_to';
    public const ORD_USER_TYPE = 'user_type';
    public const ORD_ID = 'id';
    public const ORD_AUCTION_INFO = 'auction_info';
    public const ORD_DEFAULT = self::ORD_ID;
}