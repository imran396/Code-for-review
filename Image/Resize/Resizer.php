<?php
/**
 * Class for image resizing
 *
 * SAM-4274: Remote image fetching improvements using ETag and expires and cache-control header to determine changes rather than last modified
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         $Id$
 * @since           Aug 4, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Image\Resize;

use Exception;
use Imagick;
use ImagickException;
use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\File\FolderManagerAwareTrait;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Log\Support\SupportLoggerAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class Resizer
 * @package Sam\Image\Resize
 */
class Resizer extends CustomizableClass
{
    use FileManagerCreateTrait;
    use FolderManagerAwareTrait;
    use SettingsManagerAwareTrait;
    use SupportLoggerAwareTrait;

    /**
     * @var string
     */
    protected string $image = '';

    /**
     * @var int
     */
    protected int $width = 0;

    /**
     * @var int
     */
    protected int $height = 0;

    /**
     * @var int|null
     */
    protected ?int $quality = 0; //for jpg

    /**
     * @var string
     */
    protected string $targetFileRootPath = '';

    /**
     * @var bool|null
     */
    protected ?bool $isFixImageOrientationEnabled = null;

    /**
     * @var bool
     */
    protected bool $needSaveImageAfterResize = true;

    /**
     * @var Imagick
     */
    protected Imagick $imageMagick;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        try {
            $this->imageMagick = new Imagick();
        } catch (ImagickException) {
            log_warning('Failed to initialize Imagick');
        }
        return $this;
    }

    /**
     * @param bool $needSaveImage
     * @return $this
     */
    public function needSaveImageAfterResize(bool $needSaveImage): static
    {
        $this->needSaveImageAfterResize = $needSaveImage;
        return $this;
    }

    /**
     * @param string $image
     * @return static
     */
    public function setImage(string $image): static
    {
        $this->image = trim($image);
        return $this;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        if (!$this->image) {
            throw new InvalidArgumentException('Image is empty');
        }
        return $this->image;
    }

    /**
     * @param int $width
     * @return static
     */
    public function setWidth(int $width): static
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        if ($this->width === 0) {
            $this->width = $this->imageMagick->getImageWidth();
        }
        return $this->width;
    }

    /**
     * @param int $height
     * @return static
     */
    public function setHeight(int $height): static
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        if ($this->height === 0) {
            $this->height = $this->imageMagick->getImageHeight();
        }
        return $this->height;
    }

    /**
     * @param int $quality
     * @return static
     */
    public function setQuality(int $quality): static
    {
        $this->quality = Cast::toInt($quality, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuality(): ?int
    {
        return $this->quality;
    }

    /**
     * @param string $fileRootPath
     * @return static
     */
    public function setTargetFileRootPath(string $fileRootPath): static
    {
        $this->targetFileRootPath = trim($fileRootPath);
        return $this;
    }

    /**
     * @return string
     */
    public function getTargetFileRootPath(): string
    {
        if (!$this->targetFileRootPath) {
            throw new InvalidArgumentException('Target file root path unknown');
        }
        return $this->targetFileRootPath;
    }

    /**
     * @return bool
     */
    public function resize(): bool
    {
        $wasImagickInitialized = $this->initImagick();
        if (!$wasImagickInitialized) {
            return false;
        }

        if (!$this->isValid()) {
            return false;
        }

        $this->fixImageOrientation();

        $success = false;

        $finalHeight = $this->getHeight();
        $finalWidth = $this->getWidth();

        try {
            if (
                $finalHeight
                && $finalWidth
            ) {
                $this->imageMagick->readImageBlob($this->getImage());
                if ($this->getQuality()) {
                    $this->imageMagick->setCompressionQuality($this->getQuality());
                }
                $this->imageMagick->setImageFormat(Constants\Image::IMAGE_JPEG);

                $existingImageHeight = $this->imageMagick->getImageHeight();
                $existingImageWidth = $this->imageMagick->getImageWidth();
                if (
                    $existingImageHeight > $finalHeight
                    || $existingImageWidth > $finalWidth
                ) {
                    $this->imageMagick->scaleImage($finalWidth, $finalHeight, true);
                }
                $this->image = $this->imageMagick->getImageBlob();
            }
            $success = $this->needSaveImageAfterResize ? $this->saveImage() : true;
        } catch (Exception) {
            $this->getSupportLogger()->error(sprintf('Failed to save image : "%s"', $this->getTargetFileRootPath()));
        }
        return $success;
    }

    /**
     * @param bool $enabled
     * @return static
     */
    public function enableFixImageOrientation(bool $enabled): static
    {
        $this->isFixImageOrientationEnabled = $enabled;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFixImageOrientationEnabled(): bool
    {
        if ($this->isFixImageOrientationEnabled === null) {
            $this->isFixImageOrientationEnabled = (bool)$this->getSettingsManager()
                ->getForSystem(Constants\Setting::IMAGE_AUTO_ORIENT);
        }
        return $this->isFixImageOrientationEnabled;
    }

    /**
     * @return void
     */
    protected function fixImageOrientation(): void
    {
        if (
            $this->isFixImageOrientationEnabled()
            && $this->imageMagick->getImageFormat() === Constants\Image::IMAGE_JPEG
        ) {
            switch ($this->imageMagick->getImageOrientation()) {
                case Imagick::ORIENTATION_TOPLEFT:
                    break;
                case Imagick::ORIENTATION_TOPRIGHT:
                    $this->imageMagick->flopImage();
                    break;
                case Imagick::ORIENTATION_BOTTOMRIGHT:
                    $this->imageMagick->rotateImage('#000', 180);
                    break;
                case Imagick::ORIENTATION_BOTTOMLEFT:
                    $this->imageMagick->flopImage();
                    $this->imageMagick->rotateImage('#000', 180);
                    break;
                case Imagick::ORIENTATION_LEFTTOP:
                    $this->imageMagick->flipImage();
                    $this->imageMagick->rotateImage('#000', 90);
                    break;
                case Imagick::ORIENTATION_RIGHTTOP:
                    $this->imageMagick->rotateImage('#000', 90);
                    break;
                case Imagick::ORIENTATION_RIGHTBOTTOM:
                    $this->imageMagick->flipImage();
                    $this->imageMagick->rotateImage('#000', 270);
                    break;
                case Imagick::ORIENTATION_LEFTBOTTOM:
                    $this->imageMagick->rotateImage('#000', 270);
                    break;
                default: // Invalid orientation
                    break;
            }
        }
        $this->imageMagick->stripImage();
        $this->image = $this->imageMagick->getImageBlob();
    }

    /**
     * @return bool
     */
    protected function saveImage(): bool
    {
        $staticFilePath = $this->getTargetFileRootPath();
        $pathInfo = pathinfo($staticFilePath);
        $rootPath = $pathInfo['dirname'];
        $permissions = $this->getFolderManager()->thumbnailPermissions();

        $isChmod = false;
        $isDir = is_dir($rootPath);
        if (!$isDir) {
            log_debug(
                'Directory for storing original account image is absent and will be created' . composeSuffix(
                    ['dir' => $rootPath]
                )
            );
            $oldMask = umask(0);
            $isChmod = @mkdir($rootPath, $permissions, true);
            umask($oldMask);
            if (
                !$isChmod
                && !is_dir($rootPath)
            ) {
                $this->getSupportLogger()->error(sprintf('Directory "%s" was not created', $rootPath));
                return false;
            }
        }
        if ($isChmod) {
            $this->getFolderManager()->chmodRecursively($rootPath, $permissions);
        }


        $relativeFilePath = substr($this->getTargetFileRootPath(), strlen(path()->sysRoot()));
        try {
            if ($this->createFileManager()->exist($relativeFilePath)) {
                $this->createFileManager()->delete($relativeFilePath);
            }
            $this->createFileManager()->write($this->imageMagick->getImageBlob(), $relativeFilePath);
            $this->getSupportLogger()->debug(sprintf('Image thumbnail saved "%s"', $rootPath));
            return true;
        } catch (FileException) {
            $this->getSupportLogger()->error(sprintf('Error creating static file "%s"', $relativeFilePath));
            return false;
        }
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if (
            !$this->image
            || !$this->imageMagick->valid()
        ) {
            return false;
        }
        $imageFormat = $this->imageMagick->getImageFormat();
        if (!in_array(
            $imageFormat,
            [Constants\Image::IMAGE_JPEG, Constants\Image::IMAGE_GIF, Constants\Image::IMAGE_PNG],
            true
        )) {
            return false;
        }
        return true;
    }

    protected function initImagick(): bool
    {
        try {
            $this->imageMagick->readImageBlob($this->getImage());
        } catch (ImagickException $e) {
            $this->getSupportLogger()->error(
                sprintf(
                    'Failed to fix image orientation for: %s : "%s"',
                    $e->getMessage(),
                    $this->getTargetFileRootPath()
                )
            );
            return false;
        }
        return true;
    }
}
