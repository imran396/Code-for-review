<?php
/**
 * This config can be created without urlType definition,
 * because it is intended for search of landing page in auction context.
 *
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\Auction;

/**
 * Class AnySingleAuctionUrlConfig
 * @package Sam\Application\Url
 */
class ResponsiveAuctionLandingUrlConfig extends AbstractResponsiveSingleAuctionUrlConfig
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
