<?php
/**
 * Image Helper class.
 * SAM-4429: Admin image import client side resize
 *
 * @author        Oleg Kovalov
 * @version       SAM 2.0
 * @since         Oct 14, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.api
 *
 */

namespace Sam\Image;

use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Imagick;
use ImagickException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Core\Constants;
use Sam\Image\Resize\Resizer;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class ImageHelper
 * @package Sam\Image
 */
class ImageHelper extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use FileManagerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return image file correct extension
     * @param string $filePath
     * @return string
     */
    public function findImageExtension(string $filePath): string
    {
        try {
            $imageInfo = $this->createFileManager()->getImageInfo($filePath);
            $extensions = [IMAGETYPE_GIF => 'gif', IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png'];
            if (array_key_exists($imageInfo[2], $extensions)) {
                return $extensions[$imageInfo[2]];
            }
        } catch (FileException) {
        }
        return '';
    }

    /**
     * @param string|null $mapping - null for default size
     * @return string
     */
    public function detectSizeFromMapping(?string $mapping = null): string
    {
        $defaultSize = Constants\Image::EXTRA_LARGE;
        if (!$mapping) {
            return $defaultSize;
        }
        $thumbnailMapping = $this->cfg()->get("core->image->thumbnail->{$mapping}");
        if (isset($thumbnailMapping)) {
            return strtolower(substr($mapping, 4));
        }
        return $defaultSize;
    }

    /**
     * Get original image using exif data if auto-orient was applied in past
     *
     * @param string $blobImage
     * @return string
     */
    public function getOriginalImageGeometry(string $blobImage): string
    {
        try {
            $imagick = new Imagick();
            $imagick->readImageBlob($blobImage);
            if ($imagick->getImageFormat() === Constants\Image::IMAGE_JPEG) {
                switch ($imagick->getImageOrientation()) {
                    case Imagick::ORIENTATION_TOPLEFT:
                        break;
                    case Imagick::ORIENTATION_TOPRIGHT:
                        $imagick->flopImage();
                        break;
                    case Imagick::ORIENTATION_BOTTOMRIGHT:
                        $imagick->rotateImage('#000', 180);
                        break;
                    case Imagick::ORIENTATION_BOTTOMLEFT:
                        $imagick->flopImage();
                        $imagick->rotateImage('#000', 180);
                        break;
                    case Imagick::ORIENTATION_LEFTTOP:
                        $imagick->flipImage();
                        $imagick->rotateImage('#000', -90);
                        break;
                    case Imagick::ORIENTATION_RIGHTTOP:
                        $imagick->rotateImage('#000', -90);
                        break;
                    case Imagick::ORIENTATION_RIGHTBOTTOM:
                        $imagick->flipImage();
                        $imagick->rotateImage('#000', -270);
                        break;
                    case Imagick::ORIENTATION_LEFTBOTTOM:
                        $imagick->rotateImage('#000', -270);
                        break;
                    default: // Invalid orientation
                        break;
                }
                $blobImage = $imagick->getImageBlob();
            }
        } catch (ImagickException) {
            log_warning('Failed to extract original image based on exif data');
        }
        return $blobImage;
    }

    /**
     * Return original file path (relative for project's sysRoot) of empty image stub
     * @return string
     */
    public function makeEmptyStubOriginalFilePath(): string
    {
        return substr($this->makeEmptyStubOriginalFileRootPath(), strlen(path()->sysRoot()));
    }

    /**
     * Return original file root path of coming soon image stub
     * @return string
     */
    public function makeEmptyStubOriginalFileRootPath(): string
    {
        return path()->baseImage() . '/' . Constants\Image::EMPTY_STUB_ORIGINAL_FILE_NAME;
    }

    /**
     * Return original file root path of empty image stub
     * @return string
     */
    public function makeComingSoonStubOriginalFileRootPath(): string
    {
        return path()->baseImage() . '/' . Constants\Image::COMING_SOON_STUB_ORIGINAL_FILE_NAME;
    }

    public function createDefaultLotImageThumbsFromStub(): void
    {
        $defaultStubPath = $this->makeEmptyStubOriginalFileRootPath();
        $destThumbPath = path()->defaultLotImages();
        $fileNamePrefix = '0_';
        $this->generateThumbsFromStub($defaultStubPath, $destThumbPath, $fileNamePrefix);
    }

    public function createComingSoonLotImageThumbsFromStub(): void
    {
        $defaultStubPath = $this->makeComingSoonStubOriginalFileRootPath();
        $destThumbPath = path()->defaultLotImages();
        $fileNamePrefix = 'coming_soon_';
        $this->generateThumbsFromStub($defaultStubPath, $destThumbPath, $fileNamePrefix);
    }

    public function createDefaultAuctionImageThumbsFromStub(): void
    {
        $defaultStubPath = $this->makeEmptyStubOriginalFileRootPath();
        $destThumbPath = path()->defaultAuctionImages();
        $fileNamePrefix = '0_';
        $this->generateThumbsFromStub($defaultStubPath, $destThumbPath, $fileNamePrefix);
    }

    public function createDefaultAccountImageThumbsFromStub(): void
    {
        $defaultStubPath = $this->makeEmptyStubOriginalFileRootPath();
        $destThumbPath = path()->defaultAccountImages();
        $fileNamePrefix = '0_';
        $this->generateThumbsFromStub($defaultStubPath, $destThumbPath, $fileNamePrefix);
    }

    public function generateThumbsFromStub(string $stubPath, string $destPath, string $fileNamePrefix): void
    {
        $thumbnailSizes = $this->cfg()->get('core->image->thumbnail')->toArray();
        foreach ($thumbnailSizes as $sizeName => $data) {
            $size = strtolower(substr($sizeName, 4));
            $width = (int)$data['width'];
            $height = (int)$data['height'];
            $blobImage = file_get_contents($stubPath);
            $targetFileRootPath = $destPath . '/' . $fileNamePrefix . $size . '.jpg';

            $success = Resizer::new()
                ->enableFixImageOrientation(false)
                ->setImage($blobImage)
                ->setHeight($height)
                ->setWidth($width)
                ->setTargetFileRootPath($targetFileRootPath)
                ->resize();
            if (!$success) {
                log_warning('Failed to resize image :' . $stubPath);
            }
        }
    }
}
