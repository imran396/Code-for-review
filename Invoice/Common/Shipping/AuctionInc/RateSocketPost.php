<?php
/**
 * SAM-4770 : Refactor Auction Inc modules
 * https://bidpath.atlassian.net/browse/SAM-4770
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/3/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Shipping\AuctionInc;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;

/**
 * Class RateSocketPost
 * @package Sam\Invoice\Common\Shipping\AuctionInc
 */
class RateSocketPost extends CustomizableClass
{
    /**
     * @var int|bool|null
     */
    protected int|bool|null $timeout = 20;
    /**
     * @var int|null
     */
    protected ?int $port = 80;  // SSL (https)
    /**
     * @var int
     */
    protected int $errorNum = 0;
    /**
     * @var string
     */
    protected string $url = 'api.auctioninc.com';
    /**
     * @var string
     */
    protected string $uri = '/websvc/shire';
    /**
     * @var string|null
     */
    protected ?string $respContent = null;
    /**
     * @var string|null
     */
    protected ?string $respHeaders = null;
    /**
     * @var string|null
     */
    protected ?string $response = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $queryData
     * @param array $reqHeaders
     * @return static
     */
    public function post(string $queryData, array $reqHeaders): static
    {
        $host = match ($this->port) {
            443 => "ssl://" . $this->url,
            default => $this->url,
        };
        if (!function_exists('curl_init')) {
            die('PHP Curl module must be configured');
        }
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $queryData);
        curl_setopt($ch, CURLOPT_URL, $host . $this->uri);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        //curl_setopt($ch, CURLOPT_VERBOSE, ($this->verbose ? 1 : 0));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $reqHeaders);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        if ($this->timeout !== false) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        }
        $this->response = curl_exec($ch);
        // $info = curl_getinfo($ch);
        $this->errorNum = curl_errno($ch);
        curl_close($ch);
        log_debug($this->response);
        return $this;
    }

    /**
     * @return static
     */
    public function processResponse(): static
    {
        if (strstr($this->response, "\r\n\r\n")) {
            // loop to handle "HTTP/1.1 100 Continue" headers
            $headers = '';
            while (true) {
                [$respHeaders, $respContent] = preg_split("/\r\n\r\n/", $this->response, 2);
                $this->respContent = $respContent;
                // See if we got a 100 Continue header
                if (@preg_match('/^HTTP\/1\.[0-9][ ]{1,}100[ ]{1,}/', $respHeaders)) {
                    // Hold onto the continue header
                    $headers .= $respHeaders;
                    $this->response = $respContent;
                    continue;
                }
                break;
            }
            // Tack the headers back together if we had multiple.
            $this->respHeaders = $headers . $respHeaders;
        } elseif (false !== stripos($this->response, 'content-length: 0')) {
            $this->respHeaders = $this->response;
            $this->respContent = '';
        } else {
            $this->respContent = $this->response;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getResponseErrorMessage(): string
    {
        $errorMsg = 'An unexpected error occured while communicating with Shipping Rate Web Service (' . $this->errorNum . ')';
        return $errorMsg;
    }

    /**
     * @return bool
     */
    public function hasResponseError(): bool
    {
        return $this->errorNum !== 0
            || !preg_match("#HTTP/1.1 200 OK#i", $this->response);
    }

    /**
     * @param string $respContent
     * @return static
     */
    public function setRespContent(string $respContent): static
    {
        $this->respContent = $respContent;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRespContent(): ?string
    {
        return $this->respContent;
    }

    /**
     * @param string $respHeaders
     * @return static
     */
    public function setRespHeaders(string $respHeaders): static
    {
        $this->respHeaders = $respHeaders;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRespHeaders(): ?string
    {
        return $this->respHeaders;
    }

    /**
     * @param string $response
     * @return static
     */
    public function setResponse(string $response): static
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getResponse(): ?string
    {
        return $this->response;
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
     * @param int $port
     * @return static
     */
    public function setPort(int $port): static
    {
        $this->port = Cast::toInt($port, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @param int $timeout
     * @return static
     */
    public function setTimeout(int $timeout): static
    {
        $this->timeout = Cast::toInt($timeout, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTimeout(): ?int
    {
        return $this->timeout;
    }

    /**
     * @param string $uri
     * @return static
     */
    public function setUri(string $uri): static
    {
        $this->uri = trim($uri);
        return $this;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}
