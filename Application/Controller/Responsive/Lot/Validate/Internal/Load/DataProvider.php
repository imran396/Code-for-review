<?php
/**
 * SAM-6798: Validations at controller layer for v3.5 - LotControllerValidator at responsive site
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           07 Apr 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Lot\Validate\Internal\Load;

use Auction;
use AuctionLotItem;
use LotItem;
use Sam\Account\DomainAuctionVisibility\VisibilityChecker;
use Sam\Auction\Load\AuctionLoader;
use Sam\AuctionLot\Load\AuctionLotLoader;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoader;

/**
 * Class DataProvider
 * @package Sam\Application\Controller\Responsive\Lot\Internal\Load
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
     * @param int|null $auctionId null leads to null result
     * @param bool $isReadOnlyDb
     * @return Auction|null
     */
    public function loadAuction(?int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        return AuctionLoader::new()
            ->filterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->load($auctionId, $isReadOnlyDb);
    }

    /**
     * @param int|null $lotItemId null leads to null result
     * @param int|null $auctionId null leads to null result
     * @param bool $isReadOnlyDb
     * @return AuctionLotItem|null
     */
    public function loadAuctionLotItem(?int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): ?AuctionLotItem
    {
        return AuctionLotLoader::new()->load($lotItemId, $auctionId, $isReadOnlyDb);
    }

    /**
     * @param int|null $lotItemId null leads to null result
     * @param bool $isReadOnlyDb
     * @return LotItem|null
     */
    public function loadLotItem(?int $lotItemId, bool $isReadOnlyDb = false): ?LotItem
    {
        return LotItemLoader::new()->load($lotItemId, $isReadOnlyDb);
    }

    /**
     * @param Auction $auction
     * @return bool
     */
    public function isAllowedForAuction(Auction $auction): bool
    {
        return VisibilityChecker::new()->isAllowedForAuction($auction);
    }
}
