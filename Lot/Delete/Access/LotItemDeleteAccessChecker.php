<?php
/**
 * This service checks access rights of user for operation of lot item deleting.
 *
 * SAM-9129: Verify lot item deleting operation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Delete\Access;

use LotItem;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Lot\Delete\Access\Internal\Load\DataProvider;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Delete\Access\LotItemDeleteAccessCheckingResult as Result;
use User;

/**
 * Class LotItemDeleteAccessChecker
 * @package Sam\Lot\Delete\Access
 */
class LotItemDeleteAccessChecker extends CustomizableClass
{
    public const OP_TARGET_LOT_ITEM = 'targetLotItem'; // ?LotItem
    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool

    /** @var DataProvider|null */
    protected ?DataProvider $dataProvider = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check access rights of editor user (admin or consignor) for deletion of target lot item.
     * @param LotItem $targetLotItem
     * @param int|null $editorUserId
     * @param array $optionals
     * @return Result
     */
    public function canDeleteLotItem(LotItem $targetLotItem, ?int $editorUserId, array $optionals = []): Result
    {
        $optionals[self::OP_TARGET_LOT_ITEM] = $targetLotItem;
        return $this->canDelete($targetLotItem->Id, $editorUserId, $optionals);
    }

    /**
     * Check access rights of editor user for deletion of target lot item passed by id.
     * There are cases when admin and consignor can delete lot item.
     * @param int|null $targetLotItemId
     * @param int|null $editorUserId
     * @param array $optionals
     * @return Result
     */
    public function canDelete(?int $targetLotItemId, ?int $editorUserId, array $optionals = []): Result
    {
        $result = Result::new()->construct($targetLotItemId, $editorUserId);
        $isReadOnlyDb = $optionals[self::OP_IS_READ_ONLY_DB] ?? true;
        $dataProvider = $this->getDataProvider();

        if (!$editorUserId) {
            return $result->addError(Result::ERR_DENIED_FOR_ANONYMOUS);
        }

        if (!$targetLotItemId) {
            return $result->addError(Result::ERR_TARGET_LOT_ITEM_ID_UNDEFINED);
        }

        $targetLotItem = $this->fetchOptionalTargetLotItem($targetLotItemId, $isReadOnlyDb, $optionals);
        if (!$targetLotItem) {
            return $result->addError(Result::ERR_TARGET_LOT_ITEM_NOT_FOUND);
        }

        $editorUser = $dataProvider->loadEditorUser($editorUserId, $isReadOnlyDb);
        if (!$editorUser) {
            return $result->addError(Result::ERR_EDITOR_USER_NOT_FOUND);
        }

        $isAdmin = $dataProvider->hasEditorUserAdminRole($editorUserId, $isReadOnlyDb);
        if (!$isAdmin) {
            return $this->checkConsignorAccess($targetLotItem, $editorUser, $isReadOnlyDb, $result);
        }

        if ($targetLotItem->AccountId === $editorUser->AccountId) {
            return $result->addSuccess(Result::OK_ADMIN_AND_LOT_ACCOUNTS_MATCH);
        }

        $hasEditorUserPrivilegeForCrossDomainAdmin = $dataProvider
            ->hasEditorUserPrivilegeForCrossDomainAdmin($editorUserId, $isReadOnlyDb);
        if ($hasEditorUserPrivilegeForCrossDomainAdmin) {
            return $result->addSuccess(Result::OK_CROSS_DOMAIN_ADMIN_CAN_DELETE_ANY_LOT);
        }

        return $result->addError(Result::ERR_DENIED_FOR_REGULAR_ADMIN_OF_DIFFERENT_ACCOUNT);
    }

    protected function fetchOptionalTargetLotItem(?int $lotItemId, bool $isReadOnlyDb = false, array $optionals = []): ?LotItem
    {
        return array_key_exists(self::OP_TARGET_LOT_ITEM, $optionals)
            ? $optionals[self::OP_TARGET_LOT_ITEM]
            : $this->getDataProvider()->loadTargetLotItem($lotItemId, $isReadOnlyDb);
    }

    protected function checkConsignorAccess(LotItem $targetLotItem, User $editorUser, bool $isReadOnlyDb, Result $result): Result
    {
        $dataProvider = $this->getDataProvider();
        if (!$dataProvider->hasEditorUserConsignorRole($editorUser->Id, $isReadOnlyDb)) {
            return $result->addError(Result::ERR_DENIED_BECAUSE_NOT_ADMIN_AND_NOT_CONSIGNOR);
        }

        if (!$dataProvider->isAllowConsignorDeleteItem()) {
            return $result->addError(Result::ERR_DENIED_FOR_CONSIGNOR_BECAUSE_ITEM_DELETE_BY_CONSIGNOR_RESTRICTED);
        }

        if (!$targetLotItem->isConsignorLinkedWith($editorUser->Id)) {
            return $result->addError(Result::ERR_DENIED_FOR_CONSIGNOR_BECAUSE_NOT_OWNER);
        }

        return $result->addSuccess(Result::OK_CONSIGNOR_CAN_DELETE_OWN_LOT);
    }

    // --- DI ---

    protected function getDataProvider(): DataProvider
    {
        if ($this->dataProvider === null) {
            $this->dataProvider = DataProvider::new();
        }
        return $this->dataProvider;
    }

    public function setDataProvider(DataProvider $dataProvider): static
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }
}
