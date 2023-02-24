<?php
/**
 * SAM-6356: Unite mutual logic in Auction Pdf Catalog module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis, Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 28, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\Catalog\Pdf\Delete;

use Auction;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Report\Auction\Catalog\Pdf\Path\AuctionPdfCatalogPathResolverCreateTrait;

/**
 * Class AuctionPdfCatalogDeleter
 * @package Sam\Report\Auction\Catalog\Pdf
 */
class AuctionPdfCatalogDeleter extends CustomizableClass
{
    use AuctionPdfCatalogPathResolverCreateTrait;
    use FileManagerCreateTrait;
    use ResultStatusCollectorAwareTrait;

    /** @var int If pdf file not exists in master file system */
    public const ERR_FILE_NOT_FOUND = 1;
    /** @var int If file exists in master file system but unable to modify it. */
    public const ERR_FILE_IS_PROTECTED = 2;
    /** @var int If exception situation occurred. */
    public const ERR_EXCEPTION = 3;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_FILE_NOT_FOUND => 'Auction Catalog pdf file not found %s%s',
        self::ERR_FILE_IS_PROTECTED => 'Auction Catalog pdf file is protected %s%s',
        self::ERR_EXCEPTION => 'An exception occurred when try to delete auction Catalog pdf file with message: "%s". %s'
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        return $this;
    }

    /**
     * @param Auction $auction
     * @return bool
     */
    public function delete(Auction $auction): bool
    {
        $this->getResultStatusCollector()->construct(static::ERROR_MESSAGES);
        $pathResolver = $this->createAuctionPdfCatalogPathResolver();
        $filePath = $pathResolver->detectFilePath($auction);

        if (!$pathResolver->existInMasterFs($auction)) {
            $this->processError(self::ERR_FILE_NOT_FOUND, $filePath);
            return $this->checkForErrors();
        }

        $fileManager = $this->createFileManager();
        try {
            $fileManager->delete($filePath);
            if ($fileManager->exist($filePath)) {
                $this->processError(self::ERR_FILE_IS_PROTECTED, $filePath);
                return $this->checkForErrors();
            }
        } catch (FileException $e) {
            $this->processError(self::ERR_EXCEPTION, $filePath, $e->getMessage());
        }

        return $this->checkForErrors();
    }

    /**
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    /**
     * @return int[]
     * @internal
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @param int $errorCode
     * @param string $filePath
     * @param string $message
     */
    protected function processError(int $errorCode, string $filePath, string $message = ''): void
    {
        $this->getResultStatusCollector()->addErrorWithInjectedInMessageArguments(
            $errorCode,
            [
                $message,
                composeSuffix(['filePath' => $filePath])
            ]
        );
    }

    /**
     * @return bool
     */
    protected function checkForErrors(): bool
    {
        $success = !$this->getResultStatusCollector()->hasError();
        if (!$success) {
            log_error($this->errorMessage());
        }
        return $success;
    }
}
