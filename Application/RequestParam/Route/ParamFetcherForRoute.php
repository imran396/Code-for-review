<?php
/**
 * SAM-4824: Encapsulate $_SERVER access
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           06-25, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\RequestParam\Route;

use Sam\Application\RequestParam\RequestParamFetcher;

/**
 * Class ParamFetcherForRoute
 * @package Sam\Application\RequestParam
 */
class ParamFetcherForRoute extends RequestParamFetcher
{
    protected RequestRouteParser $requestRouteParser;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        $this->requestRouteParser = RequestRouteParser::new()->construct();
        $this->setParameters($this->requestRouteParser->detectParams());
        return $this;
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        return $this->requestRouteParser->detectActionName();
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        return $this->requestRouteParser->detectControllerName();
    }
}
