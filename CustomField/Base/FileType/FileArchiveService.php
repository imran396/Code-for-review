<?php
/**
 * Base class for file archive processing services, which uploaded via bulk imports
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
 * @property int AccountId      account.id
 * @property bool Public         true - public side, false - admin side
 */

namespace Sam\CustomField\Base\FileType;

use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileManagerCreateTrait;

/**
 * Class FileArchiveService
 * @package Sam\CustomField\Base\FileType
 */
abstract class FileArchiveService extends CustomizableClass
{
    use FileManagerCreateTrait;

    protected bool $isPublic;
    protected int $accountId;
    /** @var string[] */
    protected array $errors = [];

    /**
     * Set initialization parameters
     * @param int $accountId
     * @param bool $isPublic
     * @return static
     */
    public function construct(int $accountId, bool $isPublic = false): static
    {
        $this->accountId = $accountId;
        $this->isPublic = $isPublic;
        return $this;
    }

    /**
     * Return array of errors happened during process
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Add error message
     * @param string $message
     */
    protected function addError(string $message): void
    {
        $this->errors[] = $message;
    }

    /**
     * Check if file name is among files added via csv
     * @param string $fileName checking file
     * @param array $custFiles files added via csv
     * @return bool
     */
    protected function checkFileInCsv(string $fileName, array $custFiles): bool
    {
        return ArrayHelper::inMultiArray($fileName, $custFiles);
    }

    /**
     * Track error, that file from archive doesn't have correspondence in csv
     * @param string $fileName
     */
    protected function trackErrorFileNotInCsv(string $fileName): void
    {
        $message = "File " . $fileName . " is not in csv";
        $this->addError($message);
        log_info($message);
    }

    /**
     * Check if file names added in csv are among files in uploaded zip archive
     * Track error message For files, which failed checking
     * @param array $customFiles
     * @param array $filesInArchive
     */
    protected function checkCsvFilesExistInArchiveAndTrackErrors(array $customFiles, array $filesInArchive): void
    {
        foreach ($customFiles as $fileNames) {
            foreach ($fileNames as $fileName) {
                if ($fileName && !in_array($fileName, $filesInArchive, true)) {
                    $message = "File " . $fileName . " is not in zip";
                    $this->addError($message);
                    log_info($message);
                }
            }
        }
    }

    /**
     * Replace file name in pipe separated string of file names
     * @param string $fileNames pipe separated string of file names
     * @param string $oldFileName file name to replace
     * @param string $newFileName new file name
     * @return string
     */
    protected function replaceFileName(string $fileNames, string $oldFileName, string $newFileName): string
    {
        $fileNames = '|' . $fileNames . '|';
        $fileNames = preg_replace('/\|' . $oldFileName . '\|/', '|' . $newFileName . '|', $fileNames);
        $fileNames = trim($fileNames, '|');
        return $fileNames;
    }
}
