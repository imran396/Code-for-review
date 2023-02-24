<?php
/**
 * SAM-6292: Move fields from auction_cache to auction_details_cache
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 04, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class AuctionDetailsCache
 * @package Sam\Core\Constants
 */
class AuctionDetailsCache
{
    public const SEO_URL = 1;
    public const CAPTION = 2;

    public const ALL_KEYS = [
        self::SEO_URL,
        self::CAPTION
    ];
}
