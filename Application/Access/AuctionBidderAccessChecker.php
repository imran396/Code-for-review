<?php
/**
 * SAM-5419: Access checkers for application features
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/25/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Access;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;

/**
 * @package com.swb.sam2
 */
class AuctionBidderAccessChecker extends CustomizableClass
{
    use AdminPrivilegeCheckerAwareTrait;
    use ApplicationAccessCheckerCreateTrait;
    use ConfigRepositoryAwareTrait;
    use EditorUserAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if current user is allowed to add floor bidder (SAM-2383)
     * @param int $accountId current account id
     * @return bool
     */
    public function isAllowedAddFloorBidder(int $accountId): bool
    {
        $editorUserId = $this->getEditorUserId();
        $hasPrivilegeForManageUsers = $this->getAdminPrivilegeChecker()
            ->initByUserId($editorUserId)
            ->hasPrivilegeForManageUsers();
        if (!$hasPrivilegeForManageUsers) {
            return false;
        }

        if ($this->cfg()->get('core->portal->enabled')) {
            $isAllowAccountAdminAddFloorBidder = (bool)$this->getSettingsManager()
                ->getForMain(Constants\Setting::ALLOW_ACCOUNT_ADMIN_ADD_FLOOR_BIDDER);
            $hasCrossDomainAdminAccess = $this->createApplicationAccessChecker()
                ->isCrossDomainAdminOnMainAccountForMultipleTenantOrAdminForSingleTenant($editorUserId, $accountId, true);
            if (
                $accountId === (int)$this->cfg()->get('core->portal->mainAccountId')
                && $hasCrossDomainAdminAccess
            ) {
                return true;
            }

            if ($isAllowAccountAdminAddFloorBidder) {
                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * Check if current user is allowed to make floor bidder preferred (SAM-2383)
     * @param int $accountId current account id
     * @return bool
     */
    public function isAllowedMakeBidderPreferred(int $accountId): bool
    {
        $editorUserId = $this->getEditorUserId();
        $hasPrivilegeForManageUsers = $this->getAdminPrivilegeChecker()
            ->initByUserId($editorUserId)
            ->hasPrivilegeForManageUsers();
        if (!$hasPrivilegeForManageUsers) {
            return false;
        }

        $sm = $this->getSettingsManager();
        $isAllowAccountAdminAddFloorBidder = (bool)$sm->getForMain(Constants\Setting::ALLOW_ACCOUNT_ADMIN_ADD_FLOOR_BIDDER);
        $isAllowAccountAdminMakeBidderPreferred = (bool)$sm->getForMain(Constants\Setting::ALLOW_ACCOUNT_ADMIN_MAKE_BIDDER_PREFERRED);
        if ($this->cfg()->get('core->portal->enabled')) {
            $hasCrossDomainAdminAccess = $this->createApplicationAccessChecker()
                ->isCrossDomainAdminOnMainAccountForMultipleTenantOrAdminForSingleTenant($editorUserId, $accountId, true);
            if (
                $accountId === $this->cfg()->get('core->portal->mainAccountId')
                && $hasCrossDomainAdminAccess
            ) {
                return true;
            }

            if (
                $isAllowAccountAdminAddFloorBidder
                && $isAllowAccountAdminMakeBidderPreferred
            ) {
                return true;
            }

            return false;
        }

        return true;
    }
}
