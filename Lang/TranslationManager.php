<?php
/**
 * SAM-4449: Language label translation modules
 * https://bidpath.atlassian.net/browse/SAM-4449
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/2/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lang;

use Account;
use Exception;
use RuntimeException;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Cache\File\FilesystemCacheManager;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\Singleton;
use Sam\File\FilePathHelperAwareTrait;
use Sam\File\FolderManagerAwareTrait;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\ViewLanguage\Load\ViewLanguageLoaderAwareTrait;
use Sam\Log\Support\SupportLoggerAwareTrait;

/**
 * Class TranslationManager
 * @package Sam\Lang
 */
class TranslationManager extends Singleton
{
    use AccountLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;
    use FileManagerCreateTrait;
    use FilePathHelperAwareTrait;
    use FilesystemCacheManagerAwareTrait;
    use FolderManagerAwareTrait;
    use LocalFileManagerCreateTrait;
    use SupportLoggerAwareTrait;
    use ViewLanguageLoaderAwareTrait;

    private const PARAM_LIFE_TIME = 300; // keep translations alive for 5 mins
    /**
     * @var array
     */
    public array $translations = [];
    /**
     * @var array
     */
    public array $paramModTime = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function getInstance(): static
    {
        return parent::_getInstance(self::class);
    }

    /**
     * Returns an array of words/phrases
     * and their translations from the file
     *    /var/upload/language/translation.csv
     * @param string $fileName
     * @param int $accountId
     * @param bool $isMaster
     * @param int|null $viewLanguageId
     * @param bool $isCached
     * @return array
     */
    public function getTranslations(
        string $fileName,
        int $accountId,
        bool $isMaster = false,
        ?int $viewLanguageId = null,
        bool $isCached = true
    ): array {
        $cacheKey = sprintf("%s_%d", $fileName, $accountId);
        // clear cache for an un- cached request
        if (!$isCached && array_key_exists($cacheKey, $this->translations)) {
            unset($this->translations[$cacheKey]);
        }

        // random garbage collection
        if (random_int(0, 100) > 90) {
            $this->collectGarbage();
        }

        // reload if parameters for this account is not set of if it is too old
        if (
            !array_key_exists($cacheKey, $this->translations)
            || $this->translations[$cacheKey] === null
            || (array_key_exists($fileName, $this->paramModTime)
                && time() > $this->paramModTime[$cacheKey] + self::PARAM_LIFE_TIME)
        ) {
            $sFile = $this->toFilename($fileName, $accountId, $viewLanguageId);
            $fileName .= '.csv';
            $masterFileRootPath = in_array($fileName, Constants\CustomField::$masterTranslationFiles)
                ? path()->customFieldTranslationMaster() . '/' . $fileName
                : path()->translationMaster() . '/' . $fileName;
            $languageFileRootPath = path()->translation() . "/$sFile";
            $fileRootPath = $isMaster ? $masterFileRootPath : $languageFileRootPath;

            $translations = false;
            try {
                $translations = $this->getFilesystemCacheManager()
                    ->setNamespace('translation')
                    ->setExtension(FilesystemCacheManager::EXT_PHP)
                    ->get($fileRootPath, false);
            } catch (Exception $e) {
                log_warning(
                    'Failed to get from file system cache for '
                    . $fileRootPath . ': '
                    . '(' . $e->getCode() . ') ' . $e->getMessage()
                );
            }

            if (!$translations) {
                $translations = [];
                if (!file_exists($masterFileRootPath)) {
                    touch($masterFileRootPath);
                }

                if (!file_exists($languageFileRootPath)) {
                    try {
                        $this->checkOrCreateLangRootPath();
                        copy($masterFileRootPath, $languageFileRootPath);
                        $this->createLocalFileManager()->applyDefaultPermissions($languageFileRootPath);
                    } catch (Exception) {
                        log_error("Failed to copy $masterFileRootPath to language directory");
                    }
                }

                $logData = [
                    'filename' => $fileName,
                    'acc' => $accountId,
                    'lang id' => $viewLanguageId,
                ];
                if ($isMaster) {
                    $fh = fopen($masterFileRootPath, "rb");
                    if (!$fh) {
                        log_error("Master translation file cannot be opened" . composeSuffix($logData + ['master path' => $masterFileRootPath]));
                        return [];
                    }
                } else {
                    $fh = fopen($languageFileRootPath, "rb");
                    if (!$fh) {
                        log_error("Translation file cannot be opened" . composeSuffix($logData + ['path' => $languageFileRootPath]));
                        return [];
                    }
                }

                while (!feof($fh)) {
                    $row = fgetcsv($fh);
                    if (
                        is_array($row)
                        && count($row) === 3
                    ) {
                        $translations[$row[0]] = [$row[0], $row[1], trim($row[2])];
                    }
                }
                fclose($fh);

                try {
                    $this->getFilesystemCacheManager()
                        ->setNamespace('translation')
                        ->setExtension(FilesystemCacheManager::EXT_PHP)
                        ->set($fileRootPath, $translations);
                } catch (Exception $e) {
                    log_warning(
                        'Failed to write to file system cache for '
                        . $fileRootPath . ': '
                        . '(' . $e->getCode() . ') ' . $e->getMessage()
                    );
                }
            }

            $this->translations[$cacheKey] = $translations;
            $this->paramModTime[$cacheKey] = time();
        }
        return $this->translations[$cacheKey] ?: [];
    }

