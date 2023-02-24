<?php

/**
 * Remote file client to perform file operations at remote master server,
 * which are handled by RemoteFileServer.
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
 *
 */

namespace Sam\File\Manage;

use Psr\Http\Message\StreamInterface;
use RuntimeException;
use Sam\Core\Service\CustomizableClass;
use Sam\File\FolderManagerAwareTrait;
use Sam\Infrastructure\Net\HttpClientCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class RemoteFileClient
 */
class RemoteFileClient extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use FolderManagerAwareTrait;
    use HttpClientCreateTrait;
    use LocalFileManagerCreateTrait;

    protected string $remoteServerAddress;
    protected string $remoteServerScheme = 'http';
    protected string $masterServerExceptionPrefix = 'Master server exception: ';
    protected string $localServerExceptionPrefix = 'Local server exception: ';

    /**
     * Return instance of RemoteFileClient
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Initialize the RemoteFileClient instance
     * @return static
     */
    public function init(): static
    {
        $this->setRemoteServerAddress((string)$this->cfg()->get('core->filesystem->remote->masterHost'));
        return $this;
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
        log_debug('(\'' . $filePath . '\')');
        $responseStream = $this->readData($filePath);
        $fileRootPath = path()->sysRoot() . $filePath;
        $this->createDirPath($fileRootPath);
        $writeHandle = fopen($fileRootPath, 'wb');
        $isSuccess = stream_copy_to_stream($responseStream->detach(), $writeHandle);
        fclose($writeHandle);
        // Write to local file
        if (!$isSuccess) {
            $message = $this->localServerExceptionPrefix . sprintf(FileException::FILE_WRITE, $fileRootPath);
            log_warning($message);
            throw new FileException($message, FileException::FILE_WRITE_NO);
        }
        $this->createLocalFileManager()->applyDefaultPermissions($fileRootPath);
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
        log_debug('(\'' . $filePath . '\')');
        $responseStream = $this->readData($filePath);
        return $responseStream->getContents();
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
        log_debug('(\'' . $sourceFileRootPath . '\', \'' . $filePath . '\')');
        if (!file_exists($sourceFileRootPath)) {
            $message = $this->localServerExceptionPrefix . sprintf(FileException::FILE_NOT_FOUND, $sourceFileRootPath);
            log_error($message);
            throw new FileException($message, FileException::FILE_NOT_FOUND_NO);
        }

        $postUrl = $this->getUrl('put');
        $response = $this->createHttpClient()->postFiles($postUrl, ['Source' => $sourceFileRootPath], ['Path' => $filePath]);
        $response = (array)json_decode($response->getBody()->getContents(), true);
        if (isset($response['error'])) {
            $message = $this->masterServerExceptionPrefix . $response['error'];
            log_warning($message);
            throw new FileException($message, $response['err_no']);
        }

        $message = 'Uploaded file "' . $filePath . '" successfully sent to master server';
        log_warning($message);
    }

    /**
     * Write a data to the file
     * Data will be saved in local filesystem too, so file could be passed by "put" api call
     *
     * @param string $text
     * @param string $filePath
     * @return void
     * @throws FileException
     */
    public function write(string $text, string $filePath): void
    {
        $logMessage = (strlen($text) > 15) ? substr($text, 0, 15) . '...' : $text;
        log_debug('(\'' . $logMessage . '\', \'' . $filePath . '\')');
        LocalFileManager::new()->write($text, $filePath);
        $sourceFileRootPath = path()->sysRoot() . $filePath;
        $this->put($sourceFileRootPath, $filePath);
    }

    /**
     * Append a data to the remote file
     *
     * @param string $text
     * @param string $filePath
     * @throws FileException
     */
    public function append(string $text, string $filePath): void
    {
        $logMessage = (strlen($text) > 15) ? substr($text, 0, 15) . '...' : $text;
        log_debug('(\'' . $logMessage . '\', \'' . $filePath . '\')');
        $postUrl = $this->getUrl('append');
        $postData = ['Data' => base64_encode($text), 'Path' => $filePath];
        $responseJson = $this->makePostRequest($postUrl, $postData);
        $response = (array)json_decode($responseJson);
        if (isset($response['error'])) {
            $message = $this->masterServerExceptionPrefix . $response['error'];
            log_warning($message);
            throw new FileException($message, $response['err_no']);
        }

        $message = 'Data was successfully appended to file "' . $filePath . '" at master server';
        log_warning($message);
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
        log_debug('(\'' . $filePath . '\')');
        $postUrl = $this->getUrl('delete');
        $postData = 'Path=' . $filePath;
        $responseJson = $this->makePostRequest($postUrl, $postData);
        $response = (array)json_decode($responseJson);
        if (isset($response['error'])) {
            $message = $this->masterServerExceptionPrefix . $response['error'];
            log_warning($message);
            throw new FileException($message, $response['err_no']);
        }

        if (isset($response['result'])) {
            $isSuccess = (bool)base64_decode($response['result']);
        } else {
            $isSuccess = false;
            log_error('"result" key is missing in response of remote fs delete operation' . composeSuffix(['file' => $filePath]));
        }
        $message = 'File delete (' . $filePath . ') ' . ($isSuccess ? ' success' : ' failed');
        log_debug($message);
        return $isSuccess;
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
        log_debug('(\'' . $sourceFilePath . '\', \'' . $filePath . '\')');
        $postUrl = $this->getUrl('move');
        $postData = 'Source=' . $sourceFilePath . '&Path=' . $filePath;
        $response = $this->makePostRequest($postUrl, $postData);
        return (bool)$response;
    }

    /**
     * Send command to copy file at remote server
     *
     * @param string $sourceFilePath
     * @param string $filePath
     * @return bool
     */
    public function copy(string $sourceFilePath, string $filePath): bool
    {
        log_debug('(\'' . $sourceFilePath . '\', \'' . $filePath . '\')');
        $postUrl = $this->getUrl('copy');
        $postData = 'Source=' . $sourceFilePath . '&Path=' . $filePath;
        $response = $this->makePostRequest($postUrl, $postData);
        return (bool)$response;
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
        // log_debug('(\'' . $filePath . '\')');
        $postUrl = $this->getUrl('getMTime');
        $postData = 'Path=' . $filePath;
        $responseJson = $this->makePostRequest($postUrl, $postData);
        $response = (array)json_decode($responseJson);
        if (isset($response['error'])) {
            $message = $this->masterServerExceptionPrefix . $response['error'];
            log_warning($message);
            throw new FileException($message, $response['err_no']);
        }

        if (isset($response['result'])) {
            $modifyTime = (int)base64_decode($response['result']);
        } else {
            $modifyTime = 0;
            log_error('"result" key is missing in response of remote file modification time request operation' . composeSuffix(['file' => $filePath]));
        }
        $message = 'File MTime "' . $filePath . '" is ' . $modifyTime;
        log_debug($message);
        return $modifyTime;
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
        $postUrl = $this->getUrl('getImageInfo');
        $postData = 'Path=' . $filePath;
        $responseJson = $this->makePostRequest($postUrl, $postData);
        $response = (array)json_decode($responseJson, true);
        if (isset($response['error'])) {
            $message = $this->masterServerExceptionPrefix . $response['error'];
            log_warning($message);
            throw new FileException($message, $response['err_no']);
        }

        if (isset($response['result'])) {
            $imageInfo = json_decode(base64_decode($response['result']), true);
        } else {
            $imageInfo = [];
            log_error('"result" key is missing in response of remote image-file info read operation' . composeSuffix(['file' => $filePath]));
        }

        log_debug(static function () use ($imageInfo, $filePath): string {
            if ($imageInfo) {
                $logImageInfo = '';
                foreach ($imageInfo as $key => $value) {
                    $logImageInfo .= $key . ': ' . $value . ', ';
                }
                $logImageInfo = rtrim($logImageInfo, ', ');
            } else {
                $logImageInfo = '';
            }
            $message = 'File image info "' . $filePath . '" is ' . $logImageInfo;
            return $message;
        });

        return $imageInfo;
    }

    /**
     * Check remote file existence
     *
     * @param string $filePath
     * @return bool
     */
    public function exist(string $filePath): bool
    {
        $postUrl = $this->getUrl('exist');
        $postData = 'Path=' . $filePath;
        $responseJson = $this->makePostRequest($postUrl, $postData);
        $response = (array)json_decode($responseJson);
        if (isset($response['error'])) {
            $message = $this->masterServerExceptionPrefix . $response['error'];
            log_warning($message);
            return false;
        }

        if (isset($response['result'])) {
            $isFound = (bool)base64_decode($response['result']);
        } else {
            $isFound = false;
            log_error('"result" key is missing in response of remote file existing check operation' . composeSuffix(['file' => $filePath]));
        }
        $message = 'File "' . $filePath . '" ' . ($isFound ? 'exists' : 'does not exist');
        log_debug($message);
        return $isFound;
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
        $postUrl = $this->getUrl('getSize');
        $postData = 'Path=' . $filePath;
        $responseJson = $this->makePostRequest($postUrl, $postData);
        $response = (array)json_decode($responseJson);
        if (isset($response['error'])) {
            $message = $this->masterServerExceptionPrefix . $response['error'];
            log_warning($message);
            throw new FileException($message, $response['err_no']);
        }

        if (isset($response['result'])) {
            $size = (int)base64_decode($response['result']);
        } else {
            $size = 0;
            log_error('"result" key is missing in response of remote file size read operation' . composeSuffix(['file' => $filePath]));
        }
        $message = 'File Size "' . $filePath . '" is ' . $size;
        log_debug($message);
        return $size;
    }

    /**
     * Return url to master server api
     *
     * @param string $action
     * @return string
     */
    protected function getUrl(string $action): string
    {
        return $this->remoteServerScheme . '://' . $this->getRemoteServerAddress() . '/api/file/' . $action;
    }

    /**
     * Extract directories from file path and create them if don't exist
     *
     * @param string $fileRootPath
     * @throws FileException
     */
    protected function createDirPath(string $fileRootPath): void
    {
        $fileRootPath = str_replace('\\', '/', $fileRootPath);
        $dirRootPath = substr($fileRootPath, 0, strrpos($fileRootPath, '/'));

        try {
            $permissions = $this->getFolderManager()->defaultPermissions();
            (new Filesystem())->mkdir($dirRootPath, $permissions);
        } catch (IOException) {
            $message = $this->localServerExceptionPrefix . sprintf(FileException::DIR_CREATE, $dirRootPath);
            log_warning($message);
            throw new FileException($message);
        }
    }

    /**
     * Get remote file data
     *
     * @param string $filePath
     * @return StreamInterface
     * @throws FileException
     */
    protected function readData(string $filePath): StreamInterface
    {
        $postUrl = $this->getUrl('get');
        $response = $this->createHttpClient()->post($postUrl, ['Path' => $filePath]);

        if ($response->getStatusCode() === 200) {
            return $response->getBody();
        }
        $data = (array)json_decode($response->getBody()->getContents(), true);
        if (isset($data['error'])) {
            $message = $this->masterServerExceptionPrefix . $data['error'];
            log_warning($message);
            throw new FileException($message, $data['err_no']);
        }
        throw new RuntimeException($this->masterServerExceptionPrefix . $response->getReasonPhrase());
    }

    /**
     * Return value of remoteServerAddress property
     * @return string
     */
    public function getRemoteServerAddress(): string
    {
        return $this->remoteServerAddress;
    }

    /**
     * Set remoteServerAddress property value and normalize to string value
     * @param string $remoteServerAddress
     * @return static
     */
    public function setRemoteServerAddress(string $remoteServerAddress): static
    {
        $this->remoteServerAddress = trim($remoteServerAddress);
        return $this;
    }

    /**
     * @param string $url
     * @param $postData
     * @return string
     */
    protected function makePostRequest(string $url, $postData): string
    {
        $response = $this->createHttpClient()
            ->post($url, $postData)
            ->getBody()
            ->getContents();
        return $response;
    }
}
