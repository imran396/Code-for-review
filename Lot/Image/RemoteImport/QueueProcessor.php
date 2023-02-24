<?php
/**
 * https://bidpath.atlassian.net/browse/SAM-4328
 *
 * SAM-10383: Refactor remote image import for v3-7
 * SAM-4328 : Remote Image Import Manager
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/21/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\RemoteImport;

use Exception;
use ImageImportQueue;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Image\Resize\Resizer;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Lot\Image\Path\LotImagePathResolverCreateTrait;
use Sam\Lot\Image\Queue\LotImageQueueCreateTrait;
use Sam\Lot\Image\RemoteImport\Ftp\FtpClient;
use Sam\Lot\Image\RemoteImport\Ftp\FtpClientAwareTrait;
use Sam\Lot\Load\LotItemLoader;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\ImageImportQueue\ImageImportQueueReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotImage\LotImageReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\ImageImportQueue\ImageImportQueueWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotImage\LotImageWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class QueueProcessor
 * @package Sam\Lot\Image\RemoteImport;
 */
class QueueProcessor extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use BlockCipherProviderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use EntityFactoryCreateTrait;
    use FileManagerCreateTrait;
    use FtpClientAwareTrait;
    use ImageImportQueueReadRepositoryCreateTrait;
    use ImageImportQueueWriteRepositoryAwareTrait;
    use LotImageLoaderAwareTrait;
    use LotImagePathResolverCreateTrait;
    use LotImageQueueCreateTrait;
    use LotImageReadRepositoryCreateTrait;
    use LotImageWriteRepositoryAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * @var ImageImportQueue[]
     */
    public array $imageImports = [];
    public array $downloadedImages = [];
    public string $extRegex = '(\.\d+)?\.(gif|jpg|jpeg|png)';
    public int $ftpPort = 21; // port to connect to
    public int $ftpTimeout = 90; // ftp connection timeout
    public int $startTs = 0;
    public int $maxExecTime = 110; // max 110sec execution time
    public int $execStartTime = 0;

    /**
     * Class instantiation method
     *
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $this->execStartTime = time();
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $this->process($editorUserId);
    }

    /**
     * Process remote images
     * @param int $editorUserId
     * @return void
     */
    public function process(int $editorUserId): void
    {
        $this->loadAllNonProcessed();
        if ($this->imageImports) {
            $ftpClient = $this->getFtpClient();
            $host = '';
            $username = '';
            $password = '';
            $directory = '';
            $fileManager = $this->createFileManager();
            foreach ($this->imageImports as $imageImport) {
                $lotItem = LotItemLoader::new()->load($imageImport->LotId, true);
                $lotItemImagesDir = $this->createLotImagePathResolver()->makeLotImageDirectoryPath($lotItem->AccountId ?? null);
                $fileManager->createDirPath($lotItemImagesDir);
                $this->extRegex = match ($imageImport->LotNumSeparator) {
                    Constants\LotImageImport::LNS_UNDERSCORE => '(\_\d+)?\.(gif|jpg|jpeg|png)',
                    Constants\LotImageImport::LNS_DASH => '(\-\d+)?\.(gif|jpg|jpeg|png)',
                    Constants\LotImageImport::LNS_BRACKET => '(\(\d+\))?\.(gif|jpg|jpeg|png)',
                    default => '(\.\d+)?\.(gif|jpg|jpeg|png)',
                };
                if (time() > $this->execStartTime + $this->maxExecTime) {
                    break;
                }
                $isError = false;
                if ($imageImport->Host === '') {
                    log_error(
                        'no host was provided - image_import_queue.id ='
                        . composeSuffix(['iiq' => $imageImport->Id])
                    );
                    $isError = true;
                }
                if ($imageImport->Username === '') {
                    log_error(
                        'no username was provided - image_import_queue.id ='
                        . composeSuffix(['iiq' => $imageImport->Id])
                    );
                    $isError = true;
                }
                if ($imageImport->Password === '') {
                    log_error(
                        'no password was provided - image_import_queue.id ='
                        . composeSuffix(['iiq' => $imageImport->Id])
                    );
                    $isError = true;
                }
                if ($imageImport->Directory === '') {
                    log_error(
                        'no directory was provided - image_import_queue.id = '
                        . composeSuffix(['iiq' => $imageImport->Id])
                    );
                    $isError = true;
                }
                if ($isError) {
                    $imageImport->Processed = true;
                    $this->getImageImportQueueWriteRepository()->saveWithModifier($imageImport, $editorUserId);
                    continue;
                }
                $isTimedOut = false;
                if (
                    $this->startTs
                    && (time() - $this->startTs) >= $this->ftpTimeout
                ) {
                    log_error('connection had timed out');
                    $isTimedOut = true;
                }

                if (
                    $host !== $imageImport->Host
                    || $isTimedOut
                ) {
                    $ftpClientResult = $ftpClient->connect($imageImport->Host, $this->ftpPort, $this->ftpTimeout);
                    if ($ftpClientResult->hasSuccess()) {
                        $this->startTs = time();
                        log_debug('Successfully connected to ' . $imageImport->Host);
                    } else {
                        log_error($ftpClientResult->errorMessage() . composeSuffix(['iiq' => $imageImport->Id]));
                        $isError = true;
                    }
                }

                $decPassword = $this->createBlockCipherProvider()->construct()->decrypt($imageImport->Password);
                if (
                    $username !== $imageImport->Username
                    || $password !== $decPassword
                    || $isTimedOut
                ) {
                    $ftpClientResult = $ftpClient->login($imageImport->Username, $decPassword);
                    if ($ftpClientResult->hasSuccess()) {
                        log_debug('Successfully authenticated using password ' . $decPassword);
                    } else {
                        log_error($ftpClientResult->errorMessage() . composeSuffix(['iiq' => $imageImport->Id]));
                        $isError = true;
                    }
                }

                if (
                    $directory !== $imageImport->Directory
                    || $isTimedOut
                ) {
                    $ftpClientResult = $ftpClient->changeDirectory($imageImport->Directory);
                    if ($ftpClientResult->hasSuccess()) {
                        log_debug('Successfully changed directory to ' . $imageImport->Directory);
                    } else {
                        log_error($ftpClientResult->errorMessage() . composeSuffix(['iiq' => $imageImport->Id]));
                        $isError = true;
                    }
                }

                if (
                    !$isError
                    && $imageImport->Passive
                ) {
                    $ftpClientResult = $ftpClient->turnOnPassiveMode();
                    if ($ftpClientResult->hasSuccess()) {
                        log_debug('Successfully turned passive mode on');
                    } else {
                        log_error($ftpClientResult->errorMessage() . composeSuffix(['iiq' => $imageImport->Id]));
                        $isError = true;
                    }
                }

                if ($isError) {
                    log_error('Failed to download: ' . var_export($imageImport, true));
                } else {
                    $ftpClientResult = $ftpClient->listFiles();
                    if ($ftpClientResult->hasSuccess()) {
                        if (!$ftpClientResult->fileNames) {
                            log_error('Files not found: ' . var_export($imageImport, true));
                        } else {
                            $this->processFiles($ftpClient, $imageImport, $ftpClientResult->fileNames, $lotItemImagesDir);
                        }
                    } else {
                        log_error($ftpClientResult->logMessage() . ': ' . var_export($imageImport, true));
                    }
                    unset($ftpClientResult);
                }
                $imageImport->Processed = true;
                $this->getImageImportQueueWriteRepository()->saveWithModifier($imageImport, $editorUserId);
                $host = $imageImport->Host;
                $username = $imageImport->Username;
                $password = $decPassword;
                $directory = $imageImport->Directory;
            }
            $ftpClient->disconnect();
            $this->imageImports = [];
            unset($ftpClient);
            $this->sortDownloadedImages();
            $this->saveDownloadedImages($editorUserId);
            log_debug('end of image import cron job.');
        }
    }

    protected function processFiles(
        FtpClient $ftpClient,
        ImageImportQueue $imageImport,
        array $fileNames,
        string $lotItemImagesDir
    ): void {
        $imageIdentifier = $imageImport->ImageNameBase;
        /** download all images that match the image identifier **/
        foreach ($fileNames as $fileName) {
            if (time() > $this->execStartTime + $this->maxExecTime) {
                break;
            }
            if (
                $fileName === '.'
                || $fileName === '..'
                || is_dir($fileName)
            ) { // Should skip directory folders
                continue;
            }
            //we can't associate this image with this lot
            if (!preg_match('/^' . preg_quote($imageIdentifier, '/') . $this->extRegex . '$/i', $fileName)) {
                log_error('unable to associate ' . $fileName . ' with the selected lot ' . $imageIdentifier);
                continue;
            }
            if ($imageImport->ReplaceExisting) {
                $checkingFiles = [];    // files to be checked for - which of them can be replaced
                $checkFile = $fileName;
                while (file_exists($lotItemImagesDir . '/' . $checkFile)) {
                    $checkingFiles[] = $checkFile;
                    $checkFile = preg_replace('/(\.(gif|jpg|jpeg|png))$/i', '__' . $imageImport->LotId . '\1', $checkFile);
                }
                if (empty($checkingFiles)) {     // no file to replace, create new
                    $newFile = $fileName;
                    log_debug('Create new file ' . $newFile);
                } else {
                    $newFile = null;
                    foreach ($checkingFiles as $checkFile) {
                        $lotImage = $this->getLotImageLoader()->loadFirstByAuctionIdAndImageLink($imageImport->AuctionId, $checkFile);
                        $lotItemId = $lotImage->LotItemId ?? null;
                        if ($lotItemId === $imageImport->LotId) {  // we found first related to auction lot for replacing
                            $newFile = $checkFile;
                            log_debug('Replace file ' . $newFile);
                            break;
                        }
                    }
                    if ($newFile === null) {     // no found for replacing (among prepared for checking), create new one with extended file name
                        $newFile = preg_replace('/(\.(gif|jpg|jpeg|png))$/i', '__' . $imageImport->LotId . '\1', array_pop($checkingFiles));
                        log_debug('Create new file ' . $newFile);
                    }
                }
            } elseif (file_exists($lotItemImagesDir . '/' . $fileName)) {
                log_debug('file ' . $fileName . ' already exists.');
                $newFile = $fileName;
                do {
                    $newFile = preg_replace('/(\.(gif|jpg|jpeg|png))$/i', '__' . $imageImport->LotId . '\1', $newFile);
                } while (file_exists($lotItemImagesDir . '/' . $newFile));
                log_debug('new filename ' . $newFile);
            } else {
                $newFile = $fileName;
                log_error('create new file ' . $newFile);
            }

            $ftpClientResult = $ftpClient->get($lotItemImagesDir . '/' . $newFile, $fileName, FTP_BINARY);
            if ($ftpClientResult->hasError()) {
                log_error($ftpClientResult->logMessage());
                @unlink($lotItemImagesDir . '/' . $fileName); //possibly corrupted
                continue;
            }
            log_debug('successful download of image.');
            $this->downloadedImages[$imageImport->LotId][] = [
                'file' => $newFile,
                'replace_existing' => $imageImport->ReplaceExisting,
                'lot_id' => $imageImport->LotId,
                'image_optimize' => $imageImport->ImageOptimize,
                'image_auto_orient' => $imageImport->ImageAutoOrient,
            ];
        }
    }

    /**
     * Load all non processed
     * @retun void
     *
     */
    public function loadAllNonProcessed(): void
    {
        $this->imageImports = $this->createImageImportQueueReadRepository()
            ->filterProcessed(false)
            ->skipAuctionId(null)
            ->skipLotId(null)
            ->orderByHost()
            ->orderByUsername()
            ->orderByDirectory()
            ->orderByAuctionId()
            ->orderByLotId()
            ->loadEntities();
    }

    /**
     * Save downloaded images
     * @param int $editorUserId
     * @return void
     */
    public function saveDownloadedImages(int $editorUserId): void
    {
        $fileManager = $this->createFileManager();
        $lotImageQueue = $this->createLotImageQueue();
        foreach ($this->downloadedImages as $lotItemId => $lotImages) {
            $lotItem = LotItemLoader::new()->load((int)$lotItemId, true);
            $lotItemImagesDir = $this->createLotImagePathResolver()->makeLotImageDirectoryPath($lotItem->AccountId ?? null);
            log_trace(var_export($lotImages, true));
            /** resize all images with width greater than IMAGE_MAX_WIDTH and/or height greater than IMAGE_MAX_HEIGHT **/
            foreach ($lotImages as $index => $fileProperties) {
                $file = $fileProperties['file'];
                $filePath = $lotItemImagesDir . '/' . $file;
                if (
                    is_readable($filePath)
                    && $imgInfo = @getimagesize($filePath)
                ) {
                    $imageType = (int)$imgInfo[2];
                    if (!in_array($imageType, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
                        log_debug('Invalid image type - ' . $file);
                        @unlink($filePath);
                        unset($lotImages[$index]);
                        continue;
                    }
                    if ($imgInfo[0] > $this->cfg()->get('core->image->maxWidth') || $imgInfo[1] > $this->cfg()->get('core->image->maxHeight')) {
                        $quality = ($imageType === IMAGETYPE_JPEG) ? $this->cfg()->get('core->image->jpegQuality') : 75;
                        $blobImage = file_get_contents($filePath);
                        $resizer = Resizer::new();
                        if ($fileProperties['image_auto_orient']) {
                            $resizer->setHeight($this->cfg()->get('core->image->maxHeight'))
                                ->setWidth($this->cfg()->get('core->image->maxWidth'));
                        }
                        if (!$fileProperties['image_optimize']) {
                            $resizer->enableFixImageOrientation(false);
                        }

                        $success = $resizer->setImage($blobImage)
                            ->setQuality($quality)
                            ->setTargetFileRootPath($lotItemImagesDir . '/' . $file)
                            ->resize();
                        if (!$success) {
                            log_warning('Failed to resize image' . composeSuffix(['source' => $filePath]));
                            continue;
                        }
                    }
                } else {
                    log_debug(composeSuffix(['Cannot get the image size of file' => $filePath]));
                    continue;
                }
                unset($imgInfo);
                $maxOrderRow = $this->createLotImageReadRepository()
                    ->select(['MAX(limg.order) as `orderMax`'])
                    ->filterLotItemId(100)
                    ->groupByLotItemId()
                    ->loadRow();
                if (isset($maxOrderRow['orderMax'])) {
                    $orderNum = $maxOrderRow['orderMax'] + 1;
                } else {
                    $orderNum = 0;
                }
                $lotImage = null;
                if ($fileProperties['replace_existing']) {
                    $lotImage = $this->getLotImageLoader()->loadByLotItemIdAndImageLink((int)$lotItemId, $file, true);
                }
                if (!$lotImage) {
                    $lotImage = $this->createEntityFactory()->lotImage();
                }
                $imageFilePath = str_replace(path()->sysRoot(), '', $lotItemImagesDir . '/' . $file);
                try {
                    $imageSize = $fileManager->getSize($imageFilePath);
                } catch (Exception $e) {
                    log_error($e->getMessage());
                    continue;
                }
                $lotImage->Size = $imageSize;
                $lotImage->LotItemId = $lotItemId;
                $lotImage->ImageLink = $file;
                $lotImage->Order = $orderNum;
                $this->getLotImageWriteRepository()->saveWithModifier($lotImage, $editorUserId);
                $lotImageId = $lotImage->Id;

                $row = $this->createAuctionLotItemReadRepository()
                    ->select(['ali.auction_id'])
                    ->filterLotItemId($lotItemId)
                    ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
                    ->orderByCreatedOn(false)
                    ->loadRow();

                if ($row) {
                    $lotImageQueue->addToCached($lotImageId, $editorUserId, (int)$row['auction_id']);
                }
            }
        }
    }

    /**
     * Sort downloaded images.
     * @return void
     */
    protected function sortDownloadedImages(): void
    {
        $sortedDownloadedImages = [];
        foreach ($this->downloadedImages as $lotItemId => $lotImages) {
            usort($lotImages, [$this, 'callBackSort']);
            $sortedDownloadedImages[$lotItemId] = $lotImages;
        }
        $this->downloadedImages = $sortedDownloadedImages;
    }

    /**
     * @param array $elem1
     * @param array $elem2
     * @return int
     */
    protected function callBackSort(array $elem1, array $elem2): int
    {
        $elem1['file'] = preg_replace('/(\.(gif|jpg|jpeg|png))$/i', '', $elem1['file']);
        $elem2['file'] = preg_replace('/(\.(gif|jpg|jpeg|png))$/i', '', $elem2['file']);
        $elem1['file'] = preg_replace('/__' . $elem1['lot_id'] . '/', '', $elem1['file']);
        $elem2['file'] = preg_replace('/__' . $elem1['lot_id'] . '/', '', $elem2['file']);
        return strcmp($elem1['file'], $elem2['file']);
    }
}
