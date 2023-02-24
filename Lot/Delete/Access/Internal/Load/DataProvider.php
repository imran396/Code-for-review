<?php
/**
 * SAM-9129: Verify lot item deleting operation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Delete\Access\Internal\Load;

use LotItem;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoader;
use Sam\Settings\SettingsManager;
use Sam\User\Load\UserLoader;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;
use Sam\User\Privilege\Validate\ConsignorPrivilegeChecker;
use User;
use Sam\Core\Constants;

/**
 * Class DataProvider
 * @package Sam\Lot\Delete\Access\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    private ?AdminPrivilegeChecker $editorAdminPrivilegeChecker = null;
    private ?ConsignorPrivilegeChecker $editorConsignorPrivilegeChecker = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadEditorUser(int $editorUserId, bool $isReadOnlyDb = false): ?User
    {
        return UserLoader::new()->load($editorUserId, $isReadOnlyDb);
    }

    public function loadTargetLotItem(?int $targetLotItemId, bool $isReadOnlyDb = false): ?LotItem
    {
        return LotItemLoader::new()->load($targetLotItemId, $isReadOnlyDb);
    }

    public function hasEditorUserPrivilegeForCrossDomainAdmin(int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        return $this->getEditorAdminPrivilegeChecker($editorUserId, $isReadOnlyDb)->hasPrivilegeForSuperadmin();
    }

    public function hasEditorUserAdminRole(int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        return $this->getEditorAdminPrivilegeChecker($editorUserId, $isReadOnlyDb)->isAdmin();
    }

    public function hasEditorUserConsignorRole(int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        return $this->getEditorConsignorPrivilegeChecker($editorUserId, $isReadOnlyDb)->isConsignor();
    }

    public function isAllowConsignorDeleteItem(): bool
    {
        return (bool)SettingsManager::new()->getForSystem(Constants\Setting::ALLOW_CONSIGNOR_DELETE_ITEM);
    }

    protected function getEditorAdminPrivilegeChecker(int $editorUserId, bool $isReadOnlyDb = false): AdminPrivilegeChecker
    {
        if ($this->editorAdminPrivilegeChecker === null) {
            $this->editorAdminPrivilegeChecker = AdminPrivilegeChecker::new()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->initByUserId($editorUserId);
        }
        return $this->editorAdminPrivilegeChecker;
    }

    protected function getEditorConsignorPrivilegeChecker(int $editorUserId, bool $isReadOnlyDb = false): ConsignorPrivilegeChecker
    {
        if ($this->editorConsignorPrivilegeChecker === null) {
            $this->editorConsignorPrivilegeChecker = ConsignorPrivilegeChecker::new()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->initByUserId($editorUserId);
        }
        return $this->editorConsignorPrivilegeChecker;
    }
}
