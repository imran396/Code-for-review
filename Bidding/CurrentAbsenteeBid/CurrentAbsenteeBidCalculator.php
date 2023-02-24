<?php
/**
 * It detects current absentee bid value
 *
 * SAM-3791: Current Absentee Bid calculation refactoring https://bidpath.atlassian.net/browse/SAM-3791
 *
 * @author        Igors Kotlevskis
 * @since         Jun 20, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidding\CurrentAbsenteeBid;

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
 * Class CurrentAbsenteeBidCalculator
 * @package Sam\Bidding\CurrentAbsenteeBid
 */
class CurrentAbsenteeBidCalculator extends CustomizableClass
{
    use AskingBidDetectorCreateTrait;
    use AuctionAwareTrait;
    use BidIncrementLoaderAwareTrait;
    use HighAbsenteeBidDetectorCreateTrait;
    use LotItemAwareTrait;

    /**
     * Different calculation ways for result
     */
    private const UNKNOWN = 0;
    private const RESERVE_PRICE = 1;
    private const HIGH_ABSENTEE = 2;
    private const SECOND_ABSENTEE_PLUS_INCREMENT = 3;
    private const STARTING_BID = 4;

    /** @var float|null */
    protected ?float $firstAbsenteeAmount = null;
    /** @var float|null */
    protected ?float $secondAbsenteeAmount = null;
    /** @var float|null */
    protected ?float $reservePrice = null;
    /** @var float|null */
    protected ?float $startingBid = null;
    /** @var AbsenteeBid|null */
    protected ?AbsenteeBid $firstAbsenteeBid = null;
    /** @var AbsenteeBid|null */
    protected ?AbsenteeBid $secondAbsenteeBid = null;
    /** @var bool */
    protected bool $isTwoHighestInitialized = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Calculating current absentee bid amount
     * @return float|null
     */
    public function calculate(): ?float
    {
        $calculationWay = $this->detectCalculationWay();
        $amount = null;
        if ($calculationWay === self::RESERVE_PRICE) {
            $amount = $this->getReservePrice();
        } elseif ($calculationWay === self::HIGH_ABSENTEE) {
            $amount = $this->getFirstAbsenteeAmount();
        } elseif ($calculationWay === self::SECOND_ABSENTEE_PLUS_INCREMENT) {
            $amount = $this->calcSecondAbsenteePlusIncrement();
        } elseif ($calculationWay === self::STARTING_BID) {
            $amount = $this->calcStartingAmount();
        }
        return $amount;
    }

    /**
     * @return AbsenteeBid|null
     */
    public function getFirstAbsenteeBid(): ?AbsenteeBid
    {
        if ($this->firstAbsenteeBid === null) {
            $this->initTwoHighestAbsenteeBids();
        }
        return $this->firstAbsenteeBid;
    }

    /**
     * @param AbsenteeBid $absenteeBid
     * @return $this
     */
    public function setFirstAbsenteeBid(AbsenteeBid $absenteeBid): static
    {
        $this->firstAbsenteeBid = $absenteeBid;
        return $this;
    }

    /**
     * @return AbsenteeBid|null
     */
    public function getSecondAbsenteeBid(): ?AbsenteeBid
    {
        if ($this->secondAbsenteeBid === null) {
            $this->initTwoHighestAbsenteeBids();
        }
        return $this->secondAbsenteeBid;
    }

    /**
     * @param AbsenteeBid $absenteeBid
     * @return static
     */
    public function setSecondAbsenteeBid(AbsenteeBid $absenteeBid): static
    {
        $this->secondAbsenteeBid = $absenteeBid;
        return $this;
    }

    /**
     * Returns quantized amount for first high absentee bid
     * @return float
     */
    protected function getFirstAbsenteeAmount(): float
    {
        if ($this->firstAbsenteeAmount === null) {
            $this->firstAbsenteeAmount = 0.;
            $firstAbsenteeBid = $this->getFirstAbsenteeBid();
            if ($firstAbsenteeBid) {
                try {
                    $this->firstAbsenteeAmount = $this->createAskingBidDetector()
                        ->detectQuantizedBid(
                            $firstAbsenteeBid->MaxBid,
                            false,
                            $this->getLotItemId(),
                            $this->getAuctionId(),
                            $this->getLotItem()->StartingBid
                        );
                } catch (Exception $e) {
                    $this->log('Caught exception: ' . $e->getMessage());
                }
            }
        }
        return $this->firstAbsenteeAmount;
    }

