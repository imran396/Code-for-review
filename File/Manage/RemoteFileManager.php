<?php

namespace Sam\File\Manage;

use Sam\Core\Service\CustomizableClass;

/**
 * File manager, which supposed for remote file operations
 * It uses RemoteFileClient
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
class RemoteFileManager extends CustomizableClass implements FileManagerInterface
{
    /**
     * @var RemoteFileClient|null
     */
    protected ?RemoteFileClient $remoteFileClient = null;

    /**
     * Return instance of RemoteFileManager
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->remoteFileClient = RemoteFileClient::new()->init();
        return $this;
    }

    /**
     * @return bool
     */
    public function isRemote(): bool
    {
        return true;
    }

    /**
     * Load file from remote server and save it to local location
     *
     * @param string $filePath
     * @return void
     * @throws FileException
     */
    public function get(string $filePath): void
    {
        $this->remoteFileClient->get($filePath);
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
        return $this->remoteFileClient->read($filePath);
    }

    /**
     * Put file to remote server
     *
     * @param string $sourceFileRootPath
     * @param string $filePath
     * @return void
     * @throws FileException
     */
    public function put(string $sourceFileRootPath, string $filePath): void
    {
        $this->remoteFileClient->put($sourceFileRootPath, $filePath);
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
        $this->remoteFileClient->write($text, $filePath);
    }

    /**
     * Append data to end of remote file
     *
     * @param string $text
     * @param string $filePath
     * @return void
     * @throws FileException
     */
    public function append(string $text, string $filePath): void
    {
        $this->remoteFileClient->append($text, $filePath);
    }

    /**
     * Delete remote file
     *
     * @param string $filePath
     * @return bool
     * @throws FileException
     */
    public function delete(string $filePath): bool
    {
        return $this->remoteFileClient->delete($filePath);
    }

    /**
     * Send command to move file at remote server
     *
     * @param string $sourceFilePath
     * @param string $filePath
     * @return bool
     */
    public function move(string $sourceFilePath, string $filePath): bool
    {
        return $this->remoteFileClient->move($sourceFilePath, $filePath);
    }

    /**
     * Send command to copy file at remote server
     *
     * @param string $sourceFilePath
     * @param string $targetFilePath
     * @return bool
     */
    public function copy(string $sourceFilePath, string $targetFilePath): bool
    {
        return $this->remoteFileClient->copy($sourceFilePath, $targetFilePath);
    }

    /**
     * Get remote file mtime
     *
     * @param string $filePath
     * @return int File mtime
     * @throws FileException
     */
    public function getMTime(string $filePath): int
    {
        return $this->remoteFileClient->getMTime($filePath);
    }

    /**
     * Check remote file exist
     *
     * @param string $filePath
     * @return bool
     * @throws FileException
     */
    public function exist(string $filePath): bool
    {
        return $this->remoteFileClient->exist($filePath);
    }

    /**
     * Get remote file image info
     *
     * @param string $filePath
     * @return array
     * @throws FileException
     */
    public function getImageInfo(string $filePath): array
    {
        return $this->remoteFileClient->getImageInfo($filePath);
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
        return $this->remoteFileClient->getSize($filePath);
    }
}
