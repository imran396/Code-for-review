<?php
/**
 * SAM-9538: Decouple ACL checking logic from front controller
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Acl\Protect;

use QApplication;
use Sam\Application\Acl\Protect\Internal\Access\AclControllerChecker;
use Sam\Application\ApplicationAwareTrait;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Application\Acl\Protect\Internal\Access\AclControllerCheckingInput as Input;

/**
 * Class AclProtector
 * @package Sam\Application\Acl
 */
class AclControllerProtector extends CustomizableClass
{
    use ApplicationAwareTrait;
    use ApplicationRedirectorCreateTrait;
    use EditorUserAwareTrait;
    use ParamFetcherForRouteAwareTrait;
    use SystemAccountAwareTrait;

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
    public function protect(): static
    {
        $routeParamFetcher = $this->getParamFetcherForRoute();
        $input = Input::new()->construct(
            $this->getEditorUserId(),
            $this->getSystemAccountId(),
            $this->getApplication()->ui(),
            $routeParamFetcher->getControllerName(),
            $routeParamFetcher->getActionName(),
            $routeParamFetcher->getIntPositiveOrZero(Constants\UrlParam::R_ID),
            QApplication::getRequestMode()
        );
        $result = AclControllerChecker::new()->detectRedirectionUrl($input);
        if ($result->hasError()) {
            log_warning('ACL check failed' . composeSuffix($result->logData()));
        }
        if ($result->redirectUrl) {
            $this->createApplicationRedirector()->redirect($result->redirectUrl);
        }
        return $this;
    }

}
