<?php
/**
 * General repository for AuctionRtbdReadRepository Parameters entity
 *
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           30 Oct, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionRtbd;

/**
 * Class AuctionRtbdReadRepository
 * @package Sam\Storage\ReadRepository\Entity\AuctionRtbd
 */
class AuctionRtbdReadRepository extends AbstractAuctionRtbdReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
