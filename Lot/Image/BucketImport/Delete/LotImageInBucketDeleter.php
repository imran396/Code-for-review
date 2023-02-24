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

namespace Sam\Lot\Image\BucketImport\Delete;

use Exception;
use LotImageInBucket;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Lot\Image\BucketImport\Path\LotImageInBucketPathResolverCreateTrait;
use Sam\Lot\Image\Load\LotImageInBucketLoaderCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotImageInBucket\LotImageInBucketWriteRepositoryAwareTrait;

/**
 * Class LotImageInBucketDeleter
 * @package Sam\Lot\Image\BucketImport\Delete
 */
class LotImageInBucketDeleter extends CustomizableClass
{
    use FileManagerCreateTrait;
    use LotImageInBucketLoaderCreateTrait;
    use LotImageInBucketPathResolverCreateTrait;
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
     * Delete lot image in bucket (db entry and file)
     * @param LotImageInBucket $bucketImage
     * @param int $editorUserId
     */
    public function delete(LotImageInBucket $bucketImage, int $editorUserId): void
    {
        $pathHelper = $this->createLotImageInBucketPathResolver();
        $filePath = $pathHelper->makeFilePath($bucketImage);
        $thumbPath = $pathHelper->makeThumbFilePath($bucketImage->ImageLink, $bucketImage->AuctionId);
        try {
            $fileManager = $this->createFileManager();
            $fileManager->delete($filePath);
            $fileManager->delete($thumbPath);
        } catch (Exception) {
        }
        $this->getLotImageInBucketWriteRepository()->deleteWithModifier($bucketImage, $editorUserId);
    }

    /**
     * Delete all lot images in auction bucket
     * @param int $auctionId
     * @param int $editorUserId
     */
    public function deleteAllInAuction(int $auctionId, int $editorUserId): void
    {
        foreach ($this->createLotImageInBucketLoader()->loadForAuction($auctionId) as $bucketImage) {
            $this->delete($bucketImage, $editorUserId);
        }
    }
}
