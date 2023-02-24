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

namespace Sam\Image\Favicon\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\Image\Favicon\Path\FaviconImagePathResolverCreateTrait;


class FaviconLoader extends CustomizableClass
{
    use LocalFileManagerCreateTrait;
    use FaviconImagePathResolverCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function load(int $accountId): string
    {
        $blobImage = '';
        $fileManager = $this->createLocalFileManager()->withRootPath('');
        $pathResolver = $this->createFaviconImagePathResolver();
        $baseFaviconPath = $pathResolver->makeBaseFaviconFileRootPath($accountId);
        if ($fileManager->exist($baseFaviconPath)) {
            $blobImage = $fileManager->read($baseFaviconPath);
        }
        return $blobImage;
    }
}
