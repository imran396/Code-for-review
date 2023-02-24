<?php
/**
 * SAM-6765: Add "Refresh" button  to refresh the auction cache on demand
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Ivan Zgoniaiko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Cache;

use Sam\Core\Service\CustomizableClass;

/**
 * Update cache for single auction
 *
 * Class SingleAuctionAllCacheUpdater
 * @package Sam\Auction\Cache
 */
class SingleAuctionAllCacheUpdater extends CustomizableClass
{
    use AuctionDetailsDbCacheManagerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = self::_new(self::class);
        return $instance;
    }

    /**
     * Refresh cache for single auction
     *
     * @param int $auctionId
     * @param int $editorUserId
     */
    public function refresh(int $auctionId, int $editorUserId): void
    {
        $this->createAuctionDetailsDbCacheManager()
            ->refresh((array)$auctionId, $editorUserId, true);

        $auctionDbCacheManager = AuctionDbCacheManager::new();
        $auctionDbCacheManager->refreshByAuctionId($auctionId, $editorUserId);
    }
}
