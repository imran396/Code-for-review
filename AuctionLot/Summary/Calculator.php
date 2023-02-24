<?php
/**
 * Calculating useful summary of totals on each of the my items pages
 *
 * SAM-3015: RR Auctions - Totals at bottom of my items pages
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           15 Oct, 2015
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Summary;

use Sam\Bidding\ReservePrice\ReservePriceSimpleCheckerAwareTrait;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Entity\Model\LotItem\SellInfo\LotSellInfoPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Load\AdvancedSearchLotDto;

/**
 * Class Calculator
 * @package Sam\AuctionLot\Summary
 */
class Calculator extends CustomizableClass
{
    use CurrencyLoaderAwareTrait;
    use ReservePriceSimpleCheckerAwareTrait;
    use NumberFormatterAwareTrait;

    /** @var int|null */
    protected ?int $userId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Calculating total bidding on
     * @param AdvancedSearchLotDto[] $dtos
     * @return array
     */
    public function calcTotalBiddingOn(array $dtos): array
    {
        $biddingOn = [];
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        foreach ($dtos as $dto) {
            $currencyId = $dto->currency;
            if (!$currencyId) {
                continue;
            }
            $currentMaxBid = $dto->currentBid;
            $biddingOn[$currencyId] = $biddingOn[$currencyId] ?? 0;
            if (
                $dto->currentBidderId === $this->userId
                && $auctionLotStatusPureChecker->isActive($dto->lotStatusId)
            ) {
                $biddingOn[$currencyId] += $currentMaxBid;
            }
        }
        return $biddingOn;
    }

    /**
     * Calculating total max bid
     * @param AdvancedSearchLotDto[] $dtos
     * @return array
     */
    public function calcTotalMaxBid(array $dtos): array
    {
        $maxBids = [];
        foreach ($dtos as $dto) {
            $currencyId = $dto->currency;
            if (!$currencyId) {
                continue;
            }
            $maxBids[$currencyId] = $maxBids[$currencyId] ?? 0;
            if ($dto->currentBidderId === $this->userId) {
                $maxBids[$currencyId] += $dto->maxBid;
            }
        }
        return $maxBids;
    }

    /**
     * Calculating total won.
     * @param AdvancedSearchLotDto[] $dtos
     * @return array
     */
    public function calcTotalWon(array $dtos): array
    {
        $hammerPrices = [];
        foreach ($dtos as $dto) {
            $currencyId = $dto->currency;
            if (!$currencyId) {
                continue;
            }
            $hammerPrices[$currencyId] = $hammerPrices[$currencyId] ?? 0.;
            $hammerPrices[$currencyId] += (float)$dto->hammerPrice;
        }
        return $hammerPrices;
    }

    /**
     * Calculating total sold
     * @param AdvancedSearchLotDto[] $dtos
     * @return array
     */
    public function calcTotalSold(array $dtos): array
    {
        $hammerPricesPerCurrency = [];
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        $lotSellInfoPureChecker = LotSellInfoPureChecker::new();
        foreach ($dtos as $dto) {
            $currencyId = $dto->currency;
            if (!$currencyId) {
                continue;
            }
            $hammerPricesPerCurrency[$currencyId] = $hammerPricesPerCurrency[$currencyId] ?? 0;
            if (
                $lotSellInfoPureChecker->isHammerPrice($dto->hammerPrice)
                && $auctionLotStatusPureChecker->isAmongWonStatuses($dto->lotStatusId)
            ) {
                $hammerPricesPerCurrency[$currencyId] += $dto->hammerPrice;
            }
        }
        return $hammerPricesPerCurrency;
    }

    /**
     * Count Sold and Received lots
     * @param AdvancedSearchLotDto[] $dtos
     * @return int
     */
    public function countWonLots(array $dtos): int
    {
        $soldCounter = 0;
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        foreach ($dtos as $dto) {
            if ($auctionLotStatusPureChecker->isAmongWonStatuses($dto->lotStatusId)) {
                $soldCounter++;
            }
        }
        return $soldCounter;
    }

    /**
     * Count unsold lots
     * @param AdvancedSearchLotDto[] $dtos
     * @return int
     */
    public function countUnsoldLots(array $dtos): int
    {
        $unsoldCounter = 0;
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        foreach ($dtos as $dto) {
            if ($auctionLotStatusPureChecker->isUnsold($dto->lotStatusId)) {
                $unsoldCounter++;
            }
        }
        return $unsoldCounter;
    }

    /**
     * Calculating total current bid
     * @param AdvancedSearchLotDto[] $dtos
     * @return array
     */
    public function calcTotalCurrentBid(array $dtos): array
    {
        $currentBids = [];
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        foreach ($dtos as $dto) {
            $currencyId = $dto->currency;
            if (!$currencyId) {
                continue;
            }
            $currentBid = $dto->currentBid;
            $currentBids[$currencyId] = $currentBids[$currencyId] ?? 0;
            if ($auctionLotStatusPureChecker->isActive($dto->lotStatusId)) {
                $currentBids[$currencyId] += $currentBid;
            }
        }
        return $currentBids;
    }

    /**
     * Calculating net current bid
     * @param AdvancedSearchLotDto[] $dtos
     * @return array
     */
    public function calcNetTotalCurrentBid(array $dtos): array
    {
        $currentNetBids = [];
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        foreach ($dtos as $dto) {
            $currencyId = $dto->currency;
            if (!$currencyId) {
                continue;
            }
            $currentBid = $dto->currentBid;
            $currentNetBids[$currencyId] = $currentNetBids[$currencyId] ?? 0;
            if ($auctionLotStatusPureChecker->isActive($dto->lotStatusId)) {
                $isMeetReserve = $this->getReservePriceSimpleChecker()
                    ->meet($currentBid, $dto->reservePrice, $dto->isAuctionReverse);
                if (!$dto->reservePrice) {
                    $currentNetBids[$currencyId] += $currentBid;
                } elseif ($isMeetReserve) {
                    $currentNetBids[$currencyId] += $currentBid;
                }
            }
        }
        return $currentNetBids;
    }

    /**
     * Formatting result for different currency
     * @param array $totalBiddingOn
     * @return string
     */
    public function render(array $totalBiddingOn): string
    {
        $output = '';
        if (empty($totalBiddingOn)) {
            return $output;
        }
        $totalCountByCurrencies = [];
        foreach ($totalBiddingOn as $currencyId => $total) {
            $currency = $this->getCurrencyLoader()->load((int)$currencyId);
            $currencySign = $currency->Sign ?? '';
            $totalCountByCurrencies[] = $currencySign . $this->getNumberFormatter()->formatMoney($total);
        }
        $output = implode(',', $totalCountByCurrencies);
        return $output;
    }

    /**
     * @param int|null $userId . It can be null for absent user
     * @return static
     */
    public function setUserId(?int $userId): static
    {
        $this->userId = $userId;
        return $this;
    }

}
