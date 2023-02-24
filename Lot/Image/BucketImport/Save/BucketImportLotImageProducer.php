<?php
/**
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 31, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Save;

use InvalidArgumentException;
use LotImage;
use LotImageInBucket;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Lot\Image\BucketImport\Delete\LotImageInBucketDeleterCreateTrait;
use Sam\Lot\Image\BucketImport\Path\LotImageInBucketPathResolverCreateTrait;
use Sam\Lot\Image\BucketImport\Save\Internal\Load\DataLoaderCreateTrait;
use Sam\Lot\Image\BucketImport\Save\Internal\Load\Dto\LotItemDto;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Lot\Image\Order\LotImageOrderAdviserCreateTrait;
use Sam\Lot\Image\Path\LotImagePathResolverCreateTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotImage\LotImageWriteRepositoryAwareTrait;

/**
 * Class BucketImportLotImageProducer
 * @package Sam\Lot\Image\BucketImport\Save
 */
class BucketImportLotImageProducer extends CustomizableClass
{
    use DataLoaderCreateTrait;
    use EntityFactoryCreateTrait;
    use FileManagerCreateTrait;
    use LotImageInBucketDeleterCreateTrait;
    use LotImageInBucketPathResolverCreateTrait;
    use LotImageLoaderAwareTrait;
    use LotImageOrderAdviserCreateTrait;
    use LotImagePathResolverCreateTrait;
    use LotImageWriteRepositoryAwareTrait;
    use LotRendererAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotImageInBucket $imageInBucket
     * @param int $lotItemId
     * @param bool $deleteImageFromBucket
     * @param int $editorUserId
     * @return BucketImportLotImageProductionResult
     * @throws FileException
     */
    public function produce(LotImageInBucket $imageInBucket, int $lotItemId, bool $deleteImageFromBucket, int $editorUserId): BucketImportLotImageProductionResult
    {
        $lotItem = $this->createDataLoader()->loadLotItemDto($lotItemId);
        if (!$lotItem) {
            $message = "Available lot item not found, when saving lot images" . composeSuffix(['li' => $lotItemId]);
            log_error($message);
            throw new InvalidArgumentException($message);
        }

        $targetFileName = $imageInBucket->ImageLink;
        if ($this->isExistLotImageFile($lotItem, $imageInBucket->ImageLink)) {
            if ($this->isExistLotItemImageInAuction($imageInBucket, $lotItemId)) {
                return $this->makeImageAlreadyAcceptedResult($imageInBucket, $lotItem);
            }

            $targetFileName = $this->makeUniqueLotImageFileName($lotItem, $imageInBucket->ImageLink);
        }

        $this->copyImageInBucketToLotImagePath($imageInBucket, $lotItem, $targetFileName);

        if ($this->isExistLotItemImage($lotItemId, $targetFileName)) {
            return $this->makeImageAlreadyAcceptedResult($imageInBucket, $lotItem);
        }

        $lotImage = $this->produceLotImageEntity($lotItem, $targetFileName, $editorUserId);

        if ($deleteImageFromBucket) {
            $this->createLotImageInBucketDeleter()->delete($imageInBucket, $editorUserId);
        }

        return BucketImportLotImageProductionResult::new()->constructSuccessResult($lotImage);
    }

    /**
     * @param LotItemDto $lotItemDto
     * @param string $fileName
     * @param int $editorUserId
     * @return LotImage
     * @throws FileException
     */
    protected function produceLotImageEntity(LotItemDto $lotItemDto, string $fileName, int $editorUserId): LotImage
    {
        $lotImage = $this->createEntityFactory()->lotImage();
        $lotImage->LotItemId = $lotItemDto->id;
        $lotImage->ImageLink = $fileName;
        $filePath = $this->createLotImagePathResolver()->makeLotImageRelativePath($lotItemDto->accountId, $fileName);
        $lotImage->Size = $this->createFileManager()->getSize($filePath);
        $lotImage->Order = $this->createLotImageOrderAdviser()->suggest($lotItemDto->id);
        $this->getLotImageWriteRepository()->saveWithModifier($lotImage, $editorUserId);
        return $lotImage;
    }

