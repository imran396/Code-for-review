<?php
/**
 * SAM-5788: Adjust proxy.php script
 * SAM-7980: Refactor proxy.php
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\CrossDomainAjaxRequestProxy;

use RuntimeException;
use Sam\Application\Controller\Responsive\CrossDomainAjaxRequestProxy\Internal\CacheManagerCreateTrait;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Application\RequestParam\ParamFetcherForPostAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Infrastructure\Net\HttpClientCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * AJAX request handler to do proxy requests to PROXY_URL to bypass the AJAX sandbox cross domain restrictions
 * Remote URL is hard coded to avoid abuse of the script
 * Result of core->vendor->samSharedService->httpProxy->url is cached core->vendor->samSharedService->httpProxy->cacheTimeout seconds
 * core->vendor->samSharedService->httpProxy->requestTimeout can define the max number of seconds to wait
 * until the request to PROXY_URL times out
 *
 * Class CrossDomainAjaxRequestHandler
 * @package Sam\Application\Controller\Responsive\CrossDomainAjaxRequestProxy
 */
class CrossDomainAjaxRequestHandler extends CustomizableClass
{
    use CacheManagerCreateTrait;
    use HttpClientCreateTrait;
    use OptionalsTrait;
    use ParamFetcherForGetAwareTrait;
    use ParamFetcherForPostAwareTrait;
    use ServerRequestReaderAwareTrait;

    public const OP_URL = 'url';
    public const OP_REQUEST_TIMEOUT = 'requestTimeout';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @return string
     */
    public function handle(): string
    {
        $cacheManager = $this->createCacheManager()->construct();
        $result = $cacheManager->get();
        if ($result === null) {
            $result = $this->makeRequest();
            $cacheManager->set($result);
        }
        return $result;
    }

    /**
     * @return string
     */
    protected function makeRequest(): string
    {
        $url = $this->makeUrl();
        $requestTimeout = $this->fetchOptional(self::OP_REQUEST_TIMEOUT);
        if ($this->getServerRequestReader()->isPost()) {
            log_debug('Make POST request');
            $postData = $this->getParamFetcherForPost()->getParameters();
            $response = $this->createHttpClient()->post($url, $postData, [], $requestTimeout);
        } else {
            log_debug('Make GET request');
            $response = $this->createHttpClient()->get($url, [], [], $requestTimeout);
        }
        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException('Request finished with code ' . $response->getStatusCode());
        }
        $data = $response->getBody()->getContents();

        if ($data) {
            log_debug("Request finished with result for '{$url}'");
        } else {
            log_warning("Request finished without result for '{$url}'");
        }

        return $data;
    }

    /**
     * Create url from base URL + parameters
     *
     * @return string
     */
    protected function makeUrl(): string
    {
        $url = $this->fetchOptional(self::OP_URL);
        if (!$url) {
            throw new \InvalidArgumentException('Proxy url must be defined in local config');
        }

        $queryData = $this->getParamFetcherForGet()->getParameters();
        if ($queryData) {
            $baseUrlHasQueryPart = str_contains($url, '?');
            $url .= $baseUrlHasQueryPart ? '&' : '?';
            $url .= http_build_query($queryData);
        }
        return $url;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_URL] = $optionals[self::OP_URL]
            ?? static function (): string {
                return (string)ConfigRepository::getInstance()->get('core->vendor->samSharedService->httpProxy->url');
            };
        $optionals[self::OP_REQUEST_TIMEOUT] = $optionals[self::OP_REQUEST_TIMEOUT]
            ?? static function (): int {
                return ConfigRepository::getInstance()->get('core->vendor->samSharedService->httpProxy->requestTimeout');
            };
        $this->setOptionals($optionals);
    }
}
