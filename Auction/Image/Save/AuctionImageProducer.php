<?php
/**
 * SAM-6434: Refactor auction image logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Image\Save;

use AuctionImage;
use Sam\Application\Url\Build\Config\Image\AuctionImageUrlConfig;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Auction\Image\Load\AuctionImageLoaderAwareTrait;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\Image\Cache\ImageCacheManagerCreateTrait;
use Sam\Image\ImageHelper;
use Sam\Image\ImageHelperCreateTrait;
use Sam\Image\NameGenerator;
use Sam\Image\Resize\Resizer;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionImage\AuctionImageWriteRepositoryAwareTrait;

/**
 * Class AuctionImageProducer
 * @package Sam\Auction\Image
 */
class AuctionImageProducer extends CustomizableClass
{
    use AuctionImageLoaderAwareTrait;
    use AuctionImageWriteRepositoryAwareTrait;
    use ConfigRepositoryAwareTrait;
    use EntityFactoryCreateTrait;
    use FileManagerCreateTrait;
    use ImageCacheManagerCreateTrait;
    use ImageHelperCreateTrait;
    use LocalFileManagerCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Create a record in auction_image table
     * @param string $fileName
     * @param int $auctionId
     * @param int $accountId
     * @param int $editorUserId
     * @throws FileException
     */
    public function save(string $fileName, int $auctionId, int $accountId, int $editorUserId): void
    {
        if ($fileName === '') {
            // Remove image
            $auctionImage = $this->getAuctionImageLoader()->loadDefault($auctionId);
            if ($auctionImage) {
                $this->getAuctionImageWriteRepository()->deleteWithModifier($auctionImage, $editorUserId);
            }
            return;
        }
        $auctionImage = $this->getAuctionImageLoader()->loadDefault($auctionId);
        if (!$auctionImage) {
            $auctionImage = $this->createEntityFactory()->auctionImage();
            $auctionImage->AuctionId = $auctionId;
            $this->getAuctionImageWriteRepository()->saveWithModifier($auctionImage, $editorUserId);
        } else {
            $this->deleteImageThumbs($auctionImage->Id);
        }
        $oldImageLink = $auctionImage->ImageLink;
        $auctionImage->ImageLink = $fileName;
        $this->getAuctionImageWriteRepository()->saveWithModifier($auctionImage, $editorUserId);

        $auctionImagesDir = path()->uploadAuctionImage() . '/' . $accountId;
        if (filter_var($fileName, FILTER_VALIDATE_URL)) {
            // Create thumbnails from remote image
            $this->createThumbnailsFromRemoteImage($auctionImage);
            // make sure to delete previous local image if it was uploaded
            if (
                $oldImageLink
                && !filter_var($oldImageLink, FILTER_VALIDATE_URL)
            ) {
                $sourceFileRootPath = $auctionImagesDir . '/' . $oldImageLink;
                $sourceFileRootPath = substr($sourceFileRootPath, strlen(path()->sysRoot()));
                $this->createLocalFileManager()->delete($sourceFileRootPath);
            }
        } else {
            // Create thumbnails from local
            $sourceFileRootPath = $auctionImagesDir . '/' . $fileName;
            $this->createThumbnailsFromLocalImage($sourceFileRootPath, $auctionImage->Id);
        }
    }

    /**
     * Create image thumbnails from local image
     *
     * @param string $sourceFileRootPath
     * @param int $auctionImageId
     * @param string[]|null $sizeTypes
     */
    public function createThumbnailsFromLocalImage(
        string $sourceFileRootPath,
        int $auctionImageId,
        ?array $sizeTypes = null
    ): void {
        if ($sizeTypes === null) {
            $auctionListSize = ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->auctionList'));
            $sizeTypes = [$auctionListSize];
        }

        foreach ($sizeTypes as $sizeType) {
            $this->createSingleThumbnailFromLocalImage($sourceFileRootPath, $auctionImageId, $sizeType);
        }
    }

