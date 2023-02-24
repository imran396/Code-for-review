<?php
/**
 * Consignor Report Constants
 *
 * SAM-6032: Refactor Consignor report page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 28, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ConsignorReportForm;

/**
 * Class ConsignorReportConstants
 */
class ConsignorReportConstants
{
    public const ORD_CUSTOMER_NO = 'customer_no';
    public const ORD_USERNAME = 'username';
    public const ORD_FIRST_NAME = 'first_name';
    public const ORD_LAST_NAME = 'last_name';
    public const ORD_DEFAULT = self::ORD_USERNAME;
}
