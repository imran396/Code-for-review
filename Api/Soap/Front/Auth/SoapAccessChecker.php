<?php
/**
 * SAM-3566: Refactoring for user authorization logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           3/22/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Api\Soap\Front\Auth;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;


class SoapAccessChecker extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use SystemAccountAwareTrait;
    use UserAwareTrait;

    public const ERR_NOT_AUTHENTICATED = 1;
    public const ERR_ADMIN_REQUIRED = 2;
    public const ERR_ADMIN_NOT_ASSIGNED = 3;
    public const ERR_PORTAL_DISABLED = 4;
    public const ERR_ACCOUNT_ON_PORTAL_ACCOUNT = 5;
    public const ERR_SUPERADMIN_REQUIRED = 6;

    /** @var string[] */
    protected array $errorMessages = [
        self::ERR_NOT_AUTHENTICATED => 'Not Authenticated',
        self::ERR_ADMIN_REQUIRED => 'Admin privileges required',
        self::ERR_ADMIN_NOT_ASSIGNED => 'Admin was not assigned in this account',
        self::ERR_PORTAL_DISABLED => 'Portal is disabled',
        self::ERR_ACCOUNT_ON_PORTAL_ACCOUNT => 'Cannot add account on portal account',
        self::ERR_SUPERADMIN_REQUIRED => 'Superadmin privileges required',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $userId
     * @param int $systemAccountId
     * @return $this
     */
    public function construct(int $userId, int $systemAccountId): SoapAccessChecker
    {
        $this->setUserId($userId);
        $this->setSystemAccountId($systemAccountId);
        return $this;
    }

    /**
     * Getting error messages from ResultStatusCollector, generated in $this->verify()
     * @return string[]
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * @return int|null
     */
    public function errorCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstErrorCode();
    }


    /**
     * Get list of error codes
     * @return int[]
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * Getting error messages as single string
     * @return string
     */
    public function concatenatedErrorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }


    /**
     * Check whether the authenticated user is authorized for an action
     *
     * @param string $action name of the action to check
     * @return bool true if authorized
     */
    public function check(string $action): bool
    {
        $this->initResultStatusCollector();

        $editorUser = $this->getUser();
        if (!$editorUser) {
            // Not authenticated return error message & log
            $this->getResultStatusCollector()->addError(self::ERR_NOT_AUTHENTICATED);
            return false;
        }

        // if not, return error message & log
        // For now, just make sure the user is admin
        // TODO: check if $user is authorized for $action
        // Note: a better place to do that would be in the
        // User_Soap12Api, Auction_Soap12Api, etc
        if (!$this->hasAdminRole()) {
            $this->getResultStatusCollector()->addError(self::ERR_ADMIN_REQUIRED);
            return false;
        }

        if (
            $this->isPortalSystemAccount()
            && $editorUser->AccountId !== $this->getSystemAccountId() // User was not assigned in this account
            && !$this->getUserAdminPrivilegeChecker()->hasPrivilegeForSuperadmin()
        ) {
            $this->getResultStatusCollector()->addError(self::ERR_ADMIN_NOT_ASSIGNED);
            return false;
        }

        switch ($action) {
            case 'CreateAccount':
            case 'UpdateAccount':
            case 'DeleteAccount':
                if (!$this->cfg()->get('core->portal->enabled')) {
                    $this->getResultStatusCollector()->addError(self::ERR_PORTAL_DISABLED);
                    return false;
                }
                if ($this->isPortalSystemAccount()) {
                    $this->getResultStatusCollector()->addError(self::ERR_ACCOUNT_ON_PORTAL_ACCOUNT);
                    return false;
                }
                if ($this->getUserAdminPrivilegeChecker()->hasPrivilegeForSuperadmin()) {
                    $this->getResultStatusCollector()->addError(self::ERR_SUPERADMIN_REQUIRED);
                    return false;
                }
                break;
        }

        return true;
    }

    /**
     * Initialize ResultStatusCollector
     */
    protected function initResultStatusCollector(): void
    {
        $this->getResultStatusCollector()->construct($this->errorMessages);
    }

}
