<?php

namespace Sam\Reseller\AuctionBidderCert;

use InvalidArgumentException;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\File\Manage\LocalFileManager;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\File\Validate\FileFormatCheckerCreateTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;

/**
 * Reseller certificate uploading service for auction bidder linked files
 * SAM-2483: Adjustments for "auction bidder certificates" feature
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Sep 23, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */
class AuctionBidderCertUploader extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionBidderCertHelperAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use CurrentDateTrait;
    use FileFormatCheckerCreateTrait;
    use FileManagerCreateTrait;
    use LocalFileManagerCreateTrait;
    use ResultStatusCollectorAwareTrait;
    use TranslatorAwareTrait;
    use UserAwareTrait;

    public const ERR_TMP_FILE_MISSING = 1;
    public const ERR_WRONG_PDF_FORMAT = 2;
    public const ERR_WRONG_IMAGE_FILE = 3;
    public const ERR_WRONG_FILE_FORMAT = 4;

    protected string $originalFileName = '';
    protected string $resultFileName = '';
    protected string $tempFile = '';
    protected bool $isPublic = false;

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
            self::ERR_WRONG_FILE_FORMAT => 'Wrong file format. Allowed: pdf, gif, jpg, png'
        ];
        $successMessages = [];
        $this->getResultStatusCollector()->construct($errorMessages, $successMessages);
        return $this;
    }

    /**
     * Process uploaded certificate file
     */
    public function upload(): void
    {
        $this->deleteCurrent();
        $this->resultFileName = $this->suggestFileName();
        $fileRootPath = $this->getAuctionBidderCertHelper()
            ->getFileRootPath($this->resultFileName, $this->getAuctionId());
        LocalFileManager::new()->createDirPath($fileRootPath);
        if (copy($this->tempFile, $fileRootPath)) {
            $this->createLocalFileManager()->applyDefaultPermissions($fileRootPath);
            $fileManager = $this->createFileManager();
            $filePath = substr($fileRootPath, strlen(path()->sysRoot()));
            $fileManager->put($fileRootPath, $filePath);
        } else {
            log_error(
                'Problem with copying or chmod uploaded temporary file'
                . composeSuffix(
                    [
                        'File' => $this->originalFileName,
                        'u' => $this->getUserId(),
                        'a' => $this->getAuctionId()
                    ]
                )
            );
        }
    }

    /**
     * Delete current certificate, if it exists
     */
    public function deleteCurrent(): void
    {
        if (!$this->getUserId() || !$this->getAuctionId()) {
            throw new InvalidArgumentException("Cannot delete certificate file, because UserId or AuctionId property not set");
        }
        $auctionBidder = $this->getAuctionBidderLoader()->load($this->getUserId(), $this->getAuctionId(), true);
        if (
            $auctionBidder
            && $auctionBidder->ResellerCertificate !== ''
        ) {
            $fileManager = $this->createFileManager();
            $filePath = $this->getAuctionBidderCertHelper()
                ->getFilePath($auctionBidder->ResellerCertificate, $this->getAuctionId());
            try {
                $fileManager->delete($filePath);
            } catch (FileException) {
                // file absent
            }
        }
    }

    /**
     * Suggest name for certificate file stored in permanent location (we don't keep original names)
     * @return string
     */
    public function suggestFileName(): string
    {
        $dateFormatted = $this->getCurrentDateNoTimeUtcIso();
        $fileName = "acert_{$this->getUserId()}_{$dateFormatted}.{$this->getFileExtension()}";
        return $fileName;
    }

    /**
     * Extract extension from file name
     * @return string
     */
    protected function getFileExtension(): string
    {
        $pos = mb_strrpos($this->originalFileName, '.') + 1;
        $extension = mb_substr($this->originalFileName, $pos);
        $extension = strtolower($extension);
        return $extension;
    }

    /**
     * Validate uploaded certificate file
     * @return bool
     */
    public function validate(): bool
    {
        $collector = $this->getResultStatusCollector()->clear();
        if (!file_exists($this->tempFile)) {
            $collector->addError(self::ERR_TMP_FILE_MISSING);
        } else {
            $extension = $this->getFileExtension();
            if ($extension === 'pdf') {
                if (!$this->createFileFormatChecker()->isPdf($this->tempFile)) {
                    $collector->addError(self::ERR_WRONG_PDF_FORMAT);
                }
            } elseif (in_array($extension, ['gif', 'jpg', 'jpeg', 'png'])) {
                $imageInfo = @getimagesize($this->tempFile);
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
     * @param string $originalFileName
     * @return static
     */
    public function setOriginalFileName(string $originalFileName): static
    {
        $this->originalFileName = $originalFileName;
        return $this;
    }

    /**
     * @return string
     */
    public function getOriginalFileName(): string
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
     * @param string $tmpFile
     * @return static
     */
    public function setTmpFile(string $tmpFile): static
    {
        $this->tempFile = $tmpFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getTmpFile(): string
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
