<?php

namespace Sam\File\Manage;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\File\FolderManagerAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Log\Support\SupportLoggerAwareTrait;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * File manager, which supposed for local filesystem operations
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
class LocalFileManager extends CustomizableClass implements FileManagerInterface
{
    use ConfigRepositoryAwareTrait;
    use FolderManagerAwareTrait;
    use SupportLoggerAwareTrait;

    /**
     * @var string
     */
    protected string $rootPath = '';

    /**
     * Return instance of LocalFileManager
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function initInstance(): static
    {
        $this->rootPath = path()->sysRoot();
        return $this;
    }

    /**
     * @return bool
     */
    public function isRemote(): bool
    {
        return false;
    }

    /**
     * Has no sense working on local filesystem
     *
     * @param string $filePath
     * @return void
     */
    public function get(string $filePath): void
    {
    }

    /**
     * Return file content
     *
     * @param string $filePath
     * @return string
     * @throws FileException
     */
    public function read(string $filePath): string
    {
        $this->getSupportLogger()->traceFile($filePath);
        $fileRootPath = $this->rootPath . $filePath;
        $result = @file_get_contents($fileRootPath);
        if ($result === false) {
            $errorMessage = sprintf(FileException::FILE_READ, $filePath);
            $this->getSupportLogger()->warning($errorMessage);
            throw new FileException($errorMessage, FileException::FILE_READ_NO);
        }
        return $result;
    }

    public function output(string $filePath): bool
    {
        $this->getSupportLogger()->traceFile($filePath);
        $fileRootPath = $this->rootPath . $filePath;
        if (!is_readable($fileRootPath)) {
            $errorMessage = sprintf(FileException::FILE_READ, $filePath);
            $this->getSupportLogger()->warning($errorMessage);
            throw new FileException($errorMessage, FileException::FILE_READ_NO);
        }
        $result = readfile($fileRootPath);
        return $result !== false;
    }

    /**
     * Has no sense working on local filesystem, because we are supporting remote and local file structure coincidence
     *
     * @param string $sourceFileRootPath
     * @param string $filePath
     * @return void
     */
    public function put(string $sourceFileRootPath, string $filePath): void
    {
    }

    /**
     * Write a data to the file
     *
     * @param string $text
     * @param string $filePath
     * @return void
     * @throws FileException
     */
    public function write(string $text, string $filePath): void
    {
        $logMessage = TextTransformer::new()->cut($text, 15, '...');
        $this->getSupportLogger()->traceFile($logMessage . composeSuffix(['file' => $filePath]));
        $fileRootPath = $this->rootPath . $filePath;
        if (@file_put_contents($fileRootPath, $text) === false) {
            $errorMessage = sprintf(FileException::FILE_WRITE, $filePath);
            $this->getSupportLogger()->warning($errorMessage);
            throw new FileException($errorMessage, FileException::FILE_WRITE_NO);
        }
        $this->applyDefaultPermissions($fileRootPath);
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
        $logMessage = TextTransformer::new()->cut($text, 15, '...');
        $this->getSupportLogger()->traceFile($logMessage . composeSuffix(['file' => $filePath]));
        $fileRootPath = $this->rootPath . $filePath;
        if (@file_put_contents($fileRootPath, $text, FILE_APPEND) === false) {
            $errorMessage = sprintf(FileException::FILE_WRITE, $filePath);
            $this->getSupportLogger()->warning($errorMessage);
            throw new FileException($errorMessage, FileException::FILE_WRITE_NO);
        }
        $this->applyDefaultPermissions($fileRootPath);
    }

    /**
     * Delete local file
     *
     * @param string $filePath
     * @return bool
     * @throws FileException
     */
    public function delete(string $filePath): bool
    {
        $this->getSupportLogger()->traceFile($filePath);
        $fileRootPath = $this->rootPath . $filePath;
        if (!file_exists($fileRootPath)) {
            $this->handleFileLost($filePath);
        }

        // File deleting
        if (!@unlink($fileRootPath)) {
            $errorMessage = sprintf(FileException::FILE_DELETE, $filePath);
            $this->getSupportLogger()->warning($errorMessage);
            throw new FileException($errorMessage, FileException::FILE_DELETE_NO);
        }

        return true;
    }

    /**
     * Move file to another location
     *
     * @param string $sourceFilePath
     * @param string $filePath
     * @return bool
     * @throws FileException
     */
    public function move(string $sourceFilePath, string $filePath): bool
    {
        $this->getSupportLogger()->traceFile('(\'' . $sourceFilePath . '\', \'' . $filePath . '\')');
        $sourceFileRootPath = $this->rootPath . $sourceFilePath;
        $fileRootPath = $this->rootPath . $filePath;
        if (!file_exists($sourceFileRootPath)) {
            $this->handleSourceFileLost($sourceFileRootPath, $filePath);
        }

        $this->createDirPath($fileRootPath);

        // Write to local file
        if (file_exists($fileRootPath)) {
            $isSuccess = @copy($sourceFileRootPath, $fileRootPath);
        } else {
            $isSuccess = @rename($sourceFileRootPath, $fileRootPath);
        }
        if (!$isSuccess) {
            $message = sprintf(FileException::FILE_COPY, $sourceFileRootPath, $fileRootPath);
            $this->getSupportLogger()->error($message);
            throw new FileException($message, FileException::FILE_COPY_NO);
        }
        $this->applyDefaultPermissions($fileRootPath);
        @unlink($sourceFileRootPath);

        return true;
    }

