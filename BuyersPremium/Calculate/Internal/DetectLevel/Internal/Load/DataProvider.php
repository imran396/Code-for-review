<?php
/**
 * SAM-10463: Refactor BP calculator for v3-7 and cover with unit tests
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Calculate\Internal\DetectLevel\Internal\Load;

use Auction;
use Bidder;
use BuyersPremium;
use LotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\BuyersPremium\Load\BuyersPremiumLoaderCreateTrait;
use Sam\BuyersPremium\Validate\BuyersPremiumRangeExistenceCheckerCreateTrait;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class DataProvider
 * @package Sam\BuyersPremium\Calculate\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use BuyersPremiumRangeExistenceCheckerCreateTrait;
    use BuyersPremiumLoaderCreateTrait;
    use LotItemLoaderAwareTrait;
    use UserLoaderAwareTrait;

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    public function loadLotItem(int $lotItemId, bool $isReadOnlyDb = false): ?LotItem
    {
        return $this->getLotItemLoader()->load($lotItemId, $isReadOnlyDb);
    }

    public function loadAuction(?int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        return $this->getAuctionLoader()->load($auctionId, $isReadOnlyDb);
    }

    public function loadBuyersPremiumForLot(int $buyersPremiumId, bool $isReadOnlyDb = false): ?BuyersPremium
    {
        return $this->createBuyersPremiumLoader()->load($buyersPremiumId, $isReadOnlyDb);
    }

    public function loadBidder(int $userId, bool $isReadOnlyDb = false): ?Bidder
    {
        return $this->getUserLoader()->loadBidder($userId, $isReadOnlyDb);
    }

    public function loadUserDirectAccountId(int $userId, bool $isReadOnlyDb = false): ?int
    {
        $row = $this->getUserLoader()->loadSelected(['account_id'], $userId, $isReadOnlyDb);
        $accountId = Cast::toInt($row['account_id'] ?? null);
        return $accountId;
    }

    public function loadBuyersPremiumForUser(int $buyersPremiumId, bool $isReadOnlyDb = false): ?BuyersPremium
    {
        return $this->createBuyersPremiumLoader()->load($buyersPremiumId, $isReadOnlyDb);
    }

    public function loadBuyersPremiumForAuction(int $buyersPremiumId, bool $isReadOnlyDb = false): ?BuyersPremium
    {
        return $this->createBuyersPremiumLoader()->load($buyersPremiumId, $isReadOnlyDb);
    }

    public function loadBuyersPremiumForAuctionType(string $auctionType, int $accountId, bool $isReadOnlyDb = false): ?BuyersPremium
    {
        return $this->createBuyersPremiumLoader()->loadByShortNameAndAccount($auctionType, $accountId, $isReadOnlyDb);
    }

    public function hasIndividualBpForLot(?LotItem $lotItem, bool $isLiveOrHybrid, bool $isReadOnlyDb = false): bool
    {
        return $this->createBuyersPremiumRangeExistenceChecker()->hasIndividualBpForLot($lotItem, $isLiveOrHybrid, $isReadOnlyDb);
    }

    public function hasIndividualBpForUser(?Bidder $bidder, ?int $userDirectAccountId, string $auctionType, bool $isReadOnlyDb = false): bool
    {
        return $this->createBuyersPremiumRangeExistenceChecker()->hasIndividualBpForUser($bidder, $userDirectAccountId, $auctionType, $isReadOnlyDb);
    }

    public function hasIndividualBpForAuction(Auction $auction, bool $isReadOnlyDb = false): bool
    {
        return $this->createBuyersPremiumRangeExistenceChecker()->hasIndividualBpForAuction($auction, $isReadOnlyDb);
    }
}
