<?php

namespace Sam\Reseller\UserCert;

use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\File\Manage\LocalFileManager;
use RuntimeException;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\File\Validate\FileFormatCheckerCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Reseller certificate uploading service for user linked files
 * SAM-2428: Bidonfusion - Reseller certificate tracking changes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Sep 09, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */
class UserCertUploader extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use FileFormatCheckerCreateTrait;
    use FileManagerCreateTrait;
    use LocalFileManagerCreateTrait;
    use ResellerUserCertHelperAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use TranslatorAwareTrait;
    use UserAwareTrait;
    use UserLoaderAwareTrait;

    public const ERR_TMP_FILE_MISSING = 1;
    public const ERR_WRONG_PDF_FORMAT = 2;
    public const ERR_WRONG_IMAGE_FILE = 3;
    public const ERR_WRONG_FILE_FORMAT = 4;

    protected bool $isPublic = false;
    protected string $originalFileName = '';
    protected string $resultFileName = '';
    protected string $tempFile = '';

    /**
     * Class instantiation method
     * @return static or customized instance
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Initialize error messages
     * @return static
     */
    public function initInstance(): static
    {
        $errorMessages = [
            self::ERR_TMP_FILE_MISSING => 'Uploaded temporary file missing',
            self::ERR_WRONG_PDF_FORMAT => 'Uploaded file doesn\'t have PDF format',
            self::ERR_WRONG_IMAGE_FILE => 'Invalid image file',
            self::ERR_WRONG_FILE_FORMAT => 'Wrong file format. Allowed: pdf, gif, jpg, png',
        ];
        $this->getResultStatusCollector()->construct($errorMessages);
        return $this;
    }

    /**
     * Process uploaded certificate file
     */
    public function upload(): void
    {
        $this->deleteCurrent();
        $rootPath = path()->uploadReseller() . $this->cfg()->get('core->user->reseller->userCertUploadDir');
        $this->setResultFileName($this->suggestFileName($this->getUserId()));
        $fileRootPath = $rootPath . '/' . $this->getResultFileName();
        LocalFileManager::new()->createDirPath($fileRootPath);
        if (copy($this->getTempFile(), $fileRootPath)) {
            $this->createLocalFileManager()->applyDefaultPermissions($fileRootPath);
            $fileManager = $this->createFileManager();
            $filePath = substr($fileRootPath, strlen(path()->sysRoot()));
            $fileManager->put($fileRootPath, $filePath);
        } else {
            log_error(
                'Problem with coping or chmod uploaded temporary file'
                . composeSuffix(
                    [
                        'File' => $this->getOriginalFileName(),
                        'u' => $this->getUserId()
                    ]
                )
            );
        }
    }

    /**
     * Validate uploaded certificate file
     * @return bool
     */
    public function validate(): bool
    {
        $collector = $this->getResultStatusCollector()->clear();
        if (!file_exists($this->getTempFile())) {
            $collector->addError(self::ERR_TMP_FILE_MISSING);
        } else {
            $extension = $this->getFileExtension();
            if ($extension === 'pdf') {
                if (!$this->createFileFormatChecker()->isPdf($this->getTempFile())) {
                    $collector->addError(self::ERR_WRONG_PDF_FORMAT);
                }
            } elseif (in_array($extension, ['gif', 'jpg', 'jpeg', 'png'])) {
                $imageInfo = @getimagesize($this->getTempFile());
                if ($imageInfo === false) {
                    $collector->addError(self::ERR_WRONG_IMAGE_FILE);
                }
            } else {
                $collector->addError(self::ERR_WRONG_FILE_FORMAT);
            }
        }
        $success = !$collector->hasError();
        return $success;
    }

    /**
     * Return error message array
     * @return string[]
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * Get TempFileMissing Error Message
     * @return string
     */
    public function tempFileMissingErrorMessage(): string
    {
        $searchErrors = [self::ERR_TMP_FILE_MISSING];
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($searchErrors);
        return $errorMessage;
    }

    /**
     * Get FileFormat Error Message
     * @return string
     */
    public function fileFormatErrorMessage(): string
    {
        $searchErrors = [self::ERR_WRONG_PDF_FORMAT, self::ERR_WRONG_IMAGE_FILE, self::ERR_WRONG_FILE_FORMAT];
        $errorMessage = (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($searchErrors);
        return $errorMessage;
    }

    /**
     * @return string
     */
    public function successMessage(): string
    {
        $message = $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
        return $message;
    }

    /**
     * Delete current certificate, if it exists
     */
    public function deleteCurrent(): void
    {
        if (!$this->getUserId()) {
            throw new RuntimeException("Cannot delete certificate file, because UserId property not set");
        }
        $resellerCertFile = trim($this->getUserInfo(true)->ResellerCertFile ?? '');
        if ($resellerCertFile !== '') {
            $fileManager = $this->createFileManager();
            $filePath = $this->getResellerUserCertHelper()->getFilePath($resellerCertFile);
            try {
                $fileManager->delete($filePath);
            } catch (FileException) {
                // file absent
            }
        }
    }

    /**
     * Suggest name for certificate file stored in permanent location (we don't keep original names)
     * @param int $userId
     * @return string
     */
    protected function suggestFileName(int $userId): string
    {
        $dateFormatted = $this->getCurrentDateNoTimeUtcIso();
        $fileName = "ucert_{$userId}_{$dateFormatted}.{$this->getFileExtension()}";
        return $fileName;
    }

    /**
     * Extract extension from file name
     * @return string
     */
    protected function getFileExtension(): string
    {
        $extension = strtolower(
            mb_substr(
                $this->getOriginalFileName(),
                mb_strrpos($this->getOriginalFileName(), '.') + 1
            )
        );
        return $extension;
    }

    /**
     * @param string $originalFileName
     * @return static
     */
    public function setOriginalFileName(string $originalFileName): static
    {
        $this->originalFileName = trim($originalFileName);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOriginalFileName(): ?string
    {
        return $this->originalFileName;
    }

    /**
     * @param bool $isPublic
     * @return static
     */
    public function enablePublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    /**
     * @param string $tempFile
     * @return static
     */
    public function setTempFile(string $tempFile): static
    {
        $this->tempFile = trim($tempFile);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTempFile(): ?string
    {
        return $this->tempFile;
    }

    /**
     * @param string $resultFileName
     * @return static
     */
    public function setResultFileName(string $resultFileName): static
    {
        $this->resultFileName = trim($resultFileName);
        return $this;
    }

    /**
     * @return string
     */
    public function getResultFileName(): string
    {
        return $this->resultFileName;
    }
}
