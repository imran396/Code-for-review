<?php
/**
 * Manage files (images and audio), which are uploaded in settings and for location
 */

namespace Sam\Settings;

use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Application\ApplicationAwareTrait;
use Sam\Core\Constants;
use Sam\Image\ImageHelper;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\Layout\Image\Path\LayoutImagePathResolverCreateTrait;
use Sam\Image\Resize\Resizer;
use Sam\Location\Load\LocationLoaderAwareTrait;
use Sam\Sound\RtbMessage\Path\RtbMessageSoundFilePathResolverCreateTrait;

/**
 * Class FileManager
 * @package Sam\Settings
 */
class SettingFileHelper extends CustomizableClass
{
    use ApplicationAwareTrait;
    use ConfigRepositoryAwareTrait;
    use FileManagerCreateTrait;
    use LayoutImagePathResolverCreateTrait;
    use LocationLoaderAwareTrait;
    use RtbMessageSoundFilePathResolverCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Save uploaded audio file in permanent location
     *
     * @param int $accountId
     * @param string $sourceFileRootPath
     * @param string $fileName
     * @throws FileException
     */
    public function saveAudio(int $accountId, string $sourceFileRootPath, string $fileName): void
    {
        $fileManager = $this->createFileManager();
        $sourceFilePath = substr($sourceFileRootPath, strlen(path()->sysRoot()));
        $settingsFileDir = path()->uploadSetting() . '/' . $accountId;
        $filePath = substr($settingsFileDir, strlen(path()->sysRoot())) . '/' . $fileName;
        $fileManager->move($sourceFilePath, $filePath);
    }

    /**
     * @param int $accountId
     * @param string $sourceFileRootPath
     * @param string $fileName
     * @throws FileException
     */
    public function saveRtbMessageAudio(int $accountId, string $sourceFileRootPath, string $fileName): void
    {
        $sourceFilePath = substr($sourceFileRootPath, strlen(path()->sysRoot()));
        $filePath = $this->createRtbMessageSoundFilePathResolver()->makeFilePath($accountId, $fileName);
        $this->createFileManager()->move($sourceFilePath, $filePath);
    }

    /**
     * @param int $accountId
     * @param string $fileName
     */
    public function deleteRtbMessageAudio(int $accountId, string $fileName): void
    {
        $filePath = $this->createRtbMessageSoundFilePathResolver()->makeFilePath($accountId, $fileName);
        try {
            $this->createFileManager()->delete($filePath);
        } catch (FileException $e) {
            log_error("Cannot delete audio file" . composeSuffix(['path' => $filePath, 'error' => $e->getMessage()]));
        }
    }

    /**
     * Save uploaded image in permanent location and create its thumbnails
     *
     * @param string $sourceFileRootPath temporary absolute path of uploaded file
     * @param string $fileName original file name
     * @param int $accountId
     * @param string $imageType type of settings image
     * @param int|null $locationId optional location.id when image type is self::LocationLogo
     */
    public function saveImage(
        string $sourceFileRootPath,
        string $fileName,
        int $accountId,
        string $imageType,
        ?int $locationId = null
    ): void {
        $destFilePath = path()->uploadSetting() . '/' . $accountId . '/' . $fileName;
        $blobImage = @file_get_contents($sourceFileRootPath);
        if (file_exists($sourceFileRootPath)) {
            unlink($sourceFileRootPath);
        }
        if (!$blobImage) {
            log_error("Image file read failed (file: {$sourceFileRootPath})");
            return;
        }

        $success = Resizer::new()
            ->setImage($blobImage)
            ->setTargetFileRootPath($destFilePath)
            ->resize();
        if (!$success) {
            log_error("Failed to upload settings image dest: $destFilePath");
            return;
        }
        $this->createThumbnail($destFilePath, $accountId, $imageType, $locationId);
    }

