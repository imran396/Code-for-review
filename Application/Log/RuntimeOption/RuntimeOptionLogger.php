<?php
/**
 * SAM-6397: Runtime config options
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Log\RuntimeOption;

use Sam\Application\ApplicationAwareTrait;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Log\Support\SupportLoggerAwareTrait;

/**
 * Class RuntimeOptionLogger
 * @package Sam\Application\Log\RuntimeOption
 */
class RuntimeOptionLogger extends CustomizableClass
{
    use ApplicationAwareTrait;
    use ConfigRepositoryAwareTrait;
    use ParamFetcherForRouteAwareTrait;
    use SupportLoggerAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function log(): void
    {
        $paramFetcherForRoute = $this->getParamFetcherForRoute();
        $ui = $this->getApplication()->ui();
        $cfg = $this->cfg();
        $logOutputCb = static function () use ($paramFetcherForRoute, $ui, $cfg): string {
            $basePath = $ui->isWebAdmin() ? '/' . Constants\Application::UIDIR_ADMIN : '';
            $route = sprintf('%s/%s/%s', $basePath, $paramFetcherForRoute->getControllerName(), $paramFetcherForRoute->getActionName());
            $phpValueOptions = $cfg->get('runtime->__default__->phpValue')->toArray();
            $iniSetOptions = $cfg->get('runtime->__default__->iniSet')->toArray();
            $runtimeOptions = [];
            foreach (array_keys(array_merge($phpValueOptions, $iniSetOptions)) as $key) {
                $runtimeOptions[$key] = ini_get($key);
            }
            return "Runtime options for {$route}" . composeSuffix($runtimeOptions);
        };
        log_trace($logOutputCb);
    }

}
