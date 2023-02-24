<?php
/**
 * SAM-1537: Walmart - Bulk Barcode/Image Import
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

namespace Sam\Lot\Image\BucketImport\Associate\Strategy\Barcode;

use LotImageInBucket;
use LotItem;
use LotItemCustField;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Lot\Image\BucketImport\Associate\AssociationMap\AssociationMap;
use Sam\Lot\Image\BucketImport\Associate\Strategy\Barcode\Internal\BarcodeRecognizerCreateTrait;
use Sam\Lot\Image\BucketImport\Associate\Strategy\Barcode\Internal\Load\BarcodeAssociationLotLoaderCreateTrait;
use Sam\Lot\Image\BucketImport\Associate\Strategy\Barcode\Internal\Validate\BarcodeStrategyValidatorCreateTrait;
use Sam\Lot\Image\BucketImport\Associate\Strategy\StrategyInterface;
use Sam\Lot\Image\BucketImport\Associate\Strategy\StrategyValidatorInterface;
use Sam\Lot\Image\BucketImport\Path\LotImageInBucketPathResolverCreateTrait;
use Sam\Lot\Image\Path\LotImagePathResolverCreateTrait;

/**
 * Class BarcodeStrategy
 * @package Sam\Lot\Image\BucketImport\Associate\Strategy\Barcode
 */
class BarcodeStrategy extends CustomizableClass implements StrategyInterface
{
    use BarcodeAssociationLotLoaderCreateTrait;
    use BarcodeRecognizerCreateTrait;
    use BarcodeStrategyValidatorCreateTrait;
    use FileManagerCreateTrait;
    use LotImageInBucketPathResolverCreateTrait;
    use LotImagePathResolverCreateTrait;

    protected LotItemCustField $barcodeCustomField;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotItemCustField $barcodeCustomField
     * @return static
     */
    public function construct(LotItemCustField $barcodeCustomField): static
    {
        $this->barcodeCustomField = $barcodeCustomField;
        return $this;
    }

    /**
     * @param int $auctionId
     * @param array $bucketImages
     * @return AssociationMap
     */
    public function makeAssociationMap(int $auctionId, array $bucketImages): AssociationMap
    {
        $barcodeToImageMap = $this->makeBarcodeToImageMap($bucketImages);

        $associationMap = AssociationMap::new();
        foreach ($barcodeToImageMap as $barcodeText => $barcodeAssociation) {
            $lotItems = $this->createBarcodeAssociationLotLoader()->loadByBarcode($barcodeText, $this->barcodeCustomField->Id, $auctionId);
            if (!$lotItems) {
                $associationMap->addNotAssigned($barcodeAssociation['barcodeImage']);
                foreach ($barcodeAssociation['images'] as $image) {
                    $associationMap->addNotAssigned($image);
                }
            } else {
                foreach ($lotItems as $lotItem) {
                    foreach ($barcodeAssociation['images'] as $image) {
                        $associationMap->addAssigned($lotItem, $image);
                    }
                }
            }
        }
        return $associationMap;
    }

    /**
     * @param LotImageInBucket $bucketImage
     * @param LotItem $lotItem
     * @return string
     */
    public function makeLotImageFilename(LotImageInBucket $bucketImage, LotItem $lotItem): string
    {
        $fileManager = $this->createFileManager();
        $lotImagePathResolver = $this->createLotImagePathResolver();

        $fileName = $bucketImage->ImageLink;
        $extension = substr($fileName, strrpos($fileName, '.') + 1);
        $nameNoExt = substr($fileName, 0, strrpos($fileName, '.'));
        $nameAdd = '';
        while ($fileManager->exist($lotImagePathResolver->makeLotImageRelativePath($lotItem->AccountId, $fileName))) {
            $nameAdd .= '__' . $lotItem->Id;
            $fileName = $nameNoExt . $nameAdd . '.' . $extension;
        }
        return $fileName;
    }

    /**
     * @inheritDoc
     */
    public function getValidator(): ?StrategyValidatorInterface
    {
        return $this->createBarcodeStrategyValidator()->construct($this->barcodeCustomField->Name);
    }

    /**
     * @param array $bucketImages
     * @return array
     */
    protected function makeBarcodeToImageMap(array $bucketImages): array
    {
        $barcodeRecognizer = $this->createBarcodeRecognizer();
        $barcodeToImagesMap = [];
        $barcodeText = 'none';
        foreach ($bucketImages as $bucketImage) {
            $newBarcodeText = $barcodeRecognizer->recognize($bucketImage);
            if ($newBarcodeText) {
                $barcodeToImagesMap[$newBarcodeText] = [
                    'barcodeImage' => $bucketImage,
                    'images' => []
                ];
                $barcodeText = $newBarcodeText;
            } else {
                $barcodeToImagesMap[$barcodeText]['images'][] = $bucketImage;
            }
        }
        return $barcodeToImagesMap;
    }
}
