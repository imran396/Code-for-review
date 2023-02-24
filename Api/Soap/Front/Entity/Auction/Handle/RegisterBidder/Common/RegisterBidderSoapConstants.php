<?php
/**
 * SAM-5041: Soap API RegisterBidder improvement
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\Common;

/**
 * Class AuctionSoapConstants
 * @package Sam\Api\Soap\Front\Entity\Auction\Handle\Common
 */
class RegisterBidderSoapConstants
{
    // "RegisterBidder" SOAP option "ForceUpdateBidderNumber" values
    public const FUBN_YES = 'Y';
    public const FUBN_NO = 'N';
    public const FUBN_REGULAR = 'R';
    public const FUBN_DEFAULT = self::FUBN_REGULAR;
    public const FUBN_OPTIONS = [self::FUBN_YES, self::FUBN_NO, self::FUBN_REGULAR];

    /**
     * Error messages for "RegisterBidder" SOAP call
     */
    public const ERR_MSG_INVALID_VALUE_FOR_FORCE_UPDATE_BIDDER_NUMBER_OPTION = 'Invalid value "%s" for "ForceUpdateBidderNumber" option. Available: Y, N, R (default)';
    public const ERR_MSG_USER_NOT_FOUND_WITHING_SYNC_NAMESPACE = 'User %s not found within sync namespace %s';
    public const ERR_MSG_AUCTION_NOT_FOUND_WITHING_SYNC_NAMESPACE = 'Auction %s not found within sync namespace %s';
}
