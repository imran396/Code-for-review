<?php
/**
 * SAM-1537: Walmart - Bulk Barcode/Image Import
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Associate;

use LotImageInBucket;
use LotItem;
use RuntimeException;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Lot\Image\BucketImport\Associate\Option\AssociationOption;
use Sam\Lot\Image\BucketImport\Associate\Order\LotImageAssociateOrderAdviserCreateTrait;
use Sam\Lot\Image\BucketImport\Associate\Order\LotImageOrderUpdaterCreateTrait;
use Sam\Lot\Image\BucketImport\Associate\Strategy\ImageAssociationStrategyFactoryCreateTrait;
use Sam\Lot\Image\BucketImport\Associate\Validate\BucketImportValidator;
use Sam\Lot\Image\BucketImport\Associate\Validate\BucketImportValidatorCreateTrait;
use Sam\Lot\Image\BucketImport\Delete\LotImageInBucketDeleterCreateTrait;
use Sam\Lot\Image\BucketImport\Path\LotImageInBucketPathResolverCreateTrait;
use Sam\Lot\Image\Delete\LotImageDeleterAwareTrait;
use Sam\Lot\Image\File\LotImageFileManagerCreateTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Lot\Image\Path\LotImagePathResolverCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotImage\LotImageWriteRepositoryAwareTrait;

/**
 * Class BucketImageAssociator
 * @package Sam\Lot\Image\BucketImport\Associate
 */
class BucketImageAssociator extends CustomizableClass
{
    use BucketImportValidatorCreateTrait;
    use EntityFactoryCreateTrait;
    use FileManagerCreateTrait;
    use ImageAssociationStrategyFactoryCreateTrait;
    use LotImageAssociateOrderAdviserCreateTrait;
    use LotImageDeleterAwareTrait;
    use LotImageFileManagerCreateTrait;
    use LotImageInBucketDeleterCreateTrait;
    use LotImageInBucketPathResolverCreateTrait;
    use LotImageLoaderAwareTrait;
    use LotImageOrderUpdaterCreateTrait;
    use LotImagePathResolverCreateTrait;
    use LotImageWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param array $bucketImages
     * @param AssociationOption $option
     * @param int $insertStrategy
     * @param bool $shouldRemoveAssignedImages
     * @param int $editorUserId
     * @return Result
     */
    public function associate(
        int $auctionId,
        array $bucketImages,
        AssociationOption $option,
        int $insertStrategy,
        bool $shouldRemoveAssignedImages,
        int $editorUserId
    ): Result {
        $validator = $this->createBucketImportValidator()->construct();
        if (!$validator->validateImages($bucketImages)) {
            return $this->createResult($validator);
        }

        $strategy = $this->createImageAssociationStrategyFactory()->create($option);

        $strategyValidator = $strategy->getValidator();
        if (
            $strategyValidator
            && !$strategyValidator->validate($bucketImages)
        ) {
            return Result::new()
                ->enableSuccess(false)
                ->setErrorMessages($strategyValidator->errorMessages());
        }

        $associationMap = $strategy->makeAssociationMap($auctionId, $bucketImages);
        $isValidAssociationMap = $validator->validateAssociationMap($associationMap);
        $result = $this->createResult($validator);
        if ($isValidAssociationMap) {
            $orderSuggester = $this->createLotImageAssociateOrderAdviser()->construct($insertStrategy);
            $lotImageOrderUpdater = $this->createLotImageOrderUpdater();
            $lotImageInBucketDeleter = $this->createLotImageInBucketDeleter();
            foreach ($associationMap->assigned as $association) {
                $lotItem = $association->lotItem;
                if ($insertStrategy === Constants\LotImageImport::INSERT_STRATEGY_REPLACE) {
                    $this->getLotImageDeleter()->deleteAllExceptSkipped($lotItem->Id, $editorUserId);
                } elseif ($insertStrategy === Constants\LotImageImport::INSERT_STRATEGY_PREPEND) {
                    $lotImageOrderUpdater->shiftLotItemImagesOrder(
                        $lotItem->Id,
                        count($association->images),
                        $editorUserId
                    );
                }
                foreach ($association->images as $image) {
                    $this->copyAndAssignBucketImageToLot(
                        $image,
                        $association->lotItem,
                        $strategy->makeLotImageFilename($image, $association->lotItem),
                        $orderSuggester->suggest($lotItem->Id),
                        $editorUserId
                    );
                    if ($shouldRemoveAssignedImages) {
                        $lotImageInBucketDeleter->delete($image, $editorUserId);
                        $result->addRemovedImageId($image->Id);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param LotImageInBucket $bucketImage
     * @param LotItem $lotItem
     * @param string $targetFileName
     * @param int $order
     * @param int $editorUserId
     * @throws FileException
     */
    protected function copyAndAssignBucketImageToLot(
        LotImageInBucket $bucketImage,
        LotItem $lotItem,
        string $targetFileName,
        int $order,
        int $editorUserId
    ): void {
        //If an image with this filename already associated with a lot, we should replace this image with the loaded one
        $lotImage = $this->getLotImageLoader()->loadFirstByAuctionIdAndImageLink($bucketImage->AuctionId, $targetFileName);
        $sourceFilePath = $this->createLotImageInBucketPathResolver()->makeFilePath($bucketImage);
        if (
            $lotImage
            && $lotImage->LotItemId !== $lotItem->Id
        ) {
            throw new RuntimeException(
                "Cannot replace image file '{$targetFileName}' for lot ID {$lotItem->Id} 
                since this image relates to another lot with ID {$lotImage->LotItemId}"
            );
        }

        $targetFilePath = $this->createLotImagePathResolver()->makeLotImageRelativePath($lotItem->AccountId, $targetFileName);
        try {
            $this->createFileManager()->copy($sourceFilePath, $targetFilePath);
        } catch (FileException) {
            log_warning('Failed to copy file' . composeSuffix(['source' => $sourceFilePath, 'target' => $targetFilePath]));
            return;
        }
        if ($lotImage) {
            log_info("Image from the bucket '{$bucketImage->ImageLink}' replaces file '{$targetFileName}'");
            // Remove static images for updated lot image
            $this->createLotImageFileManager()->delete($lotImage->ImageLink, $lotImage->Id, $lotItem->AccountId);
        } else {
            log_info("Image from the bucket '{$bucketImage->ImageLink}' saved as '{$targetFileName}'");
            $lotImage = $this->createEntityFactory()->lotImage();
            $lotImage->LotItemId = $lotItem->Id;
        }
        $lotImage->Size = $this->createFileManager()->getSize($targetFilePath);
        $lotImage->Order = $order;
        $lotImage->ImageLink = $targetFileName;
        $this->getLotImageWriteRepository()->saveWithModifier($lotImage, $editorUserId);
    }

    /**
     * @param BucketImportValidator $validator
     * @return Result
     */
    protected function createResult(BucketImportValidator $validator): Result
    {
        return Result::new()
            ->enableSuccess(count($validator->errorMessages()) === 0)
            ->setErrorMessages($validator->errorMessages())
            ->setWarningMessages($validator->warningMessages())
            ->setSuccessMessages($validator->successMessages());
    }
}
