<?php
/**
 * SAM-7914: Refactor \LotImage_UploadLotImage
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoForm\Image\Upload;

use Sam\Core\Service\CustomizableClass;
use Sam\Image\Cache\ImageCacheManagerCreateTrait;

/**
 * Class ImageFactory
 * @package Sam\View\Admin\Form\LotInfoForm\Image\Upload
 */
class ImageInfoFactory extends CustomizableClass
{
    use ImageCacheManagerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $file
     * @return ImageInfo
     */
    public function createFromFileArray(array $file): ImageInfo
    {
        $imageLink = $file['tmp_name'];
        $blobImage = is_readable($imageLink)
            ? file_get_contents($imageLink)
            : null;

        if (!$blobImage) {
            log_warning('File does not exist or not readable' . composeSuffix(['source' => $imageLink]));
        }

        $image = ImageInfo::new()->construct(
            $blobImage,
            pathinfo($file['name'], PATHINFO_FILENAME),
            pathinfo($file['name'], PATHINFO_EXTENSION)
        );

        return $image;
    }

    /**
     * @param string $imageUrl
     * @return ImageInfo|null
     */
    public function createFromUrl(string $imageUrl): ?ImageInfo
    {
        $blobImage = $this->createImageCacheManager()
            ->setUrl($imageUrl)
            ->load();
        if (!$blobImage) {
            return null;
        }
        $image = ImageInfo::new()->construct(
            $blobImage,
            $imageUrl,
            pathinfo(basename($imageUrl), PATHINFO_EXTENSION)
        );
        return $image;
    }
}
