<?php
/**
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 2, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Upload;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class UploadResult
 * @package Sam\Lot\Image\BucketImport\Upload
 */
class UploadResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_INVALID_IMAGE = 1;
    public const ERR_UPLOAD_FAILED = 2;
    public const ERR_RESIZE_FAILED = 3;

    public const OK_UPLOADED = 11;

    protected const ERROR_MESSAGES = [
        self::ERR_INVALID_IMAGE => 'lot.image.bucket_import.upload.error.invalid_image',
        self::ERR_UPLOAD_FAILED => 'lot.image.bucket_import.upload.error.upload_failed',
        self::ERR_RESIZE_FAILED => 'lot.image.bucket_import.upload.error.resize_failed',
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_UPLOADED => 'Uploaded successfully'
    ];
    /**
     * @var string
     */
    public string $fileName;
    /**
     * @var string
     */
    public string $thumbUrl;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $fileName
     * @param string $thumbUrl
     * @return static
     */
    public function construct(string $fileName = '', string $thumbUrl = ''): static
    {
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES, self::SUCCESS_MESSAGES);
        $this->fileName = $fileName;
        $this->thumbUrl = $thumbUrl;
        return $this;
    }

    // --- Mutate ---

    public function addError(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addError($code, $message);
        return $this;
    }

    public function addSuccess(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addSuccess($code, $message);
        return $this;
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorMessage(string $glue = "\n"): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }
}