    /**
     * clean up system parameters stored for different accounts that
     * are older than System_Parameters::intParamLifeTime seconds
     */
    private function collectGarbage(): void
    {
        $ts = time();
        foreach ($this->paramModTime as $cacheKey => $v) {
            if ($ts > $this->paramModTime[$cacheKey] + self::PARAM_LIFE_TIME) {
                unset($this->paramModTime[$cacheKey], $this->translations[$cacheKey]);
            }
        }
    }

    /**
     * @param string $fileName
     * @param bool $isMaster
     * @return array
     */
    public function getTranslationsAsKeyedArray(string $fileName, bool $isMaster = false): array
    {
        $translations = [];
        //TODO: change to retrieved values from csv
        //ini_set('auto_detect_line_endings',true);

        if ($isMaster) {
            $fileRootPath = in_array($fileName, Constants\CustomField::$masterTranslationFiles)
                ? path()->customFieldTranslationMaster() . "/$fileName"
                : path()->translationMaster() . "/$fileName";
        } else {
            $fileRootPath = path()->translation() . "/$fileName";
        }

        $fh = @fopen($fileRootPath, "rb");

        // file does not exist, return empty array
        if ($fh === false) {
            log_warning(composeLogData(['File not found' => $fileRootPath]));
            return $translations;
        }

        while (!feof($fh)) {
            $row = fgetcsv($fh);
            //$arrRow = explode(',', $row);
            if (isset($row[2])) {
                $translations[$row[0]] = [$row[1], trim($row[2])];
            }
        }
        fclose($fh);
        return $translations;
    }

    /**
     * @param array $translations
     * @param string $fileName
     * @param int $accountId
     * @param int|null $languageId
     * @return void
     */
    public function writeTranslations(array $translations, string $fileName, int $accountId, ?int $languageId = null): void
    {
        $fileName = $this->toFilename($fileName, $accountId, $languageId);
        $fileRootPath = path()->translation() . '/' . $fileName;
        $filePath = substr($fileRootPath, strlen(path()->sysRoot()));

        $fh = fopen($fileRootPath, "wb");
        foreach ($translations as $line) {
            /** @noinspection PhpRedundantOptionalArgumentInspection */
            fputcsv($fh, $line, ',', '"');
        }
        fclose($fh);

        $fileManager = $this->createFileManager();
        $fileManager->put($fileRootPath, $filePath);

        try {
            $this->getFilesystemCacheManager()
                ->setNamespace('translation')
                ->setExtension(FilesystemCacheManager::EXT_PHP)
                ->delete($fileRootPath);
        } catch (Exception $e) {
            log_warning(
                'Failed to delete in file system cache for '
                . $fileRootPath . ': '
                . '(' . $e->getCode() . ') ' . $e->getMessage()
            );
        }
    }

