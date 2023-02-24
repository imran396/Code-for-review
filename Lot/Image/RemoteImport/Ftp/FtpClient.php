<?php
/**
 * SAM-10383: Refactor remote image import for v3-7
 * SAM-4328: Remote Image Import Manager
 *
 * @author        Igor Mironyak
 * @version       SVN: $Id: $
 * @since         Jun. 11, 2021
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Lot\Image\RemoteImport\Ftp;

use FTP\Connection;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\LocalFileManager;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Image\RemoteImport\Ftp\FtpClientResult as Result;

/**
 * Class FtpClient
 * @package Sam\Lot\Image\RemoteImport
 */
class FtpClient extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    public Connection|false $connection = false;
    private string $ftpErrorMessage = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $host
     * @param int $port
     * @param int $timeout
     * @return Result
     */
    public function connect(string $host, int $port = 21, int $timeout = 90): Result
    {
        $result = Result::new()->construct();
        if ($this->isConnected()) {
            $this->disconnect(); // close previous connection
        }

        $this->connection = $this->wrap(fn() => ftp_ssl_connect($host, $port, $timeout));

        if (
            $this->connection === false
            && $this->cfg()->get('core->ftp->allowUnsecureFtp')
        ) {
            log_warning('Failed to connect via FTPS, trying FTP for' . composeSuffix(['host' => $host, 'port' => $port]));
            $this->connection = $this->wrap(fn() => ftp_connect($host, $port, $timeout));
        }

        if ($this->connection) {
            return $result;
        }

        return $result->addError(Result::ERR_CANNOT_CONNECT, $this->ftpErrorMessage);
    }

    /**
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->connection instanceof Connection;
    }

    /**
     * @param string $username
     * @param string $password
     * @return Result
     */
    public function login(string $username, string $password): Result
    {
        $result = Result::new()->construct();
        $success = $this->wrap(fn() => ftp_login($this->connection, $username, $password));
        if ($success) {
            return $result;
        }

        return $result->addError(Result::ERR_CANNOT_LOGIN, $this->ftpErrorMessage);
    }

    /**
     * @param string $directory
     * @return Result
     */
    public function changeDirectory(string $directory = '.'): Result
    {
        $result = Result::new()->construct();
        $success = $this->wrap(fn() => ftp_chdir($this->connection, $directory));
        if ($success) {
            return $result;
        }

        return $result->addError(Result::ERR_CANNOT_CHANGE_DIRECTORY, $this->ftpErrorMessage);
    }

    /**
     * @return Result
     */
    public function turnOnPassiveMode(): Result
    {
        $result = Result::new()->construct();
        $success = $this->wrap(fn() => ftp_pasv($this->connection, true));
        if ($success) {
            return $result;
        }

        return $result->addError(Result::ERR_CANNOT_TURN_ON_PASSIVE_MODE, $this->ftpErrorMessage);
    }

    /**
     * @param string $directory
     * @return Result
     */
    public function listFiles(string $directory = '.'): Result
    {
        $result = Result::new()->construct();
        $fileNames = $this->wrap(fn() => ftp_nlist($this->connection, $directory));
        if ($this->ftpErrorMessage) {
            return $result->addError(Result::ERR_CANNOT_READ_DIRECTORY, $this->ftpErrorMessage);
        }

        $result->fileNames = $fileNames; // May be empty array, when no files are found
        return $result;
    }

    /**
     * @param string $localFile
     * @param string $remoteFile
     * @param int $mode
     * @param int $resumePos
     * @return Result
     */
    public function get(
        string $localFile,
        string $remoteFile,
        int $mode = FTP_ASCII,
        int $resumePos = 0
    ): Result {
        $result = Result::new()->construct();
        $success = $this->wrap(fn() => ftp_get($this->connection, $localFile, $remoteFile, $mode, $resumePos));
        if ($success) {
            LocalFileManager::new()->applyDefaultPermissions($localFile);
            return $result;
        }

        $result->remoteFile = $remoteFile;
        return $result->addError(Result::ERR_CANNOT_DOWNLOAD_FILE, $this->ftpErrorMessage);
    }

    public function disconnect(): void
    {
        if ($this->isConnected()) {
            $this->wrap(fn() => ftp_close($this->connection));
        }
    }

    /**
     * Wrap function call in the scope where we intercept error message produced by passed ftp functions.
     * @param callable $fn
     * @return mixed
     */
    protected function wrap(callable $fn): mixed
    {
        $this->ftpErrorMessage = '';
        set_error_handler(function (int $errorNo, string $message, string $fileRootPath, int $line): bool {
            $logData = [
                'error#' => $errorNo,
                'message' => $message,
                'file' => $fileRootPath,
                'line' => $line
            ];
            log_error('Error in FTP operation' . composeSuffix($logData));
            $this->ftpErrorMessage = $message;
            return true;
        });
        $return = $fn();
        restore_error_handler();
        return $return;
    }
}
