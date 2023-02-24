<?php
/**
 * Analyses pending images and adds them to queue, that will be processed by cron script (bin/cron/image_import_queued.php)
 *
 * SAM-10383: Refactor remote image import for v3-7
 * SAM-4328: Remote Image Import Manager
 *
 * @author        Imran Rahman
 * @version       SVN: $Id: $
 * @since         July 28, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Lot\Image\RemoteImport;

use ImageImportQueue;
use RuntimeException;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Lot\Image\RemoteImport\Ftp\FtpClient;
use Sam\Lot\Image\RemoteImport\Ftp\FtpClientAwareTrait;
use Sam\Lot\Image\RemoteImport\Ftp\FtpClientResult;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Storage\ReadRepository\Entity\ImageImportQueue\ImageImportQueueReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\ImageImportQueue\ImageImportQueueWriteRepositoryAwareTrait;

/**
 * Class LotImageRemoteImporter
 * @package Sam\Lot\Image\RemoteImport
 */
class LotImageRemoteImporter extends CustomizableClass
{
    use AuctionLotLoaderAwareTrait;
    use BlockCipherProviderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use EntityFactoryCreateTrait;
    use FtpClientAwareTrait;
    use ImageImportQueueReadRepositoryCreateTrait;
    use ImageImportQueueWriteRepositoryAwareTrait;
    use LotCustomDataLoaderCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotImageLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;

    public const ERR_USERNAME_REQUIRED = 1;
    public const ERR_PASSWORD_REQUIRED = 2;
    public const ERR_HOST_REQUIRED = 3;
    public const ERR_DIRECTORY_REQUIRED = 4;
    public const ERR_CONNECTION = 5;

    protected ?int $auctionId = null;
    protected ?int $locationType = null;
    protected array $errors = [];
    protected array $limitExceededLotNums = [];
    protected array $processedLotNums = [];
    protected array $successLotNums = [];
    protected bool $isAutoOrientImageEnabled = true;
    protected bool $isOptimizeImageEnabled = true;
    protected bool $isPassive = false;
    protected bool $isReplaceExistingImages = false;
    protected string $directory = '';
    protected string $host = '';
    protected string $imageIdentifier = '';
    protected string $imgNumSeparator = '';
    protected string $password = '';
    protected string $username = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function errorList(): array
    {
        $errorMessages = [
            self::ERR_USERNAME_REQUIRED => "Required",
            self::ERR_PASSWORD_REQUIRED => "Required",
            self::ERR_HOST_REQUIRED => "Required",
            self::ERR_DIRECTORY_REQUIRED => "Required",
            self::ERR_CONNECTION => "ftp connection test with credentials is successful using FTP",
        ];
        return $errorMessages;
    }

    /**
     * @param int|null $auctionId - Some cases we set $this->getAuctionId() but it can be null
     * @return static
     */
    public function setAuctionId(?int $auctionId): static
    {
        $this->auctionId = $auctionId;
        return $this;
    }

    /**
     * @param int $locationType
     * @return static
     */
    public function setLocationType(int $locationType): static
    {
        $this->locationType = $locationType;
        return $this;
    }

    /**
     * @param string $username
     * @return static
     */
    public function setUsername(string $username): static
    {
        $this->username = trim($username);
        return $this;
    }

    /**
     * @param string $password
     * @return static
     */
    public function setPassword(string $password): static
    {
        $this->password = trim($password);
        return $this;
    }

    /**
     * @param string $host
     * @return static
     */
    public function setHost(string $host): static
    {
        $this->host = trim($host);
        return $this;
    }

    /**
     * @param string $directory
     * @return static
     */
    public function setDirectory(string $directory): static
    {
        $this->directory = trim($directory);
        return $this;
    }

    /**
     * @param bool $isReplaceExistingImages
     * @return static
     */
    public function enableReplaceExistingImages(bool $isReplaceExistingImages): static
    {
        $this->isReplaceExistingImages = $isReplaceExistingImages;
        return $this;
    }

    /**
     * @param string $imageIdentifier
     * @return static
     */
    public function setImageIdentifier(string $imageIdentifier): static
    {
        $this->imageIdentifier = trim($imageIdentifier);
        return $this;
    }

