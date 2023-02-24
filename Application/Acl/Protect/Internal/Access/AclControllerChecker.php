<?php
/**
 * SAM-9538: Decouple ACL checking logic from front controller
 * SAM-5171: Application layers
 *
 * @author        Boanerge A. Regidor
 * @version       SVN: $Id: $
 * @since         Jul 15, 2009
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Application\Acl\Protect\Internal\Access;

use QRequestMode;
use Sam\Account\Validate\MultipleTenantAccountSimpleChecker;
use Sam\Application\Acl\Protect\Internal\Access\Internal\Load\DataProviderCreateTrait;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Application\Url\Build\Config\Auth\AdminLoginUrlConfig;
use Sam\Application\Url\Build\Config\Auth\ResponsiveLoginUrlConfig;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Application\Acl\Protect\Internal\Access\AclControllerCheckingInput as Input;
use Sam\Application\Acl\Protect\Internal\Access\AclControllerCheckingResult as Result;

/**
 * Class AclPlugin
 * @package Sam\Application\Acl
 */
class AclControllerChecker extends CustomizableClass
{
    use BackUrlParserAwareTrait;
    use ConfigRepositoryAwareTrait;
    use DataProviderCreateTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AclControllerCheckingInput $input
     * @return Result
     */
    public function detectRedirectionUrl(Input $input): Result
    {
        $result = Result::new()->construct();
        $dataProvider = $this->createDataProvider();
        $aclChecker = $dataProvider->createAclChecker($input->editorUserId, $input->ui);
        $role = $dataProvider->detectAclRole($input->editorUserId, $input->ui);

        // Mapping to determine which Resource the current
        $resourceController = $input->controller;
        if (!$aclChecker->hasResource($resourceController)) {
            $resourceController = null;
        }

        $ownerResource = $resourceController . ":all";

        if (
            !$input->resourceEntityId
            || !$aclChecker->hasResource($ownerResource)
        ) {
            $ownerResource = null;
        }

        // Access Check
        // not allowed to access page, specific action or have no owner rights

        if (
            !$aclChecker->isAllowed($role, $resourceController, $input->action)
            || (
                $ownerResource
                && !$aclChecker->isAllowed($role, $ownerResource)
                && !$dataProvider->isOwnerOfResource($input->editorUserId, $input->resourceEntityId, $resourceController, true)
            )
        ) {
            switch ($role) {
                case Constants\Role::ACL_ANONYMOUS:
                    if ($resourceController === null) {
                        $redirectUrl = $dataProvider->loadPageRedirectionUrl($input->systemAccountId);
                        if (
                            $redirectUrl
                            && $input->requestMode !== QRequestMode::Ajax
                        ) {
                            return $result->addError(
                                Result::ERR_ACCESS_DENIED_TO_UNKNOWN_RESOURCE_FOR_ANONYMOUS_AND_REDIRECT_URL_DEFINED_BY_SYSTEM_PARAMETER,
                                $redirectUrl
                            );
                        }

                        return $result->addError(
                            Result::ERR_ACCESS_DENIED_TO_UNKNOWN_RESOURCE_FOR_ANONYMOUS_AND_REDIRECT_URL_UNDEFINED,
                            $this->buildAccessErrorUrl($input->ui)
                        );
                    }

                    return $result->addError(
                        Result::ERR_ACCESS_DENIED_TO_KNOWN_RESOURCE_FOR_ANONYMOUS,
                        $this->buildLoginUrl($input->ui)
                    );

                case Constants\Role::ACL_CUSTOMER:
                case Constants\Role::ACL_STAFF:
                case Constants\Role::ACL_ADMIN:
                    return $result->addError(
                        Result::ERR_ACCESS_DENIED_FOR_AUTHORIZED_USER,
                        $this->buildAccessErrorUrl($input->ui)
                    );
            }
        }

        $isMultipleTenant = (bool)$this->cfg()->get('core->portal->enabled');
        if ($isMultipleTenant) {
            switch ($role) {
                case Constants\Role::ACL_STAFF:
                case Constants\Role::ACL_ADMIN:
                    $editorUser = $dataProvider->loadEditorUser($input->editorUserId, true);
                    /**
                     * Regular admin user can access domain that is directly linked with his account.
                     * When portal admin follows main domain, he actually visits his portal account as it is system account.
                     */
                    if (
                        $editorUser
                        && $editorUser->AccountId === $input->systemAccountId
                    ) {
                        return $result->addSuccess(Result::OK_ACCESS_ALLOWED_TO_OWN_ACCOUNT_FOR_ADMIN);
                    }

                    /**
                     * Only cross-domain admin can access account that is not directly linked with his user entity
                     */
                    if (
                        $editorUser
                        && $editorUser->AccountId !== $input->systemAccountId
                    ) {
                        $hasPrivilegeForSuperadmin = $dataProvider->hasPrivilegeForSuperadmin($input->editorUserId, true);
                        if ($hasPrivilegeForSuperadmin) {
                            return $result->addSuccess(Result::OK_ACCESS_ALLOWED_TO_OTHER_ACCOUNT_FOR_CROSS_DOMAIN_ADMIN);
                        }
                    }

                    return $result->addError(
                        Result::ERR_ACCESS_DENIED_TO_MULTIPLE_TENANT_INSTALL_FOR_ADMIN,
                        $this->buildAccessErrorUrl($input->ui)
                    );
            }
        }
        return $result->addSuccess(Result::OK_ACCESS_ALLOWED);
    }

    protected function buildAccessErrorUrl(Ui $ui): string
    {
        $urlConfig = $ui->isWebAdmin()
            ? ZeroParamUrlConfig::new()->forRedirect(Constants\Url::A_ACCESS_ERROR)
            : ZeroParamUrlConfig::new()->forRedirect(Constants\Url::P_ACCESS_ERROR);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    protected function buildLoginUrl(Ui $ui): string
    {
        $urlConfig = $ui->isWebAdmin()
            ? AdminLoginUrlConfig::new()
            : ResponsiveLoginUrlConfig::new();
        $url = $this->getUrlBuilder()->build($urlConfig);
        $backUrl = $this->createDataProvider()->fetchServerRequestUrl();
        $url = $this->getBackUrlParser()->replace($url, $backUrl);
        return $url;
    }

    /**
     * Check, if current system account is portal sub-account of multiple tenant installation
     * @param int $systemAccountId
     * @return bool
     */
    protected function isPortalAccount(int $systemAccountId): bool
    {
        return MultipleTenantAccountSimpleChecker::new()->isPortalAccount(
            $systemAccountId,
            $this->cfg()->get('core->portal->enabled'),
            $this->cfg()->get('core->portal->mainAccountId')
        );
    }
}
