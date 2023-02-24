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

namespace Sam\Auction\Load;

use AuctionDynamic;
use InvalidArgumentException;
use Sam\Auction\AuctionDynamic\AuctionDynamicProducerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionDynamic\AuctionDynamicReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionDynamic\AuctionDynamicReadRepositoryCreateTrait;

/**
 * Class AuctionDynamicLoader
 * @package Sam\Auction\Load
 */
class AuctionDynamicLoader extends CustomizableClass
{
    use AuctionDynamicProducerCreateTrait;
    use AuctionDynamicReadRepositoryCreateTrait;
    use EntityMemoryCacheManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * Load AuctionDynamic entity by an auction id
     *
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return AuctionDynamic|null
     */
    public function load(?int $auctionId, bool $isReadOnlyDb = false): ?AuctionDynamic
    {
        if (!$auctionId) {
            return null;
        }

        $fn = function () use ($auctionId, $isReadOnlyDb) {
            $auctionDynamic = $this->prepareRepository($isReadOnlyDb)
                ->filterAuctionId($auctionId)
                ->loadEntity();
            return $auctionDynamic;
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::AUCTION_DYNAMIC_AUCTION_ID, $auctionId);
        $auctionDynamic = $this->getEntityMemoryCacheManager()->load($entityKey, $fn);
        return $auctionDynamic;
    }

    /**
     * Load AuctionDynamic entity by an auction id or create if it is not exist.
     * Created entity will not be saved automatically
     *
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return AuctionDynamic
     */
    public function loadOrCreate(?int $auctionId, bool $isReadOnlyDb = false): AuctionDynamic
    {
        if (!$auctionId) {
            throw new InvalidArgumentException('Cannot load or create AuctionDynamic record for unknown auction id');
        }

        $auctionDynamic = $this->load($auctionId, $isReadOnlyDb);
        if (!$auctionDynamic) {
            $auctionDynamic = $this->createAuctionDynamicProducer()->create($auctionId);
        }
        return $auctionDynamic;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return AuctionDynamicReadRepository
     */
    private function prepareRepository(bool $isReadOnlyDb = false): AuctionDynamicReadRepository
    {
        return $this->createAuctionDynamicReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
    }
}
