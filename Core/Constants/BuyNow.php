<?php
/**
 * SAM-3578: Buyer select from quantity for timed buy now
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class BuyNow
 * @package Sam\Core\Constants
 */
class BuyNow
{
    public const BUY_NOW_CLONE_IMAGE_NONE = 0;
    public const BUY_NOW_CLONE_IMAGE_ALL = 1;
    public const BUY_NOW_CLONE_IMAGE_FIRST = 2;

    public const BUY_NOW_URL_QUANTITY_PLACEHOLDER = 'qty_placeholder';
}
