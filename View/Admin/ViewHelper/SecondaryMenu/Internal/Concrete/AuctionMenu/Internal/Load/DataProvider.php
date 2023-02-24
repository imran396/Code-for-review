<?php
/**
 * SAM-9573: Refactor admin secondary menu for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 2, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\AuctionMenu\Internal\Load;

use Auction;
use Sam\Application\Acl\Role\AclChecker;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Tax\StackedTax\Feature\StackedTaxFeatureAvailabilityChecker;

/**
 * Class DataLoader
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\AuctionMenu\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use AuctionLoaderAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @return Auction
     */
    public function loadAuction(int $auctionId): Auction
    {
        return $this->getAuctionLoader()->load($auctionId);
    }

    /**
     * @param int|null $editorUserId
     * @return bool
     */
    public function isEditAllowed(?int $editorUserId): bool
    {
        $aclChecker = new AclChecker($editorUserId, Ui::new()->constructWebAdmin());
        return $aclChecker->isAllowed(Constants\Role::ACL_ADMIN, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_EDIT);
    }

    /**
     * @param int|null $editorUserId
     * @return bool
     */
    public function isLotsAllowed(?int $editorUserId): bool
    {
        $aclChecker = new AclChecker($editorUserId, Ui::new()->constructWebAdmin());
        return $aclChecker->isAllowed(Constants\Role::ACL_ADMIN, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_LOTS);
    }

    /**
     * @param int|null $editorUserId
     * @return bool
     */
    public function isBiddersAllowed(?int $editorUserId): bool
    {
        $aclChecker = new AclChecker($editorUserId, Ui::new()->constructWebAdmin());
        return $aclChecker->isAllowed(Constants\Role::ACL_ADMIN, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_BIDDERS);
    }

    /**
     * @param int|null $editorUserId
     * @return bool
     */
    public function isRunAllowed(?int $editorUserId): bool
    {
        $aclChecker = new AclChecker($editorUserId, Ui::new()->constructWebAdmin());
        return $aclChecker->isAllowed(Constants\Role::ACL_ADMIN, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_RUN);
    }

    /**
     * @param int|null $editorUserId
     * @return bool
     */
    public function isAuctioneerAllowed(?int $editorUserId): bool
    {
        $aclChecker = new AclChecker($editorUserId, Ui::new()->constructWebAdmin());
        return $aclChecker->isAllowed(Constants\Role::ACL_ADMIN, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_AUCTIONEER);
    }

    /**
     * @param int|null $editorUserId
     * @return bool
     */
    public function isProjectorAllowed(?int $editorUserId): bool
    {
        $aclChecker = new AclChecker($editorUserId, Ui::new()->constructWebAdmin());
        return $aclChecker->isAllowed(Constants\Role::ACL_ADMIN, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_PROJECTOR);
    }

    /**
     * @param int|null $editorUserId
     * @return bool
     */
    public function isBidIncrementsAllowed(?int $editorUserId): bool
    {
        $aclChecker = new AclChecker($editorUserId, Ui::new()->constructWebAdmin());
        return $aclChecker->isAllowed(Constants\Role::ACL_ADMIN, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_BID_INCREMENTS);
    }

    /**
     * @param int|null $editorUserId
     * @return bool
     */
    public function isBuyersPremiumAllowed(?int $editorUserId): bool
    {
        $aclChecker = new AclChecker($editorUserId, Ui::new()->constructWebAdmin());
        return $aclChecker->isAllowed(Constants\Role::ACL_ADMIN, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_BUYERS_PREMIUM);
    }

    /**
     * @param int|null $editorUserId
     * @return bool
     */
    public function isPermissionsAllowed(?int $editorUserId): bool
    {
        $aclChecker = new AclChecker($editorUserId, Ui::new()->constructWebAdmin());
        return $aclChecker->isAllowed(Constants\Role::ACL_ADMIN, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_PERMISSIONS);
    }

    /**
     * @param int|null $editorUserId
     * @return bool
     */
    public function isSettingsAllowed(?int $editorUserId): bool
    {
        $aclChecker = new AclChecker($editorUserId, Ui::new()->constructWebAdmin());
        return $aclChecker->isAllowed(Constants\Role::ACL_ADMIN, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_SETTINGS);
    }

    /**
     * @param int|null $editorUserId
     * @return bool
     */
    public function isSmsAllowed(?int $editorUserId): bool
    {
        $aclChecker = new AclChecker($editorUserId, Ui::new()->constructWebAdmin());
        return $aclChecker->isAllowed(Constants\Role::ACL_ADMIN, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_SMS);
    }

    /**
     * @param int|null $editorUserId
     * @return bool
     */
    public function isEmailAllowed(?int $editorUserId): bool
    {
        $aclChecker = new AclChecker($editorUserId, Ui::new()->constructWebAdmin());
        return $aclChecker->isAllowed(Constants\Role::ACL_ADMIN, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_EMAIL);
    }

    /**
     * @param int|null $editorUserId
     * @return bool
     */
    public function isShowImportAllowed(?int $editorUserId): bool
    {
        $aclChecker = new AclChecker($editorUserId, Ui::new()->constructWebAdmin());
        return $aclChecker->isAllowed(Constants\Role::ACL_ADMIN, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_SHOW_IMPORT);
    }

    /**
     * @param int|null $editorUserId
     * @return bool
     */
    public function isEnterBidsAllowed(?int $editorUserId): bool
    {
        $aclChecker = new AclChecker($editorUserId, Ui::new()->constructWebAdmin());
        return $aclChecker->isAllowed(Constants\Role::ACL_ADMIN, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_ENTER_BIDS);
    }

    /**
     * @param int|null $editorUserId
     * @return bool
     */
    public function isAuctionInvoiceAllowed(?int $editorUserId): bool
    {
        $aclChecker = new AclChecker($editorUserId, Ui::new()->constructWebAdmin());
        return $aclChecker->isAllowed(Constants\Role::ACL_ADMIN, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_AUCTION_INVOICE);
    }

    /**
     * @param int|null $editorUserId
     * @return bool
     */
    public function isAuctionReportsAllowed(?int $editorUserId): bool
    {
        $aclChecker = new AclChecker($editorUserId, Ui::new()->constructWebAdmin());
        return $aclChecker->isAllowed(Constants\Role::ACL_ADMIN, Constants\AdminRoute::C_MANAGE_AUCTIONS, Constants\AdminRoute::AMA_AUCTION_REPORTS);
    }

    public function isStackedTaxDesignationForInvoice(int $accountId): bool
    {
        return StackedTaxFeatureAvailabilityChecker::new()->isStackedTaxDesignationForInvoice($accountId);
    }
}
