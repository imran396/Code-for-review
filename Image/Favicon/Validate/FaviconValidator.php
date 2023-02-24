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

namespace Sam\Image\Favicon\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\Image\Favicon\Validate\FaviconValidationResult as Result;
use Sam\Image\Favicon\Validate\Internal\Svg\SvgValidatorCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

class FaviconValidator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use LocalFileManagerCreateTrait;
    use SvgValidatorCreateTrait;

    protected const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/vnd.microsoft.icon',
        'image/svg'
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(string $tmpFaviconFilePath): Result
    {
        $result = Result::new()->construct();

        $fileManager = $this->createLocalFileManager()->withRootPath('');
        if (!$fileManager->exist($tmpFaviconFilePath)) {
            $result->addError(Result::ERR_FILE_NOT_EXIST);
            return $result;
        }

        $imageInfo = $fileManager->getImageInfo($tmpFaviconFilePath);
        if (!$imageInfo) {
            if ($this->createSvgValidator()->validate($tmpFaviconFilePath)) {
                // If this is valid SVG no need other checks, because it is vector graphics and will be converted to raster further
                return $result->addSuccess(Result::OK_SUCCESS_VALIDATION);
            }

            $result->addError(Result::ERR_INVALID_TYPE);
            return $result;
        }

        if (
            $imageInfo
            && !in_array($imageInfo['mime'], self::ALLOWED_MIME_TYPES, true)
        ) {
            $result->addError(Result::ERR_INVALID_TYPE);
            return $result;
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $maxWidth = $this->cfg()->get('core->image->favicon->maxWidth');
        $maxHeight = $this->cfg()->get('core->image->favicon->maxHeight');
        if (
            $height > $maxHeight
            || $width > $maxWidth
        ) {
            $result->addError(Result::ERR_INVALID_DIMENSIONS);
            return $result;
        }

        $this->log($result);
        return $result->addSuccess(Result::OK_SUCCESS_VALIDATION);
    }

    protected function log(Result $result): void
    {
        if ($result->hasError()) {
            log_error("Validation failed for uploading tmp favicon" . composeSuffix($result->logData()));
        }
    }
}
