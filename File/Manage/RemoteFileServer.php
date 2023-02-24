<?php

/**
 * Remote file server to handle files and operations from RemoteFileClient
 *
 * SAM-1383: Local file writing proxy
 * SAM-6081: Apply FileManagerCreateTrait
 *
 * @package         com.swb.sam
 * @author          Igors Kotlevskis
 * @since           Feb 01, 2013
 * @copyright       Copyright 2018 by Bidpath, Inc. All rights reserved.
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\File\Manage;

use Sam\Core\Service\CustomizableClass;
use RuntimeException;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use SplFileObject;

/**
 * Class RemoteFileServer
 */
class RemoteFileServer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use LocalFileManagerCreateTrait;

    protected ?array $allowedPaths = null;
    protected ?array $deniedRegEx = null;

    /**
     * Return instance of RemoteFileServer
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Initialize the RemoteFileServer instance
     * @return static
     */
    public function init(): static
    {
        $allowedRemoteFileFolders = $this->cfg()->get('core->filesystem->remote->folderAllow')->toArray();
        foreach ($allowedRemoteFileFolders as $allowedRemoteFileFolder) {
            $this->addAllowedPath($allowedRemoteFileFolder);
        }
        $deniedRemoteFileFolders = $this->cfg()->get('core->filesystem->remote->regexDeny')->toArray();
        foreach ($deniedRemoteFileFolders as $deniedRemoteFileFolder) {
            $this->addDeniedRegEx($deniedRemoteFileFolder);
        }
        return $this;
    }

    /**
     * Read data from remote client for file path()->sysRoot() . $filePath
     *
     * @param string $filePath
     * @return SplFileObject File
     * @throws FileException
     */
    public function readData(string $filePath): SplFileObject
    {
        log_debug('(\'' . $filePath . '\')');
        $fileRootPath = path()->sysRoot() . $filePath;
        if (!$this->validatePath($filePath)) {
            $this->handleInvalidPath($filePath);
        }
        if (!file_exists($fileRootPath)) {
            $this->handleFileLost($filePath);
        }

        $file = new SplFileObject($fileRootPath, 'rb');
        return $file;
    }

    /**
     * Put file from remote client to file path()->sysRoot() . $filePath
     *
     * @param string $sourceFileTmpName
     * @param string $filePath
     * @return bool whether file was written successfully
     * @throws FileException
     */
    public function put(string $sourceFileTmpName, string $filePath): bool
    {
        log_debug('( \'' . $filePath . '\')');
        log_debug($sourceFileTmpName, 'upload.txt');
        $fileRootPath = path()->sysRoot() . $filePath;
        if (!$this->validatePath($filePath)) {
            $this->handleInvalidPath($filePath);
        }
        if (empty($sourceFileTmpName)) {
            $this->handleSourceFileLost($filePath);
        }

        LocalFileManager::new()->createDirPath($fileRootPath);

        // Write to local file
        if (file_exists($fileRootPath)) {
            unlink($fileRootPath);
        }
        $isSuccess = move_uploaded_file($sourceFileTmpName, $fileRootPath);
        if (!$isSuccess) {
            $message = sprintf(FileException::FILE_COPY, 'REMOTE', $filePath);
            log_error($message);
            throw new FileException($message, FileException::FILE_COPY_NO);
        }
        $this->createLocalFileManager()->applyDefaultPermissions($fileRootPath);
        return true;
    }

    /**
     * Append a data to the file end
     *
     * @param string $text
     * @param string $filePath
     * @return void
     * @throws FileException
     */
    public function append(string $text, string $filePath): void
    {
        $text = base64_decode($text);
        $logMessage = (strlen($text) > 15) ? substr($text, 0, 15) . '...' : $text;
        log_debug('(\'' . $logMessage . '\', \'' . $filePath . '\')');
        if (!$this->validatePath($filePath)) {
            $this->handleInvalidPath($filePath);
        }

        LocalFileManager::new()->append($text, $filePath);
    }

    /**
     * Remove file path()->sysRoot() . $filePath
     * @param string $filePath
     * @return bool whether data was deleted successfully
     * @throws FileException
     */
    public function delete(string $filePath): bool
    {
        log_debug('(\'' . $filePath . '\')');
        if (!$this->validatePath($filePath)) {
            $this->handleInvalidPath($filePath);
        }

        return LocalFileManager::new()->delete($filePath);
    }

    /**
     * Move file at remote server to another place.
     * Both paths must be in allowed folders
     *
     * @param string $sourceFilePath
     * @param string $filePath
     * @return bool whether file was moved successfully
     * @throws FileException
     */
    public function move(string $sourceFilePath, string $filePath): bool
    {
        log_debug('(\'' . $sourceFilePath . '\', \'' . $filePath . '\')');
        // $sourceFileRootPath = path()->sysRoot() . $sourceFilePath;
        if (!$this->validatePath($sourceFilePath)) {
            $this->handleInvalidPath($sourceFilePath);
        }
        if (!$this->validatePath($filePath)) {
            $this->handleInvalidPath($filePath);
        }

        return LocalFileManager::new()->move($sourceFilePath, $filePath);
    }

    /**
     * Copy file at remote server to another place.
     * Both paths must be in allowed folders
     *
     * @param string $sourceFilePath
     * @param string $filePath
     * @return bool whether file was copied successfully
     * @throws FileException
     */
    public function copy(string $sourceFilePath, string $filePath): bool
    {
        log_debug('(\'' . $sourceFilePath . '\', \'' . $filePath . '\')');
        // $sourceFileRootPath = path()->sysRoot() . $sourceFilePath;
        if (!$this->validatePath($sourceFilePath)) {
            $this->handleInvalidPath($sourceFilePath);
        }
        if (!$this->validatePath($filePath)) {
            $this->handleInvalidPath($filePath);
        }

        return LocalFileManager::new()->copy($sourceFilePath, $filePath);
    }

    /**
     * Get file mtime
     *
     * @param string $filePath
     * @return int file mtime
     * @throws FileException
     */
    public function getMTime(string $filePath): int
    {
        log_debug('(\'' . $filePath . '\')');
        if (!$this->validatePath($filePath)) {
            $this->handleInvalidPath($filePath);
        }

        return LocalFileManager::new()->getMTime($filePath);
    }

    /**
     * Check file exist
     *
     * @param string $filePath
     * @return bool
     * @throws FileException
     */
    public function exist(string $filePath): bool
    {
        log_debug('(\'' . $filePath . '\')');
        if (!$this->validatePath($filePath)) {
            $this->handleInvalidPath($filePath);
        }

        return LocalFileManager::new()->exist($filePath);
    }

    /**
     * Get file size
     *
     * @param string $filePath
     * @return int file size
     * @throws FileException
     */
    public function getSize(string $filePath): int
    {
        log_debug('(\'' . $filePath . '\')');
        if (!$this->validatePath($filePath)) {
            $this->handleInvalidPath($filePath);
        }

        return LocalFileManager::new()->getSize($filePath);
    }

    /**
     * Get image info
     *
     * @param string $filePath
     * @return string
     * @throws FileException
     */
    public function getImageInfo(string $filePath): string
    {
        log_debug('(\'' . $filePath . '\')');
        if (!$this->validatePath($filePath)) {
            $this->handleInvalidPath($filePath);
        }

        $imageInfo = LocalFileManager::new()->getImageInfo($filePath);
        return json_encode($imageInfo);
    }

    /**
     * Add an allowed path
     *
     * @param string $path
     */
    public function addAllowedPath(string $path): void
    {
        $this->allowedPaths[] = $path;
    }

    /**
     * Remove an entry from the allowed path list
     *
     * @param string $path
     * @return bool whether entry was removed successfully
     */
    public function removeAllowedPath(string $path): bool
    {
        $key = @array_search($path, $this->allowedPaths, true);
        if ($key !== false) {
            $this->allowedPaths[$key] = null;
            unset($this->allowedPaths[$key]);
            return true;
        }
        return false;
    }

    /**
     * Add a denied path
     *
     * @param string $regExp
     */
    public function addDeniedRegEx(string $regExp): void
    {
        $this->deniedRegEx[] = $regExp;
    }

    /**
     * Remove an entry from the denied path list
     *
     * @param string $regExp
     * @return bool whether entry was removed successfully
     */
    public function removeDeniedRegEx(string $regExp): bool
    {
        $key = @array_search($regExp, $this->deniedRegEx, true);
        if ($key !== false) {
            $this->deniedRegEx[$key] = null;
            unset($this->deniedRegEx[$key]);
            return true;
        }
        return false;
    }

    /**
     * Validate, whether a path is in the list of allowed paths and NOT in the list of denied paths
     *
     * @param string $filePath
     * @return bool
     */
    protected function validatePath(string $filePath): bool
    {
        $filePath = str_replace('\\', '/', $filePath);
        $filePath = trim($filePath);
        if (!is_array($this->allowedPaths)) {
            $message = FileException::ALLOWED_FOLDER_NOT_INIT;
            log_error($message);
            throw new RuntimeException($message, FileException::ALLOWED_FOLDER_NOT_INIT_NO);
        }
        if (!is_array($this->deniedRegEx)) {
            $message = FileException::DENIED_REGEX_NOT_INIT;
            log_error($message);
            throw new RuntimeException($message, FileException::DENIED_REGEX_NOT_INIT_NO);
        }

        $isValid = false;

        foreach ($this->allowedPaths as $allowedPath) {
            if (str_starts_with($allowedPath, '/')) {
                if ($allowedPath === $filePath) {
                    log_debug(composeLogData(['Exact match' => $filePath]));
                    // full path exact match is an exception, validation complete
                    return true;
                }
                if (str_starts_with($filePath, $allowedPath)) {
                    log_debug(composeLogData(['Path root match (' . $allowedPath . ')' => $filePath]));
                    $isValid = true;
                    break;
                }
            } elseif (str_contains($filePath, $allowedPath)) {
                log_debug(composeLogData(['Substring match (' . $allowedPath . ')' => $filePath]));
                $isValid = true;
                break;
            }
        }

        // No matching allowed path
        if (!$isValid) {
            return false;
        }

        foreach ($this->deniedRegEx as $deniedRegEx) {
            if (preg_match($deniedRegEx, $filePath)) {
                log_debug(composeLogData(['Denied RegExp match (' . $deniedRegEx . ')' => $filePath]));
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $filePath
     * @throws FileException
     */
    protected function handleInvalidPath(string $filePath): void
    {
        $message = sprintf(FileException::INVALID_PATH, $filePath);
        log_warning($message);
        throw new FileException($message, FileException::INVALID_PATH_NO);
    }

    /**
     * @param string $filePath
     * @throws FileException
     */
    protected function handleSourceFileLost(string $filePath): void
    {
        $message = sprintf(FileException::SOURCE_FILE_LOST, 'REMOTE', $filePath);
        log_warning($message);
        throw new FileException($message, FileException::SOURCE_FILE_LOST_NO);
    }

    /**
     * @param string $filePath
     * @throws FileException
     */
    protected function handleFileLost(string $filePath): void
    {
        $message = sprintf(FileException::FILE_NOT_FOUND, $filePath);
        log_warning($message);
        throw new FileException($message, FileException::FILE_NOT_FOUND_NO);
    }

    /**
     * Return value of allowedPaths property
     * @return array
     */
    public function getAllowedPaths(): array
    {
        return $this->allowedPaths;
    }

    /**
     * Set allowedPaths property value and normalize to string array value
     * @param array $allowedPaths
     * @return static
     */
    public function setAllowedPaths(array $allowedPaths): static
    {
        $this->allowedPaths = ArrayCast::makeStringArray($allowedPaths);
        return $this;
    }

    /**
     * Return value of deniedRegEx property
     * @return array
     */
    public function getDeniedRegEx(): array
    {
        return $this->deniedRegEx;
    }

    /**
     * Set deniedRegEx property value and normalize to string array value
     * @param array $deniedRegEx
     * @return static
     */
    public function setDeniedRegEx(array $deniedRegEx): static
    {
        $this->deniedRegEx = ArrayCast::makeStringArray($deniedRegEx);
        return $this;
    }
}
