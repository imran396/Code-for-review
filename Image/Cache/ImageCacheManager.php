<?php
/**
 * Help methods for Remote Image Caching
 *
 * SAM-4274: Remote image fetching improvements using ETag and expires and cache-control header to determine changes rather than last modified
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 22, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Image\Cache;

use Exception;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\LocalFileManager;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Cache external images
 */
class ImageCacheManager extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use FilesystemCacheManagerAwareTrait;

    /**
     * @var string
     */
    protected string $url = '';

    /**
     * @var bool
     */
    protected bool $isValid = false;

    /**
     * @var string|null
     */
    protected ?string $cachedImageBlob = null;

    /**
     * @var string[]
     */
    protected array $cachedHeaders = [];

    /**
     * @var int|null
     */
    protected ?int $errorCode = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $url
     * @return static
     */
    public function setUrl(string $url): static
    {
        $this->url = trim($url);
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param int $errorCode
     * @return static
     */
    protected function setErrorCode(int $errorCode): static
    {
        $this->errorCode = $errorCode;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getErrorCode(): ?int
    {
        return $this->errorCode;
    }

    /**
     * @return string|null
     */
    public function load(): ?string
    {
        if (!$this->isValidUrl()) {
            return null;
        }

        $this->loadCachedHeaders();

        $this->validateCachedHeaders();

        $this->cachedImageBlob = $this->loadFromCache();

        if (
            !$this->cachedImageBlob
            || $this->isValid
        ) {
            $this->cachedImageBlob = $this->loadRemote();
        }
        return $this->cachedImageBlob;
    }

    /**
     * @return bool
     */
    protected function isValidUrl(): bool
    {
        if (
            $this->url === ''
            || !filter_var($this->url, FILTER_VALIDATE_URL)
        ) {
            $this->setErrorCode(Constants\Image::INVALID_IMAGE_URL);
            log_warning('Invalid Url' . composeSuffix(['url' => $this->getUrl()]));
            return false;
        }
        return true;
    }

    /**
     * @return void
     */
    protected function loadCachedHeaders(): void
    {
        $this->cachedHeaders = $this->getFilesystemCacheManager()
            ->setReader(LocalFileManager::new())
            ->setExtension('txt')
            ->setNamespace('remote-image-header')
            ->get($this->url, []);
    }

    /**
     * @return  void
     */
    protected function validateCachedHeaders(): void
    {
        if (
            !$this->cachedHeaders
            || !$this->validateExpiresHeader()
            || !$this->validateCacheControlHeader()
        ) {
            $this->isValid = true;
            if (!count($this->cachedHeaders)) {
                log_debug(
                    'No cached headers, trying to fetch remote image'
                    . composeSuffix(['url' => $this->getUrl()])
                );
            }
        }
    }

    /**
     * @return bool
     */
    protected function validateExpiresHeader(): bool
    {
        if (!isset($this->cachedHeaders['Expires'])) {
            log_info('No Expires header' . composeSuffix(['url' => $this->getUrl()]));
            return true;
        }
        if (strtotime($this->cachedHeaders['Expires']) < time()) {
            log_info('Expired in past' . composeSuffix(['url' => $this->getUrl()]));
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function validateCacheControlHeader(): bool
    {
        if (!isset($this->cachedHeaders['Cache-Control'])) {
            log_info('No Cache-Control header' . composeSuffix(['url' => $this->getUrl()]));
            log_debug($this->cachedHeaders);
            return true;
        }

        $cacheControlData = explode(',', $this->cachedHeaders['Cache-Control']);
        foreach ($cacheControlData as $option) {
            $option = trim($option);
            if (
                str_contains($option, 'no-cache')
                || str_contains($option, 'no-store')
                || str_contains($option, 'must-revalidate')
            ) {
                log_info(
                    'Headers no-cache, no-store or must-revalidate set'
                    . composeSuffix(['url' => $this->getUrl()])
                );
                log_debug($this->cachedHeaders);
                return false;
            }
            if (str_contains($option, 'max-age')) {
                $maxAgeData = explode('=', $option);
                if (
                    isset($maxAgeData[1])
                    && isset($this->cachedHeaders['Date'])
                ) {
                    $expiredTime = (int)$maxAgeData[1] * 60 + strtotime($this->cachedHeaders['Date']);
                    if (time() > $expiredTime) {
                        log_debug(composeLogData(['Header max-age expired for' => $this->getUrl()]));
                        log_debug($this->cachedHeaders);
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * @return string|null
     */
    protected function loadFromCache(): ?string
    {
        log_debug('Loading image from cache' . composeSuffix(['url' => $this->getUrl()]));
        $imageBlob = $this->getFilesystemCacheManager()
            ->setReader(LocalFileManager::new())
            ->setExtension('txt')
            ->setNamespace('remote-image-blob')
            ->get($this->url);
        return $imageBlob;
    }

    /**
     * @return string|null
     */
    protected function loadRemote(): ?string
    {
        log_debug(composeLogData(['Trying to fetch remote resource' => $this->getUrl()]));
        $imageBlob = null;
        $httpOptions = [
            'method' => 'GET',
            'timeout' => $this->cfg()->get('core->image->remoteImageTimeout'),
            'ignore_errors' => 1,
        ];
        $httpOptions['header'][] = 'Connection: close';

        if (
            $this->cachedImageBlob
            && isset($this->cachedHeaders['ETag'])
        ) {
            $httpOptions['header'][] = 'If-None-Match: ' . $this->cachedHeaders['ETag'];
        } else {
            $httpOptions['header'][] = 'If-None-Match: 0';
        }

        if (
            $this->cachedImageBlob
            && isset($this->cachedHeaders['Last-Modified'])
        ) {
            $httpOptions['header'][] = 'If-Modified-Since: ' . $this->cachedHeaders['Last-Modified'];
        }

        try {
            $contextOptions = [
                'https' => $httpOptions
            ];

            if (!$this->cfg()->get('core->image->sslCertPeerCheck')) {
                $contextOptions['ssl'] = [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ];
            }

            $context = stream_context_create($contextOptions);
            $stream = @fopen($this->url, 'rb', false, $context);
            if (!is_resource($stream)) {
                $this->setErrorCode(Constants\Image::FAILED_TO_FETCH);
                log_warning('Cannot open resource' . composeSuffix(['url' => $this->url]));
                return null;
            }

            $meta = stream_get_meta_data($stream);
            if (!isset($meta['wrapper_data'])) {
                $this->setErrorCode(Constants\Image::FAILED_TO_FETCH);
                log_warning('Stream data is empty' . composeSuffix(['url' => $this->getUrl()]));
                return null;
            }

            $headers = $this->parseHeaders($meta['wrapper_data']);
            $headersToBeCached = $headers['initialResponse'];
            /** @var array $headersToBeValidated */
            $headersToBeValidated = $headers['finalResponse'];
            if ($this->validateHttpResponse($headersToBeValidated)) {
                $responseCode = (int)$headersToBeValidated['httpResponseCode'];
                if ($responseCode === 304) {
                    log_info('Remote resource unmodified 304' . composeSuffix(['url' => $this->getUrl()]));
                    //not modified, then load from cache and return image
                    $imageBlob = $this->loadFromCache();
                } else {
                    //if Http code 200, then save to cache and return image
                    log_info('Download remote resource' . composeSuffix(['url' => $this->getUrl()]));
                    $imageBlob = @stream_get_contents($stream);
                    if (!$imageBlob) {
                        log_warning('Empty remote resource' . composeSuffix(['url' => $this->getUrl()]));
                        return null;
                    }
                    $this->saveImageBlobToCache($imageBlob);
                }
                if ($imageBlob) {
                    $this->saveHeadersToCache($headersToBeCached);
                }
            }
            return $imageBlob;
        } catch (Exception) {
            $this->setErrorCode(Constants\Image::FAILED_TO_FETCH);
            log_error('Unknown server error' . composeSuffix(['url' => $this->getUrl()]));
            return null;
        }
    }

    /**
     * @param string[] $wrapperData
     * @return string[][]
     */
    protected function parseHeaders(array $wrapperData): array
    {
        $headers = [];
        $i = 0;
        foreach ($wrapperData as $line) {
            if (str_starts_with($line, 'HTTP')) {
                $i++;
                $code = null;
                preg_match('|HTTP/\d\.\d\s+(\d+)\s+.*|', $line, $match);
                if (isset($match[1])) {
                    $code = (int)$match[1];
                }
                $headers[$i]['httpResponseCode'] = $code;
                continue;
            }

            if (!preg_match("/^([^:\s]*)(\s*:\s*)?(.*)$/", $line, $matches)) {
                log_warning("Couldn't parse header" . composeSuffix(['header' => $line, 'url' => $this->getUrl()]));
                continue;
            }
            $key = $matches[1];
            $value = $matches[3];
            $headers[$i][$key] = $value;
        }

        if (count($headers)) {
            $firstResponseHeaders = current($headers);
            $lastResponseHeaders = end($headers);
            $headers = [
                'initialResponse' => $firstResponseHeaders,
                'finalResponse' => $lastResponseHeaders,
            ];
        }
        return $headers;
    }

    /**
     * @param array $headers
     */
    protected function saveHeadersToCache(array $headers): void
    {
        $this->getFilesystemCacheManager()
            ->setWriter(LocalFileManager::new())
            ->setExtension('txt')
            ->setNamespace('remote-image-header')
            ->set($this->url, $headers);
    }

    /**
     * @param string $imageBlob
     */
    protected function saveImageBlobToCache(string $imageBlob): void
    {
        $this->getFilesystemCacheManager()
            ->setWriter(LocalFileManager::new())
            ->setExtension('txt')
            ->setNamespace('remote-image-blob')
            ->set($this->url, $imageBlob);
    }

    /**
     * @param array $responseHeaders
     * @return bool
     */
    protected function validateHttpResponse(array $responseHeaders): bool
    {
        $responseCode = isset($responseHeaders['httpResponseCode']) ? (int)$responseHeaders['httpResponseCode'] : null;
        $contentType = $responseHeaders['Content-Type'] ?? null;
        $contentLength = $responseHeaders['Content-Length'] ?? null;

        if (
            !$this->validateResponseCode($responseCode)
            || (
                $contentType
                && !$this->validateContentType($contentType)
            ) || (
                $contentLength
                && !$this->validateContentLength($contentLength)
            )
        ) {
            log_debug($responseHeaders);
            return false;
        }
        return true;
    }

    /**
     * @param int|null $responseCode
     * @return bool
     */
    protected function validateResponseCode(?int $responseCode): bool
    {
        if (
            $responseCode === 304
            || $responseCode === 200
        ) {
            return true;
        }
        log_warning('Invalid HTTP response code' . composeSuffix(['url' => $this->getUrl()]));
        $this->setErrorCode(Constants\Image::INVALID_HTTP_RESPONSE_CODE);
        return false;
    }

    /**
     * @param int|null $contentLength
     * @return bool
     */
    protected function validateContentLength(?int $contentLength): bool
    {
        $dataSize = (int)round((int)($contentLength / 1024));
        if ($dataSize <= $this->cfg()->get('core->image->uploadMaxSize')) {
            return true;
        }
        log_warning(
            'Invalid image size.'
            . composeSuffix(['Too big' => $contentLength, 'url' => $this->getUrl()])
        );
        $this->setErrorCode(Constants\Image::INVALID_IMAGE_SIZE);
        return false;
    }

    /**
     * @param string|null $contentType
     * @return bool
     */
    protected function validateContentType(?string $contentType): bool
    {
        if (str_contains($contentType, 'image')) {
            return true;
        }
        log_warning(
            'Invalid content-type'
            . composeSuffix(['content-type' => $contentType, 'url' => $this->getUrl()])
        );
        $this->setErrorCode(Constants\Image::INVALID_CONTENT_TYPE);
        return false;
    }
}
