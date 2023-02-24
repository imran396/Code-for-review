<?php
/**
 * SAM-5412: Validations at controller layer
 * SAM-6791: Validations at controller layer for v3.5 - InventoryControllerValidator at admin site
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

namespace Sam\Application\Controller\Admin\Inventory\Validate;

use LotItem;
use Sam\Application\Controller\Admin\Inventory\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Application\Controller\Admin\Inventory\Validate\InventoryControllerValidationResult as Result;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;

/**
 * Class InventoryControllerValidator
 * @package Sam\Application\Controller\Admin\Inventory\Validate
 */
class InventoryControllerValidator extends CustomizableClass
{
    use DataProviderCreateTrait;
    use OptionalsTrait;

    // --- Input values ---

    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool

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
     * Validate/Check if Inventory ID exists, and not archived or deleted
     * @param int|null $targetLotItemId
     * @param int $editorUserId
     * @param int $systemAccountId
     * @return Result
     */
    public function validate(?int $targetLotItemId, int $editorUserId, int $systemAccountId): Result
    {
        $result = Result::new()->construct();
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);
        $dataProvider = $this->createDataProvider();
        $lotItem = $dataProvider->loadLotItem($targetLotItemId, $isReadOnlyDb);

        $result = $this->checkAccessPermissions($editorUserId, $lotItem, $systemAccountId, $result);
        if ($result->hasError()) {
            return $result;
        }

        if (!$targetLotItemId) {
            // do not do other checks when creating new lot item
            return $result->addSuccess(Result::OK_CREATE_NEW_LOT_ITEM);
        }

        $result = $this->validateTargetLotItem($lotItem, $result);
        if ($result->hasError()) {
            return $result;
        }

        return $result->addSuccess(Result::OK_SUCCESS_VALIDATION);
    }

    // --- Internal logic ---

    protected function checkAccessPermissions(int $editorUserId, ?LotItem $lotItem, int $systemAccountId, Result $result): Result
    {
        $dataProvider = $this->createDataProvider();
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);
        if (!$dataProvider->hasPrivilegeForManageInventory($editorUserId, $isReadOnlyDb)) {
            return $result->addError(Result::ERR_USER_DOES_NOT_HAVE_PRIVILEGE_TO_MANAGE_INVENTORY);
        }

        if (
            $lotItem
            && $lotItem->AccountId !== $systemAccountId
            && !$dataProvider->isCrossDomainAdminOnMainAccountForMultipleTenant($editorUserId, $systemAccountId, $isReadOnlyDb)
        ) {
            return $result->addError(Result::ERR_USER_DOES_NOT_HAVE_PRIVILEGE_TO_MANAGE_INVENTORY_OF_NOT_SYSTEM_ACCOUNT);
        }

        return $result;
    }

    protected function validateTargetLotItem(?LotItem $lotItem, Result $result): Result
    {
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);
        $dataProvider = $this->createDataProvider();

        // check inventory existence
        if (!$lotItem) {
            return $result->addError(Result::ERR_INCORRECT_LOT_ITEM_ID);
        }

        // check inventory availability
        if ($lotItem->isDeleted()) {
            return $result->addError(Result::ERR_UNAVAILABLE_LOT_ITEM);
        }

        // check that inventory's account availability
        if (!$dataProvider->isLotItemAccountAvailable($lotItem->AccountId, $isReadOnlyDb)) {
            return $result->addError(Result::ERR_LOT_ITEM_ACCOUNT_NOT_FOUND);
        }

        return $result;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IS_READ_ONLY_DB] = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;
        $this->setOptionals($optionals);
    }
}
