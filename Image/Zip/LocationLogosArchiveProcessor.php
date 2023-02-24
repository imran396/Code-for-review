<?php
/**
 * Checking location logos and extracting them from archive
 *
 * SAM-10273 Entity locations: Implementation (Dev)
 *
 * @copyright   2018 Bidpath, Inc.
 * @author      Victor Pautoff
 * @package     com.swb.sam2
 * @version     SVN: $Id$
 * @since       Mar 16, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Image\Zip;

use Sam\Application\Url\Build\Config\Image\LocationImageUrlConfig;
use Sam\Core\Constants\Location;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Image\Resize\Resizer;
use Sam\Image\UploadFolderCreator;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Location\Load\LocationLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\Location\LocationWriteRepositoryAwareTrait;

/**
 * Class LocationLogosArchiveProcessor
 */
class LocationLogosArchiveProcessor extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use FileManagerCreateTrait;
    use LocationLoaderAwareTrait;
    use LocationWriteRepositoryAwareTrait;

    /**
     * @var int|null Named location has type null
     */
    public ?int $type = Location::TYPE_LOT_ITEM;
    private ?UploadFolderCreator $uploadFolderCreator = null;
    private bool $replaceExisting = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Extract image archive, assign logos to locations and perform other related actions
     * @param array $imageFileNames
     * @param int $editorUserId
     * @param int|null $auctionId - null means no auction (eg in inventory)
     * @param array{autoOrientImage?: bool, optimizeImage?: bool} $options
     * @throws FileException
     */
    public function processLocationLogosArchive(array $imageFileNames, int $editorUserId, ?int $auctionId = null, array $options = []): void
    {
        foreach ($imageFileNames as $imageFileNameInArchive) {
            $originalFileName = $fileName = $this->getFileNameFromArchiveFilePath($imageFileNameInArchive);
            $logosArray = $this->getLocationLogosByImageFileName($fileName, $auctionId);
            $this->processLocationLogos($logosArray, $originalFileName, $imageFileNameInArchive, $editorUserId, $options);
        }
    }

    /**
     * @param string $imageFileNameInArchive
     * @return string
     */
    protected function getFileNameFromArchiveFilePath(string $imageFileNameInArchive): string
    {
        $parts = explode("#", $imageFileNameInArchive, 2);
        $pathInfo = pathinfo($parts[1]);
        $fileName = $pathInfo['filename'] . "." . $pathInfo['extension'];
        return $fileName;
    }

    /**
     * @param array{autoOrientImage?: bool, optimizeImage?: bool} $options
     */
    protected function processLocationLogos(
        array $locationArray,
        string $originalFileName,
        string $imageFileNameInArchive,
        int $editorUserId,
        array $options = []
    ): void {
        $relativePath = $this->getUploadFolderCreator()->getRelativePath();
        foreach ($locationArray as $location) {
            $fileName = $originalFileName;
            if ($this->createFileManager()->exist($relativePath . '/' . $fileName)) {
                // TODO: apply $this->isReplaceExisting() here
                $fileName = LocationImageUrlConfig::new()
                    ->construct($location->Id, $location->AccountId)
                    ->fileName();
            }
            $fileRootPath = $this->getFileRootPath($fileName);

            $this->copyImagesToUploadFolder($imageFileNameInArchive, $fileRootPath, $options);
            $this->putFileToRemoteServer($fileName, $fileRootPath);
            $this->saveLocation($location, $fileName, $editorUserId);
            $this->logResult($originalFileName, $fileName, $location->EntityId);
        }
    }

    /**
     * @param string $imageFileNameInArchive
     * @param string $fileRootPath
     * @param array{autoOrientImage?: bool, optimizeImage?: bool} $options
     * @return bool
     * @internal param $fileName
     */
    protected function copyImagesToUploadFolder(
        string $imageFileNameInArchive,
        string $fileRootPath,
        array $options = []
    ): bool {
        if (!copy($imageFileNameInArchive, $fileRootPath)) {
            log_warning("Failed to copy " . $imageFileNameInArchive . " to " . $fileRootPath);
            return false;
        }
        $success = true;
        if (count($options)) {
            $blobImage = @file_get_contents($fileRootPath);
            if (!$blobImage) {
                log_warning('File does not exist or not readable' . composeSuffix(['source' => $fileRootPath]));
                return false;
            }

            $resizer = Resizer::new();
            if (!$options['autoOrientImage']) {
                $resizer->enableFixImageOrientation(false);
            }
            if ($options['optimizeImage']) {
                $resizer->setHeight($this->cfg()->get('core->image->maxHeight'))
                    ->setWidth($this->cfg()->get('core->image->maxWidth'));
            }
            $resizer->setImage($blobImage);
            $success = $resizer->setTargetFileRootPath($fileRootPath)
                ->resize();
            if (!$success) {
                log_warning('Failed to resize image' . composeSuffix(['source' => $fileRootPath]));
            }
        }
        return $success;
    }

    protected function saveLocation(\Location $location, string $fileName, int $editorUserId): void
    {
        $location->Logo = $fileName;
        $this->getLocationWriteRepository()->saveWithModifier($location, $editorUserId);
    }

    /**
     * @param string $fileName
     * @return string
     */
    protected function getFileRootPath(string $fileName): string
    {
        $fileRootPath = $this->getUploadFolderCreator()->getRootPath() . '/' . $fileName;
        return $fileRootPath;
    }

    /**
     * @param string $fileName
     * @param int|null $auctionId - null means no auction
     * @return Location[]
     */
    protected function getLocationLogosByImageFileName(string $fileName, ?int $auctionId): iterable
    {
        $logos = $this->getLocationLoader()->loadByTypeAndLogo($this->type, $fileName);
        if (empty($logos)) {
            $logMsg = 'No locations are found for logo "' . $fileName . '" ';
            $logMsg .= 'type: ' . (Location::TYPE_TO_DB_ALIAS_MAP[$this->type] ?? '') . ' ';
            $logMsg .= ($auctionId ? 'auction "' . $auctionId . '"' : '');
            log_info($logMsg);
        }
        return $logos;
    }

    /**
     * @param string $fileName
     * @param string $fileRootPath
     * @throws FileException
     */
    protected function putFileToRemoteServer(string $fileName, string $fileRootPath): void
    {
        $filePath = $this->getUploadFolderCreator()->getRelativePath() . '/' . $fileName;
        $this->createFileManager()->put($fileRootPath, $filePath);
    }

    /**
     * @param string $originalFileName
     * @param string $fileName
     * @param int $lotItemId
     */
    protected function logResult(string $originalFileName, string $fileName, ?int $lotItemId): void
    {
        $logMsg = 'Image "' . $originalFileName . '" is ';
        $logMsg .= ($fileName !== $originalFileName ? 'renamed to "' . $fileName . '" and ' : '');
        $logMsg .= $lotItemId ? ('assigned to lot"' . composeSuffix(['li' => $lotItemId]) . '"') : '';
        log_info($logMsg);
    }

    //<editor-fold desc="Getters&Setters">

    /**
     * @return UploadFolderCreator
     */
    public function getUploadFolderCreator(): UploadFolderCreator
    {
        return $this->uploadFolderCreator;
    }

    /**
     * @param UploadFolderCreator $uploadFolderCreator
     * @return static
     */
    public function setUploadFolderCreator(UploadFolderCreator $uploadFolderCreator): static
    {
        $this->uploadFolderCreator = $uploadFolderCreator;
        return $this;
    }

    /**
     * @return bool
     */
    public function isReplaceExisting(): bool
    {
        return $this->replaceExisting;
    }

    /**
     * @param bool $replaceExisting
     * @return static
     */
    public function enableReplaceExisting(bool $replaceExisting): static
    {
        $this->replaceExisting = $replaceExisting;
        return $this;
    }
    //</editor-fold>
}
