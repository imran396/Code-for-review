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

namespace Sam\Account\Image\Delete;

use Sam\Account\Image\Path\AccountLogoPathResolverCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;

/**
 * Class AccountLogoDeleter
 * @package Sam\Account\Image
 */
class AccountLogoDeleter extends CustomizableClass
{
    use AccountLogoPathResolverCreateTrait;
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
     * Remove account images from the file system
     * @param int $accountId
     * @return void
     */
    public function delete(int $accountId): void
    {
        $this->deleteOriginalFile($accountId);
        $this->deleteThumbnailFile($accountId);
    }

    /**
     * @param int $accountId
     */
    protected function deleteOriginalFile(int $accountId): void
    {
        $sourceFilePath = $this->createAccountLogoPathResolver()->makeOriginalFileBasePath($accountId);
        try {
            $this->createFileManager()->delete($sourceFilePath);
        } catch (FileException) {
            // file lost by unknown reasons
        }
    }

    /**
     * @param int $accountId
     */
    protected function deleteThumbnailFile(int $accountId): void
    {
        $thumbnailFilePath = $this->createAccountLogoPathResolver()->makeThumbnailFileBasePath($accountId);
        try {
            $this->createFileManager()->delete($thumbnailFilePath);
        } catch (FileException) {
            // file lost by unknown reasons
        }
    }
}
