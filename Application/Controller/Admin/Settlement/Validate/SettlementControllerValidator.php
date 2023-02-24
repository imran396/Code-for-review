<?php
/**
 * SAM-5412: Validations at controller layer
 * SAM-6794: Validations at controller layer for v3.5 - SettlementControllerValidator at admin site
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/01/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Settlement\Validate;

use Sam\Account\Validate\MultipleTenantAccountSimpleChecker;
use Sam\Application\Controller\Admin\Settlement\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Application\Controller\Admin\Settlement\Validate\SettlementControllerValidationResult as Result;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Constants;
use Sam\Installation\Config\Repository\ConfigRepository;
use Settlement;

/**
 * Class SettlementControllerValidator
 * @package Sam\Application\Controller\Admin\Settlement\Validate
 */
class SettlementControllerValidator extends CustomizableClass
{
    use DataProviderCreateTrait;
    use OptionalsTrait;

    // --- Input values ---

    public const OP_AVAILABLE_SETTLEMENT_STATUSES = 'availableSettlementStatuses'; // string[]
    public const OP_IS_MULTIPLE_TENANT = OptionalKeyConstants::KEY_IS_MULTIPLE_TENANT; // bool
    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_MAIN_ACCOUNT_ID = OptionalKeyConstants::KEY_MAIN_ACCOUNT_ID; // int

    // --- Constructors ---

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

    // --- Main method ---

    /**
     * Controller level validation for access to settlement by id.
     * @param int|null $settlementId
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @return Result
     */
    public function validate(?int $settlementId, ?int $editorUserId, int $systemAccountId): Result
    {
        $result = Result::new()->construct($settlementId);
        $dataProvider = $this->createDataProvider();
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);

        $settlement = $dataProvider->loadSettlement($settlementId, $isReadOnlyDb);
        return $this->validateSettlement($settlement, $editorUserId, $systemAccountId, $result);
    }

    /**
     * Controller level validation for access to settlement by entity.
     * @param Settlement|null $settlement null means we were not able to load settlement by id
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @param SettlementControllerValidationResult|null $result null for new result-object
     * @return Result
     */
    public function validateSettlement(
        ?Settlement $settlement,
        ?int $editorUserId,
        int $systemAccountId,
        ?Result $result = null
    ): Result {
        $result ??= Result::new()->construct($settlement?->Id);
        $dataProvider = $this->createDataProvider();
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);

        if (!$settlement instanceof Settlement) {
            return $result->addError(Result::ERR_INCORRECT_SETTLEMENT_ID);
        }

        // check editor user privilege for settlement operations
        if (!$dataProvider->hasPrivilegeForManageSettlements($editorUserId, $isReadOnlyDb)) {
            return $result->addError(Result::ERR_SETTLEMENT_ACCESS_DENIED);
        }

        // check settlement availability
        if (!$this->isSettlementStatusAvailable($settlement)) {
            return $result->addError(Result::ERR_UNAVAILABLE_SETTLEMENT);
        }

        if (!$dataProvider->isSettlementAccountAvailable($systemAccountId, $isReadOnlyDb)) {
            return $result->addError(Result::ERR_SETTLEMENT_ACCOUNT_NOT_FOUND);
        }

        if (
            $settlement->AccountId !== $systemAccountId
            && !$this->isMainSystemAccount($systemAccountId)
        ) {
            return $result->addError(Result::ERR_SETTLEMENT_AND_PORTAL_ACCOUNTS_NOT_MATCH);
        }

        return $result->addSuccess(Result::OK_SUCCESS_VALIDATION);
    }

    // --- Internal logic ---

    /**
     * @param Settlement $settlement
     * @return bool
     */
    protected function isSettlementStatusAvailable(Settlement $settlement): bool
    {
        $availableStatuses = (array)$this->fetchOptional(self::OP_AVAILABLE_SETTLEMENT_STATUSES);
        return in_array($settlement->SettlementStatusId, $availableStatuses, true);
    }

    /**
     * @param int $systemAccountId
     * @return bool
     */
    protected function isMainSystemAccount(int $systemAccountId): bool
    {
        return MultipleTenantAccountSimpleChecker::new()->isMainAccount(
            $systemAccountId,
            (bool)$this->fetchOptional(self::OP_IS_MULTIPLE_TENANT),
            (int)$this->fetchOptional(self::OP_MAIN_ACCOUNT_ID)
        );
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IS_READ_ONLY_DB] = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;
        $optionals[self::OP_AVAILABLE_SETTLEMENT_STATUSES] = $optionals[self::OP_AVAILABLE_SETTLEMENT_STATUSES]
            ?? Constants\Settlement::$availableSettlementStatuses;
        $optionals[self::OP_IS_MULTIPLE_TENANT] = $optionals[self::OP_IS_MULTIPLE_TENANT]
            ?? static function (): bool {
                return (bool)ConfigRepository::getInstance()->get('core->portal->enabled');
            };
        $optionals[self::OP_MAIN_ACCOUNT_ID] = $optionals[self::OP_MAIN_ACCOUNT_ID]
            ?? static function (): int {
                return (int)ConfigRepository::getInstance()->get('core->portal->mainAccountId');
            };
        $this->setOptionals($optionals);
    }
}
