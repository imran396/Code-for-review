<?php
/**
 * SAM-7845: Refactor \Lot_Image class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\Path;

use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Path\PathResolver;
use Sam\Core\Path\PathResolverCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotImagePathResolver
 * @package Sam\Lot\Image\Helper
 */
class LotImagePathResolver extends CustomizableClass
{
    use PathResolverCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $accountId
     * @return string
     */
    public function makeLotImageDirectoryPath(?int $accountId): string
    {
        $path = $this->path()->uploadLotItemImage();
        if ($accountId !== null) {
            $path .= '/' . $accountId;
        }
        return $path;
    }

    /**
     * @param int|null $accountId
     * @param string $fileName
     * @return string
     */
    public function makeLotImagePath(?int $accountId, string $fileName): string
    {
        $path = $this->makeLotImageDirectoryPath($accountId) . '/' . $fileName;
        return $path;
    }

    /**
     * @param int|null $accountId
     * @param string $fileName
     * @return string
     */
    public function makeLotImageRelativePath(?int $accountId, string $fileName): string
    {
        $path = PathResolver::UPLOAD_LOT_ITEM_IMAGE . '/';
        if ($accountId !== null) {
            $path .= $accountId . '/';
        }
        $path .= $fileName;
        return $path;
    }

    /**
     * Return lot image static thumbnail file base path within project's system root
     * @param int $lotImageId
     * @param string|null $size
     * @return string
     */
    public function makeThumbnailRelativePath(int $lotImageId, ?string $size = null): string
    {
        $imageSize = Cast::toString($size, Constants\Image::$sizes);
        if (!$imageSize) {
            log_errorBackTrace('Lot image size incorrect' . composeSuffix(['size' => $size]));
            return '';
        }
        $fileBasePath = LotImageUrlConfig::new()
            ->construct($lotImageId, $imageSize)
            ->fileBasePath();
        return $fileBasePath;
    }

    /**
     * Return lot image static thumbnail file absolute root path
     * @param int $lotImageId
     * @param string|null $size
     * @return string
     */
    public function makeThumbnailAbsolutePath(int $lotImageId, ?string $size = null): string
    {
        return $this->path()->sysRoot() . $this->makeThumbnailRelativePath($lotImageId, $size);
    }
}
