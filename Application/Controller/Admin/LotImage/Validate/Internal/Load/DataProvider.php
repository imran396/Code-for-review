<?php
/**
 * SAM-6793: Validations at controller layer for v3.5 - LotImageControllerValidator at admin site
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 31, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\LotImage\Validate\Internal\Load;

use Auction;
use Sam\Account\Validate\AccountExistenceChecker;
use Sam\Auction\Load\AuctionLoader;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;

/**
 * Class DataProvider
 * @package Sam\Application\Controller\Admin\LotImage\Validate\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }


    /**
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return Auction|null
     */
    public function loadAuction(?int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        return AuctionLoader::new()->load($auctionId, $isReadOnlyDb);
    }

    /**
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isAccountAvailable(int $accountId, bool $isReadOnlyDb = false): bool
    {
        return AccountExistenceChecker::new()->existById($accountId, $isReadOnlyDb);
    }

    /**
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function hasPrivilegeForManageLotImage(?int $userId, bool $isReadOnlyDb = false): bool
    {
        $admiPrivilegeChecker = AdminPrivilegeChecker::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->initByUserId($userId);
        return $admiPrivilegeChecker->hasPrivilegeForManageInventory()
            || $admiPrivilegeChecker->hasPrivilegeForManageAuctions();
    }
}