    /**
     * @param string $imgNumSeparator
     * @return static
     */
    public function setImgNumSeparator(string $imgNumSeparator): static
    {
        $this->imgNumSeparator = trim($imgNumSeparator);
        return $this;
    }

    /**
     * @param bool $isPassive
     * @return static
     */
    public function enablePassive(bool $isPassive): static
    {
        $this->isPassive = $isPassive;
        return $this;
    }

    /**
     * @param bool $enabled
     * @return static
     */
    public function enableAutoOrientImage(bool $enabled): static
    {
        $this->isAutoOrientImageEnabled = $enabled;
        return $this;
    }

    /**
     * @param bool $enabled
     * @return static
     */
    public function enableOptimizeImage(bool $enabled): static
    {
        $this->isOptimizeImageEnabled = $enabled;
        return $this;
    }

    /**
     * Input validation of FTP credential
     *
     * @return bool
     */
    public function validate(): bool
    {
        if ($this->locationType === Constants\ImageImportQueue::LOCATION_TYPE_FTP) {
            if (!$this->username) {
                $this->errors[] = self::ERR_USERNAME_REQUIRED;
            }
            if (!$this->password) {
                $this->errors[] = self::ERR_PASSWORD_REQUIRED;
            }
        }
        if (!$this->host) {
            $this->errors[] = self::ERR_HOST_REQUIRED;
        }
        if (!$this->directory) {
            $this->errors[] = self::ERR_DIRECTORY_REQUIRED;
        }
        $isValid = count($this->errors) === 0;

        return $isValid;
    }

    /**
     * @return array{0: FtpClient|null, 1: FtpClientResult|null}
     */
    public function initFtpConnection(): array
    {
        $ftpClient = $this->getFtpClient();
        if ($ftpClient->isConnected()) {
            return [$ftpClient, null];
        }

        $ftpClientResult = $ftpClient->connect($this->host);
        if ($ftpClientResult->hasError()) {
            return [null, $ftpClientResult];
        }

        $ftpClientResult = $ftpClient->login($this->username, $this->password);
        if ($ftpClientResult->hasError()) {
            $ftpClient->disconnect();
            return [null, $ftpClientResult];
        }

        $ftpClientResult = $ftpClient->changeDirectory($this->directory);
        if ($ftpClientResult->hasError()) {
            $ftpClient->disconnect();
            return [null, $ftpClientResult];
        }

        if ($this->isPassive) {
            $ftpClientResult = $ftpClient->turnOnPassiveMode();
            if ($ftpClientResult->hasError()) {
                $ftpClient->disconnect();
                return [null, $ftpClientResult];
            }
        }

        return [$ftpClient, null];
    }

