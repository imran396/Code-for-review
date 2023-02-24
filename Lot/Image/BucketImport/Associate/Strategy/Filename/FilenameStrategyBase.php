<?php
/**
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

namespace Sam\Lot\Image\BucketImport\Associate\Strategy\Filename;

use LotImageInBucket;
use LotItem;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Lot\Image\BucketImport\Associate\AssociationMap\AssociationMap;
use Sam\Lot\Image\BucketImport\Associate\Strategy\StrategyInterface;
use Sam\Lot\Image\BucketImport\Associate\Strategy\StrategyValidatorInterface;
use Sam\Lot\Image\Path\LotImagePathResolverCreateTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;

/**
 * Class FilenameAssociationStrategyBase
 * @package Sam\Lot\Image\BucketImport\Associate\Strategy\Filename
 */
abstract class FilenameStrategyBase extends CustomizableClass implements StrategyInterface
{
    use FileManagerCreateTrait;
    use LotImageLoaderAwareTrait;
    use LotImagePathResolverCreateTrait;

    private const PATTERNS = [
        '/^([a-z0-9]+)\((\d+)\)$/i',
        '/^([a-z0-9]+)\.(\d+)$/i',
        '/^([a-z0-9]+)_(\d+)$/i',
        '/^([a-z0-9]+)-(\d+)$/i',
    ];

    /**
     * @inheritDoc
     */
    public function makeAssociationMap(int $auctionId, array $bucketImages): AssociationMap
    {
        $bucketImagesInfo = [];
        foreach ($bucketImages as $bucketImage) {
            $filename = $bucketImage->ImageLink;
            $fileNameComponents = $this->splitFilename($filename);
            $bucketImagesInfo[] = [
                'image' => $bucketImage,
                'searchValue' => $fileNameComponents['value'],
                'index' => $fileNameComponents['index']
            ];
        }
        $bucketImagesInfo = $this->sortBucketImagesInfoByIndex($bucketImagesInfo);
        $associationMap = AssociationMap::new();
        foreach ($bucketImagesInfo as $bucketImageInfo) {
            /** @var LotImageInBucket $bucketImage */
            $bucketImage = $bucketImageInfo['image'];
            $lotItems = $this->findLotItems($bucketImageInfo['searchValue'], $bucketImage->AuctionId);
            if ($lotItems) {
                foreach ($lotItems as $lotItem) {
                    $associationMap->addAssigned($lotItem, $bucketImage);
                }
            } else {
                $associationMap->addNotAssigned($bucketImage);
            }
        }
        return $associationMap;
    }

    /**
     * @inheritDoc
     */
    public function makeLotImageFilename(LotImageInBucket $bucketImage, LotItem $lotItem): string
    {
        $fileManager = $this->createFileManager();
        $lotImagePathResolver = $this->createLotImagePathResolver();

        $checkFileNames = [];    // files to be checked for - which of them can be replaced
        $checkFileName = $bucketImage->ImageLink;
        while ($fileManager->exist($lotImagePathResolver->makeLotImageRelativePath($lotItem->AccountId, $checkFileName))) {
            $checkFileNames[] = $checkFileName;
            $checkFileName = preg_replace('/(\.(gif|jpg|jpeg|png))$/i', '__' . $lotItem->Id . '\1', $checkFileName);
        }

        if (empty($checkFileNames)) {     // no file to replace, create new
            $fileName = $bucketImage->ImageLink;
        } else {
            $fileName = null;
            foreach ($checkFileNames as $checkFileName) {
                $lotImage = $this->getLotImageLoader()->loadFirstByAuctionIdAndImageLink($bucketImage->AuctionId, $checkFileName);
                if (
                    $lotImage
                    && $lotImage->LotItemId === $lotItem->Id
                ) {  // we found first related to auction lot for replacing
                    $fileName = $checkFileName;
                    break;
                }
            }
            if ($fileName === null) {     // no found for replacing (among prepared for checking), create new one with extended file name
                $fileName = preg_replace('/(\.(gif|jpg|jpeg|png))$/i', '__' . $lotItem->Id . '\1', array_pop($checkFileNames));
            }
        }

        return $fileName;
    }

    /**
     * @inheritDoc
     */
    public function getValidator(): ?StrategyValidatorInterface
    {
        return null;
    }

    /**
     * @param string $filename
     * @return array
     */
    protected function splitFilename(string $filename): array
    {
        $fileNameWithoutExt = substr($filename, 0, strrpos($filename, '.'));
        $result = [
            'value' => $fileNameWithoutExt,
            'index' => 0
        ];
        foreach (self::PATTERNS as $pattern) {
            if (preg_match($pattern, $fileNameWithoutExt, $matches)) {
                $result = [
                    'value' => $matches[1],
                    'index' => (int)$matches[2]
                ];
                break;
            }
        }
        return $result;
    }

    /**
     * @param string $search
     * @param int $auctionId
     * @return LotItem[]
     */
    abstract protected function findLotItems(string $search, int $auctionId): array;

    /**
     * @param array $bucketImagesInfo
     * @return array
     */
    protected function sortBucketImagesInfoByIndex(array &$bucketImagesInfo): array
    {
        usort(
            $bucketImagesInfo,
            static function (array $left, array $right) {
                if ((string)$left['index'] === (string)$right['index']) {
                    return 0;
                }
                return $left['index'] < $right['index'] ? -1 : 1;
            }
        );
        return $bucketImagesInfo;
    }
}
