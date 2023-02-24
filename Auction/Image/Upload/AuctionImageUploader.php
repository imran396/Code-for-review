<?php

/**
 * Auction image uploader class.
 * SAM-4429: Admin image import client side resize
 *
 * @author        Oleg Kovalov
 * @version       SAM 2.0
 * @since         Oct 10, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.api
 *
 */

namespace Sam\Auction\Image\Upload;

use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\Image\Cache\ImageCacheManagerCreateTrait;
use Sam\Image\ImageHelper;
use Sam\Image\Resize\Resizer;

/**
 * Class Uploader
 * @package Sam\Auction\Image
 */
class AuctionImageUploader extends CustomizableClass
{
    use FileManagerCreateTrait;
    use ImageCacheManagerCreateTrait;
    use LocalFileManagerCreateTrait;

    /**
     * @var int|null
     */
    protected ?int $errorCode = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $tmpFile
     * @param bool $wasResized
     * @param bool $doImageOrient
     * @return string|null
     */
    public function uploadTmpImage(string $tmpFile, bool $wasResized, bool $doImageOrient): ?string
    {
        $tmpFileName = md5((string)mt_rand()) . '.jpg';
        $blobImage = @file_get_contents($tmpFile);
        if (!$blobImage) {
            log_warning('Failed to load tmp file. src: ' . $tmpFile);
            return null;
        }
        $uploadImagePath = path()->temporary() . '/' . $tmpFileName;
        if ($wasResized) {
            $blobImage = ImageHelper::new()->getOriginalImageGeometry($blobImage);
        }
        $success = Resizer::new()
            ->setFileManager($this->createLocalFileManager())
            ->enableFixImageOrientation($doImageOrient)
            ->setImage($blobImage)
            ->setTargetFileRootPath($uploadImagePath)
            ->resize();

        if ($success) {
            return $tmpFileName;
        }
        return null;
    }

    /**
     * @param string $tmpImagePath
     * @param string $destImagePath
     * @param bool $enableFixImageOrientation
     * @return bool
     */
    public function moveTmpImage(string $tmpImagePath, string $destImagePath, bool $enableFixImageOrientation): bool
    {
        $sourceImagePath = path()->sysRoot() . $tmpImagePath;
        $blobImage = @file_get_contents($sourceImagePath);
        if (!$blobImage) {
            log_warning('Failed to read tmp image :' . $sourceImagePath);
            return false;
        }
        $staticPath = path()->sysRoot() . $destImagePath;
        $success = Resizer::new()
            ->enableFixImageOrientation($enableFixImageOrientation)
            ->setTargetFileRootPath($staticPath)
            ->setImage($blobImage)
            ->resize();
        if (!$success) {
            log_warning('Failed to upload tmp image: ' . $staticPath);
        }
        @unlink($sourceImagePath);
        return $success;
    }

    /**
     * @param string $url
     * @return bool
     */
    public function uploadRemoteImage(string $url): bool
    {
        $imageCacheManager = $this->createImageCacheManager();
        $blobImage = $imageCacheManager->setUrl($url)
            ->load();
        if (!$blobImage) {
            $this->errorCode = $imageCacheManager->getErrorCode();
            return false;
        }
        return true;
    }

    /**
     * @return int|null
     */
    public function getErrorCode(): ?int
    {
        return $this->errorCode;
    }
}
