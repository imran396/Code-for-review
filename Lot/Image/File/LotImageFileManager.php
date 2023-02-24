<?php
/**
 * SAM-11587: Refactor Qform_UploadHelper for v4.0
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\File;

use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Lot\Image\Path\LotImagePathResolverCreateTrait;

/**
 * Class LotImageFileManager
 * @package Sam\Lot\Image\FileManager
 */
class LotImageFileManager extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use FileManagerCreateTrait;
    use FilesystemCacheManagerAwareTrait;
    use LotImageLoaderAwareTrait;
    use LotImagePathResolverCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @throws FileException
     */
    public function moveToLotImageDirectory(string $sourceFilePath, string $imageFileName, int $accountId): void
    {
        $destFileRelativePath = $this->createLotImagePathResolver()->makeLotImageRelativePath($accountId, $imageFileName);
        $fileManager = $this->createFileManager();
        if ($fileManager->exist($destFileRelativePath)) {
            log_error("Lot image '{$imageFileName}' already exists");
            return;
        }
        $sourceFileRelativePath = substr($sourceFilePath, strlen(path()->sysRoot()));
        $fileManager->move($sourceFileRelativePath, $destFileRelativePath);
        log_info("Image {$sourceFileRelativePath} moved to {$destFileRelativePath}");
    }

    /**
     * @throws FileException
     */
    public function copyToLotImageDirectory(string $sourceFilePath, string $imageFileName, int $accountId): void
    {
        $destFileRelativePath = $this->createLotImagePathResolver()->makeLotImageRelativePath($accountId, $imageFileName);
        $fileManager = $this->createFileManager();
        if ($fileManager->exist($destFileRelativePath)) {
            log_error("Lot image '{$imageFileName}' already exists");
            return;
        }
        $sourceFileRelativePath = substr($sourceFilePath, strlen(path()->sysRoot()));
        $fileManager->copy($sourceFileRelativePath, $destFileRelativePath);
        log_info("Image {$sourceFileRelativePath} copied to {$destFileRelativePath}");
    }

    /**
     * @throws FileException
     */
    public function getSize(string $imageFileName, int $accountId): int
    {
        $imageFileRelativePath = $this->createLotImagePathResolver()->makeLotImageRelativePath($accountId, $imageFileName);
        return $this->createFileManager()->getSize($imageFileRelativePath);
    }

    /**
     * @throws FileException
     */
    public function exist(string $imageFileName, int $accountId): bool
    {
        $imageFileRelativePath = $this->createLotImagePathResolver()->makeLotImageRelativePath($accountId, $imageFileName);
        return $this->createFileManager()->exist($imageFileRelativePath);
    }

    public function delete(string $imageFileName, int $lotImageId, int $accountId): void
    {
        // Do not delete image file assigned to another lot image record
        $isAssignedToAnother = $this->getLotImageLoader()->loadByImageLink($imageFileName, [], [$lotImageId]);
        if (!$isAssignedToAnother) {
            if (filter_var($imageFileName, FILTER_VALIDATE_URL)) {
                $this->deleteRemote($imageFileName);
            } else {
                $this->deleteLocal($imageFileName, $accountId);
            }
            $this->deleteThumbs($lotImageId);
            log_info("Lot image {$imageFileName} deleted." . composeSuffix(['limg' => $lotImageId, 'acc' => $accountId]));
        }
    }

    /**
     * Delete remote lot image by url which is stored as cache file
     *
     * @param string $url
     */
    protected function deleteRemote(string $url): void
    {
        $fileManager = call_user_func([$this->cfg()->get('core->filesystem->managerClass'), 'new']);
        $fileSystemCacheManager = $this->getFilesystemCacheManager()
            ->setWriter($fileManager)
            ->setExtension('txt');
        $fileSystemCacheManager->setNamespace('remote-image-blob')
            ->delete($url);

        $fileSystemCacheManager->setNamespace('remote-image-header')
            ->delete($url);
    }

    /**
     * Delete lot image from /var/upload/lot_item folder which is uploaded as regular file through web interface
     *
     * @param string $imageFileName
     * @param int $accountId
     */
    protected function deleteLocal(string $imageFileName, int $accountId): void
    {
        $filePath = $this->createLotImagePathResolver()->makeLotImageRelativePath($accountId, $imageFileName);
        try {
            // otherwise image_link possibly is external url
            if ($this->createFileManager()->exist($filePath)) {
                $this->createFileManager()->delete($filePath);
            }
        } catch (FileException) {
            log_warning('Failed to remove lot image: ' . $filePath);
        }
    }

    /**
     * Delete thumbs(s|m|l|xl files in public folder) for related lot image
     */
    protected function deleteThumbs(int $lotImageId): void
    {
        $thumbnailSizes = $this->cfg()->get('core->image->thumbnail')->toArray();
        $thumbnailSizeNames = array_keys($thumbnailSizes);
        foreach ($thumbnailSizeNames as $sizeName) {
            $sizeIndex = strtolower(substr($sizeName, 4));
            $fileBasePath = LotImageUrlConfig::new()
                ->construct($lotImageId, $sizeIndex)
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
}
