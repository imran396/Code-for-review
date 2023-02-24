<?php
/**
 * File archive processing service for user bulk import
 * Back end: user list
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: UploadHelper.php 15444 2013-12-11 04:10:27Z SWB\bregidor $
 * @since           16 Jan, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Notes:
 * 1) We may need to handle another types of archives (i.e., rar).
 * 2) We may need to implement file processing without uploaded csv in the same upload action.
 */

namespace Sam\CustomField\User\FileType;

use Sam\CustomField\User\Load\UserCustomDataLoaderAwareTrait;
use Sam\File\Manage\FileException;
use Sam\File\Manage\LocalFileManager;
use Sam\Storage\WriteRepository\Entity\UserCustData\UserCustDataWriteRepositoryAwareTrait;
use UserCustData;
use ZipArchive;

/**
 * Class FileArchiveService
 * @package Sam\CustomField\User\FileType
 */
class FileArchiveService extends \Sam\CustomField\Base\FileType\FileArchiveService
{
    use UserCustDataWriteRepositoryAwareTrait;
    use UserCustomDataLoaderAwareTrait;

    protected string $userCustomFieldFileRootPath;
    protected array $userCustomData = []; // cache there loaded UserCustData objects

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
        $this->userCustomData = [];
        $this->userCustomFieldFileRootPath = path()->uploadUserCustomFieldFile();
        parent::initInstance();
        return $this;
    }

    /**
     * Process files from zip archive for file-type custom fields, which are imported in csv
     * We extract files, handle file names (rename if needed), move to permanent location and link them with custom fields
     * @param string $archiveFileRootPath uploaded file archive absolute path
     * @param string $archiveFileName archive file original file name
     * @param array $custFiles array of expected files imported by csv: array[user_cust_data.id] = array(<file name>)
     * @param int $editorUserId
     * @throws FileException
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
            $rootPath = $this->userCustomFieldFileRootPath . '/' . $this->accountId;
            LocalFileManager::new()->createDirPath($rootPath . '/');
            $path = substr($this->userCustomFieldFileRootPath . '/' . $this->accountId, strlen(path()->sysRoot()));
            $fileManager = $this->createFileManager();
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entry = $zip->getNameIndex($i);
                $originalFileName = $zip->getNameIndex($i);
                if ($this->checkFileInCsv($originalFileName, $custFiles)) {
                    foreach ($custFiles as $userCustomDataId => $fileNames) {
                        $fileName = $originalFileName;
                        if (in_array($fileName, $fileNames, true)) {
                            $userCustomData = $this->getUserCustData($userCustomDataId);
                            $userId = $userCustomData->UserId;
                            if ($fileManager->exist($path . '/' . $fileName)) {
                                // We need to rename file, because it exists in file-system
                                // and probably is linked to another user custom field
                                $extension = substr($fileName, strrpos($fileName, '.') + 1);
                                while ($fileManager->exist($path . '/' . $fileName)) {
                                    $fileName = preg_replace(
                                        '/(\.(' . $extension . '))$/i',
                                        '__' . $userId . '\1',
                                        $fileName
                                    );
                                }
                                $userCustomData->Text = $this->replaceFileName(
                                    $userCustomData->Text,
                                    $originalFileName,
                                    $fileName
                                );
                                $this->getUserCustDataWriteRepository()->saveWithModifier($userCustomData, $editorUserId);
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
                                'assigned to user with id "' . $userId . '"'
                            );
                        }
                    }
                } else {
                    $this->trackErrorFileNotInCsv($originalFileName);
                }
            }
            $this->checkCsvFilesExistInArchiveAndTrackErrors($custFiles, $filesInArchive);
        } else {
            $message = 'Failed to open zip file "' . $archiveFileName . '"';
            $this->addError($message);
            log_warning($message);
        }
    }

    /**
     * Return user custom data object by id
     * @param int $userCustomDataId
     * @return UserCustData
     */
    public function getUserCustData(int $userCustomDataId): UserCustData
    {
        if (!isset($this->userCustomData[$userCustomDataId])) {
            $this->userCustomData[$userCustomDataId] = $this->getUserCustomDataLoader()->loadById($userCustomDataId);
        }
        return $this->userCustomData[$userCustomDataId];
    }
}
