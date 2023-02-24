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

namespace Sam\BuyersPremium\Calculate\Internal\Load;

use BuyersPremiumRange;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\BuyersPremiumRange\BuyersPremiumRangeReadRepository;
use Sam\Storage\ReadRepository\Entity\BuyersPremiumRange\BuyersPremiumRangeReadRepositoryCreateTrait;

/**
 * Class DataProvider
 * @package Sam\BuyersPremium\Calculate\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use BuyersPremiumRangeReadRepositoryCreateTrait;

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    public function loadAllBpRangesForLot(int $lotItemId, float $amount, bool $isReadOnlyDb = false): array
    {
        return $this->buildBuyersPremiumRangeReadRepositoryForLot($lotItemId, $amount, $isReadOnlyDb)
            ->loadEntities();
    }

    public function loadFirstBpRangeForLot(int $lotItemId, float $amount, bool $isReadOnlyDb = false): ?BuyersPremiumRange
    {
        return $this->buildBuyersPremiumRangeReadRepositoryForLot($lotItemId, $amount, $isReadOnlyDb)
            ->loadEntity();
    }

    public function loadAllBpRangesForUser(int $userId, int $accountId, string $auctionType, float $amount, bool $isReadOnlyDb = false): array
    {
        return $this->buildBuyersPremiumRangeReadRepositoryForUser($userId, $accountId, $auctionType, $amount, $isReadOnlyDb)
            ->loadEntities();
    }

    public function loadFirstBpRangeForUser(int $userId, int $accountId, string $auctionType, float $amount, bool $isReadOnlyDb = false): ?BuyersPremiumRange
    {
        return $this->buildBuyersPremiumRangeReadRepositoryForUser($userId, $accountId, $auctionType, $amount, $isReadOnlyDb)
            ->loadEntity();
    }

    public function loadAllBpRangesForAuction(int $auctionId, float $amount, bool $isReadOnlyDb = false): array
    {
        return $this->buildBuyersPremiumRangeReadRepositoryForAuction($auctionId, $amount, $isReadOnlyDb)
            ->loadEntities();
    }

    public function loadFirstBpRangeForAuction(int $auctionId, float $amount, bool $isReadOnlyDb = false): ?BuyersPremiumRange
    {
        return $this->buildBuyersPremiumRangeReadRepositoryForAuction($auctionId, $amount, $isReadOnlyDb)
            ->loadEntity();
    }

    public function loadAllBpRangesForNamedRule(int $buyersPremiumId, float $amount, bool $isReadOnlyDb = false): array
    {
        return $this->buildBuyersPremiumRangeReadRepositoryForNamedRule($buyersPremiumId, $amount, $isReadOnlyDb)
            ->loadEntities();
    }

    public function loadFirstBpRangesForNamedRule(int $buyersPremiumId, float $amount, bool $isReadOnlyDb = false): ?BuyersPremiumRange
    {
        return $this->buildBuyersPremiumRangeReadRepositoryForNamedRule($buyersPremiumId, $amount, $isReadOnlyDb)
            ->loadEntity();
    }

    protected function buildBuyersPremiumRangeReadRepositoryForLot(
        int $lotItemId,
        float $amount,
        bool $isReadOnlyDb = false
    ): BuyersPremiumRangeReadRepository {
        return $this->createBuyersPremiumRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotItemId($lotItemId)
            ->filterAmountLessOrEqual($amount)
            ->orderByAmount(false);
    }

    protected function buildBuyersPremiumRangeReadRepositoryForUser(
        int $userId,
        int $accountId,
        string $auctionType,
        float $amount,
        bool $isReadOnlyDb = false
    ): BuyersPremiumRangeReadRepository {
        return $this->createBuyersPremiumRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterUserId($userId)
            ->filterAccountId($accountId)
            ->filterAuctionType($auctionType)
            ->filterAmountLessOrEqual($amount)
            ->orderByAmount(false);
    }

    protected function buildBuyersPremiumRangeReadRepositoryForAuction(
        int $auctionId,
        float $amount,
        bool $isReadOnlyDb = false
    ): BuyersPremiumRangeReadRepository {
        return $this->createBuyersPremiumRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterAmountLessOrEqual($amount)
            ->orderByAmount(false);
    }

    protected function buildBuyersPremiumRangeReadRepositoryForNamedRule(
        int $buyersPremiumId,
        float $amount,
        bool $isReadOnlyDb = false
    ): BuyersPremiumRangeReadRepository {
        return $this->createBuyersPremiumRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterBuyersPremiumId($buyersPremiumId)
            ->filterAmountLessOrEqual($amount)
            ->orderByAmount(false);
    }
}
