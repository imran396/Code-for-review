<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\OutbidBidder\Internal;

/**
 * Class PlaceholderKey
 * @package Sam\Sms\Template\Placeholder\OutbidBidder\Internal
 */
class PlaceholderKey
{
    public const USER_CUSTOMER_NO = 'outbid_user_customer_no';
    public const USER_ID = 'outbid_user_id';
    public const USER_PHONE = 'outbid_user_phone';
}