    /* ==== Translation Master Files ======= */
    /**
     * @param int $accountId
     * @param string|null $language
     * @param string|null $fileName
     * @return void
     */
    public function syncMasterFiles(int $accountId, ?string $language = null, ?string $fileName = null): void
    {
        //get master translations array
        $masterRootPath = path()->translationMaster() . "/";
        $langRootPath = path()->translation() . "/";

        if ($fileName === null) {
            $masterFiles = $this->getFileList($masterRootPath, true);
        } else {
            $masterFiles = [$fileName];
        }

        $accountName = '';
        if ($accountId !== $this->cfg()->get('core->portal->mainAccountId')) {
            $account = $this->getAccountLoader()->load($accountId);
            if ($account) {
                $accountName = $this->normalizeAccountNameToFilename($account->Name);
            }
        }

        foreach ($masterFiles as $masterFileName) {
            $langFileName = $masterFileName;

            if ($accountName) {
                $langFileName = substr($masterFileName, 0, -4);
                $langFileName = $accountName . '.' . $langFileName . '.csv';
            }

            if ($language !== null) {
                $langFileName = substr($langFileName, 0, -4);
                $langFileName .= '.' . $language . '.csv';
            }

            //check if the same file exists in the main language dir
            if (file_exists($langRootPath . $langFileName)) {
                //get the translations in this master file
                $masterLanguageFileTranslations = $this->getTranslationsAsKeyedArray($masterFileName, true);
                $languageFileTranslations = $this->getTranslationsAsKeyedArray($langFileName);
                foreach ($masterLanguageFileTranslations as $key => $val) {
                    //if this does key does not exist in
                    //the main lang file, add it
                    if (!array_key_exists($key, $languageFileTranslations)) {
                        $languageFileTranslations[$key] = $val;
                    } elseif ($masterLanguageFileTranslations[$key][0] !== $languageFileTranslations[$key][0]) {
                        //sync the master default content
                        //if it exists and if the default values are different
                        $newArrVal = [$masterLanguageFileTranslations[$key][0], $languageFileTranslations[$key][1]];
                        $languageFileTranslations[$key] = $newArrVal;
                    }
                }

                $differences = array_diff_key($languageFileTranslations, $masterLanguageFileTranslations);
                foreach ($differences as $key => $val) {
                    unset($languageFileTranslations[$key]);
                }

                //sort the array by key
                ksort($languageFileTranslations);

                //write the file
                $this->writeKeyedTranslations($languageFileTranslations, $langFileName);

                unset($masterLanguageFileTranslations, $languageFileTranslations);
            } else {
                //copy this master file to the main lang file
                try {
                    $this->checkOrCreateLangRootPath();
                    $fileManager = $this->createFileManager();
                    $fileRootPath = $langRootPath . $langFileName;
                    // Make the destination directory if not exist
                    $masterFileRootPath = in_array($masterFileName, Constants\CustomField::$masterTranslationFiles, true) ? path()->customFieldTranslationMaster(
                        ) . '/' . $masterFileName : $masterRootPath . $masterFileName;
                    if (!file_exists($masterFileRootPath)) {
                        // create empty file and put to master server
                        touch($masterFileRootPath);
                        $this->createLocalFileManager()->applyDefaultPermissions($masterFileRootPath);
                        $masterFilePath = substr($masterFileRootPath, strlen(path()->sysRoot()));
                        $fileManager->put($masterFileRootPath, $masterFilePath);
                    }
                    copy($masterFileRootPath, $fileRootPath);
                    $this->createLocalFileManager()->applyDefaultPermissions($fileRootPath);
                    $filePath = substr($fileRootPath, strlen(path()->sysRoot()));
                    $fileManager->put($fileRootPath, $filePath);
                } catch (Exception) {
                    log_error("Failed to copy $masterFileName to language directory");
                    continue;
                }
            }
        }
    }

    /**
     * @param string $directory
     * @param bool $isLangDir
     * @return array
     */
    public function getFileList(string $directory, bool $isLangDir = false): array
    {
        // create an array to hold directory list
        $results = [];

        // create a handler for the directory
        $handler = opendir($directory);

        // keep going until all files in directory have been read
        while ($file = readdir($handler)) {
            // if $file isn't this directory or its parent,
            // add it to the results array
            if ($file !== '.' && $file !== '..' && $file !== '.svn') {
                if ($isLangDir && $file === 'master') {
                    //skip if retrieving main lang files
                    continue;
                }

                $results[] = $file;
            }
        }

        // tidy up: close the handler
        closedir($handler);

        // done!
        return $results;
    }

    /**
     * Save translations and synchronize related data
     *
     * @param array $translations array of keyed translation
     * @param string $fileName name of translations file
     * @param int $accountId
     * @return void
     */
    public function saveKeyedTranslations(array $translations, string $fileName, int $accountId): void
    {
        $this->writeKeyedTranslations($translations, $fileName, true);
        $this->syncMasterFiles($accountId, null, $fileName);
        $viewLanguages = $this->getViewLanguageLoader()->loadByAccountId($accountId);
        foreach ($viewLanguages as $viewLanguage) {
            $this->syncMasterFiles($accountId, $this->getFilePathHelper()->toFilename($viewLanguage->Name), $fileName);
        }
    }

