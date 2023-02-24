<?php
/**
 *  Piecemeal lot hammer price calculator based on bulk master lot distribution option.
 *  If distribution option is "EQUALLY" then distrubte master lot current bid equall into piecemeal lots
 *  If distribution option is "WINNING" then distribute master lot current bid according to winning bid distribution rules
 *  SAM-5379: Bulk vs piecemeal restrict to force bid and next bid only, remove change max bid option
 *  https://bidpath.atlassian.net/browse/SAM-5379
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           May 15, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\BulkGroup\Calculate;

use AuctionLotItem;
use Sam\Bidding\BidTransaction\Load\BidTransactionLoaderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionLotAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class HammerPriceCalculator
 * @package Sam\AuctionLot\BulkGroup
 */
class LotBulkGroupPiecemealHpCalculator extends CustomizableClass
{
    use AuctionLotAwareTrait;
    use BidTransactionLoaderCreateTrait;
    use LotItemLoaderAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * @var AuctionLotItem[]
     */
    protected array $piecemealLots;
    /**
     * @var array
     */
    protected array $piecemealLotHammerPrices = [];
    protected float $masterWinningBid;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AuctionLotItem $masterAuctionLot
     * @param float $masterWinningBid
     * @param AuctionLotItem[] $piecemealAuctionLots
     * @return $this
     */
    public function construct(
        AuctionLotItem $masterAuctionLot,
        float $masterWinningBid,
        array $piecemealAuctionLots
    ): static {
        $this->setAuctionLot($masterAuctionLot);
        $this->masterWinningBid = $masterWinningBid;
        $this->piecemealLots = $piecemealAuctionLots;
        return $this;
    }

    /**
     * Calculate hammer prices for every piecemeal lot and store in service state
     */
    public function calculate(): void
    {
        $distribution = $this->getAuctionLot()->BulkMasterWinBidDistribution;
        if ($distribution === Constants\LotBulkGroup::BMWBD_EQUALLY) {
            $this->calculateHammerPricesEqually();
        }
        if ($distribution === Constants\LotBulkGroup::BMWBD_WINNING) {
            if (!$this->hasCurrentBidAmongPiecemealLots()) {
                $this->calculateHammerPricesEqually();
            } else {
                $this->calculateWinningHammerPrices();
            }
        }
    }

    /**
     * Provide calculative hammer price by using lot item id
     * @param int $lotItemId
     * @return int|null
     */
    public function getHammerPriceByLotItemId(int $lotItemId): ?int
    {
        return $this->piecemealLotHammerPrices[$lotItemId];
    }

    /**
     * @return array
     */
    public function getHammerPrices(): array
    {
        return $this->piecemealLotHammerPrices;
    }

    /**
     * Calculate piecemeal lots hammer price and distribute equally into them and add discrepancy with last lot.
     */
    protected function calculateHammerPricesEqually(): void
    {
        $piecemealLotsCount = count($this->piecemealLots);
        $bulkPieceHp = $piecemealLotsCount > 0 ? floor($this->masterWinningBid / $piecemealLotsCount) : 0;
        $totalHammerPrice = 0;
        foreach ($this->piecemealLots as $index => $pieceLot) {
            $this->piecemealLotHammerPrices[$pieceLot->LotItemId] = $bulkPieceHp;
            $totalHammerPrice += $this->piecemealLotHammerPrices[$pieceLot->LotItemId];
            if ($index === $piecemealLotsCount - 1) {
                $this->piecemealLotHammerPrices[$pieceLot->LotItemId] += ($this->masterWinningBid - $totalHammerPrice);
            }
        }
    }

    /**
     * Calculate piecemeal lots hammer price and distribute it to winning piecemeal lots and add discrepancy with last lot
     */
    protected function calculateWinningHammerPrices(): void
    {
        $totalHammerPrice = 0.;
        $currentBidSum = $this->calculateCurrentBidSum();
        $lastLotItemId = null;
        foreach ($this->piecemealLots as $pieceLot) {
            if ($pieceLot->CurrentBidId) {
                $bidTransaction = $this->createBidTransactionLoader()->loadById($pieceLot->CurrentBidId);
                $price = 0.;
                if (
                    $bidTransaction
                    && $bidTransaction->Bid
                    && $this->isReserveMeet($pieceLot->LotItemId, $bidTransaction->Bid)
                ) {
                    $price = floor(($this->masterWinningBid / $currentBidSum) * $bidTransaction->Bid);
                    $lastLotItemId = $pieceLot->LotItemId; // assigning last lot item id for adding discrepancy
                }
                $this->piecemealLotHammerPrices[$pieceLot->LotItemId] = $price;
            } else {
                $this->piecemealLotHammerPrices[$pieceLot->LotItemId] = 0.;
            }
            $totalHammerPrice += $this->piecemealLotHammerPrices[$pieceLot->LotItemId];
        }
        if ($lastLotItemId) {
            $this->piecemealLotHammerPrices[$lastLotItemId] += ($this->masterWinningBid - $totalHammerPrice);
        }
    }

    /**
     * Calculate total of current bids for all piecemeal lots
     * @return float
     */
    protected function calculateCurrentBidSum(): float
    {
        $totalCurrentBid = 0.;
        foreach ($this->piecemealLots as $auctionLot) {
            $bidTransaction = $this->createBidTransactionLoader()->loadById($auctionLot->CurrentBidId);
            if (
                $bidTransaction
                && $this->isReserveMeet($auctionLot->LotItemId, $bidTransaction->Bid)
            ) {
                $totalCurrentBid += $bidTransaction->Bid;
            }
        }
        return $totalCurrentBid;
    }

    /**
     * Checks, if any of piecemeal lots has current bid
     * @return bool
     */
    protected function hasCurrentBidAmongPiecemealLots(): bool
    {
        foreach ($this->piecemealLots as $auctionLot) {
            $bidTransaction = $this->createBidTransactionLoader()->loadById($auctionLot->CurrentBidId);
            if (
                $bidTransaction
                && $bidTransaction->Bid
                && $this->isReserveMeet($auctionLot->LotItemId, $bidTransaction->Bid)
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check current bid meet the reserve or not
     * @param int $lotItemId
     * @param float $currentBid
     * @return bool
     */
    protected function isReserveMeet(int $lotItemId, float $currentBid): bool
    {
        $lotItem = $this->getLotItemLoader()->load($lotItemId, true);
        if (!$lotItem) {
            log_error("Available lot item not found, when checking reserve meet" . composeSuffix(['ali' => $lotItemId]));
            return false;
        }
        if (!$lotItem->ReservePrice) {
            return true;
        }
        return $lotItem->ReservePrice <= $currentBid;
    }
}
