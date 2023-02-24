<?php
/**
 * Implement this interface if you want your class to be used as file operation manager
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

/**
 * Interface FileManagerInterface
 * @package Sam\File
 */
interface FileManagerInterface
{
    /**
     * Determine if remote file operation behaviour is turned on
     * @return bool
     */
    public function isRemote(): bool;

    /**
     * Load file from remote server and save it to local location
     *
     * @param string $filePath
     * @return void
     * @throws FileException
     */
    public function get(string $filePath): void;

    /**
     * Return file content
     *
     * @param string $filePath
     * @return string
     * @throws FileException
     */
    public function read(string $filePath): string;

    /**
     * Get file from absolute path ($sourceFileRootPath, at local filesystem) and put it to destination path ($filePath)
     *
     * @param string $sourceFileRootPath
     * @param string $filePath
     * @return void
     * @throws FileException
     */
    public function put(string $sourceFileRootPath, string $filePath): void;

    /**
     * Write a data to the file
     *
     * @param string $text
     * @param string $filePath
     * @return void
     * @throws FileException
     */
    public function write(string $text, string $filePath): void;

    /**
     * Append a data to the file end
     *
     * @param string $text
     * @param string $filePath
     * @return void
     * @throws FileException
     */
    public function append(string $text, string $filePath): void;

    /**
     * Delete file
     *
     * @param string $filePath
     * @return bool
     * @throws FileException
     */
    public function delete(string $filePath): bool;

    /**
     * Move file
     *
     * @param string $sourceFilePath
     * @param string $filePath
     * @return bool
     * @throws FileException
     */
    public function move(string $sourceFilePath, string $filePath): bool;

    /**
     * Copy file to another location
     *
     * @param string $sourceFilePath
     * @param string $targetFilePath
     * @return bool
     * @throws FileException
     */
    public function copy(string $sourceFilePath, string $targetFilePath): bool;

    /**
     * Get file mtime
     *
     * @param string $filePath
     * @return int File mtime
     * @throws FileException
     */
    public function getMTime(string $filePath): int;

    /**
     * Check file exists
     *
     * @param string $filePath
     * @return bool
     * @throws FileException
     */
    public function exist(string $filePath): bool;

    /**
     * Get file image info
     *
     * @param string $filePath
     * @return array
     * @throws FileException
     */
    public function getImageInfo(string $filePath): array;

    /**
     * Get file size
     *
     * @param string $filePath
     * @return int file size
     * @throws FileException
     */
    public function getSize(string $filePath): int;
}