    /**
     * Saving queueing data
     * @param int $editorUserId
     * @return void
     */
    public function putInQueue(int $editorUserId): void
    {
        if ($this->locationType !== Constants\ImageImportQueue::LOCATION_TYPE_FTP) {
            return;
        }

        [$ftpClient, $ftpClientResult] = $this->initFtpConnection();
        if (
            $ftpClientResult
            && $ftpClientResult->hasError()
        ) {
            log_error($ftpClientResult->logMessage());
            throw new RuntimeException($ftpClientResult->logMessage());
        }

        $generator = $this->getAuctionLotLoader()->yieldByAuctionId($this->auctionId, true);
        foreach ($generator as $auctionLot) {
            $imageNameBase = '';
            switch ($this->imageIdentifier) {
                case 'Item #':
                    $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId);
                    $imageNameBase = $this->getLotRenderer()->renderItemNo($lotItem);
                    break;
                case 'Prefix + Lot # + Extension':
                    $imageNameBase = $this->getLotRenderer()->renderLotNo($auctionLot);
                    break;
                default:
                    $lotCustomField = $this->createLotCustomFieldLoader()->loadByName($this->imageIdentifier);
                    if ($lotCustomField) {
                        $lotCustomData = $this->createLotCustomDataLoader()->load($lotCustomField->Id, $auctionLot->LotItemId, true);
                        if ($lotCustomData) {
                            if ($lotCustomField->Type === Constants\CustomField::TYPE_INTEGER) {
                                $imageNameBase = $lotCustomData->Numeric;
                            } else {
                                if ($lotCustomField->Type === Constants\CustomField::TYPE_TEXT) {
                                    $imageNameBase = $lotCustomData->Text;
                                }
                            }
                        }
                        unset($lotCustomData);
                    }
                    unset($lotCustomField);
                    break;
            }

            if ($imageNameBase === '') {
                continue;
            }

            $ftpClientResult = $ftpClient->listFiles();
            if ($ftpClientResult->hasError()) {
                log_error($ftpClientResult->errorMessage());
                continue;
            }

            $uploadedImages = [];
            foreach ($ftpClientResult->fileNames as $val) {
                if (strrpos($val, $imageNameBase) !== false) {
                    $uploadedImages[$imageNameBase][] = $val;
                }
            }

            $imageCount = $this->getLotImageLoader()->countByLotItemId($auctionLot->LotItemId);
            $uploadedImagesCount = isset($uploadedImages[$imageNameBase])
                ? count($uploadedImages[$imageNameBase])
                : 0;
            if (
                !$this->isReplaceExistingImages
                && $imageCount + $uploadedImagesCount > $this->cfg()->get('core->lot->image->perLotLimit')
            ) {
                $this->limitExceededLotNums[] = $auctionLot->LotNum;
            } else {
                if (!$this->loadNonProcessed($auctionLot->LotItemId)) {
                    $imageImportQueue = $this->createEntityFactory()->imageImportQueue();
                    $imageImportQueue->LotId = $auctionLot->LotItemId;
                    $imageImportQueue->AuctionId = $this->auctionId;
                    $imageImportQueue->ImageNameBase = $imageNameBase;
                    $imageImportQueue->LocationType = Constants\ImageImportQueue::LOCATION_TYPE_FTP;
                    $imageImportQueue->Host = $this->host;
                    $imageImportQueue->Directory = $this->directory;
                    $imageImportQueue->Username = $this->username;
                    $imageImportQueue->Password = $this->createBlockCipherProvider()->construct()->encrypt($this->password);
                    $imageImportQueue->Processed = false;
                    $imageImportQueue->ReplaceExisting = $this->isReplaceExistingImages;
                    $imageImportQueue->LotNumSeparator = (int)$this->imgNumSeparator;
                    $imageImportQueue->Passive = $this->isPassive;
                    $imageImportQueue->ImageOptimize = $this->isOptimizeImageEnabled;
                    $imageImportQueue->ImageAutoOrient = $this->isAutoOrientImageEnabled;
                    $this->getImageImportQueueWriteRepository()->saveWithModifier($imageImportQueue, $editorUserId);
                    $this->successLotNums[] = $auctionLot->LotNum;
                } else {
                    $this->processedLotNums[] = $auctionLot->LotNum;
                }
            }
        }

        /**
         * Close FTP connection. It helps so avoid error:
         * "A connection attempt failed because the connected party did not properly respond after a period of time,
         * or established connection failed because connected host has failed to respond"
         */
        $ftpClient->disconnect();
    }

    /**
     * Check FTP credential input validation error
     *
     * @param int $errorNo
     * @return bool
     */
    public function hasError(int $errorNo): bool
    {
        $isError = in_array($errorNo, $this->errors);
        return $isError;
    }

    /**
     * Get FTP input validation error
     *
     * @param int $errorNo
     * @return string
     */
    public function getError(int $errorNo): string
    {
        $errors = $this->errorList();
        $error = $errors[$errorNo];
        return $error;
    }

    /**
     * Max upload limit of files exceeded lot no
     *
     * @return array
     */
    public function getLimitExceededLotNos(): array
    {
        return $this->limitExceededLotNums;
    }

    /**
     * Already processed lot no
     *
     * @return array
     */
    public function getAlreadyProcessedLotNos(): array
    {
        return $this->processedLotNums;
    }

    /**
     * Processed lot no
     *
     * @return array
     */
    public function getSuccessLotNos(): array
    {
        return $this->successLotNums;
    }

    /**
     * @param int $lotItemId
     * @return ImageImportQueue[]
     */
    public function loadNonProcessed(int $lotItemId): array
    {
        $imageImportQueue = $this->createImageImportQueueReadRepository()
            ->filterProcessed(false)
            ->skipAuctionId(null)
            ->filterLotId($lotItemId)
            ->orderByHost()
            ->orderByUsername()
            ->orderByDirectory()
            ->orderByAuctionId()
            ->orderByLotId()
            ->loadEntities();
        return $imageImportQueue;
    }
}
