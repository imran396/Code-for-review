<?php
/**
 * Flexible starting bid calculator.
 * It is used for Live/Hybrid auctions only.
 *
 * SAM-3784: Flexible Starting Bid calculation refactoring https://bidpath.atlassian.net/browse/SAM-3784
 *
 * @author        Igors Kotlevskis
 * @since         Jun 10, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidding\StartingBid;

use AbsenteeBid;
use Exception;
use Sam\Bidding\AbsenteeBid\Detect\HighAbsenteeBidDetectorCreateTrait;
use Sam\Bidding\AskingBid\AskingBidDetectorCreateTrait;
use Sam\Bidding\BidIncrement\Load\BidIncrementLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;

/**
 * Class FlexibleStartingBidCalculator
 * @package Sam\Bidding\StartingBid
 */
class FlexibleStartingBidCalculator extends CustomizableClass
{
    use AskingBidDetectorCreateTrait;
    use AuctionAwareTrait;
    use BidIncrementLoaderAwareTrait;
    use HighAbsenteeBidDetectorCreateTrait;
    use LotItemAwareTrait;

    public const UNKNOWN = -1;
    public const NO_ABSENTEE_NO_RESERVE = 0;
    public const HIGH_BID_GREATER_INITIAL_SECOND_BID_LOWER_INITIAL = 1;
    public const BIDS_LOWER_INITIAL = 2;
    public const NO_BIDS_RESERVE_EXISTS = 3;
    public const BIDS_GREATER_INITIAL = 4;
    public const EQUAL_BIDS_GREATER_INITIAL = 5;
    public const HIGH_ABSENTEE_LOWER_OR_EQUAL_INITIAL = 6;


    protected ?float $highAbsentee = null;
    protected ?float $secondAbsentee = null;
    protected ?float $reservePrice = null;
    protected ?float $suggestedStartingBid = null;
    protected ?float $initialPrice = null;
    protected ?AbsenteeBid $highAbsenteeBid = null;
    protected ?AbsenteeBid $secondAbsenteeBid = null;
    protected int $calculationWay = self::UNKNOWN;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Calculating flexible starting bid.
     * @return float
     */
    public function calculate(): float
    {
        // $this->init();
        $calculationWay = $this->getCalculationWay();
        $flexibleStartingBid = $this->getSuggestedStartingBid();

        if ($calculationWay === self::NO_ABSENTEE_NO_RESERVE) {
            // if there are no reserve price and absentee bids, the system should again fall
            // back on the starting bid
        } else {
            if ($calculationWay === self::HIGH_BID_GREATER_INITIAL_SECOND_BID_LOWER_INITIAL) {
                $flexibleStartingBid = $this->calcForHighBidGreaterInitialSecondBidLowerInitial();
            } elseif ($calculationWay === self::BIDS_LOWER_INITIAL) {
                $flexibleStartingBid = $this->calcForBidsLowerInitial();
            } elseif ($calculationWay === self::NO_BIDS_RESERVE_EXISTS) {
                $flexibleStartingBid = $this->calcForNoBidsReserveExists();
            } elseif ($calculationWay === self::BIDS_GREATER_INITIAL) {
                $flexibleStartingBid = $this->calcForBidsGreaterInitial();
            } elseif ($calculationWay === self::EQUAL_BIDS_GREATER_INITIAL) {
                $flexibleStartingBid = $this->calcForEqualBidsGreaterInitial();
            } elseif ($calculationWay === self::HIGH_ABSENTEE_LOWER_OR_EQUAL_INITIAL) {
                $flexibleStartingBid = $this->calcForHighAbsenteeLowerOrEqualInitial();
            }
        }

        return $flexibleStartingBid;
    }

    /**
     * @return int
     * @internal
     */
    public function getCalculationWay(): int
    {
        if ($this->calculationWay === self::UNKNOWN) {
            $this->calculationWay = $this->detectCalculationWay();
        }
        return $this->calculationWay;
    }

