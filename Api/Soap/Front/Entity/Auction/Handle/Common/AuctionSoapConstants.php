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

namespace Sam\Api\Soap\Front\Entity\Auction\Handle\Common;

/**
 * Class AuctionSoapConstants
 * @package Sam\Api\Soap\Front\Entity\Auction\Handle\Common
 */
class AuctionSoapConstants
{
    public const NAMESPACE_ID = 'SAM auction.id';
    public const NAMESPACE_SALE_NO = 'SAM auction.sale_no';
    public const NAMESPACE_USER_ID = 'SAM UserId-AuctionId';
    public const NAMESPACE_USER_NAME = 'SAM Username-AuctionId';

    /** @var string[] */
    public const DEFAULT_NAMESPACES = [
        self::NAMESPACE_ID,
        self::NAMESPACE_SALE_NO,
        self::NAMESPACE_USER_ID,
        self::NAMESPACE_USER_NAME,
    ];
}
