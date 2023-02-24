<?php
/**
 * SAM-5420: Http request info logger
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Log\HttpRequest;

use Sam\Application\ApplicationAwareTrait;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Application\RequestParam\ParamFetcherForPostAwareTrait;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Log\Support\SupportLoggerAwareTrait;

/**
 * Class HttpRequestLogger
 * @package Sam\Application\Log\HttpRequest
 */
class HttpRequestLogger extends CustomizableClass
{
    use ApplicationAwareTrait;
    use ConfigRepositoryAwareTrait;
    use OptionalsTrait;
    use ParamFetcherForGetAwareTrait;
    use ParamFetcherForPostAwareTrait;
    use ParamFetcherForRouteAwareTrait;
    use ServerRequestReaderAwareTrait;
    use SupportLoggerAwareTrait;

    public const OP_HTTP_REQUEST_LOG_LEVEL = 'httpRequestLogLevel'; // int
    public const OP_IS_HTTP_REQUEST_LOG_ENABLED = 'isHttpRequestLogEnabled'; // bool

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    public function log(): void
    {
        $isLogEnabled = (bool)$this->fetchOptional(self::OP_IS_HTTP_REQUEST_LOG_ENABLED);
        if (!$isLogEnabled) {
            return;
        }

        $logLevel = (int)$this->fetchOptional(self::OP_HTTP_REQUEST_LOG_LEVEL);
        $this->getSupportLogger()->log($logLevel, fn() => Internal\LogMessageBuilder::new()->construct()->build(
            $this->getApplication()->ui(),
            $this->getParamFetcherForRoute()->getControllerName(),
            $this->getParamFetcherForRoute()->getActionName(),
            $this->getServerRequestReader()->requestMethod(),
            $this->collectParams()
        ));
    }

    /**
     * Build array with request parameters from route, GET, POST
     * @return array
     */
    private function collectParams(): array
    {
        $paramFetcherForRoute = $this->getParamFetcherForRoute();
        $serverRequestReader = $this->getServerRequestReader();
        $routeParams = $paramFetcherForRoute->getParameters();
        // get and post request params
        $getRequestParams = $serverRequestReader->isGet()
            ? $this->getParamFetcherForGet()->getParameters()
            : [];
        $postRequestParams = $serverRequestReader->isPost()
            ? $this->getParamFetcherForPost()->getParameters()
            : [];
        $output = array_merge($routeParams, $getRequestParams, $postRequestParams);
        return $output;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IS_HTTP_REQUEST_LOG_ENABLED] ??= $this->cfg()->get('core->app->httpRequest->log->enabled');
        $optionals[self::OP_HTTP_REQUEST_LOG_LEVEL] ??= $this->cfg()->get('core->app->httpRequest->log->level');
        $this->setOptionals($optionals);
    }
}
