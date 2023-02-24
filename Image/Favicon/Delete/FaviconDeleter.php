<?php
/**
 * SAM-11607: Custom favicon
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 14, 2023
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Image\Favicon\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\File\FolderManagerAwareTrait;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\Image\Favicon\Path\FaviconImagePathResolverCreateTrait;
use Symfony\Component\Config\Definition\Exception\Exception;


class FaviconDeleter extends CustomizableClass
{
    use LocalFileManagerCreateTrait;
    use FolderManagerAwareTrait;
    use FaviconImagePathResolverCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function delete(int $accountId): bool
    {
        $fileManager = $this->createLocalFileManager()->withRootPath('');
        $pathResolver = $this->createFaviconImagePathResolver();
        try {
            $baseFaviconPath = $pathResolver->makeBaseFaviconFileRootPath($accountId);
            if ($fileManager->exist($baseFaviconPath)) {
                $fileManager->delete($baseFaviconPath);
            }
            $generatedFaviconPath = $pathResolver->makeGeneratedFaviconFileRootPath($accountId);
            $this->getFolderManager()->rrmdir($generatedFaviconPath);
            return true;
        } catch (Exception $e) {
            log_error('Failed to remove image', composeSuffix(['errorMessage' => $e->getMessage()]));
            return false;
        }
    }
}
