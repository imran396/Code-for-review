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
 * @since           Mar. 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Associate\Validate;

use LotImageInBucket;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Path\PathResolver;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Lot\Image\BucketImport\Associate\AssociationMap\AssociationMap;
use Sam\Lot\Image\BucketImport\Associate\AssociationMap\LotItemAssociation;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;

/**
 * Class AssociationMapValidator
 * @package Sam\Lot\Image\BucketImport\Associate\Validate
 */
class BucketImportValidator extends CustomizableClass
{
    use FileManagerCreateTrait;
    use LotImageLoaderAwareTrait;
    use LotRendererAwareTrait;
    use OptionalsTrait;
    use ResultStatusCollectorAwareTrait;

    public const OP_LOT_IMAGE_LIMIT = 'lotImageLimit';

    public const ERR_NO_IMAGES = 1;
    public const ERR_UNSUPPORTED_IMAGE_FORMAT = 2;
    public const ERR_LOT_IMAGE_LIMIT_EXCEED = 3;

    public const WARN_NOT_ASSIGNED_IMAGE = 4;

    public const SUCCESS_ASSIGNED_IMAGE = 5;

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
        $this->getResultStatusCollector()->construct(
            [
                self::ERR_NO_IMAGES => 'No images supplied',
                self::ERR_UNSUPPORTED_IMAGE_FORMAT => 'Unsupported format of image file "%s"',
                self::ERR_LOT_IMAGE_LIMIT_EXCEED => 'Image per lot limit (%s of %s) exceeded for item# %s'
            ],
            [
                self::SUCCESS_ASSIGNED_IMAGE => 'Images assigned to lot %s: %s',
            ],
            [
                self::WARN_NOT_ASSIGNED_IMAGE => 'No lots were found for image "%s"',
            ]
        );
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param LotImageInBucket[] $imageInBuckets
     * @return bool
     */
    public function validateImages(array $imageInBuckets): bool
    {
        $resultStatusCollector = $this->getResultStatusCollector();
        if (count($imageInBuckets) === 0) {
            $resultStatusCollector->addError(self::ERR_NO_IMAGES);
            return false;
        }
        foreach ($imageInBuckets as $imageInBucket) {
            $this->checkImageFormat($imageInBucket->ImageLink, $imageInBucket->AuctionId);
        }
        return !$resultStatusCollector->hasError();
    }

    /**
     * @param AssociationMap $associationMap
     * @return bool
     */
    public function validateAssociationMap(AssociationMap $associationMap): bool
    {
        foreach ($associationMap->assigned as $association) {
            $this->checkLotImagesQuantity($association);
        }
        $resultStatusCollector = $this->getResultStatusCollector();
        $isValid = !$resultStatusCollector->hasError();
        if ($isValid) {
            foreach ($associationMap->notAssigned as $notAssignedImage) {
                $resultStatusCollector->addWarningWithInjectedInMessageArguments(
                    self::WARN_NOT_ASSIGNED_IMAGE,
                    [$notAssignedImage->ImageLink]
                );
            }
            foreach ($associationMap->assigned as $association) {
                $lotName = $this->getLotRenderer()->makeName($association->lotItem->Name);
                $images = ArrayHelper::toArrayByProperty($association->images, 'ImageLink');
                $imageList = implode(', ', $images);
                $resultStatusCollector->addSuccessWithInjectedInMessageArguments(
                    self::SUCCESS_ASSIGNED_IMAGE,
                    [$lotName, $imageList]
                );
            }
        }
        return $isValid;
    }

    /**
     * @return array|string[]
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * @return array|string[]
     */
    public function warningMessages(): array
    {
        return $this->getResultStatusCollector()->getWarningMessages();
    }

    /**
     * @return array|string[]
     */
    public function successMessages(): array
    {
        return $this->getResultStatusCollector()->getSuccessMessages();
    }

    /**
     * @param string $fileName
     * @param int $auctionId
     */
    protected function checkImageFormat(string $fileName, int $auctionId): void
    {
        $filePath = $this->getFilePath($fileName, $auctionId);
        try {
            $imageInfo = $this->createFileManager()->getImageInfo($filePath);
            $isValid = $imageInfo
                && in_array((int)$imageInfo[2], [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true);
        } catch (FileException) {
            $isValid = false;
        }
        if (!$isValid) {
            $this->getResultStatusCollector()->addErrorWithInjectedInMessageArguments(
                self::ERR_UNSUPPORTED_IMAGE_FORMAT,
                [$fileName]
            );
        }
    }

    /**
     * @param LotItemAssociation $association
     */
    protected function checkLotImagesQuantity(LotItemAssociation $association): void
    {
        $actualQuantity = $this->getLotImageLoader()->countByLotItemId($association->lotItem->Id);
        $futureQuantity = $actualQuantity + count($association->images);
        if ($futureQuantity > $this->fetchOptional(self::OP_LOT_IMAGE_LIMIT)) {
            $resultStatusCollector = $this->getResultStatusCollector();
            $resultStatusCollector->addErrorWithInjectedInMessageArguments(
                self::ERR_LOT_IMAGE_LIMIT_EXCEED,
                [
                    $futureQuantity,
                    (int)$this->fetchOptional(self::OP_LOT_IMAGE_LIMIT),
                    $this->getLotRenderer()->renderItemNo($association->lotItem)
                ]
            );
        }
    }

    /**
     * @param string $filename
     * @param int $auctionId
     * @return string
     */
    protected function getFilePath(string $filename, int $auctionId): string
    {
        $filepath = PathResolver::UPLOAD_LOT_IMAGE_BUCKET . '/' . $auctionId . '/' . $filename;
        return $filepath;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_LOT_IMAGE_LIMIT] = $optionals[self::OP_LOT_IMAGE_LIMIT]
            ?? static function () {
                return ConfigRepository::getInstance()->get('core->lot->image->perLotLimit');
            };
        $this->setOptionals($optionals);
    }
}
