<?php

/**
 * Uploading service for settlement print check image
 * SAM-2563: Roseberys - Print check on settlements
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Nov 06, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Upload;

use InvalidArgumentException;
use RuntimeException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\File\Manage\LocalFileManager;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\Settings\SettingsManager;
use Sam\Settlement\Check\Path\SettlementCheckPathResolverCreateTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;

/**
 * Class Sam\Settlement\Check\Uploader
 */
class SettlementCheckUploader extends CustomizableClass
{
    use AccountAwareTrait;
    use FileManagerCreateTrait;
    use LocalFileManagerCreateTrait;
    use SettlementCheckPathResolverCreateTrait;

    protected const SETTLEMENT_CHECK_FILE_NAME_TPL = 'stlm_check_%s.%s';

    /**
     * Check picture file name
     */
    protected string $originalFileName = '';
    /**
     * (read-only) Check picture file name stored at permanent location
     */
    protected string $resultFileName = '';
    /**
     * Root file path to uploaded image file at temporary location
     */
    protected string $tempFile = '';
    /**
     * @var string[]
     */
    protected array $errorMessages = [];
    /**
     * @var string[]
     */
    protected array $translations = [
        'ERR_TMP_FILE_MISSING' => 'Uploaded temporary file missing',
        'ERR_WRONG_IMAGE_FILE' => 'Invalid image file',
        'ERR_WRONG_FILE_FORMAT' => 'Wrong file format. Allowed: gif, jpg, png',
    ];

    /**
     * Class instantiation method
     * @return static or customized instance
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Process uploaded printing check image file
     */
    public function upload(): void
    {
        $settlementCheckRootPath = $this->createSettlementCheckPathResolver()->makeRootPath($this->getAccountId());
        $this->resultFileName = $this->suggestFileName();
        $fileRootPath = $settlementCheckRootPath . '/' . $this->resultFileName;
        LocalFileManager::new()->createDirPath($fileRootPath);

        if (!copy($this->tempFile, $fileRootPath)) {
            $message = 'Problem with coping or chmod uploaded temporary file for user'
                . composeSuffix(['file' => $this->originalFileName, 'u' => $this->getAccountId()]);
            log_error($message);
            throw new RuntimeException($message);
        }

        $this->createLocalFileManager()->applyDefaultPermissions($fileRootPath);
        $fileManager = $this->createFileManager();
        $filePath = substr($fileRootPath, strlen(path()->sysRoot()));
        $fileManager->put($fileRootPath, $filePath);
        $this->delete();
    }

    /**
     * Validate uploaded settlement check image.
     * YV, SAM-7984, 14.06.2021. No usage cases found. We can move it to ./Validate/SettlementUploadValidator.
     *
     * @return bool
     */
    public function validate(): bool
    {
        if (!file_exists($this->tempFile)) {
            $this->errorMessages[] = $this->translations['ERR_TMP_FILE_MISSING'];
            return false;
        }

        $extension = $this->getFileExtension();
        if (!in_array($extension, ['gif', 'jpg', 'jpeg', 'png'], true)) {
            $this->errorMessages[] = $this->translations['ERR_WRONG_FILE_FORMAT'];
            return false;
        }

        $imageInfo = @getimagesize($this->tempFile);
        if ($imageInfo === false) {
            $this->errorMessages[] = $this->translations['ERR_WRONG_IMAGE_FILE'];
            return false;
        }

        return true;
    }

    /**
     * Delete current settlement check image, if it exists
     * YV, SAM-7984, 14.06.2021. We can move it to ./../Delete/SettlementCheckFileDeleter.
     */
    public function delete(): void
    {
        if (!$this->getAccountId()) {
            throw new InvalidArgumentException("Cannot delete settlement check file, because AccountId property not set");
        }

        $stlmCheckFile = SettingsManager::new()->get(Constants\Setting::STLM_CHECK_FILE, $this->getAccountId());
        if ($stlmCheckFile !== '') {
            $fileManager = $this->createFileManager();
            $filePath = $this->createSettlementCheckPathResolver()
                ->makeFilePath($this->getAccountId(), $stlmCheckFile);
            try {
                $fileManager->delete($filePath);
            } catch (FileException) {
                // file absent
            }
        }
    }

    /**
     * Return all error messages, that has happened at validation
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errorMessages;
    }

    /**
     * Suggest name for file stored in permanent location (we don't keep original names)
     * @return string
     */
    protected function suggestFileName(): string
    {
        $fileName = sprintf(self::SETTLEMENT_CHECK_FILE_NAME_TPL, date('Y-m-d_H-i-s'), $this->getFileExtension());
        return $fileName;
    }

    /**
     * Extract extension from file name
     * @return string
     */
    protected function getFileExtension(): string
    {
        $extension = mb_substr(
            $this->originalFileName,
            mb_strrpos($this->originalFileName, '.') + 1
        );
        $extension = strtolower($extension);
        return $extension;
    }

    /**
     * Return value of originalFileName property
     * @return string
     */
    public function getOriginalFileName(): string
    {
        return $this->originalFileName;
    }

    /**
     * Set originalFileName property value and normalize to string  value
     * @param string $originalFileName
     * @return static
     */
    public function setOriginalFileName(string $originalFileName): static
    {
        $this->originalFileName = trim($originalFileName);
        return $this;
    }

    /**
     * Return value of resultFileName property
     * @return string
     */
    public function getResultFileName(): string
    {
        return $this->resultFileName;
    }

    /**
     * Set resultFileName property value and normalize to string value
     * @param string $resultFileName
     * @return static
     */
    public function setResultFileName(string $resultFileName): static
    {
        $this->resultFileName = trim($resultFileName);
        return $this;
    }

    /**
     * Return value of tempFile property
     * @return string
     */
    public function getTempFile(): string
    {
        return $this->tempFile;
    }

    /**
     * Set tempFile property value and normalize to string value
     * @param string $tempFile
     * @return static
     */
    public function setTempFile(string $tempFile): static
    {
        $this->tempFile = trim($tempFile);
        return $this;
    }
}
