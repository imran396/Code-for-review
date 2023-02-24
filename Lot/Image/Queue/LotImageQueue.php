<?php
/**
 * SAM-7845: Refactor \Lot_Image class
 *
 * TB: cached_queue entries should ONLY contain the relative path within an installation
 * no prefix, no cache busting timestamp parameter.
 * Since cache queued cron script uses that information to determine the lot_image.id
 * and look up all the other information.
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\Queue;

use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\CachedQueue\CachedQueueWriteRepositoryAwareTrait;

/**
 * Class LotImageQueue
 * @package Sam\Lot\Image\Queue
 */
class LotImageQueue extends CustomizableClass
{
    use CachedQueueWriteRepositoryAwareTrait;
    use EntityFactoryCreateTrait;

    protected const CACHING_SIZES = [
        Constants\Image::SMALL,
        Constants\Image::MEDIUM,
        Constants\Image::LARGE,
        Constants\Image::EXTRA_LARGE,
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $lotImageId
     * @param int $editorUserId
     * @param int|null $auctionId
     * @param string[] $availableSizes allows to restrict caching thumbnail sizes
     * @return int
     */
    public function addToCached(
        int $lotImageId,
        int $editorUserId,
        ?int $auctionId = null,
        array $availableSizes = self::CACHING_SIZES
    ): int {
        $addedCount = 0;
        foreach (self::CACHING_SIZES as $size) {
            if (in_array($size, $availableSizes, true)) {
                // Return lot image static thumbnail file path within document root
                $urlPath = LotImageUrlConfig::new()
                    ->construct($lotImageId, $size)
                    ->urlFilled();

                $this->addToQueue($auctionId, $urlPath, $editorUserId);
                $addedCount++;
            }
        }
        return $addedCount;
    }

    /**
     * @param int|null $auctionId
     * @param string $filePath
     * @param int $editorUserId
     */
    protected function addToQueue(?int $auctionId, string $filePath, int $editorUserId): void
    {
        $cachedQueue = $this->createEntityFactory()->cachedQueue();
        $cachedQueue->AuctionId = $auctionId;
        $cachedQueue->Type = Constants\CachedQueue::LOT_IMAGE;
        $cachedQueue->FilePath = $filePath;
        $cachedQueue->Cached = 0;
        $this->getCachedQueueWriteRepository()->saveWithModifier($cachedQueue, $editorUserId);
    }
}
