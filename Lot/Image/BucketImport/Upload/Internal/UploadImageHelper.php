<?php
/**
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 2, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Upload\Internal;

use Psr\Http\Message\UploadedFileInterface;
use Sam\Core\Service\CustomizableClass;
use Sam\Image\ImageHelper;
use Sam\Image\Resize\Resizer;

/**
 * Class UploadImageHelper
 * @package Sam\Lot\Image\BucketImport\Upload\Internal
 * @interanl
 */
class UploadImageHelper extends CustomizableClass
{
    protected const THUMB_WIDTH = 60;
    protected const THUMB_HEIGHT = 60;

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
     * @return array
     */
    public function detectImageSize(UploadedFileInterface $file): array
    {
        $file->getStream()->rewind();
        $blobImage = $file->getStream()->getContents();
        if (!$blobImage) {
            return [];
        }
        $imageInfo = @getimagesizefromstring($blobImage);
        if (!$imageInfo) {
            return [];
        }
        return ['width' => $imageInfo[0], 'height' => $imageInfo[1]];
    }

    /**
     * @param UploadedFileInterface $file
     * @param string $originalFilePath
     * @param string $thumbFilePath
     * @param bool $fixImageOrientation
     * @param bool $wasResizedOnClientSide
     * @return bool
     */
    public function resize(
        UploadedFileInterface $file,
        string $originalFilePath,
        string $thumbFilePath,
        bool $fixImageOrientation,
        bool $wasResizedOnClientSide
    ): bool {
        $file->getStream()->rewind();
        $blobImage = $file->getStream()->getContents();
        if (!$blobImage) {
            return false;
        }
        // If image was resized on client, then it was automatically rotated too. It's impossible to prevent such behavior, but we need original image.
        if ($wasResizedOnClientSide) {
            $blobImage = ImageHelper::new()->getOriginalImageGeometry($blobImage);
        }

        $resizer = Resizer::new();
        $successUpload = $resizer
            ->enableFixImageOrientation($fixImageOrientation)
            ->setImage($blobImage)
            ->setTargetFileRootPath(path()->sysRoot() . $originalFilePath)
            ->resize();

        $successThumbCreated = $resizer->setHeight(self::THUMB_HEIGHT)
            ->setWidth(self::THUMB_WIDTH)
            ->enableFixImageOrientation(false)
            ->setTargetFileRootPath(path()->sysRoot() . $thumbFilePath)
            ->resize();

        return $successUpload && $successThumbCreated;
    }
}
