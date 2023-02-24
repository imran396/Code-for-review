<?php
/**
 * SAM-6790: Validations at controller layer for v3.5 - ManageAuctionsController
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           06-28, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Auction\Validate\Internal\Load;

use Admin;
use Auction;
use Sam\Account\Validate\AccountExistenceChecker;
use Sam\Auction\AuctionHelper;
use Sam\Auction\Load\AuctionLoader;
use Sam\AuctionLot\Validate\AuctionLotExistenceChecker;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Load\UserLoader;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;

/**
 * Class DataProvider
 * @package Sam\Application\Controller\Admin\Auction\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use EditorUserAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadAuction(?int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        return AuctionLoader::new()->load($auctionId, $isReadOnlyDb);
    }

    public function isAuctionAccountAvailable(int $accountId, bool $isReadOnlyDb = false): bool
    {
        return AccountExistenceChecker::new()->existById($accountId, $isReadOnlyDb);
    }

    public function isAuctionLotAvailable(int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): bool
    {
        return AuctionLotExistenceChecker::new()->exist($lotItemId, $auctionId, $isReadOnlyDb);
    }

    /**
     * @param int $systemAccountId
     * @return string[]
     */
    public function getAvailableAuctionTypes(int $systemAccountId): array
    {
        return AuctionHelper::new()->getAvailableTypes($systemAccountId);
    }

    public function loadAdmin(int $userId, $isReadOnlyDb): ?Admin
    {
        return UserLoader::new()->loadAdmin($userId, $isReadOnlyDb);
    }

    public function hasSubPrivilegeForDeleteAuction(): bool
    {
        return AdminPrivilegeChecker::new()->hasSubPrivilegeForDeleteAuction();
    }

    public function hasSubPrivilegeForManageAllAuctions(): bool
    {
        return AdminPrivilegeChecker::new()->hasSubPrivilegeForManageAllAuctions();
    }

    public function equalEditorUserId(?int $editorUserId): bool
    {
        return $this->getEditorUserId() === $editorUserId;
    }
}
