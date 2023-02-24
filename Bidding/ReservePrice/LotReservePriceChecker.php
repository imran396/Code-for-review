<?php
/**
 * SAM-5045: Reserve met label for auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/17/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\ReservePrice;

use Sam\Bidding\BidTransaction\Load\BidTransactionLoaderCreateTrait;
use Sam\Bidding\CurrentAbsenteeBid\CurrentAbsenteeBidCalculatorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;

/**
 * Class LotReservePriceChecker
 * @package Sam\Bidding\ReservePrice
 */
class LotReservePriceChecker extends CustomizableClass
{
    use AuctionAwareTrait;
    use BidTransactionLoaderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentAbsenteeBidCalculatorCreateTrait;
    use LotItemAwareTrait;
    use ReservePriceSimpleCheckerAwareTrait;

    public const RESERVE_NOTICE_DISABLED = 0;
    public const IS_MET = 1;
    public const NOT_MET = 2;
    public const RESERVE_PRICE_UNSET = 3;
    public const CURRENT_BID_UNSET = 4;
    public const AUCTION_ABSENT = 5;
    public const LOT_ITEM_ABSENT = 6;

    protected ?float $bidAmount = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int
     */
    public function check(): int
    {
        if (!$this->cfg()->get('core->lot->reserveNotice->enabled')) {
            return self::RESERVE_NOTICE_DISABLED;
        }

        $lotItem = $this->getLotItem();
        if (!$lotItem) {
            return self::LOT_ITEM_ABSENT;
        }

        $auction = $this->getAuction();
        if (!$auction) {
            return self::AUCTION_ABSENT;
        }

        $reservePrice = $lotItem->ReservePrice;
        if (Floating::eq($reservePrice, 0)) {
            return self::RESERVE_PRICE_UNSET;
        }

        $amount = $this->getBidAmount();
        if (!Floating::gt($amount, 0)) {
            return self::CURRENT_BID_UNSET;
        }

        $isMet = $this->getReservePriceSimpleChecker()->meet($amount, $reservePrice, $auction->Reverse);
        if ($isMet && !$auction->ReserveMetNotice) {
            return self::RESERVE_NOTICE_DISABLED;
        }
        if (!$isMet && !$auction->ReserveNotMetNotice) {
            return self::RESERVE_NOTICE_DISABLED;
        }

        return $isMet ? self::IS_MET : self::NOT_MET;
    }

    /**
     * @return float|null
     */
    public function getBidAmount(): ?float
    {
        if ($this->bidAmount === null) {
            $this->bidAmount = $this->detectCurrentBidAmount();
        }
        return $this->bidAmount;
    }

    /**
     * @param float|null $bidAmount
     * @return static
     */
    public function setBidAmount(?float $bidAmount): static
    {
        $this->bidAmount = Cast::toFloat($bidAmount, Constants\Type::F_FLOAT_POSITIVE);
        return $this;
    }

    /**
     * @return float|null
     */
    protected function detectCurrentBidAmount(): ?float
    {
        $bidAmount = null;
        if (
            $this->getAuction()
            && $this->getAuction()->isLiveOrHybrid()
        ) {
            $bidAmount = $this->createCurrentAbsenteeBidCalculator()
                ->setLotItem($this->getLotItem())
                ->setAuction($this->getAuction())
                ->calculate();
        } else {
            $bidTransaction = $this->createBidTransactionLoader()
                ->loadLastActiveBid($this->getLotItemId(), $this->getAuctionId());
            if ($bidTransaction) {
                $bidAmount = (float)$bidTransaction->MaxBid;
            }
        }
        return $bidAmount;
    }
}
