<?php
/**
 * Checking image and extracting them from archive
 *
 * Image zip upload improvement
 * @see https://bidpath.atlassian.net/browse/SAM-3409
 *
 * @copyright   2018 Bidpath, Inc.
 * @author      Maxim Lyubetskiy
 * @package     com.swb.sam2
 * @version     SVN: $Id$
 * @since       Sep 09, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Image\Zip;

use LotImage;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Image\NameGenerator;
use Sam\Image\Resize\Resizer;
use Sam\Image\UploadFolderCreator;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotImage\LotImageWriteRepositoryAwareTrait;

/**
 * Class LotImagesArchiveProcessor
 */
class LotImagesArchiveProcessor extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use FileManagerCreateTrait;
    use LotImageLoaderAwareTrait;
    use LotImageWriteRepositoryAwareTrait;

    private ?UploadFolderCreator $uploadFolderCreator = null;
    private ?NameGenerator $nameGenerator = null;
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
     * Extract image archive, assign images to lots and perform other related actions
     * @param array $imageFileNames
     * @param int $editorUserId
     * @param int|null $auctionId - null means no auction (eg in inventory)
     * @param array{autoOrientImage?: bool, optimizeImage?: bool} $options
     * @throws FileException
     */
    public function processLotImagesArchive(array $imageFileNames, int $editorUserId, ?int $auctionId = null, array $options = []): void
    {
        foreach ($imageFileNames as $imageFileNameInArchive) {
            $originalFileName = $fileName = $this->getFileNameFromArchiveFilePath($imageFileNameInArchive);
            $lotImageArray = $this->getLotImagesByImageFileName($fileName, $auctionId);
            $this->processLotImages($lotImageArray, $originalFileName, $imageFileNameInArchive, $editorUserId, $options);
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
     * We process all found images. For each image we check if exists a file with the same name.
     * If the file exists then we generate a new name. Move uploaded file to a proper folder with a new name.
     * Save name and image size in db.
     * @param LotImage[] $lotImageArray
     * @param string $originalFileName
     * @param string $imageFileNameInArchive
     * @param int $editorUserId
     * @param array{autoOrientImage?: bool, optimizeImage?: bool} $options
     * @return void
     * @throws FileException
     */
    protected function processLotImages(
        array $lotImageArray,
        string $originalFileName,
        string $imageFileNameInArchive,
        int $editorUserId,
        array $options = []
    ): void {
        $relativePath = $this->getUploadFolderCreator()->getRelativePath();
        foreach ($lotImageArray as $lotImage) {
            $fileName = $originalFileName;
            $lotItemId = $lotImage->LotItemId;
            $nameGenerator = $this->getNameGenerator();
            if (
                $nameGenerator
                && $this->createFileManager()->exist($relativePath . '/' . $fileName)
            ) {
                $fileName = $nameGenerator->getNewFileName(
                    $fileName,
                    $lotItemId,
                    $this->isReplaceExisting()
                );
            }
            $fileRootPath = $this->getFileRootPath($fileName);
            $this->copyImagesToUploadFolder($imageFileNameInArchive, $fileRootPath, $options);
            $this->putFileToRemoteServer($fileName, $fileRootPath);
            $imageSize = $this->createFileManager()->getSize($relativePath . '/' . $fileName);
            $this->saveLotImage($lotImage, $fileName, $imageSize, $editorUserId);
            $this->logResult($originalFileName, $fileName, $lotItemId);
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

    /**
     * @param LotImage $lotImage
     * @param string $fileName
     * @param int $imageSize
     * @param int $editorUserId
     */
    protected function saveLotImage(LotImage $lotImage, string $fileName, int $imageSize, int $editorUserId): void
    {
        $lotImage->ImageLink = $fileName;
        $lotImage->Size = $imageSize;
        $this->getLotImageWriteRepository()->saveWithModifier($lotImage, $editorUserId);
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
     * @return LotImage[]
     */
    protected function getLotImagesByImageFileName(string $fileName, ?int $auctionId): iterable
    {
        $lotImages = $this->getLotImageLoader()->loadByAuctionIdAndImageLink($auctionId, $fileName);
        if (empty($lotImages)) {
            $logMsg = 'No lots are found for image "' . $fileName . '" in ';
            $logMsg .= ($auctionId ? 'auction "' . $auctionId . '"' : 'inventory');
            log_info($logMsg);
        }
        return $lotImages;
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
    protected function logResult(string $originalFileName, string $fileName, int $lotItemId): void
    {
        $logMsg = 'Image "' . $originalFileName . '" is ';
        $logMsg .= ($fileName !== $originalFileName ? 'renamed to "' . $fileName . '" and ' : '');
        $logMsg .= 'assigned to lot"' . composeSuffix(['li' => $lotItemId]) . '"';
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
     * @return NameGenerator
     */
    public function getNameGenerator(): ?NameGenerator
    {
        return $this->nameGenerator;
    }

    /**
     * @param NameGenerator|null $nameGenerator
     * @return static
     */
    public function setNameGenerator(?NameGenerator $nameGenerator): static
    {
        $this->nameGenerator = $nameGenerator;
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
