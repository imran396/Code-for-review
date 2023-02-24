<?php
/**
 * SAM-10464: Separate BP manager to services
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Validate;

use Auction;
use Bidder;
use LotItem;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\BuyersPremiumRange\BuyersPremiumRangeReadRepositoryCreateTrait;

/**
 * Class BuyersPremiumRangeExistenceChecker
 * @package Sam\BuyersPremium\Validate
 */
class BuyersPremiumRangeExistenceChecker extends CustomizableClass
{
    use BuyersPremiumRangeReadRepositoryCreateTrait;

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    /**
     * @param LotItem|null $lotItem
     * @param bool $isLiveOrHybrid
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function hasIndividualBpForLot(
        ?LotItem $lotItem,
        bool $isLiveOrHybrid,
        bool $isReadOnlyDb = false
    ): bool {
        if (!$lotItem) {
            return false;
        }

        $isFound = $this->existByLotItemId($lotItem->Id, $isReadOnlyDb);
        if ($isFound) {
            return true;
        }

        $has = $isLiveOrHybrid
            && Floating::gteq($lotItem->AdditionalBpInternet, 0.)
            && $lotItem->AdditionalBpInternet !== null;
        return $has;
    }

    /**
     * @param Bidder|null $bidder
     * @param int|null $userDirectAccountId
     * @param string $auctionType
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function hasIndividualBpForUser(
        ?Bidder $bidder,
        ?int $userDirectAccountId,
        string $auctionType,
        bool $isReadOnlyDb = false
    ): bool {
        if (!$bidder) {
            return false;
        }

        $isFound = $this->existByUserIdAndAuctionType(
            $bidder->UserId,
            $auctionType,
            $userDirectAccountId,
            $isReadOnlyDb
        );
        if ($isFound) {
            return true;
        }

        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $has = (
                $auctionStatusPureChecker->isLive($auctionType)
                && Floating::gteq($bidder->AdditionalBpInternetLive, 0.)
                && $bidder->AdditionalBpInternetLive !== null
            ) || (
                $auctionStatusPureChecker->isHybrid($auctionType)
                && Floating::gteq($bidder->AdditionalBpInternetHybrid, 0.)
                && $bidder->AdditionalBpInternetHybrid !== null
            );
        return $has;
    }

    /**
     * @param Auction $auction
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function hasIndividualBpForAuction(Auction $auction, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->existByAuctionId($auction->Id, $isReadOnlyDb);
        if ($isFound) {
            return true;
        }

        $has = $auction->isLiveOrHybrid()
            && Floating::gteq($auction->AdditionalBpInternet, 0.)
            && $auction->AdditionalBpInternet !== null;
        return $has;
    }

    /**
     * Count individual BP ranges at lot level
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function countByLotItemId(int $lotItemId, bool $isReadOnlyDb = false): int
    {
        return $this->createBuyersPremiumRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotItemId($lotItemId)
            ->count();
    }

    /**
     * Count individual BP ranges at user level for auction type by account
     * @param int $userId
     * @param int $accountId
     * @param string $auctionType
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function countByUserId(int $userId, int $accountId, string $auctionType, bool $isReadOnlyDb = false): int
    {
        return $this->createBuyersPremiumRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterUserId($userId)
            ->filterAccountId($accountId)
            ->filterAuctionType($auctionType)
            ->count();
    }

    /**
     * Count individual BP ranges at auction level
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function countByAuctionId(int $auctionId, bool $isReadOnlyDb = false): int
    {
        return $this->createBuyersPremiumRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->count();
    }

    /**
     * Count named BP ranges referenced by BuyersPremium->Id
     * @param int $bpId buyers_premium.id
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function countByBpId(int $bpId, bool $isReadOnlyDb = false): int
    {
        return $this->createBuyersPremiumRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterBuyersPremiumId($bpId)
            ->count();
    }

    /**
     * Check if Individual BP ranges exist at lot level.
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByLotItemId(int $lotItemId, bool $isReadOnlyDb = false): bool
    {
        return $this->createBuyersPremiumRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotItemId($lotItemId)
            ->exist();
    }

    /**
     * Check if Individual BP ranges exist at user level for auction type and account (direct user's account).
     * @param int $userId
     * @param string $auctionType
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByUserIdAndAuctionType(int $userId, string $auctionType, int $accountId, bool $isReadOnlyDb = false): bool
    {
        return $this->createBuyersPremiumRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterUserId($userId)
            ->filterAccountId($accountId)
            ->filterAuctionType($auctionType)
            ->exist();
    }

    /**
     *  Check if Individual BP ranges exist at auction level.
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByAuctionId(int $auctionId, bool $isReadOnlyDb = false): bool
    {
        return $this->createBuyersPremiumRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->exist();
    }

    public function existByBpId(int $bpId, bool $isReadOnlyDb = false): bool
    {
        return $this->createBuyersPremiumRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterBuyersPremiumId($bpId)
            ->exist();
    }

    /**
     * @param float $amount
     * @param int $bpId buyers_premium.id
     * @param bool $isReadOnlyDb
     * @return bool $isExist
     */
    public function existAmountInGlobalBp(float $amount, int $bpId, bool $isReadOnlyDb = false): bool
    {
        return $this->existAmount($amount, ['BpId' => $bpId], $isReadOnlyDb);
    }

    /**
     * @param float $amount
     * @param int $lotItemId lot_item.id
     * @param bool $isReadOnlyDb
     * @return bool $isExist
     */
    public function existAmountInLotItemBp(float $amount, int $lotItemId, bool $isReadOnlyDb = false): bool
    {
        return $this->existAmount($amount, ['LotItemId' => $lotItemId], $isReadOnlyDb);
    }

    /**
     * @param float $amount
     * @param int $auctionId auction.id
     * @param bool $isReadOnlyDb
     * @return bool $isExist
     */
    public function existAmountInAuctionBp(float $amount, int $auctionId, bool $isReadOnlyDb = false): bool
    {
        return $this->existAmount($amount, ['AuctionId' => $auctionId], $isReadOnlyDb);
    }

    /**
     * @param float $amount
     * @param int $userId user.id
     * @param bool $isReadOnlyDb
     * @return bool $isExist
     */
    public function existAmountInUserBp(float $amount, int $userId, bool $isReadOnlyDb = false): bool
    {
        return $this->existAmount($amount, ['UserId' => $userId], $isReadOnlyDb);
    }

    /**
     * @param float $amount
     * @param array $filterParams
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existAmount(float $amount, array $filterParams = [], bool $isReadOnlyDb = false): bool
    {
        $bprRepository = $this->createBuyersPremiumRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAmount($amount);
        if (isset($filterParams['AccountId'])) {
            $bprRepository->filterAccountId($filterParams['AccountId']);
        }
        if (isset($filterParams['AuctionType'])) {
            $bprRepository->filterAuctionType((string)$filterParams['AuctionType']);
        }
        if (isset($filterParams['BpId'])) {
            $bprRepository->filterBuyersPremiumId($filterParams['BpId']);
        }
        if (isset($filterParams['LotItemId'])) {
            $bprRepository->filterLotItemId($filterParams['LotItemId']);
        }
        if (isset($filterParams['AuctionId'])) {
            $bprRepository->filterAuctionId($filterParams['AuctionId']);
        }
        if (isset($filterParams['UserId'])) {
            $bprRepository->filterUserId($filterParams['UserId']);
        }

        $isFound = $bprRepository->exist();
        return $isFound;
    }
}
