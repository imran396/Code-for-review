<?php
/**
 * File archive service for lot bulk import
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: UploadHelper.php 15444 2013-12-11 04:10:27Z SWB\bregidor $
 * @since           16 Jan, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\FileType;

use LotItemCustData;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\File\Manage\LocalFileManager;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItemCustData\LotItemCustDataWriteRepositoryAwareTrait;
use ZipArchive;

/**
 * Class FileArchiveService
 * @package Sam\CustomField\Lot\FileType
 */
class FileArchiveService extends \Sam\CustomField\Base\FileType\FileArchiveService
{
    use LotCustomDataLoaderCreateTrait;
    use LotItemCustDataWriteRepositoryAwareTrait;
    use TranslatorAwareTrait;

    protected string $customFieldFileRootPath;
    /**
     * Loaded entries cache
     */
    protected array $lotCustomData = [];

    /**
     * Class instantiation method
     * @return static or customized class extended from it
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Init instance with defaults, inject dependencies
     * @return static
     */
    public function initInstance(): static
    {
        $this->lotCustomData = [];
        $this->customFieldFileRootPath = path()->uploadLotCustomFieldFile();
        parent::initInstance();
        return $this;
    }

    /**
     * Extract file archive, which contain files for file-type custom fields
     * @param string $archiveFileRootPath
     * @param string $archiveFileName
     * @param array $custFiles
     * @param int $editorUserId
     * @throws \Sam\File\Manage\FileException
     */
    public function processImportedByCsv(
        string $archiveFileRootPath,
        string $archiveFileName,
        array $custFiles,
        int $editorUserId
    ): void {
        $zip = new ZipArchive();
        $filesInArchive = [];
        if ($zip->open($archiveFileRootPath) === true) {
            $rootPath = $this->customFieldFileRootPath . '/' . $this->accountId;
            LocalFileManager::new()->createDirPath($rootPath . '/');
            $path = substr($this->customFieldFileRootPath . '/' . $this->accountId, strlen(path()->sysRoot()));
            $fileManager = $this->createFileManager();
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entry = $zip->getNameIndex($i);
                $originalFileName = $zip->getNameIndex($i);
                if ($this->checkFileInCsv($originalFileName, $custFiles)) {
                    foreach ($custFiles as $lotCustomDataId => $fileNames) {
                        $fileName = $originalFileName;
                        if (in_array($fileName, $fileNames, true)) {
                            $lotCustomData = $this->getLotCustomData($lotCustomDataId);
                            if (!$lotCustomData) {
                                log_error("Lot Item Custom Data record cannot be found by id" . composeSuffix(['licd' => $lotCustomDataId]));
                                continue;
                            }
                            $lotItemId = $lotCustomData->LotItemId;
                            if ($fileManager->exist($path . '/' . $fileName)) {
                                // We need to rename file, because it exists in file-system
                                // and probably is linked to another lot custom field
                                $extension = substr($fileName, strrpos($fileName, '.') + 1);
                                while ($fileManager->exist($path . '/' . $fileName)) {
                                    $fileName = preg_replace(
                                        '/(\.(' . $extension . '))$/i',
                                        '__' . $lotItemId . '\1',
                                        $fileName
                                    );
                                }
                                $lotCustomData->Text = $this->replaceFileName(
                                    $lotCustomData->Text,
                                    $originalFileName,
                                    $fileName
                                );
                                $this->getLotItemCustDataWriteRepository()->saveWithModifier($lotCustomData, $editorUserId);
                            }
                            // Put file in permanent location
                            $filePath = $path . '/' . $fileName;
                            $fileRootPath = $rootPath . '/' . $fileName;
                            copy("zip://" . $archiveFileRootPath . "#" . $entry, $fileRootPath);
                            $fileManager->put($fileRootPath, $filePath);
                            $filesInArchive[] = $originalFileName;
                            log_info(
                                'File "' . $originalFileName . '" is ' .
                                ($fileName !== $originalFileName ? 'renamed to "' . $fileName . '" and ' : '') .
                                'assigned to lot with id "' . $lotItemId . '"'
                            );
                        }
                    }
                } else {
                    $this->trackErrorFileNotInCsv($originalFileName);
                }
            }
            $this->checkCsvFilesExistInArchiveAndTrackErrors($custFiles, $filesInArchive);
        } else {
            $message = $this->isPublic ? $this->getTranslator()->translate('MYITEMS_FAILED_OPEN_ZIP', 'myitems')
                : 'Failed to open zip file';
            $message .= ' ' . $archiveFileName;
            $this->addError($message);
            log_warning($message);
        }
    }

    /**
     * Return lot item custom data object by id
     * @param int $lotCustomDataId
     * @return ?LotItemCustData
     */
    public function getLotCustomData(int $lotCustomDataId): ?LotItemCustData
    {
        if (!isset($this->lotCustomData[$lotCustomDataId])) {
            $this->lotCustomData[$lotCustomDataId] = $this->createLotCustomDataLoader()->loadById($lotCustomDataId);
        }
        return $this->lotCustomData[$lotCustomDataId];
    }
}
