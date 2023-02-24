<?php
/**
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Order;

use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Image\Load\LotImageInBucketLoaderCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotImageInBucket\LotImageInBucketReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotImageInBucket\LotImageInBucketWriteRepositoryAwareTrait;

/**
 * Class LotImageInBucketReorderer
 * @package Sam\Lot\Image\BucketImport\Order
 */
class LotImageInBucketOrderManager extends CustomizableClass
{
    use LotImageInBucketLoaderCreateTrait;
    use LotImageInBucketReadRepositoryCreateTrait;
    use LotImageInBucketWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Save new order of images
     * @param int $auctionId auction.id of the bucket
     * @param int[] $orderedBucketImageIds array of image ids placed in necessary order
     * @param int $editorUserId
     */
    public function applyImageOrder(int $auctionId, array $orderedBucketImageIds, int $editorUserId): void
    {
        $bucketImages = $this->createLotImageInBucketLoader()->loadForAuction($auctionId);
        $bucketImages = ArrayHelper::indexEntities($bucketImages, 'Id');
        if (count($bucketImages) && count($orderedBucketImageIds)) {
            $orderNum = 1;
            foreach ($orderedBucketImageIds as $bucketImageId) {
                $bucketImage = $bucketImages[$bucketImageId] ?? null;
                if ($bucketImage) {
                    $bucketImage->Order = $orderNum++;
                    $this->getLotImageInBucketWriteRepository()->saveWithModifier($bucketImage, $editorUserId);
                }
            }
        }
    }

    /**
     * Order images by file name
     * @param int $auctionId
     * @param int $editorUserId
     */
    public function orderByFilename(int $auctionId, int $editorUserId): void
    {
        $bucketImages = $this->createLotImageInBucketReadRepository()
            ->filterAuctionId($auctionId)
            ->orderByImageLink()
            ->loadEntities();
        $orderNum = 0;
        foreach ($bucketImages as $bucketImage) {
            $bucketImage->Order = $orderNum;
            $this->getLotImageInBucketWriteRepository()->saveWithModifier($bucketImage, $editorUserId);
            $orderNum++;
        }
    }
}
