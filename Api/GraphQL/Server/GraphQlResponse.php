<?php
/**
 * SAM-10686: Improve debugging for GraphQL
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 20, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Server;

use GraphQL\Error\DebugFlag;
use GraphQL\Error\FormattedError;
use GraphQL\Executor\ExecutionResult;
use GraphQL\Executor\Promise\Promise;
use JsonSerializable;
use Sam\Core\Service\CustomizableClass;
use Throwable;

/**
 * Class GraphQlResponse
 * @package Sam\Api\GraphQL\Server
 */
class GraphQlResponse extends CustomizableClass
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
     * Converts and exception to error and sends spec-compliant HTTP 403 error.
     */
    public function send403Error(Throwable $error, int $debug = DebugFlag::NONE): void
    {
        $response = [
            'errors' => [FormattedError::createFromException($error, $debug)],
        ];
        $this->emitResponse($response, 403);
    }

    /**
     * Converts and exception to error and sends spec-compliant HTTP 500 error.
     * Useful when an exception is thrown somewhere outside of server execution context
     * (e.g. during schema instantiation).
     */
    public function send500Error(Throwable $error, int $debug = DebugFlag::NONE): void
    {
        $response = [
            'errors' => [FormattedError::createFromException($error, $debug)],
        ];
        $this->emitResponse($response, 500);
    }

    /**
     * Send response using standard PHP `header()` and `echo`.
     */
    public function sendResponse(Promise|ExecutionResult|array $result): void
    {
        if ($result instanceof Promise) {
            $result->then($this->doSendResponse(...));
        } else {
            $this->doSendResponse($result);
        }
    }

    protected function doSendResponse(ExecutionResult|array $result): void
    {
        $httpStatus = $this->resolveHttpStatus($result);
        $this->emitResponse($result, $httpStatus);
    }

    public function emitResponse(JsonSerializable|array $jsonSerializable, int $httpStatus): void
    {
        $body = json_encode($jsonSerializable, JSON_THROW_ON_ERROR);
        log_debug($body);
        header('Content-Type: application/json', true, $httpStatus);
        echo $body;
    }

    protected function resolveHttpStatus(ExecutionResult|array $result): int
    {
        if (
            $result instanceof ExecutionResult
            && $result->data === null
            && count($result->errors) > 0
        ) {
            return 400;
        }
        return 200;
    }
}
