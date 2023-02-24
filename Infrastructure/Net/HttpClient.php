<?php
/**
 * SAM-7963: Replace Net_Curl with GuzzleHttp
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Net;

use GuzzleHttp;
use Psr\Http\Message\ResponseInterface;
use Sam\Core\Service\CustomizableClass;

/**
 * This class contains methods for making HTTP requests.
 *
 * Class Client
 * @package Sam\Infrastructure\Net
 */
class HttpClient extends CustomizableClass
{
    public const DEFAULT_TIMEOUT = 60;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Make GET request
     *
     * @param string $uri
     * @param array $query
     * @param array $headers
     * @param int $timeout
     * @return ResponseInterface
     */
    public function get(string $uri, array $query = [], array $headers = [], int $timeout = self::DEFAULT_TIMEOUT): ResponseInterface
    {
        $options = [
            'headers' => $headers,
            'timeout' => $timeout,
            'query' => $query,
        ];
        $response = $this->initClient()->request('GET', $uri, $options);
        return $response;
    }

    /**
     * Make POST request
     *
     * @param string $uri
     * @param string|array $postData
     * @param array $headers
     * @param int $timeout
     * @return ResponseInterface
     */
    public function post(string $uri, string|array $postData, array $headers = [], int $timeout = self::DEFAULT_TIMEOUT): ResponseInterface
    {
        $headers['Content-Type'] = $headers['Content-Type'] ?? 'application/x-www-form-urlencoded';
        $postDataOptionKey = is_array($postData) ? 'form_params' : 'body';
        $options = [
            'headers' => $headers,
            'timeout' => $timeout,
            $postDataOptionKey => $postData
        ];

        $response = $this->initClient()->request('POST', $uri, $options);
        return $response;
    }

    /**
     * Send multipart/form-data form post request
     *
     * @param string $uri
     * @param array $files
     * @param array $postData
     * @param array $headers
     * @param int $timeout
     * @return ResponseInterface
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function postFiles(
        string $uri,
        array $files,
        array $postData = [],
        array $headers = [],
        int $timeout = self::DEFAULT_TIMEOUT
    ): ResponseInterface {
        $options = [
            'headers' => $headers,
            'timeout' => $timeout,
            GuzzleHttp\RequestOptions::MULTIPART => []
        ];

        foreach ($files as $key => $filePath) {
            $options[GuzzleHttp\RequestOptions::MULTIPART][] = [
                'name' => $key,
                'contents' => GuzzleHttp\Psr7\Utils::tryFopen($filePath, 'r'),
                'filename' => basename($filePath)
            ];
        }
        foreach ($postData as $key => $value) {
            $options[GuzzleHttp\RequestOptions::MULTIPART][] = [
                'name' => $key,
                'contents' => $value,
            ];
        }

        $response = $this->initClient()->request('POST', $uri, $options);
        return $response;
    }

    /**
     * @return GuzzleHttp\Client
     */
    protected function initClient(): GuzzleHttp\Client
    {
        $client = new GuzzleHttp\Client(['http_errors' => false]);
        return $client;
    }
}
