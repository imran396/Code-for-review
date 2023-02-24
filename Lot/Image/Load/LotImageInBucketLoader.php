<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\Load;

use LotImageInBucket;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LotImageInBucket\LotImageInBucketReadRepositoryCreateTrait;

/**
 * Class LotImageInBucketLoader
 * @package Sam\Lot\Image\Load
 */
class LotImageInBucketLoader extends CustomizableClass
{
    use LotImageInBucketReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load a LotImageInBucket
     * @param int|null $id
     * @param bool $isReadOnlyDb query to read-only db
     * @return LotImageInBucket|null
     */
    public function load(?int $id, bool $isReadOnlyDb = false): ?LotImageInBucket
    {
        if (!$id) {
            return null;
        }
        $lotImageInBucket = $this->createLotImageInBucketReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($id)
            ->loadEntity();
        return $lotImageInBucket;
    }

    /**
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return LotImageInBucket[]
     */
    public function loadForAuction(?int $auctionId, bool $isReadOnlyDb = false): array
    {
        if (!$auctionId) {
            return [];
        }

        $lotImagesInBucket = $this->createLotImageInBucketReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->orderByOrder()
            ->loadEntities();
        return $lotImagesInBucket;
    }

    /**
     * Count images in the bucket
     * @param int|null $auctionId
     * @return int
     */
    public function count(?int $auctionId): int
    {
        if (!$auctionId) {
            return 0;
        }
        $count = $this->createLotImageInBucketReadRepository()
            ->filterAuctionId($auctionId)
            ->count();
        return $count;
    }
}
