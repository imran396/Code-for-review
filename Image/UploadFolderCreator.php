<?php
/**
 * Generate a new name for image file if the name already exists.
 *
 * Image zip upload improvement (https://bidpath.atlassian.net/browse/SAM-3409)
 *
 * @copyright   2018 Bidpath, Inc.
 * @author      Maxim Lyubetskiy
 * @package     com.swb.sam2
 * @version     SVN: $Id$
 * @since       Sep 12, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Image;

use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;

/**
 * Class UploadFolderCreator
 * @package Sam\Image
 */
class UploadFolderCreator extends CustomizableClass
{
    use FileManagerCreateTrait;

    /**
     * @var string
     */
    private string $rootPath = '';

    /**
     * @var string
     */
    private string $relativePath = '';

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $accountId
     * @param string $absolutePathToUploadLotItemImages
     * @param string $sysRootPath
     * @throws FileException
     */
    public function generatePath(int $accountId, string $absolutePathToUploadLotItemImages, string $sysRootPath): void
    {
        $this->rootPath = $absolutePathToUploadLotItemImages . '/' . $accountId;
        $this->createFileManager()->createDirPath($this->rootPath . '/');
        $this->relativePath = substr($this->rootPath, strlen($sysRootPath));
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        if (!$this->rootPath) {
            throw new \LogicException("Please, call generatePath method to generate a path");
        }
        return $this->rootPath;
    }

    /**
     * @return string
     */
    public function getRelativePath(): string
    {
        if (!$this->relativePath) {
            throw new \LogicException("Please, call generatePath method to generate a path");
        }
        return $this->relativePath;
    }
}
