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

namespace Sam\Image\Favicon\Upload;

use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\Image\Favicon\Upload\FaviconUploadResult as Result;
use Sam\Image\Favicon\Upload\Internal\Convert\DataConverterCreateTrait;

class FaviconUploader extends CustomizableClass
{
    use DataConverterCreateTrait;
    use FilesystemCacheManagerAwareTrait;
    use LocalFileManagerCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function upload(string $tmpFaviconFilePath): Result
    {
        $result = Result::new()->construct();
        $blobImage = is_readable($tmpFaviconFilePath)
            ? file_get_contents($tmpFaviconFilePath)
            : null;

        if (!$blobImage) {
            log_warning('File does not exist or not readable' . composeSuffix(['source' => $tmpFaviconFilePath]));
            return $result->addError(Result::ERR_UPLOAD_FAILED);
        }

        $imageInfo = $this->createLocalFileManager()->withRootPath('')
            ->getImageInfo($tmpFaviconFilePath);
        $dataConverter = $this->createDataConverter();

        if (!$imageInfo) {
            //SVG
            $blobImage = $dataConverter->convertSvgToPngBlob($blobImage);
        } elseif ($dataConverter->isIco($imageInfo['mime'])) {
            $blobImage = $dataConverter->convertIcoToPngBlob($blobImage);
        }

        $cacheKey = uniqid('', true);
        $success = $this->getFilesystemCacheManager()
            ->setNamespace('favicon')
            ->set($cacheKey, $blobImage);
        if (!$success) {
            log_warning('Failed to create favicon cache from blob' . composeSuffix(['source' => $tmpFaviconFilePath]));
            return $result->addError(Result::ERR_UPLOAD_FAILED);
        }

        return Result::new()->construct($cacheKey)->addSuccess(Result::OK_SUCCESS_UPLOAD);
    }
}