    /**
     * @param LotItemDto $lotItemDto
     * @param string $existingFileName
     * @return string
     */
    protected function makeUniqueLotImageFileName(LotItemDto $lotItemDto, string $existingFileName): string
    {
        $extension = substr($existingFileName, strrpos($existingFileName, '.') + 1);
        $nameWithoutExt = substr($existingFileName, 0, strrpos($existingFileName, '.'));
        $uniqueFileName = "{$nameWithoutExt}__{$lotItemDto->id}.{$extension}";
        return $uniqueFileName;
    }

    /**
     * @param LotItemDto $lotItemDto
     * @param string $fileName
     * @return bool
     */
    protected function isExistLotImageFile(LotItemDto $lotItemDto, string $fileName): bool
    {
        $filepath = $this->createLotImagePathResolver()->makeLotImageRelativePath($lotItemDto->accountId, $fileName);
        $isExist = $this->createFileManager()->exist($filepath);
        return $isExist;
    }

    /**
     * @param LotImageInBucket $imageInBucket
     * @param LotItemDto $lotItemDto
     * @param string $targetFileName
     */
    protected function copyImageInBucketToLotImagePath(LotImageInBucket $imageInBucket, LotItemDto $lotItemDto, string $targetFileName): void
    {
        $sourceFilePath = $this->createLotImageInBucketPathResolver()->makeFilePath($imageInBucket);
        $targetFilePath = $this->createLotImagePathResolver()->makeLotImageRelativePath($lotItemDto->accountId, $targetFileName);
        try {
            $this->createFileManager()->copy($sourceFilePath, $targetFilePath);
        } catch (FileException) {
            log_warning('Failed to copy file' . composeSuffix(['source' => $sourceFilePath, 'target' => $targetFilePath]));
        }
    }

    /**
     * @param LotImageInBucket $imageInBucket
     * @param int $lotItemId
     * @return bool
     */
    protected function isExistLotItemImageInAuction(LotImageInBucket $imageInBucket, int $lotItemId): bool
    {
        $lotImage = $this->getLotImageLoader()->loadFirstByAuctionIdAndImageLink($imageInBucket->AuctionId, $imageInBucket->ImageLink);
        $imageLotItemId = $lotImage->LotItemId ?? null;
        $isAccepted = $imageLotItemId === $lotItemId;
        return $isAccepted;
    }

    /**
     * @param int $lotItemId
     * @param string $fileName
     * @return bool
     */
    protected function isExistLotItemImage(int $lotItemId, string $fileName): bool
    {
        $lotImage = $this->getLotImageLoader()->loadByLotItemIdAndImageLink($lotItemId, $fileName, true);
        return $lotImage !== null;
    }

    /**
     * @param LotItemDto $lotItemDto
     * @param int|null $auctionId
     * @return string
     */
    protected function makeLotItemName(LotItemDto $lotItemDto, ?int $auctionId): string
    {
        $isTestAuction = $this->createDataLoader()->isTestAuction($auctionId);
        $name = $this->getLotRenderer()->makeName($lotItemDto->name, $isTestAuction);
        return $name;
    }

    /**
     * @param LotImageInBucket $imageInBucket
     * @param LotItemDto $lotItemDto
     * @return BucketImportLotImageProductionResult
     */
    protected function makeImageAlreadyAcceptedResult(LotImageInBucket $imageInBucket, LotItemDto $lotItemDto): BucketImportLotImageProductionResult
    {
        $lotName = $this->makeLotItemName($lotItemDto, $imageInBucket->AuctionId);
        $message = "{$imageInBucket->ImageLink} is already accepted to {$lotName}";
        return BucketImportLotImageProductionResult::new()->constructFailResult($message);
    }
}