    /**
     * @param float|null $amount
     * @return $this
     */
    public function setFirstAbsenteeAmount(?float $amount): static
    {
        $this->firstAbsenteeAmount = Cast::toFloat($amount, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * Returns quantized amount for second high absentee bid
     * @return float
     */
    protected function getSecondAbsenteeAmount(): float
    {
        if ($this->secondAbsenteeAmount === null) {
            $this->secondAbsenteeAmount = 0.;
            $secondAbsenteeBid = $this->getSecondAbsenteeBid();
            if ($secondAbsenteeBid) {
                try {
                    $this->secondAbsenteeAmount = $this->createAskingBidDetector()
                        ->detectQuantizedBid(
                            $secondAbsenteeBid->MaxBid,
                            false,
                            $this->getLotItemId(),
                            $this->getAuctionId()
                        );
                } catch (Exception $e) {
                    $this->log('Caught exception: ' . $e->getMessage());
                }
            }
        }
        return $this->secondAbsenteeAmount;
    }

    /**
     * @param float|null $amount
     * @return $this
     */
    public function setSecondAbsenteeAmount(?float $amount): static
    {
        $this->secondAbsenteeAmount = Cast::toFloat($amount, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return float|null
     */
    public function getReservePrice(): ?float
    {
        if ($this->reservePrice === null) {
            $reservePrice = $this->getLotItem()->ReservePrice;
            // SAM-2778 Ignore Reserve Price, when it is below Starting Bid
            if (Floating::gteq($reservePrice, $this->getLotItem()->StartingBid)) {
                $this->reservePrice = $reservePrice;
            }
        }
        return $this->reservePrice;
    }

    /**
     * @param float|null $amount
     * @return $this
     */
    public function setReservePrice(?float $amount): static
    {
        $this->reservePrice = Cast::toFloat($amount, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return float|null
     */
    public function getStartingBid(): ?float
    {
        if ($this->startingBid === null) {
            $this->startingBid = $this->getLotItem()->StartingBid;
        }
        return $this->startingBid;
    }

    /**
     * @param float|null $amount
     * @return $this
     */
    public function setStartingBid(?float $amount): static
    {
        $this->startingBid = Cast::toFloat($amount, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * Detect the way we should calculate current absentee bid
     * @return int
     */
    protected function detectCalculationWay(): int
    {
        $calculationWay = self::UNKNOWN;
        $firstAbsenteeAmount = $this->getFirstAbsenteeAmount();
        $secondAbsenteeAmount = $this->getSecondAbsenteeAmount();
        $reservePrice = $this->getReservePrice();
        $startingBid = $this->getStartingBid();

        // formatter:off
        /**
         * a) Reserve price related case
         * If there is only ONE abs above reserve price, then
         * Current bid: reserve price
         */
        if (
            Floating::gt($reservePrice, 0)
            && Floating::gteq($firstAbsenteeAmount, $reservePrice)
            && Floating::lt($secondAbsenteeAmount, $reservePrice)
        ) {
            $calculationWay = self::RESERVE_PRICE;
        } /**
         * b) Reserve price related case
         * If there are two effective abs above reserve then the current bid is
         * the second highest absentee bid plus one increment, unless they are
         * both the same effectively
         */
        elseif (
            Floating::gt($reservePrice, 0)
            && Floating::gteq($firstAbsenteeAmount, $reservePrice)
            && Floating::gteq($secondAbsenteeAmount, $reservePrice)
        ) {
            if (Floating::eq($firstAbsenteeAmount, $secondAbsenteeAmount)) {
                $calculationWay = self::HIGH_ABSENTEE;
            } else {
                $calculationWay = self::SECOND_ABSENTEE_PLUS_INCREMENT;
            }
        }

        /**
         * c) Reserve price related case
         * High absentee bid less to the reserve price:
         * Current bid: <highest absentee bid>
         */
        // IK, 2020-01-13, this logic looks redundant, because if high abs >= reserve price
        // and second abs < reserve price, then we take reserve price by self::RESERVE_PRICE calculation
        // elseif ((Floating::gt($highAbsenteeAmount, 0)
        //         && Floating::eq($secondAbsenteeAmount, 0)
        //         && Floating::lt($highAbsenteeAmount, $reservePrice))
        //     || (Floating::gt($highAbsenteeAmount, 0)
        //         && Floating::gt($secondAbsenteeAmount, 0)
        //         && Floating::lt($secondAbsenteeAmount, $reservePrice))
        elseif (
            Floating::gt($firstAbsenteeAmount, 0)
            && Floating::lt($firstAbsenteeAmount, $reservePrice)
        ) {
            $calculationWay = self::HIGH_ABSENTEE;
        } /**
         * One absentee bid above or equal to the starting bid:
         * Current bid: <starting bid>
         */
        elseif (
            Floating::gt($firstAbsenteeAmount, 0)
            && Floating::eq($secondAbsenteeAmount, 0)
            && Floating::gteq($firstAbsenteeAmount, $startingBid)
        ) {
            $calculationWay = self::STARTING_BID;
        } elseif (
            Floating::gt($firstAbsenteeAmount, 0)
            && Floating::gt($secondAbsenteeAmount, 0)
            && Floating::gteq($firstAbsenteeAmount, $startingBid)
            && Floating::lt($secondAbsenteeAmount, $startingBid)
        ) {
            $calculationWay = self::STARTING_BID;
        } /**
         * Starting bid and 2 identical absentee bids (quantized to increments) above or equal to the starting bid:
         * current bid: <earlier absentee bid>
         */
        elseif (
            Floating::gt($firstAbsenteeAmount, 0)
            && Floating::gt($secondAbsenteeAmount, 0)
            && Floating::eq($firstAbsenteeAmount, $secondAbsenteeAmount)
            && Floating::gteq($firstAbsenteeAmount, $startingBid)
        ) {
            $calculationWay = self::HIGH_ABSENTEE;
        } /**
         * 2 different absentee bids (quantized to increments) are above or equal to the starting bid:
         * Current bid: based on footing higher absentee at lower absentee
         * or + one increment or whatever the calculation is
         */
        elseif (
            Floating::gt($firstAbsenteeAmount, 0)
            && Floating::gt($secondAbsenteeAmount, 0)
            && Floating::neq($firstAbsenteeAmount, $secondAbsenteeAmount)
            && Floating::gteq($secondAbsenteeAmount, $startingBid)
        ) {
            $calculationWay = self::SECOND_ABSENTEE_PLUS_INCREMENT;
        }
        // formatter:on

        return $calculationWay;
    }

    /**
     * @return $this
     */
    protected function initTwoHighestAbsenteeBids(): static
    {
        if (!$this->isTwoHighestInitialized) {
            [$this->firstAbsenteeBid, $this->secondAbsenteeBid] = $this->createHighAbsenteeBidDetector()
                ->detectTwoHighest($this->getLotItemId(), $this->getAuctionId());
            $this->isTwoHighestInitialized = true;
        }
        return $this;
    }

    /**
     * @return float
     */
    protected function calcSecondAbsenteePlusIncrement(): float
    {
        $secondAbsenteeAmount = $this->getSecondAbsenteeAmount();
        $bidIncrement = $this->getBidIncrementLoader()->loadAvailable(
            $secondAbsenteeAmount,
            $this->getLotItem()->AccountId,
            $this->getAuction()->AuctionType,
            $this->getAuctionId(),
            $this->getLotItemId()
        );
        $resultAmount = $secondAbsenteeAmount;
        $resultAmount += $bidIncrement->Increment ?? 0.;
        return $resultAmount;
    }

    /**
     * @return float
     */
    protected function calcStartingAmount(): float
    {
        $startingBid = $this->getStartingBid();
        if (Floating::lteq($startingBid, 0)) {
            $bidIncrement = $this->getBidIncrementLoader()->loadAvailable(
                0.,
                $this->getLotItem()->AccountId,
                $this->getAuction()->AuctionType,
                $this->getAuctionId(),
                $this->getLotItemId()
            );
            $startingAmount = $bidIncrement->Increment ?? 0.;
        } else {
            $startingAmount = $startingBid;
        }
        return $startingAmount;
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