    /**
     * @return float
     */
    protected function getHighAbsentee(): float
    {
        if ($this->highAbsentee === null) {
            [$highAbsenteeBid,] = $this->getHighAndSecondAbsenteeBids();
            $this->highAbsentee = $highAbsenteeBid
                ? $this->createAskingBidDetector()->detectQuantizedBid(
                    $highAbsenteeBid->MaxBid,
                    false,
                    $this->getLotItemId(),
                    $this->getAuctionId()
                )
                : 0.;
        }
        return $this->highAbsentee;
    }

    /**
     * @param float $highAbsentee
     * @return static
     * @internal
     */
    public function setHighAbsentee(float $highAbsentee): static
    {
        $this->highAbsentee = Cast::toFloat($highAbsentee, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return array{0: AbsenteeBid|null, 1: AbsenteeBid|null}
     */
    protected function getHighAndSecondAbsenteeBids(): array
    {
        if ($this->highAbsenteeBid === null) {
            [$this->highAbsenteeBid, $this->secondAbsenteeBid] = $this->createHighAbsenteeBidDetector()
                ->detectTwoHighest($this->getLotItemId(), $this->getAuctionId());
        }
        return [$this->highAbsenteeBid, $this->secondAbsenteeBid];
    }

    /**
     * @return float
     */
    protected function getSecondAbsentee(): float
    {
        if ($this->secondAbsentee === null) {
            [, $secondAbsenteeBid] = $this->getHighAndSecondAbsenteeBids();
            $this->secondAbsentee = $secondAbsenteeBid
                ? $this->createAskingBidDetector()->detectQuantizedBid(
                    $secondAbsenteeBid->MaxBid,
                    false,
                    $this->getLotItemId(),
                    $this->getAuctionId()
                )
                : 0.;
        }
        return $this->secondAbsentee;
    }

    /**
     * @param float $secondAbsentee
     * @return static
     * @internal
     */
    public function setSecondAbsentee(float $secondAbsentee): static
    {
        $this->secondAbsentee = Cast::toFloat($secondAbsentee, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return float|null
     */
    protected function getReservePrice(): ?float
    {
        if ($this->reservePrice === null) {
            $this->reservePrice = $this->getLotItem()->ReservePrice;
        }
        return $this->reservePrice;
    }

    /**
     * @param float $reservePrice
     * @return static
     * @internal
     */
    public function setReservePrice(float $reservePrice): static
    {
        $this->reservePrice = Cast::toFloat($reservePrice, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return float
     */
    protected function getSuggestedStartingBid(): float
    {
        if ($this->suggestedStartingBid === null) {
            $this->suggestedStartingBid = $this->getLotItem()->StartingBid
                ?? $this->createAskingBidDetector()->detectQuantizedBid(null, true, $this->getLotItemId(), $this->getAuctionId());
        }
        return $this->suggestedStartingBid;
    }

    /**
     * @param float $suggestedStartingBid
     * @return static
     * @internal
     */
    public function setSuggestedStartingBid(float $suggestedStartingBid): static
    {
        $this->suggestedStartingBid = Cast::toFloat($suggestedStartingBid, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return float
     */
    protected function getInitialPrice(): float
    {
        if ($this->initialPrice === null) {
            $this->initialPrice = $this->getReservePrice() ?: $this->getSuggestedStartingBid();
        }
        return $this->initialPrice;
    }

    /**
     * @param float $initialPrice
     * @return static
     * @internal
     */
    public function setInitialPrice(float $initialPrice): static
    {
        $this->initialPrice = Cast::toFloat($initialPrice, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return int
     * @internal
     * Detect the way we should calculate flexible starting bid
     */
    public function detectCalculationWay(): int
    {
        $initialPrice = $this->getInitialPrice();
        $reservePrice = $this->getReservePrice();
        $highAbsentee = $this->getHighAbsentee();
        $secondAbsentee = $this->getSecondAbsentee();

        if (
            Floating::eq($reservePrice, 0.)
            && Floating::eq($highAbsentee, 0.)
            && Floating::eq($secondAbsentee, 0.)
        ) {
            $this->calculationWay = self::NO_ABSENTEE_NO_RESERVE;
        } else {
            // 1. One high absentee bid and absentee bid greater than reserve
            // recommended starting bid = (reserve or reserve + increment)
            if (
                Floating::gt($highAbsentee, 0.)
                && Floating::gt($highAbsentee, $initialPrice)
                && (
                    Floating::eq($secondAbsentee, 0.)
                    || (
                        Floating::gt($secondAbsentee, 0.)
                        && Floating::lt($secondAbsentee, $initialPrice)
                    )
                )
            ) {
                $this->calculationWay = self::HIGH_BID_GREATER_INITIAL_SECOND_BID_LOWER_INITIAL;
            }

            // 2. One or multiple absentee bids below reserve price
            // recommended starting bid = (one increment below reserve) <<< Deprecated rule since 2011-08-10
            // recommended starting bid = reserve
            elseif (((
                        Floating::gt($highAbsentee, 0.)
                        && Floating::gt($secondAbsentee, 0.)
                    ) || (
                        Floating::gt($highAbsentee, 0.)
                        && Floating::eq($secondAbsentee, 0.)
                    )
                )
                && Floating::lt($highAbsentee, $initialPrice)
                && Floating::lt($secondAbsentee, $initialPrice)
            ) {
                $this->calculationWay = self::BIDS_LOWER_INITIAL;
            }

            // 3. No absentee bids and reserve price.
            // recommended starting bid = (one increment below reserve)
            elseif (
                Floating::eq($highAbsentee, 0.)
                && Floating::eq($secondAbsentee, 0.)
            ) {
                $this->calculationWay = self::NO_BIDS_RESERVE_EXISTS;
            }

            // 4. If there are two different highest absentee bids above or equal reserve, the starting bid should be one of the following:
            // recommended starting bid = (2nd absentee + 1 increment OR 2nd absentee + 2 increment)
            // The decision criterion between the two remains the same: the number of increments between starting bid and highest absentee bid needs to be even.
            // If the Highest absentee bid is only one increment above the 2nd highest, recommended starting bid = (the highest absentee bid which is in this case the same as 2nd highest absentee bid + 1 increment)
            elseif (
                Floating::gt($highAbsentee, 0.)
                && Floating::gt($secondAbsentee, 0.)
                && Floating::gt($highAbsentee, $secondAbsentee)
                && Floating::gteq($secondAbsentee, $initialPrice)
            ) {
                $this->calculationWay = self::BIDS_GREATER_INITIAL;
            }

            // 5. Two highest absentee bids, both at the same amount
            // recommended starting bid = (absentee bid amount), but bid goes to the bidder who placed the absentee bid first
            elseif (
                Floating::eq($highAbsentee, $secondAbsentee)
                && Floating::gt($highAbsentee, 0.)
                && Floating::gteq($secondAbsentee, $initialPrice)
            ) {
                $this->calculationWay = self::EQUAL_BIDS_GREATER_INITIAL;
            }

            // 6.
            //
            elseif (
                Floating::gt($highAbsentee, 0.)
                && Floating::lteq($highAbsentee, $initialPrice)
            ) {
                $this->calculationWay = self::HIGH_ABSENTEE_LOWER_OR_EQUAL_INITIAL;
            }
        }
        return $this->calculationWay;
    }

    /**
     * 1. One high absentee bid and absentee bid greater than reserve
     * recommended starting bid = (reserve or reserve + increment)
     * @return float
     */
    protected function calcForHighBidGreaterInitialSecondBidLowerInitial(): float
    {
        $bid = 0.;
        $firstStartingBid = 0.;
        $step = 0;
        $startPoint = 0.;
        $endPoint = 0.;

        try {
            $startPoint = $this->createAskingBidDetector()->detectQuantizedBid(
                $this->getInitialPrice(),
                false,
                $this->getLotItemId(),
                $this->getAuctionId()
            );
            $endPoint = $this->createAskingBidDetector()->detectQuantizedBid(
                $this->getHighAbsentee(),
                false,
                $this->getLotItemId(),
                $this->getAuctionId()
            );
        } catch (Exception $e) {
            $this->log('Caught exception: ' . $e->getMessage());
        }

        do {
            $step++;
            $bidIncrement = $this->getBidIncrementLoader()->loadAvailable(
                $bid,
                $this->getLotItem()->AccountId,
                $this->getAuction()->AuctionType,
                $this->getAuctionId(),
                $this->getLotItemId()
            );
            $increment = $bidIncrement->Increment ?? 0.;
            $bid += $increment;
            $quantizedBid = 0.;

            try {
                $quantizedBid = $this->createAskingBidDetector()->detectQuantizedBid(
                    $bid,
                    false,
                    $this->getLotItemId(),
                    $this->getAuctionId()
                );
            } catch (Exception $e) {
                $this->log('Caught exception: ' . $e->getMessage());
            }

            if (
                Floating::eq($firstStartingBid, 0.)
                && Floating::gteq($quantizedBid, $startPoint)
            ) {
                $step = 0;
                $firstStartingBid = $quantizedBid;
            }

            if (Floating::gteq($quantizedBid, $endPoint)) {
                break;
            }

            if (Floating::lteq($increment, 0.)) {
                $this->log('Wrong increment: ' . $increment);
                break;
            }
        } while (true);

        if ($step % 2 === 1) {
            $bidIncrement = $this->getBidIncrementLoader()->loadAvailable(
                $firstStartingBid,
                $this->getLotItem()->AccountId,
                $this->getAuction()->AuctionType,
                $this->getAuctionId(),
                $this->getLotItemId()
            );
            $firstStartingBid += ($bidIncrement->Increment ?? 0.);
        }

        return $firstStartingBid;
    }

    /**
     * 2. One or multiple absentee bids below reserve price
     * recommended starting bid = reserve
     * @return float
     */
    protected function calcForBidsLowerInitial(): float
    {
        $quantizedBid = 0.;
        try {
            $quantizedBid = $this->createAskingBidDetector()->detectQuantizedBid(
                $this->getInitialPrice(),
                false,
                $this->getLotItemId(),
                $this->getAuctionId()
            );
        } catch (Exception $e) {
            $this->log('Caught exception: ' . $e->getMessage());
        }
        return $quantizedBid;
    }

    /**
     * 3. No absentee bids and reserve price.
     * recommended starting bid = (one increment below reserve)
     * @return float
     */
    protected function calcForNoBidsReserveExists(): float
    {
        $quantizedBid = 0.;
        try {
            $quantizedBid = $this->createAskingBidDetector()->detectQuantizedBid(
                $this->getInitialPrice(),
                false,
                $this->getLotItemId(),
                $this->getAuctionId()
            );
        } catch (Exception $e) {
            $this->log('Caught exception: ' . $e->getMessage());
        }
        return $quantizedBid;
    }

    /**
     * 4. If there are two different highest absentee bids above or equal reserve, the starting bid should be one of the following:
     * recommended starting bid = (2nd absentee + 1 increment OR 2nd absentee + 2 increment)
     * The decision criterion between the two remains the same: the number of increments between starting bid and highest absentee bid needs to be even.
     * If the Highest absentee bid is only one increment above the 2nd highest, recommended starting bid = (the highest absentee bid which is in this case the same as 2nd highest absentee bid + 1 increment)
     * @return float
     */
    protected function calcForBidsGreaterInitial(): float
    {
        $bid = 0.;
        $step = 0;
        $startPoint = 0.;
        $endPoint = 0.;
        $oneIncrementBid = 0.;
        $twoIncrementBid = 0.;

        try {
            $startPoint = $this->createAskingBidDetector()->detectQuantizedBid(
                $this->getSecondAbsentee(),
                false,
                $this->getLotItemId(),
                $this->getAuctionId()
            );
            $endPoint = $this->createAskingBidDetector()->detectQuantizedBid(
                $this->getHighAbsentee(),
                false,
                $this->getLotItemId(),
                $this->getAuctionId()
            );
        } catch (Exception $e) {
            log_error(composeLogData(['Caught exception' => $e->getMessage()]));
        }

        do {
            $step++;
            $bidIncrement = $this->getBidIncrementLoader()->loadAvailable(
                $bid,
                $this->getLotItem()->AccountId,
                $this->getAuction()->AuctionType,
                $this->getAuctionId(),
                $this->getLotItemId()
            );
            $increment = $bidIncrement->Increment ?? 0.;
            $bid += $increment;
            $quantizedBid = 0.;
            try {
                $quantizedBid = $this->createAskingBidDetector()->detectQuantizedBid(
                    $bid,
                    false,
                    $this->getLotItemId(),
                    $this->getAuctionId()
                );
            } catch (Exception $e) {
                log_error(composeLogData(['Caught exception' => $e->getMessage()]));
            }

            if (
                Floating::eq($twoIncrementBid, 0.)
                && Floating::gt($oneIncrementBid, 0.)
            ) {
                $twoIncrementBid = $quantizedBid;
                //echo "2nd absentee + 2 increment: $twoIncrementBid <br />";
            }

            if (
                Floating::eq($oneIncrementBid, 0.)
                && Floating::gt($quantizedBid, $startPoint)
            ) {
                $oneIncrementBid = $quantizedBid;
                //echo "2nd absentee + 1 increment: $oneIncrementBid <br />";
                $step = 0;
            }

            if (Floating::gteq($quantizedBid, $endPoint)) {
                break;
            }

            if (Floating::lteq($increment, 0.)) {
                $this->log('Wrong increment: ' . $increment);
                break;
            }
        } while (true);

        if ($step % 2 === 1) {
            $flexibleStartingBid = $twoIncrementBid;
        } else {
            $flexibleStartingBid = $oneIncrementBid;
        }

        return $flexibleStartingBid;
    }

    /**
     * 5. Two highest absentee bids, both at the same amount
     * recommended starting bid = (absentee bid amount), but bid goes to the bidder who placed the absentee bid first
     * @return float
     */
    protected function calcForEqualBidsGreaterInitial(): float
    {
        $endPoint = 0.;
        try {
            $endPoint = $this->createAskingBidDetector()->detectQuantizedBid(
                $this->getSecondAbsentee(),
                false,
                $this->getLotItemId(),
                $this->getAuctionId()
            );
        } catch (Exception $e) {
            $this->log('Caught exception: ' . $e->getMessage());
        }
        return $endPoint;
    }

    /**
     * @return float
     */
    protected function calcForHighAbsenteeLowerOrEqualInitial(): float
    {
        $bid = 0.;
        $testB = 0.;
        try {
            $testB = $this->createAskingBidDetector()->detectQuantizedBid(
                $this->getInitialPrice(),
                false,
                $this->getLotItemId(),
                $this->getAuctionId()
            );
        } catch (Exception $e) {
            $this->log('Caught exception: ' . $e->getMessage());
        }

        do {
            $bidIncrement = $this->getBidIncrementLoader()->loadAvailable(
                $bid,
                $this->getLotItem()->AccountId,
                $this->getAuction()->AuctionType,
                $this->getAuctionId(),
                $this->getLotItemId()
            );
            $increment = $bidIncrement->Increment ?? 0.;
            $bid += $increment;

            $testA = 0.;
            try {
                $testA = $this->createAskingBidDetector()->detectQuantizedBid(
                    $bid,
                    false,
                    $this->getLotItemId(),
                    $this->getAuctionId()
                );
            } catch (Exception $e) {
                log_error(composeLogData(['Caught exception' => $e->getMessage()]));
            }

            if (Floating::gteq($testA, $testB)) {
                break;
            }

            if (Floating::lteq($increment, 0.)) {
                $this->log('Wrong increment: ' . $increment);
                break;
            }
        } while (true);

        return $bid;
    }

    /**
     * @param string $message
     */
    protected function log(string $message): void
    {
        $message .= composeSuffix(['a' => $this->getAuctionId(), 'li' => $this->getLotItemId()]);
        log_error($message);
    }
}
