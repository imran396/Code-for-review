<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Load;

use BuyersPremiumRange;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\BuyersPremiumRange\BuyersPremiumRangeReadRepositoryCreateTrait;

/**
 * Class BuyersPremiumRangeLoader
 * @package Sam\BuyersPremium\Load
 */
class BuyersPremiumRangeLoader extends CustomizableClass
{
    use BuyersPremiumRangeReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load a BuyersPremiumRange from PK Info
     *
     * @param int|null $id
     * @param bool $isReadOnlyDb
     * @return BuyersPremiumRange|null
     */
    public function loadById(?int $id, bool $isReadOnlyDb = false): ?BuyersPremiumRange
    {
        if (!$id) {
            return null;
        }
        $buyersPremiumRange = $this->createBuyersPremiumRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($id)
            ->loadEntity();
        return $buyersPremiumRange;
    }

    /**
     * Load auction's, lotItem's or user's BuyersPremiumRange.
     * User's BuyersPremiumRange is defined as $accountId + $auctionType + $userId
     * @param int|null $auctionId
     * @param int|null $lotItemId
     * @param int|null $accountId
     * @param string|null $auctionType
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return BuyersPremiumRange[]
     */
    public function load(
        ?int $auctionId = null,
        ?int $lotItemId = null,
        ?int $accountId = null,
        ?string $auctionType = null,
        ?int $userId = null,
        bool $isReadOnlyDb = false
    ): array {
        if ($auctionId) {
            return $this->loadBpRangeByAuctionId($auctionId, $isReadOnlyDb);
        }
        if ($lotItemId) {
            return $this->loadBpRangeByLotItemId($lotItemId, $isReadOnlyDb);
        }
        if ($userId) {
            return $this->loadBpRangeByUserId($userId, $accountId, $auctionType ?? '', $isReadOnlyDb);
        }
        return [];
    }

    /**
     * @param int $bpId buyers_premium.id
     * @param bool $isReadOnlyDb
     * @return BuyersPremiumRange[]
     */
    public function loadBpRangeByBpId(int $bpId, bool $isReadOnlyDb = false): array
    {
        return $this->createBuyersPremiumRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterBuyersPremiumId($bpId)
            ->orderByAmount()
            ->loadEntities();
    }

    /**
     * @param int $auctionId auction.id
     * @param bool $isReadOnlyDb
     * @return BuyersPremiumRange[]
     */
    public function loadBpRangeByAuctionId(int $auctionId, bool $isReadOnlyDb = false): array
    {
        return $this->createBuyersPremiumRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->orderByAmount()
            ->loadEntities();
    }

    /**
     * @param int $lotItemId lot_item.id
     * @param bool $isReadOnlyDb
     * @return BuyersPremiumRange[]
     */
    public function loadBpRangeByLotItemId(int $lotItemId, bool $isReadOnlyDb = false): array
    {
        return $this->createBuyersPremiumRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotItemId($lotItemId)
            ->orderByAmount()
            ->loadEntities();
    }

    /**
     * @param int $userId user.id
     * @param int $accountId
     * @param string $auctionType
     * @param bool $isReadOnlyDb
     * @return BuyersPremiumRange[]
     */
    public function loadBpRangeByUserId(
        int $userId,
        int $accountId,
        string $auctionType,
        bool $isReadOnlyDb = false
    ): array {
        return $this->createBuyersPremiumRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterUserId($userId)
            ->filterAccountId($accountId)
            ->filterAuctionType($auctionType)
            ->orderByAmount()
            ->loadEntities();
    }
}
