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

namespace Sam\Image\Favicon\Save;

use Exception;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\Image\Favicon\Path\FaviconImagePathResolverCreateTrait;
use Sam\Image\Favicon\Save\FaviconSaveResult as Result;
use Sam\Image\Favicon\Save\Internal\Generate\IconGeneratorCreateTrait;

class FaviconSaver extends CustomizableClass
{
    use FilesystemCacheManagerAwareTrait;
    use IconGeneratorCreateTrait;
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

    public function save(string $cacheKey, int $accountId): Result
    {
        $result = Result::new()->construct();
        $blobImage = $this->getFilesystemCacheManager()
            ->setNamespace('favicon')
            ->get($cacheKey);

        if (!$blobImage) {
            $result->addError(Result::ERR_FAILED_TO_SAVE);
            return $result;
        }

        $fileManager = $this->createLocalFileManager();
        try {
            $iconGenerator = $this->createIconGenerator();
            $pathResolver = $this->createFaviconImagePathResolver();

            // Save original image
            $baseIcon = $iconGenerator->generateBaseIcon($blobImage);
            $fileManager->createDirPath($pathResolver->makeBaseFaviconFileRootPath($accountId));
            $fileManager->write($baseIcon, $pathResolver->makeBaseFaviconFilePath($accountId));

            // Create folder for generated icons
            $fileManager->createDirPath($pathResolver->makeMediumFaviconFileRootPath($accountId));
            // Generate medium image
            $mediumIcon = $iconGenerator->generateMediumIcon($blobImage);
            $fileManager->write($mediumIcon, $pathResolver->makeMediumFaviconFilePath($accountId));
            // Generate small image
            $smallIcon = $iconGenerator->generateSmallIcon($blobImage);
            $fileManager->write($smallIcon, $pathResolver->makeSmallFaviconFilePath($accountId));
            // Generate apple image
            $appleIcon = $iconGenerator->generateAppleIcon($blobImage);
            $fileManager->write($appleIcon, $pathResolver->makeAppleFaviconFilePath($accountId));
        } catch (Exception $e) {
            log_error($e->getMessage());
            return $result->addError(Result::ERR_FAILED_TO_GENERATE_ICONS);
        }

        return $result->addSuccess(Result::OK_SAVED);
    }
}
