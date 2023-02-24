<?php
/**
 * Trait for access to RequestParamFetcher initialized by Route request
 *
 * SAM-4824: Encapsulate $_SERVER access
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-08, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\RequestParam\Route;

/**
 * Trait ParamFetcherForRouteAwareTrait
 * @package Sam\Application\RequestParam
 */
trait ParamFetcherForRouteAwareTrait
{
    protected ?ParamFetcherForRoute $paramFetcherForRoute = null;

    /**
     * @return ParamFetcherForRoute
     */
    protected function getParamFetcherForRoute(): ParamFetcherForRoute
    {
        if ($this->paramFetcherForRoute === null) {
            $this->paramFetcherForRoute = ParamFetcherForRoute::new()->construct();
        }
        return $this->paramFetcherForRoute;
    }

    /**
     * @param ParamFetcherForRoute $paramFetcherForRoute
     * @return $this
     * @noinspection PhpUnused
     * @internal
     */
    public function setParamFetcherForRoute(ParamFetcherForRoute $paramFetcherForRoute): static
    {
        $this->paramFetcherForRoute = $paramFetcherForRoute;
        return $this;
    }
}