    /**
     * @param array $translations
     * @param string $fileName
     * @param bool $isMaster
     * @return void
     */
    public function writeKeyedTranslations(array $translations, string $fileName, bool $isMaster = false): void
    {
        $masterTranslationsPath = in_array($fileName, Constants\CustomField::$masterTranslationFiles) ? path()->customFieldTranslationMaster() : path()->translationMaster();
        $rootPath = $isMaster ? $masterTranslationsPath : path()->translation();
        $filePath = substr($rootPath, strlen(path()->sysRoot())) . '/' . $fileName;
        $fileRootPath = $rootPath . '/' . $fileName;
        $fileHandler = fopen($fileRootPath, "wb");
        foreach ($translations as $key => $val) {
            $lines = [$key, $val[0], $val[1]];
            /** @noinspection PhpRedundantOptionalArgumentInspection */
            fputcsv($fileHandler, $lines, ',', '"');
        }
        fclose($fileHandler);
        $fileManager = $this->createFileManager();
        $fileManager->put($fileRootPath, $filePath);
        try {
            $this->getFilesystemCacheManager()
                ->setNamespace('translation')
                ->setExtension(FilesystemCacheManager::EXT_PHP)
                ->delete($fileRootPath);
        } catch (Exception $e) {
            log_warning(
                'Failed to delete in file system cache for '
                . $fileRootPath . ': '
                . '(' . $e->getCode() . ') ' . $e->getMessage()
            );
        }
    }

    /**
     * @param string $name
     * @return string
     */
    public function toLanguageKey(string $name): string
    {
        $name = strtoupper($name);
        $name = preg_replace("/[^0-9a-z]+/i", "_", $name);
        return trim($name, "_");
    }

    /**
     * Add account name prefix, language postfix to filename
     * @param Account $account
     * @param string $masterFileName
     * @param string|null $lang
     * @return string
     */
    public function toLanguageFilename(string $masterFileName, Account $account, ?string $lang = null): string
    {
        $fileName = $masterFileName;
        // Add accountName prefix to filename
        if (
            $account->Id !== $this->cfg()->get('core->portal->mainAccountId')
            && $account->Name
        ) {
            $accountName = $this->normalizeAccountNameToFilename($account->Name);
            $fileName = $accountName . '.' . substr($masterFileName, 0, -4) . '.csv';
        }
        // Add language postfix to filename
        if ($lang) {
            $fileName = substr($fileName, 0, -4) . ".$lang.csv";
        }
        return $fileName;
    }

    /**
     * @param string $fileName
     * @param int $accountId
     * @param int|null $languageId
     * @return string
     */
    protected function toFilename(string $fileName, int $accountId, ?int $languageId): string
    {
        if ($languageId) {
            $viewLanguage = $this->getViewLanguageLoader()->load($languageId);
            if ($viewLanguage && $viewLanguage->Name) {
                $fileName .= '.' . $this->getFilePathHelper()->toFilename($viewLanguage->Name);
            }
        }

        if ($accountId !== $this->cfg()->get('core->portal->mainAccountId')) {
            $account = $this->getAccountLoader()->load($accountId);
            if ($account) {
                $accountName = $this->normalizeAccountNameToFilename($account->Name);
                $fileName = $accountName . '.' . $fileName;
            }
        }

        $fileName .= '.csv';
        return $fileName;
    }

    /**
     * @param string $accountName
     * @return string
     */
    protected function normalizeAccountNameToFilename(string $accountName): string
    {
        $accountName = strtolower($accountName);
        $accountName = preg_replace("/[^0-9a-z]+/i", '', $accountName);
        return $accountName;
    }

    /**
     * @return void
     */
    private function checkOrCreateLangRootPath(): void
    {
        $isChmod = false;
        $langRootPath = path()->translation();
        if (!is_dir($langRootPath)) {
            $oldMask = umask(0);
            $permissions = $this->getFolderManager()->defaultPermissions();
            $isChmod = @mkdir($langRootPath, $permissions, true);
            umask($oldMask);
            if (
                !$isChmod
                && !is_dir($langRootPath)
            ) {
                $this->getSupportLogger()->error(sprintf('Directory "%s" was not created', $langRootPath));
                throw new RuntimeException(sprintf('Directory "%s" was not created', $langRootPath));
            }
        }
        if ($isChmod) {
            $this->getFolderManager()->chmodRecursively($langRootPath);
        }
    }
}
