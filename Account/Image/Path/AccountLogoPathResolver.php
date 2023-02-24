<?php
/**
 * SAM-7943: Refactor \Account_Image class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Image\Path;

use Sam\Application\Url\Build\Config\Image\AccountImageUrlConfig;
use Sam\Core\Constants;
use Sam\Core\Path\PathResolverCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\LocalFileManagerCreateTrait;

/**
 * This class contains methods that provide relative and root paths to the account logo
 *
 * Class AccountLogoPathHelper
 * @package Sam\Account\Image
 */
class AccountLogoPathResolver extends CustomizableClass
{
    use LocalFileManagerCreateTrait;
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
     * Return file path to the settings upload directory
     *
     * @param int $accountId
     * @return string
     */
    public function makeRootPath(int $accountId): string
    {
        return sprintf('%s/%s', $this->path()->uploadSetting(), $accountId);
    }

    /**
     * Search for thumbnail file by account
     *
     * When detecting account image existence before rendering url, we should check existence of thumbnail static file, that should be located at local fs.
     * We shouldn't check original file, because it may be located at remote fs and this would be time-consuming operation, especially in lists like feed. (SAM-6695)
     *
     * @param int $accountId
     * @return bool
     */
    public function existThumbnail(int $accountId): bool
    {
        $thumbFileBasePath = $this->makeThumbnailFileBasePath($accountId);
        return $this->createLocalFileManager()->exist($thumbFileBasePath);
    }

    /**
     * Return root file path to original image file, the source for static thumbnails
     * @param int $accountId
     * @return string
     */
    public function makeOriginalFileRootPath(int $accountId): string
    {
        return $this->makeRootPath($accountId) . '/' . Constants\Image::ACCOUNT_LOGO_ORIGINAL_FILE_NAME;
    }

    /**
     * Return base file path for original image file, the source for static thumbnails
     * @param int $accountId
     * @return string
     */
    public function makeOriginalFileBasePath(int $accountId): string
    {
        return substr(
            $this->makeOriginalFileRootPath($accountId),
            strlen($this->path()->sysRoot())
        );
    }

    /**
     * Return base file path for thumbnail static file of account image
     * @param int $accountId
     * @return string
     */
    public function makeThumbnailFileBasePath(int $accountId): string
    {
        $baseFilePath = AccountImageUrlConfig::new()
            ->construct($accountId)
            ->fileBasePath();
        return $baseFilePath;
    }

    /**
     * Return root file path for thumbnail static file of account image
     * @param int $accountId
     * @return string
     */
    public function makeThumbnailFileRootPath(int $accountId): string
    {
        $filePath = $this->path()->sysRoot() . $this->makeThumbnailFileBasePath($accountId);
        return $filePath;
    }
}
