<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Http\Response;

use RuntimeException;
use Sam\Core\Service\CustomizableClass;
use Psr\Http\Message\ResponseInterface;

/**
 * Class HttpResponseEmitter
 * @package Sam\Infrastructure\Http\Response
 */
class HttpResponseEmitter extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Process a request and return a response.
     * @param ResponseInterface $response
     */
    public function emit(ResponseInterface $response): void
    {
        if (headers_sent()) {
            throw new RuntimeException('Unable to emit response; headers already sent');
        }

        $this->sendStatus($response);
        $this->sendHeaders($response);

        echo $response->getBody();
    }

    /**
     * Sends the Response status line.
     * @param ResponseInterface $response
     */
    private function sendStatus(ResponseInterface $response): void
    {
        $version = $response->getProtocolVersion();
        $statusCode = $response->getStatusCode();
        $reasonPhrase = $response->getReasonPhrase();
        $header = sprintf('HTTP/%s %d%s', $version, $statusCode, empty($reasonPhrase) ? '' : " {$reasonPhrase}");

        header($header, true, $statusCode);
    }

    /**
     * Sends all Response headers.
     * @param ResponseInterface $response
     */
    private function sendHeaders(ResponseInterface $response): void
    {
        foreach ($response->getHeaders() as $name => $values) {
            $this->sendHeader($name, $values);
        }
    }

    /**
     * Sends one Response header.
     * @param string $name
     * @param array $values
     */
    private function sendHeader(string $name, array $values): void
    {
        $name = str_replace('-', ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '-', $name);

        foreach ($values as $value) {
            header("{$name}: {$value}", false);
        }
    }

}