    /**
     * Create static thumbnail image
     *
     * @param string $sourceFileRootPath
     * @param int $accountId
     * @param string $imageType
     * @param int|null $locationId
     * @return bool
     */
    public function createThumbnail(
        string $sourceFileRootPath,
        int $accountId,
        string $imageType,
        ?int $locationId = null
    ): bool {
        $staticFilePath = path()->docRoot() . '/images/settings';
        $pathInfo = pathinfo($sourceFileRootPath);
        if ($imageType === Constants\Logo::LOCATION_LOGO) {
            $staticFileName = sprintf('%s_%d_%d.%s', $imageType, $accountId, $locationId, $pathInfo['extension']);
        } else {
            $staticFileName = sprintf('%s_%d.jpg', $imageType, $accountId);
        }
        $staticFilePath .= '/' . $staticFileName;
        $this->createStaticFile($sourceFileRootPath, $staticFilePath, $imageType);
        return false;
    }

    /**
     * @param string $sourceFileRootPath
     * @param string $staticFilePath
     * @param string $imageType
     * @return bool
     */
    public function createStaticFile(string $sourceFileRootPath, string $staticFilePath, string $imageType): bool
    {
        $size = match ($imageType) {
            Constants\Logo::INVOICE_LOGO => ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->invoiceLogo')),
            Constants\Logo::SETTLEMENT_LOGO => ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->settlementLogo')),
            Constants\Logo::HEADER_LOGO => ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->headerLogo')),
            Constants\Logo::LOCATION_LOGO => ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->locationLogo')),
            default => Constants\Image::EXTRA_LARGE,
        };
        $sizeName = 'size' . strtoupper($size);

        $thumbnailSizeName = $this->cfg()->get("core->image->thumbnail->{$sizeName}");
        if (!isset($thumbnailSizeName)) {
            return false;
        }
        $blobImage = @file_get_contents($sourceFileRootPath);
        if (!$blobImage) {
            log_warning('File does not exist or not readable' . composeSuffix(['file' => $sourceFileRootPath, 'img. type' => $imageType]));
            return false;
        }
        $width = $this->cfg()->get("core->image->thumbnail->{$sizeName}->width");
        $height = $this->cfg()->get("core->image->thumbnail->{$sizeName}->height");

        $success = Resizer::new()
            ->setImage($blobImage)
            ->setWidth($width)
            ->setHeight($height)
            ->setTargetFileRootPath($staticFilePath)
            ->resize();
        if (!$success) {
            log_warning('Failed to resize image' . composeSuffix(['source' => $sourceFileRootPath]));
        }

        return $success;
    }

    /**
     * Remove images from file system
     *
     * @param int $accountId
     * @param string $imageType
     * @param int|null $locationId
     * @return bool false if image empty and cant be deleted
     */
    public function removeImages(int $accountId, string $imageType, ?int $locationId = null): bool
    {
        $layoutImagePathResolver = $this->createLayoutImagePathResolver();
        $fileName = '';
        switch ($imageType) {
            case Constants\Logo::HEADER_LOGO:
                $fileName = $layoutImagePathResolver->detectPageHeaderLogoFileName($accountId);
                break;
            case Constants\Logo::INVOICE_LOGO:
                $fileName = $layoutImagePathResolver->detectInvoiceLogoFileName($accountId);
                break;
            case Constants\Logo::SETTLEMENT_LOGO:
                $fileName = $layoutImagePathResolver->detectSettlementLogoFileName($accountId);
                break;
            case Constants\Logo::LOCATION_LOGO:
                $location = $this->getLocationLoader()->load($locationId);
                $fileName = $location->Logo ?? '';
                break;
        }
        if ($fileName !== '') {
            $settingsImagesDir = path()->uploadSetting() . '/' . $accountId;
            $path = substr($settingsImagesDir, strlen(path()->sysRoot()));
            $fileManager = $this->createFileManager();
            try {
                $fileManager->delete($path . '/' . $fileName);
            } catch (FileException) {
                // file absent
            }
            $extension = substr($fileName, strrpos($fileName, '.') + 1);
            if ($imageType === Constants\Logo::LOCATION_LOGO) {
                $staticFileName = sprintf('%s_%d_%d.%s', $imageType, $accountId, $locationId, $extension);
            } else {
                $staticFileName = sprintf('%s_%d.%s', $imageType, $accountId, $extension);
            }
            $staticFilePath = substr(path()->docRoot(), strlen(path()->sysRoot())) . '/images/settings/' . $staticFileName;
            try {
                $fileManager->delete($staticFilePath);
            } catch (FileException) {
                // file absent
            }
            return true;
        }

        return false;
    }
}
