<?php
/**
 * SAM-7958: Refactor \Cached_Queue class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           mar. 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\Queue\Load;

use CachedQueue;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Storage\ReadRepository\Entity\CachedQueue\CachedQueueReadRepository;
use Sam\Storage\ReadRepository\Entity\CachedQueue\CachedQueueReadRepositoryCreateTrait;

/**
 * Class LotImageQueueLoader
 * @package Sam\Lot\Image\Queue\Load
 */
class LotImageQueueLoader extends CustomizableClass
{
    use CachedQueueReadRepositoryCreateTrait;
    use OptionalsTrait;

    public const OP_IMAGE_CACHE_RETRIES = 'imageCacheRetries';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param int $limit
     * @param bool $isReadOnlyDb
     * @return CachedQueue[]
     */
    public function loadPending(int $limit = 100, bool $isReadOnlyDb = false): array
    {
        $items = $this->prepareRepository($isReadOnlyDb)
            ->betweenCached($this->fetchOptional(self::OP_IMAGE_CACHE_RETRIES) + 1, 0)
            ->limit($limit)
            ->loadEntities();
        return $items;
    }

    /**
     * Exist pending images in queue
     *
     * @param int $auctionId (Auction Id)
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function hasPending(int $auctionId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->prepareRepository($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->skipCached([1, $this->fetchOptional(self::OP_IMAGE_CACHE_RETRIES)])
            ->exist();
        return $isFound;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return CachedQueueReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb = false): CachedQueueReadRepository
    {
        return $this->createCachedQueueReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterType(Constants\CachedQueue::LOT_IMAGE);
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IMAGE_CACHE_RETRIES] = $optionals[self::OP_IMAGE_CACHE_RETRIES]
            ?? static function (): int {
                return ConfigRepository::getInstance()->get('core->image->cacheRetries');
            };
        $this->setOptionals($optionals);
    }
}
