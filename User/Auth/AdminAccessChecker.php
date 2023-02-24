<?php
/**
 * Check, if admin is allowed to access specific account
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           2 Sep, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\User\Auth;

use Sam\Account\Validate\AccountExistenceCheckerAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;

/**
 * Class AdminAccessChecker
 * @package Sam\User\Auth
 */
class AdminAccessChecker extends CustomizableClass
{
    use AccountExistenceCheckerAwareTrait;
    use AdminPrivilegeCheckerAwareTrait;
    use ConfigRepositoryAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use UserAwareTrait;
    use SystemAccountAwareTrait;

    public const ERR_USER_NOT_FOUND = 1;
    public const ERR_ADMIN_ROLE_ABSENT = 4;
    public const ERR_ADMIN_NOT_ALLOWED_ACCESS_TO_ACCOUNT = 5;
    public const ERR_ADMIN_ACCOUNT_NOT_APPROVED = 7;


    /** @var string[] */
    protected array $errorMessages = [
        self::ERR_USER_NOT_FOUND => 'Available user not found',
        self::ERR_ADMIN_ROLE_ABSENT => 'User does not have admin privilege',
        self::ERR_ADMIN_NOT_ALLOWED_ACCESS_TO_ACCOUNT => 'Admin not allowed access to account',
        self::ERR_ADMIN_ACCOUNT_NOT_APPROVED => 'Your account is not yet approved. Please hold on',
    ];

    /**
     * Get instance of LoginManager
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
    public function construct(int $userId, int $systemAccountId): AdminAccessChecker
    {
        $this->setUserId($userId);
        $this->setSystemAccountId($systemAccountId);
        return $this;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $this->initResultStatusCollector();
        $collector = $this->getResultStatusCollector();

        $user = $this->getUser();
        if (!$user) {
            $collector->addError(self::ERR_USER_NOT_FOUND);
            log_error($this->errorMessages[self::ERR_USER_NOT_FOUND]);
            return false;
        }

        $isAdmin = $this->hasAdminRole();
        $logInfo = composeSuffix(
            [
                'u' => $user->Id,
                'username' => $user->Username,
                'admin check' => true,
            ]
        );

        if ($isAdmin) {
            $isAdminAllowedAccessToAccount = $this->isAllowed();
            if (!$isAdminAllowedAccessToAccount) {
                $collector->addError(self::ERR_ADMIN_NOT_ALLOWED_ACCESS_TO_ACCOUNT);
                log_info($this->errorMessages[self::ERR_ADMIN_NOT_ALLOWED_ACCESS_TO_ACCOUNT] . $logInfo);
                return false;
            }
        } else {
            $isAccountAdmin = $this->getAccountExistenceChecker()
                ->existByName($user->Username, [], true);
            if ($isAccountAdmin) {
                $collector->addError(self::ERR_ADMIN_ACCOUNT_NOT_APPROVED);
                log_info($this->errorMessages[self::ERR_ADMIN_ACCOUNT_NOT_APPROVED] . $logInfo);
            } else {
                $collector->addError(self::ERR_ADMIN_ROLE_ABSENT);
                log_info($this->errorMessages[self::ERR_ADMIN_ROLE_ABSENT] . $logInfo);
            }
            return false;
        }
        return true;
    }

    /**
     * Getting error messages from ResultStatusCollector
     * @return string[]
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
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
     * Get list of error codes
     * @return int[]
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * Get last error code
     * @return int|null
     */
    public function errorCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstErrorCode();
    }

    /**
     * @return bool
     */
    protected function isAllowed(): bool
    {
        $isAllowed = false;
        if ($this->getUser()->AccountId === $this->getSystemAccountId()) {
            // It is admin's account, so he is allowed
            $isAllowed = true;
        } elseif ($this->cfg()->get('core->portal->enabled')) {
            /**
             * In case of Portal installation, admin user should be able to access admin side
             * to some other account only if he has superadmin privilege.
             * Or if it is main account.
             */
            $isSuperadmin = $this->getUserAdminPrivilegeChecker()->hasPrivilegeForSuperadmin();
            $this->getSystemAccountAggregate()->setConfigRepository($this->cfg());
            if (
                $isSuperadmin
                || $this->isMainSystemAccount()
            ) {
                $isAllowed = true;
            }
        }
        return $isAllowed;
    }

    /**
     * Initialize ResultStatusCollector
     */
    protected function initResultStatusCollector(): void
    {
        $this->getResultStatusCollector()->construct($this->errorMessages);
    }
}