    /**
     * Copy file to another location
     *
     * @param string $sourceFilePath
     * @param string $targetFilePath
     * @return bool
     * @throws FileException
     */
    public function copy(string $sourceFilePath, string $targetFilePath): bool
    {
        $this->getSupportLogger()->traceFile(composeLogData(['source' => $sourceFilePath, 'target' => $targetFilePath]));
        $sourceFileRootPath = $this->rootPath . $sourceFilePath;
        $fileRootPath = $this->rootPath . $targetFilePath;
        if (!file_exists($sourceFileRootPath)) {
            $this->handleSourceFileLost($sourceFileRootPath, $targetFilePath);
        }

        $this->createDirPath($fileRootPath);

        // Write to local file
        $isSuccess = @copy($sourceFileRootPath, $fileRootPath);
        if (!$isSuccess) {
            $message = sprintf(FileException::FILE_COPY, $sourceFileRootPath, $targetFilePath);
            $this->getSupportLogger()->error($message);
            throw new FileException($message, FileException::FILE_COPY_NO);
        }
        $this->applyDefaultPermissions($fileRootPath);
        return true;
    }

    /**
     * Get file mtime
     *
     * @param string $filePath
     * @return int File mtime
     * @throws FileException
     */
    public function getMTime(string $filePath): int
    {
        $this->getSupportLogger()->traceFile($filePath);
        $fileRootPath = $this->rootPath . $filePath;
        if (!file_exists($fileRootPath)) {
            $this->handleFileLost($filePath);
        }

        return filemtime($fileRootPath);
    }

    /**
     * Check file exist
     *
     * @param string $filePath
     * @return bool
     */
    public function exist(string $filePath): bool
    {
        $fileRootPath = $this->rootPath . $filePath;
        return is_file($fileRootPath) && file_exists($fileRootPath);
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
        $this->getSupportLogger()->traceFile($filePath);
        $fileRootPath = $this->rootPath . $filePath;
        if (!file_exists($fileRootPath)) {
            $this->handleFileLost($filePath);
        }

        return filesize($fileRootPath);
    }

    /**
     * Get local file image info
     *
     * @param string $filePath
     * @return array - return empty array in case of error in getimagesize()
     * @throws FileException
     */
    public function getImageInfo(string $filePath): array
    {
        $this->getSupportLogger()->traceFile($filePath);
        $fileRootPath = $this->rootPath . $filePath;
        if (!file_exists($fileRootPath)) {
            try {
                $this->handleFileLost($filePath);
            } catch (FileException $e) {
                return [];
            }
        }

        $imageInfo = @getimagesize($fileRootPath);
        if (!$imageInfo) {
            return [];
        }

        return $imageInfo;
    }

    /**
     * Extract directories from file path and create them if don't exist
     *
     * @param string $fileRootPath
     * @throws FileException
     */
    public function createDirPath(string $fileRootPath): void
    {
        $strPos = max(strrpos($fileRootPath, '/'), strrpos($fileRootPath, '\\')); // Fix for case like: C:/dir/path\to
        $dirRootPath = substr($fileRootPath, 0, $strPos);
        $this->getSupportLogger()->traceFile($dirRootPath);
        try {
            $permissions = $this->getFolderManager()->defaultPermissions();
            (new Filesystem())->mkdir($dirRootPath, $permissions);
        } catch (IOException) {
            $errorMessage = sprintf(FileException::DIR_CREATE, $dirRootPath);
            $this->getSupportLogger()->warning($errorMessage);
            throw new FileException($errorMessage, FileException::DIR_CREATE_NO);
        }
    }

    /**
     * @param string $filePath
     * @return void
     */
    public function applyDefaultPermissions(string $filePath): void
    {
        $oldMask = umask(0);
        $permissions = $this->defaultPermissions();
        @chmod($filePath, $permissions);
        umask($oldMask);
    }

    /**
     * @param string $rootPath
     * @return static
     */
    public function withRootPath(string $rootPath): static
    {
        $fileManager = clone $this;
        $fileManager->rootPath = $rootPath;
        return $fileManager;
    }

    /**
     * Reads file permissions from configuration option, where they are represented as octal number in string
     * Then transform permissions value to decimal integer, that is accepted by chmod() function.
     * @return int
     */
    public function defaultPermissions(): int
    {
        $octalPermissions = $this->cfg()->get('core->filesystem->permissions->file');
        return (int)octdec($octalPermissions);
    }

    /**
     * @param string $sourceFileRootPath
     * @param string $filePath
     * @throws FileException
     */
    protected function handleSourceFileLost(string $sourceFileRootPath, string $filePath): void
    {
        $errorMessage = sprintf(FileException::SOURCE_FILE_LOST, $sourceFileRootPath, $filePath);
        $this->getSupportLogger()->warning($errorMessage);
        throw new FileException($errorMessage, FileException::SOURCE_FILE_LOST_NO);
    }

    /**
     * @param string $filePath
     * @throws FileException
     */
    protected function handleFileLost(string $filePath): void
    {
        $errorMessage = sprintf(FileException::FILE_NOT_FOUND, $filePath);
        $this->getSupportLogger()->warning($errorMessage);
        throw new FileException($errorMessage, FileException::FILE_NOT_FOUND_NO);
    }
}
