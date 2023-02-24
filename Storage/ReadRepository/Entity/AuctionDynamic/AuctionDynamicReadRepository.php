<?php
/**
 * SAM-6019: Auction end date overhaul
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionDynamic;

/**
 * General repository for AuctionDynamic entity
 *
 * Class AuctionDynamicReadRepository
 * @package Sam\Storage\Repository
 */
class AuctionDynamicReadRepository extends AbstractAuctionDynamicReadRepository
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }
}
