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

namespace Sam\Lot\Image\BucketImport\Path;

use LotImageInBucket;
use Sam\Core\Path\PathResolver;
use Sam\Core\Path\PathResolverCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotImageInBucketHelper
 * @package Sam\Lot\Image\BucketImport\Path
 */
class LotImageInBucketPathResolver extends CustomizableClass
{
    use PathResolverCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return relative dir path for image in bucket
     * @param int $auctionId
     * @return string
     * #[Pure]
     */
    public function makePath(int $auctionId): string
    {
        return PathResolver::UPLOAD_LOT_IMAGE_BUCKET . '/' . $auctionId;
    }

    /**
     * Return relative file path for image in bucket
     * @param LotImageInBucket $bucketImage
     * @return string
     */
    public function makeFilePath(LotImageInBucket $bucketImage): string
    {
        return $this->makePath($bucketImage->AuctionId) . '/' . $bucketImage->ImageLink;
    }

    /**
     * Return relative file path for thumb image in bucket
     * @param string $imageLink
     * @param int $auctionId
     * @return string
     */
    public function makeThumbFilePath(string $imageLink, int $auctionId): string
    {
        $thumbName = md5($imageLink . $auctionId) . '.jpg';
        return PathResolver::DOCROOT . PathResolver::TEXT_IMAGES . '/' . $thumbName;
    }

    /**
     * @param string $imageLink
     * @param int $auctionId
     * @return string
     */
    public function makeThumbUrl(string $imageLink, int $auctionId): string
    {
        $thumbName = md5($imageLink . $auctionId) . '.jpg';
        return PathResolver::TEXT_IMAGES . '/' . $thumbName;
    }

    /**
     * Return absolute file path for image in bucket
     * @param LotImageInBucket $bucketImage
     * @return string
     */
    public function makeFileRootPath(LotImageInBucket $bucketImage): string
    {
        return $this->path()->sysRoot() . $this->makePath($bucketImage->AuctionId) . '/' . $bucketImage->ImageLink;
    }
}
