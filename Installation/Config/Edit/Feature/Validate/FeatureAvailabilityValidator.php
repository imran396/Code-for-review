<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           09-29, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Feature\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Core\Ip\Validate\CidrChecker;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Installation\Config\Edit\Feature\Validate\FeatureAvailabilityValidationResult as Result;

/**
 * Class FeatureAvailabilityValidator
 * @package Sam\Installation\Config
 */
class FeatureAvailabilityValidator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use EditorUserAwareTrait;
    use FeatureAvailabilityCheckerCreateTrait;
    use ServerRequestReaderAwareTrait;

    /**
     * External input remote IP
     * @var string|null
     */
    protected ?string $remoteIp = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return FeatureAvailabilityValidationResult
     */
    public function verify(): FeatureAvailabilityValidationResult
    {
        $featureChecker = $this->createFeatureAvailabilityChecker();
        $isManageAvailable = $featureChecker->isAvailable();
        $result = $this->cfg()->get('core->portal->enabled')
            ? Result::new()->constructForMultitenant()
            : Result::new()->construct();

        if (!$isManageAvailable) {
            return $result->addWarning(Result::WARN_INSTALLATION_MANAGE_DISABLED);
        }

        if (!$this->validatePrivileges()) {
            return $result->addError(Result::ERR_NO_PRIVILEGES);
        }

        $requestRemoteIp = $this->getRemoteIp();
        if (!$this->validateIpAccess($requestRemoteIp)) {
            return $result->addError(Result::ERR_NOT_VALID_IP, $requestRemoteIp);
        }

        return $result->addSuccess(Result::OK_SUCCESS_VALIDATION);
    }

    /**
     * Check has current user Superadmin privileges to access
     * Installation configuration management page.
     * @return bool
     */
    protected function validatePrivileges(): bool
    {
        if ($this->cfg()->get('core->portal->enabled')) {
            $hasAccess = $this->getEditorUserAdminPrivilegeChecker()
                ->hasPrivilegeForSuperadmin();
            return $hasAccess;
        }

        $hasAccess = $this->getEditorUserAdminPrivilegeChecker()->isAdmin();
        return $hasAccess;
    }

    /**
     * Compare request remote IP with expected IPs from $this->getExpectedRemoteIp()
     * @param string $requestRemoteIp
     * @return bool
     */
    protected function validateIpAccess(string $requestRemoteIp): bool
    {
        $allowedSubnets = $this->cfg()->get('core->install->ipAllow')->toArray();
        if (!$allowedSubnets) {
            return false;
        }

        $cidrChecker = CidrChecker::new()->setSubnets($allowedSubnets);
        $is = $cidrChecker->isInCidrList($requestRemoteIp);
        return $is;
    }

    /**
     * @return string
     */
    public function getRemoteIp(): string
    {
        if ($this->remoteIp === null) {
            $this->remoteIp = $this->getServerRequestReader()->remoteAddr();
        }
        return $this->remoteIp;
    }

    /**
     * Setup remote IP
     * @param string $remoteIp
     * @return static
     */
    public function setRemoteIp(string $remoteIp): static
    {
        $this->remoteIp = trim($remoteIp);
        return $this;
    }
}
