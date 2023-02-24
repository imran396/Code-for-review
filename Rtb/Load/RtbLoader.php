<?php
/**
 * Help methods for rtb entities loading (RtbCurrent)
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 25, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Load;

use RtbCurrent;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\RtbCurrent\RtbCurrentReadRepository;
use Sam\Storage\ReadRepository\Entity\RtbCurrent\RtbCurrentReadRepositoryCreateTrait;

/**
 * Class RtbLoader
 * @package Sam\Rtb\Load
 */
class RtbLoader extends CustomizableClass
{
    use MemoryCacheManagerAwareTrait;
    use RtbCurrentReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $rtbCurrentId null results with null
     * @param bool $isReadOnlyDb
     * @return RtbCurrent|null
     */
    public function load(?int $rtbCurrentId, bool $isReadOnlyDb = false): ?RtbCurrent
    {
        if (!$rtbCurrentId) {
            return null;
        }

        return $this->createRtbCurrentReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($rtbCurrentId)
            ->loadEntity();
    }

    /**
     * @param int|null $auctionId null results with null
     * @param bool $isReadOnlyDb
     * @return RtbCurrent|null
     */
    public function loadByAuctionIdFromDb(?int $auctionId, bool $isReadOnlyDb = false): ?RtbCurrent
    {
        if (!$auctionId) {
            return null;
        }

        return $this->createRtbCurrentReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->loadEntity();
    }

    /**
     * Load a rtb state object by rtb_current.auction_id
     * @param int|null $auctionId null results with null
     * @param bool $isReadOnlyDb
     * @return RtbCurrent|null
     */
    public function loadByAuctionId(?int $auctionId, bool $isReadOnlyDb = false): ?RtbCurrent
    {
//        return RtbCurrentReadRepository::new()
//            ->enableReadOnlyDb($isReadOnlyDb)
//            ->filterAuctionId($auctionId)
//            ->loadEntity();

        if (!$auctionId) {
            return null;
        }

        $fn = static function () use ($auctionId, $isReadOnlyDb) {
            return RtbCurrentReadRepository::new()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterAuctionId($auctionId)
                ->loadEntity();
        };

        $cacheKey = sprintf(Constants\MemoryCache::RTB_CURRENT_AUCTION_ID, $auctionId);
        return $this->getMemoryCacheManager()->load($cacheKey, $fn);
    }
}
