<?php
/**
 * SAM-4464: Apply Lot Image modules
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\Delete;

use LotImage;
use LotItem;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Image\File\LotImageFileManagerCreateTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Lot\Image\Path\LotImagePathResolverCreateTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\LotImage\LotImageReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotImage\LotImageWriteRepositoryAwareTrait;

/**
 * Class LotImageLoader
 * @package Sam\Lot\Image\Delete
 */
class LotImageDeleter extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use LotImageFileManagerCreateTrait;
    use FilesystemCacheManagerAwareTrait;
    use LotImageLoaderAwareTrait;
    use LotImagePathResolverCreateTrait;
    use LotImageReadRepositoryCreateTrait;
    use LotImageWriteRepositoryAwareTrait;
    use LotItemLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $lotImageId
     * @param int $editorUserId
     */
    public function delete(int $lotImageId, int $editorUserId): void
    {
        $lotImage = $this->getLotImageLoader()->load($lotImageId, true);
        if (!$lotImage) {
            log_error(
                "Available lot image not found, when removing image from lot" .
                composeSuffix(['limg' => $lotImageId])
            );
            return;
        }

        $lotItem = $this->getLotItemLoader()->load($lotImage->LotItemId);
        if (!$lotItem) {
            log_error(
                "Available lot item not found, when removing image from lot" .
                composeSuffix(['li' => $lotImage->LotItemId, 'limg' => $lotImageId])
            );
            return;
        }

        $this->deleteLotImage($lotImage, $lotItem, $editorUserId);
    }

    /**
     * Main function for deleting lot image
     *
     * @param int $lotItemId
     * @param int $editorUserId
     * @param int[] $skipLotImageIds
     * @return void
     */
    public function deleteAllExceptSkipped(int $lotItemId, int $editorUserId, array $skipLotImageIds = []): void
    {
        $lotItem = $this->getLotItemLoader()->load($lotItemId);
        if (!$lotItem) {
            log_error(
                "Available lot item not found, when removing unsaved image"
                . composeSuffix(['li' => $lotItemId])
            );
            return;
        }

        $lotImages = $this->createLotImageReadRepository()
            ->filterLotItemId($lotItemId)
            ->skipId($skipLotImageIds)
            ->loadEntities();
        foreach ($lotImages as $lotImage) {
            $this->deleteLotImage($lotImage, $lotItem, $editorUserId);
        }
    }

    /**
     * @param LotImage $lotImage
     * @param LotItem $lotItem
     * @param int $editorUserId
     */
    protected function deleteLotImage(LotImage $lotImage, LotItem $lotItem, int $editorUserId): void
    {
        $this->createLotImageFileManager()->delete($lotImage->ImageLink, $lotImage->Id, $lotItem->AccountId);
        $this->getLotImageWriteRepository()->deleteWithModifier($lotImage, $editorUserId);
    }
}
