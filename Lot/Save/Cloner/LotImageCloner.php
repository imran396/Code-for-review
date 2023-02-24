<?php
/**
 * SAM-3578: Buyer select from quantity for timed buy now
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Save\Cloner;

use LotImage;
use LotItem;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Lot\Image\File\LotImageFileManagerCreateTrait;
use Sam\Lot\Image\File\UniqueLotImageFileNameGeneratorCreateTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Lot\Image\Path\LotImagePathResolverCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotImage\LotImageWriteRepositoryAwareTrait;

/**
 * Class LotImageCloner
 * @package Sam\Lot\Save\Cloner
 */
class LotImageCloner extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use FileManagerCreateTrait;
    use LotImageFileManagerCreateTrait;
    use LotImageLoaderAwareTrait;
    use LotImagePathResolverCreateTrait;
    use LotImageWriteRepositoryAwareTrait;
    use UniqueLotImageFileNameGeneratorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotItem $targetLotItem
     * @param LotItem $sourceLotItem
     * @param int $editorUserId
     * @throws \Sam\File\Manage\FileException
     */
    public function cloneAll(LotItem $targetLotItem, LotItem $sourceLotItem, int $editorUserId): void
    {
        $lotImages = $this->getLotImageLoader()->loadForLot($sourceLotItem->Id, [], true);
        foreach ($lotImages as $lotItemImgOrig) {
            $this->cloneImage($lotItemImgOrig, $targetLotItem, $editorUserId);
        }
    }

    /**
     * @param LotItem $targetLotItem
     * @param LotItem $sourceLotItem
     * @param int $editorUserId
     */
    public function cloneDefault(LotItem $targetLotItem, LotItem $sourceLotItem, int $editorUserId): void
    {
        $lotItemImgOrig = $this->getLotImageLoader()->loadDefaultForLot($sourceLotItem->Id, true);
        if ($lotItemImgOrig) {
            $this->cloneImage($lotItemImgOrig, $targetLotItem, $editorUserId);
        }
    }

    /**
     * @param LotImage $lotItemImgOrig
     * @param LotItem $targetLotItem
     * @param int $editorUserId
     */
    protected function cloneImage(LotImage $lotItemImgOrig, LotItem $targetLotItem, int $editorUserId): void
    {
        $lotImage = $this->createEntityFactory()->lotImage();
        $lotImage->LotItemId = $targetLotItem->Id;

        $lotImage->Size = $lotItemImgOrig->Size; // Do not remove this line as it is important for copying of image

        if (UrlParser::new()->isSchemeWithHostOrIp($lotItemImgOrig->ImageLink)) {
            $lotImage->ImageLink = $lotItemImgOrig->ImageLink;
            $lotImage->Order = $lotItemImgOrig->Order;
            $this->getLotImageWriteRepository()->saveWithModifier($lotImage, $editorUserId);
        } else {
            $lotImageFileManager = $this->createLotImageFileManager();
            if ($lotImageFileManager->exist($lotItemImgOrig->ImageLink, $targetLotItem->AccountId)) {
                $absoluteImageFilePath = $this->createLotImagePathResolver()->makeLotImagePath($targetLotItem->AccountId, $lotItemImgOrig->ImageLink);
                $newImageFileName = $this->createUniqueLotImageFileNameGenerator()->generate($lotItemImgOrig->ImageLink, $targetLotItem->Id);
                try {
                    $lotImageFileManager->copyToLotImageDirectory($absoluteImageFilePath, $newImageFileName, $targetLotItem->AccountId);
                    $lotImage->ImageLink = $newImageFileName;
                    $lotImage->Order = $lotItemImgOrig->Order;
                } catch (FileException $e) {
                    log_error($e->getMessage());
                }
                $this->getLotImageWriteRepository()->saveWithModifier($lotImage, $editorUserId);
            }
        }
    }
}
