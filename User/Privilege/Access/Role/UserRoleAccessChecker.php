<?php
/**
 * SAM-9413: Make possible for portal admin to create bidder and consignor users linked with main account
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Privilege\Access\Role;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\User\Privilege\Access\Role\Internal\Load\DataProviderAwareTrait;

/**
 * Class UserRoleAccessChecker
 * @package Sam\User\Privilege\Access
 */
class UserRoleAccessChecker extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DataProviderAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function canEditConsignorOrBidderRole(
        int $editorUserId,
        ?int $targetUserid,
        bool $isAdmin,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): bool {
        $dataProvider = $this->getDataProvider();

        if ($dataProvider->existSystemUserId($editorUserId)) {
            return true;
        }

        if (
            $this->isAdminForSingleTenant($editorUserId)
            || $this->isCrossDomainAdminOnMainAccountForMultipleTenant($editorUserId, $systemAccountId, $isReadOnlyDb)
        ) {
            return true;
        }

        if (!$this->isShareUserInfoEditingAllowed()) {
            return false;
        }

        if ($targetUserid) {
            $targetUserAccountId = $dataProvider->loadUserDirectAccountId($targetUserid, $isReadOnlyDb);
            if (!$this->isMainAccount($targetUserAccountId)) {
                return false;
            }
        }

        if ($isAdmin) {
            return false;
        }

        return true;
    }

    public function canEditAdminRole(
        int $editorUserId,
        ?int $targetUserid,
        bool $isBidder,
        bool $isConsignor,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): bool {
        $dataProvider = $this->getDataProvider();

        if ($dataProvider->existSystemUserId($editorUserId)) {
            return true;
        }

        if (
            $this->isAdminForSingleTenant($editorUserId, $isReadOnlyDb)
            || $this->isCrossDomainAdminOnMainAccountForMultipleTenant($editorUserId, $systemAccountId, $isReadOnlyDb)
        ) {
            return true;
        }

        if ($targetUserid) {
            $targetUserAccountId = $dataProvider->loadUserDirectAccountId($targetUserid, $isReadOnlyDb);
            if ($targetUserAccountId !== $systemAccountId) {
                return false;
            }
        }

        if (
            $isBidder
            || $isConsignor
        ) {
            return false;
        }

        return true;
    }

    protected function isMainAccount(int $accountId): bool
    {
        return $accountId === (int)$this->cfg()->get('core->portal->mainAccountId');
    }

    protected function isAdminForSingleTenant(int $editorUserid, bool $isReadOnlyDb = false): bool
    {
        return !$this->isMultipleTenant()
            && $this->getDataProvider()->isEditorUserAdmin($editorUserid, $isReadOnlyDb);
    }

    protected function isCrossDomainAdminOnMainAccountForMultipleTenant(
        $editorUserid,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): bool {
        return $this->isMultipleTenant()
            && $this->isMainAccount($systemAccountId)
            && $this->getDataProvider()->isEditorUserCrossDomainAdmin($editorUserid, $isReadOnlyDb);
    }

    protected function isMultipleTenant(): bool
    {
        return $this->cfg()->get('core->portal->enabled');
    }

    protected function isShareUserInfoEditingAllowed(): bool
    {
        $shareUserInfo = $this->getSettingsManager()->getForMain(Constants\Setting::SHARE_USER_INFO);
        return $shareUserInfo === Constants\ShareUserInfo::EDIT;
    }
}
