<?php
/**
 * ApplicationUploadValidator validates files from $_FILE before they are copied to temporary folder
 *
 * SAM-5263: CSV upload process change for better UX and reverse proxy timeout handling
 *
 * @author        Victor Pautoff
 * @version       SAM 2.0
 * @since         May 15, 2020
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.api
 */

namespace Sam\Application\Upload\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Image\Zip\ImageNamesExtractor;
use ZipArchive;

/**
 * Class CookieHelper
 * @package Sam\Application\Cookie
 */
class ApplicationUploadValidator extends CustomizableClass
{
    protected const ERROR_FILE_INVALID = 'Not a valid CSV file';
    protected const ERROR_FILE_REQUIRED = 'CSV file is missing';
    protected const ERROR_ZIP_INVALID = 'Not a valid ZIP file';
    protected const ERROR_ZIP_NO_IMAGES = 'Images not found in the ZIP file';

    protected const TYPE_FILE = 'file';
    protected const TYPE_ZIP_FILES = 'ZIP file';
    protected const TYPE_ZIP_IMAGES = 'ZIP file with images';

    /** @var string */
    protected string $error = '';

    /** @var string[] */
    protected static array $acceptableMimeTypes = [
        'application/csv' => 'csv',
        'application/eml' => 'csv',
        'application/octet-stream' => 'csv',
        'application/vnd.ms-excel' => 'csv',
        'text/comma-separated-values' => 'csv',
        'text/csv' => 'csv',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function validateFile(string $name): bool
    {
        if (!isset($_FILES[$name])) {
            $this->setError(self::ERROR_FILE_REQUIRED);
            return false;
        }

        if (!array_key_exists($_FILES[$name]['type'], self::$acceptableMimeTypes)) {
            $this->setError(self::ERROR_FILE_INVALID);
            return false;
        }

        if ($this->hasErrorCode($name, self::TYPE_FILE)) {
            return false;
        }
        return true;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function validateZipFile(string $name): bool
    {
        if (!isset($_FILES[$name])) {
            return true;
        }
        if ($this->hasErrorCode($name, self::TYPE_ZIP_FILES)) {
            return false;
        }
        $archive = new ZipArchive();
        $result = $archive->open($_FILES[$name]['tmp_name'], ZipArchive::CHECKCONS);
        if ($result !== true) {
            $this->setError(self::ERROR_ZIP_INVALID);
            return false;
        }
        $archive->close();
        return true;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function validateZipFileWithImages(string $name): bool
    {
        if (!isset($_FILES[$name])) {
            return true;
        }
        if ($this->hasErrorCode($name, self::TYPE_ZIP_IMAGES)) {
            return false;
        }
        if (!ImageNamesExtractor::new()->getNamesFrom($_FILES[$name]['tmp_name'])) {
            $this->setError(self::ERROR_ZIP_NO_IMAGES);
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * https://www.php.net/manual/ru/features.file-upload.errors.php
     * @param int $code
     * @param string $fileType
     * @return string
     */
    protected function getErrorMessageByCode(int $code, string $fileType = self::TYPE_FILE): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE => "The uploaded $fileType exceeds the upload_max_filesize directive in php.ini",
            UPLOAD_ERR_FORM_SIZE => "The uploaded $fileType exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
            UPLOAD_ERR_PARTIAL => "The uploaded $fileType was only partially uploaded",
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension',
            default => 'Unknown upload error',
        };
    }

    /**
     * @param string $name
     * @param string $fileType
     * @return bool
     */
    protected function hasErrorCode(string $name, string $fileType): bool
    {
        if ($_FILES[$name]['error'] !== UPLOAD_ERR_OK) {
            $this->setError($this->getErrorMessageByCode($_FILES[$name]['error'], $fileType));
            return true;
        }
        return false;
    }

    /**
     * @param string $error
     */
    protected function setError(string $error): void
    {
        $this->error = $error;
    }
}
