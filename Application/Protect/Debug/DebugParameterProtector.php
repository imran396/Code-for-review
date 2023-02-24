<?php
/**
 * SAM-5669: Debug level parameter for web request
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1.1.2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Application\Protect\Debug;

use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;

/**
 * Class RequestParameterValidator
 * @package Sam\Application\Protect\Debug
 */
class DebugParameterProtector extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use AuthIdentityManagerCreateTrait;
    use ConfigRepositoryAwareTrait;
    use ParamFetcherForGetAwareTrait;
    use ServerRequestReaderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check, log and redirect
     */
    public function protect(): void
    {
        if (!$this->cfg()->get('core->debug->web->enabled')) {
            // Don't need to check, when disabled
            return;
        }

        $hasDebugParam = $this->getParamFetcherForGet()->has($this->cfg()->get('core->debug->web->paramName'));
        if ($hasDebugParam) {
            $logData = [
                'uri' => $this->getServerRequestReader()->requestUri(),
                'remote' => $this->getServerRequestReader()->remoteAddr(),
                $this->cfg()->get('core->debug->web->paramName') => $this->getParamFetcherForGet()->getString($this->cfg()->get('core->debug->web->paramName')),
            ];
            if (!$this->createAuthIdentityManager()->isAuthorized()) {
                log_warning('Tried to debug unauthenticated' . composeSuffix($logData));
                // then, depending on configuration exit
                if ($this->cfg()->get('core->debug->web->blockInvalid')) {
                    // 403 Forbidden
                    $this->createApplicationRedirector()->forbidden();
                }
                return;
            }

            $webDebugLevel = $this->getParamFetcherForGet()->getWebDebugLevel();
            if (!$webDebugLevel) {
                log_warning('Invalid debug option' . composeSuffix($logData));
                if ($this->cfg()->get('core->debug->web->blockInvalid')) {
                    // 400 Bad request
                    $this->createApplicationRedirector()->badRequest();
                }
                return;
            }
        }
    }
}
