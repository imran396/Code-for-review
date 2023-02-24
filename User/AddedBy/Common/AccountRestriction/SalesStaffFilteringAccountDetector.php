<?php
/**
 * SAM-6928: Sales staff user assignment and filtering control adjustments at the "User Edit" and the "Sales Report" pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\AddedBy\Common\AccountRestriction;

use Sam\Account\Validate\MultipleTenantAccountSimpleChecker;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\User\AddedBy\Common\AccountRestriction\Internal\Load\DataProviderCreateTrait;

/**
 * Class SalesStaffFilteringAccountDetector
 * @package Sam\User\AddedBy\Internal\Detect
 */
class SalesStaffFilteringAccountDetector extends CustomizableClass
{
    use DataProviderCreateTrait;
    use OptionalsTrait;
    use ResultStatusCollectorAwareTrait;

    // --- Incoming values ---

    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_IS_MULTIPLE_TENANT = OptionalKeyConstants::KEY_IS_MULTIPLE_TENANT; // bool
    public const OP_MAIN_ACCOUNT_ID = OptionalKeyConstants::KEY_MAIN_ACCOUNT_ID; // int

    // --- Outgoing values ---

    public const OK_VISIT_PORTAL_ACCOUNT = 1;
    public const OK_VISIT_MAIN_ACCOUNT_AS_REGULAR_ADMIN = 2;
    public const OK_VISIT_MAIN_ACCOUNT_AS_CROSS_ACCOUNT_ADMIN = 3;

    /** @var string[] */
    protected const OK_MESSAGES = [
        self::OK_VISIT_PORTAL_ACCOUNT => 'Visit portal account leads to filtering by system account',
        self::OK_VISIT_MAIN_ACCOUNT_AS_REGULAR_ADMIN => 'Visit main account as regular admin leads to filtering by system account',
        self::OK_VISIT_MAIN_ACCOUNT_AS_CROSS_ACCOUNT_ADMIN => 'Visit main account as cross-account superadmin leads to filtering by direct and collateral accounts of target user',
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
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Allowed Sales staff direct account restriction is based on next rules:
     * - Editor user is a cross-account super-admin who is visiting domain of main account,
     *  => filter by the set of direct and collateral accounts of Target consignor user;
     * - in all other scenarios,
     *  => filter by System account of visiting domain = direct account of editor regular admin;
     * @param int $targetUserId
     * @param int $editorUserId
     * @param int $systemAccountId
     * @return array
     */
    public function detect(int $targetUserId, int $editorUserId, int $systemAccountId): array
    {
        $this->initResultStatusCollector();

        $isMainSystemAccount = $this->isMainSystemAccount($systemAccountId);
        if (!$isMainSystemAccount) {
            $this->getResultStatusCollector()->addSuccess(self::OK_VISIT_PORTAL_ACCOUNT);
            return [$systemAccountId];
        }

        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);
        $dataProvider = $this->createDataProvider();

        $hasPrivilegeForSuperadmin = $dataProvider->hasPrivilegeForSuperadmin($editorUserId, $isReadOnlyDb);
        if ($hasPrivilegeForSuperadmin) {
            $this->getResultStatusCollector()->addSuccess(self::OK_VISIT_MAIN_ACCOUNT_AS_CROSS_ACCOUNT_ADMIN);
            $filterAccountIds = $dataProvider->loadAllAccountIdsOfUser($targetUserId, $isReadOnlyDb);
            return $filterAccountIds;
        }

        $this->getResultStatusCollector()->addSuccess(self::OK_VISIT_MAIN_ACCOUNT_AS_REGULAR_ADMIN);
        return [$systemAccountId];
    }

    /**
     * Return success code described detection result
     * @return int|null
     */
    public function successCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstSuccessCode();
    }

    /**
     * Initialize result status collection
     */
    protected function initResultStatusCollector(): void
    {
        $this->getResultStatusCollector()->initAllSuccesses(self::OK_MESSAGES);
    }

    /**
     * Check, if currently we are visiting main account.
     * It is always true for Single Tenant installation.
     * @param int $systemAccountId
     * @return bool
     */
    protected function isMainSystemAccount(int $systemAccountId): bool
    {
        $isMain = MultipleTenantAccountSimpleChecker::new()->isMainAccount(
            $systemAccountId,
            (bool)$this->fetchOptional(self::OP_IS_MULTIPLE_TENANT),
            (int)$this->fetchOptional(self::OP_MAIN_ACCOUNT_ID)
        );
        return $isMain;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IS_READ_ONLY_DB] = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;
        $optionals[self::OP_IS_MULTIPLE_TENANT] = $optionals[self::OP_IS_MULTIPLE_TENANT]
            ?? static function (): bool {
                return (bool)cfg()->core->portal->enabled;
            };
        $optionals[self::OP_MAIN_ACCOUNT_ID] = $optionals[self::OP_MAIN_ACCOUNT_ID]
            ?? static function (): int {
                return (int)cfg()->core->portal->mainAccountId;
            };
        $this->setOptionals($optionals);
    }
}