    /**
     * Delete thumbs(s|m|l|xl files in public folder)
     */
    public function deleteImageThumbs(int $auctionImageId): void
    {
        $thumbnailSizes = $this->cfg()->get('core->image->thumbnail')->toArray();
        foreach (array_keys($thumbnailSizes) as $sizeName) {
            $size = $this->createImageHelper()->detectSizeFromMapping($sizeName);
            $fileBasePath = AuctionImageUrlConfig::new()
                ->construct($auctionImageId, $size)
                ->fileBasePath();

            try {
                if ($this->createFileManager()->exist($fileBasePath)) {
                    $this->createFileManager()->delete($fileBasePath);
                }
            } catch (FileException) {
                log_error('Unable to remove static file ' . $fileBasePath);
            }
        }
    }

    /**
     * Create image thumbnails from local image
     *
     * @param AuctionImage $auctionImage
     * @param string[]|null $sizeTypes
     */
    public function createThumbnailsFromRemoteImage(
        AuctionImage $auctionImage,
        ?array $sizeTypes = null
    ): void {
        if ($sizeTypes === null) {
            $auctionListSize = ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->auctionList'));
            $sizeTypes = [$auctionListSize];
        }

        $image = $this->createImageCacheManager()
            ->setUrl($auctionImage->ImageLink)
            ->load();
        if (!$image) {
            $message = 'Failed to load remote image ' . $auctionImage->ImageLink;
            log_error($message);
            return;
        }

        foreach ($sizeTypes as $sizeType) {
            $this->createSingleThumbnailFromRemoteImage($image, $auctionImage->Id, $sizeType);
        }
    }

    /**
     * Create one image thumbnail for defined size type
     *
     * @param string $sourceFileRootPath
     * @param int $auctionImageId
     * @param string $sizeType
     * @return bool
     */
    protected function createSingleThumbnailFromLocalImage(string $sourceFileRootPath, int $auctionImageId, string $sizeType): bool
    {
        $sizeName = 'size' . strtoupper($sizeType);
        $thumbnailSizeName = $this->cfg()->get("core->image->thumbnail->{$sizeName}");
        if (!isset($thumbnailSizeName)) {
            return false;
        }
        if (!is_readable($sourceFileRootPath)) {
            log_warning('File does not exist or not readable' . composeSuffix(['source' => $sourceFileRootPath]));
            return false;
        }
        $image = file_get_contents($sourceFileRootPath);
        $width = $this->cfg()->get("core->image->thumbnail->{$sizeName}->width");
        $height = $this->cfg()->get("core->image->thumbnail->{$sizeName}->height");

        $urlConfig = AuctionImageUrlConfig::new()->construct($auctionImageId, $sizeType);
        $targetFileRootPath = path()->docRoot() . $urlConfig->urlFilled();

        $success = Resizer::new()
            ->setImage($image)
            ->setWidth($width)
            ->setHeight($height)
            ->setTargetFileRootPath($targetFileRootPath)
            ->resize();
        if (!$success) {
            log_warning('Failed to resize image' . composeSuffix(['source' => $sourceFileRootPath]));
        }

        return $success;
    }

    /**
     * Create one image thumbnail for defined size type
     *
     * @param string $image
     * @param int $auctionImageId
     * @param string $sizeType
     * @return bool
     */
    protected function createSingleThumbnailFromRemoteImage(string $image, int $auctionImageId, string $sizeType): bool
    {
        $sizeName = 'size' . strtoupper($sizeType);
        $thumbnailSizeName = $this->cfg()->get("core->image->thumbnail->{$sizeName}");
        if (!isset($thumbnailSizeName)) {
            return false;
        }
        $width = $this->cfg()->get("core->image->thumbnail->{$sizeName}->width");
        $height = $this->cfg()->get("core->image->thumbnail->{$sizeName}->height");

        $urlConfig = AuctionImageUrlConfig::new()->construct($auctionImageId, $sizeType);
        $targetFileRootPath = path()->docRoot() . $urlConfig->urlFilled();

        $success = Resizer::new()
            ->setImage($image)
            ->setWidth($width)
            ->setHeight($height)
            ->setTargetFileRootPath($targetFileRootPath)
            ->resize();

        return $success;
    }

    /**
     * Generate auction image name
     * @param string $image
     * @param int $auctionId
     * @return string
     */
    protected function generateName(string $image, int $auctionId): string
    {
        $fileName = $auctionId . '-' . time() . '.' . NameGenerator::new()->guessExtension($image);
        return $fileName;
    }
}
