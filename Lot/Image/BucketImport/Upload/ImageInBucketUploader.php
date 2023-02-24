<?php
/**
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 1, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Upload;

use Psr\Http\Message\UploadedFileInterface;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Lot\Image\BucketImport\Path\LotImageInBucketPathResolverCreateTrait;
use Sam\Lot\Image\BucketImport\Upload\Internal\UploadImageHelperCreateTrait;

/**
 * Class ImageInBucketUploader
 * @package Sam\Lot\Image\BucketImport\Upload
 */
class ImageInBucketUploader extends CustomizableClass
{
    use FileManagerCreateTrait;
    use LotImageInBucketPathResolverCreateTrait;
    use UploadImageHelperCreateTrait;

    private const MAX_IMAGE_DIMENSIONS = 3000 * 3000;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param UploadedFileInterface $file
     * @param int $auctionId
     * @param bool $wasResized
     * @param bool $fixImageOrientation
     * @return UploadResult
     */
    public function upload(UploadedFileInterface $file, int $auctionId, bool $wasResized, bool $fixImageOrientation): UploadResult
    {
        $pathHelper = $this->createLotImageInBucketPathResolver();
        $targetDirectoryPath = $pathHelper->makePath($auctionId);
        $fileName = $this->makeUniqueFileName($file->getClientFilename(), $targetDirectoryPath);
        $filePath = $targetDirectoryPath . '/' . $fileName;

        if (!$this->isValidImage($file)) {
            return UploadResult::new()->construct($fileName)
                ->addError(UploadResult::ERR_INVALID_IMAGE);
        }

        $uploadImageHelper = $this->createUploadImageHelper();
        $success = $uploadImageHelper->resize(
            $file,
            $filePath,
            $pathHelper->makeThumbFilePath($fileName, $auctionId),
            $fixImageOrientation,
            $wasResized
        );

        if (!$success) {
            log_warning('Failed to resize image' . composeSuffix(['source' => $filePath]));
            return UploadResult::new()->construct()
                ->addError(UploadResult::ERR_RESIZE_FAILED);
        }

        $result = UploadResult::new()->construct($fileName, $pathHelper->makeThumbUrl($fileName, $auctionId))
            ->addSuccess(UploadResult::OK_UPLOADED);
        return $result;
    }

    /**
     * @param UploadedFileInterface $file
     * @return bool
     */
    protected function isValidImage(UploadedFileInterface $file): bool
    {
        if ($file->getError() !== UPLOAD_ERR_OK) {
            log_error("Invalid image" . composeSuffix(['file' => $file->getClientFilename()]));
            return false;
        }
        $size = $this->createUploadImageHelper()->detectImageSize($file);
        if (!$size) {
            log_error("Invalid image" . composeSuffix(['file' => $file->getClientFilename()]));
            return false;
        }
        if ($size['width'] * $size['height'] > self::MAX_IMAGE_DIMENSIONS) {
            log_error("Image dimensions are too large" . composeSuffix(['file' => $file->getClientFilename()]));
            return false;
        }
        return true;
    }

    /**
     * @param string $originalFileName
     * @param string $targetDirectoryPath
     * @return string
     */
    protected function makeUniqueFileName(string $originalFileName, string $targetDirectoryPath): string
    {
        $extension = substr($originalFileName, strrpos($originalFileName, '.') + 1);
        $nameWithoutExt = substr($originalFileName, 0, strrpos($originalFileName, '.'));
        $increment = 0;
        $name = $originalFileName;
        $fileManager = $this->createFileManager();
        while ($fileManager->exist($targetDirectoryPath . '/' . $name)) {
            $increment++;
            $name = "{$nameWithoutExt}({$increment}).{$extension}";
        }
        return $name;
    }
}
