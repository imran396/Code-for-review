<?php
/**
 * SAM-6504: Move classes from legacy Api namespace to \Sam\Api namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          tom
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\File;


use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\RemoteFileServer;
use SplFileObject;

/**
 * API wrapper for remote file api
 * (!) ATTENTION: Do NOT rename arguments, POST parameter names are mapped with names of function arguments.
 *
 * Class FileApiWrapper
 * @package Sam\Api\File
 */
class FileApiWrapper extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get a file
     *
     * @param string $Path
     * @return SplFileObject
     * @throws FileException
     */
    public function getAction(string $Path): SplFileObject
    {
        return RemoteFileServer::new()->init()->readData($Path);
    }

    /**
     * Put a file under path()->sysRoot() . /$Path
     * File needs to be within allowed folders
     *
     * @param string $Source
     * @param string $Path
     * @return bool whether writing data was successful
     */
    public function putAction(string $Source, string $Path): bool
    {
        return RemoteFileServer::new()->init()->put($Source, $Path);
    }

    /**
     * Append data to file under path()->sysRoot() . /$Path
     * File needs to be within allowed folders
     *
     * @param string $Data
     * @param string $Path
     * @return void
     */
    public function appendAction(string $Data, string $Path): void
    {
        RemoteFileServer::new()->init()->append($Data, $Path);
    }

    /**
     * Delete path()->sysRoot() . /$Path
     * File needs to be within allowed folders
     *
     * @param string $Path
     * @return bool wheter writing data was successful
     */
    public function deleteAction(string $Path): bool
    {
        return RemoteFileServer::new()->init()->delete($Path);
    }

    /**
     * Move file at remote server
     *
     * @param string $Source
     * @param string $Path
     * @return bool
     */
    public function moveAction(string $Source, string $Path): bool
    {
        return RemoteFileServer::new()->init()->move($Source, $Path);
    }

    /**
     * Copy file at remote server
     *
     * @param string $Source
     * @param string $Path
     * @return bool
     */
    public function copyAction(string $Source, string $Path): bool
    {
        return RemoteFileServer::new()->init()->copy($Source, $Path);
    }

    /**
     * Get fileMTime of path()->sysRoot() . /$Path
     * File needs to be within allowed folders
     *
     * @param string $Path
     * @return int fileMTime
     */
    public function getMTimeAction(string $Path): int
    {
        return RemoteFileServer::new()->init()->getMTime($Path);
    }

    /**
     * Check file existence of path()->sysRoot() . /$Path
     *
     * @param string $Path
     * @return bool
     */
    public function existAction(string $Path): bool
    {
        return RemoteFileServer::new()->init()->exist($Path);
    }

    /**
     * Get image info of path()->sysRoot() . /$Path
     *
     * @param string $Path
     * @return string
     */
    public function getImageInfoAction(string $Path): string
    {
        return RemoteFileServer::new()->init()->getImageInfo($Path);
    }

    /**
     * Get file size of path()->sysRoot() . /$Path
     *
     * @param string $Path
     * @return int size
     */
    public function getSizeAction(string $Path): int
    {
        return RemoteFileServer::new()->init()->getSize($Path);
    }
}
