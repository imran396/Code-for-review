<?php
/**
 * SAM-8963: Create constants for billing transaction parameters
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class BillingParam
 * @package Sam\Core\Constants
 */
class BillingAuthNet
{
    public const P_AUTH_NET_CPI = 'AuthNetCpi';
    public const P_AUTH_NET_CPPI = 'AuthNetCppi';
    public const P_AUTH_NET_CAI = 'AuthNetCai';

    public const RESPONSE_CODE_UNKNOWN_ERROR = 0;
    public const RESPONSE_CODE_APPROVED = 1;
    public const RESPONSE_CODE_DECLINED = 2;
    public const RESPONSE_CODE_CARD_ERROR = 3;
    public const RESPONSE_CODE_CARD_NEEDS_TO_BE_PICKED_UP = 4;
    public const RESPONSE_CODE_HELD_FOR_REVIEW = 253;
}
