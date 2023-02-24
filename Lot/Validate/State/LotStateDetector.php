<?php
/**
 * Helper method collection for lot different states detection
 *
 * SAM-5633: Lot state detector
 * SAM-4103: Problem with conditional sale lot after auto update
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 26, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Validate\State;

use AuctionLotItem;
use DateTime;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Validate\AuctionLotStatusCheckerCreateTrait;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Entity\Model\LotItem\SellInfo\LotSellInfoPureChecker;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Lot\Date\TimedLotDateDetectorCreateTrait;

/**
 * Class LotStateDetector
 * @package Sam\Lot\Validate\State
 */
class LotStateDetector extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotStatusCheckerCreateTrait;
    use CurrentDateTrait;
    use TimedLotDateDetectorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if lot sold with reservation in conditional sale.
     * Has sense for live/hybrid auctions only.
     * @param int $lotStatus
     * @param float|null $reservePrice
     * @param float|null $hammerPrice
     * @param string $auctionType
     * @param bool $isConditionalSales
     * @return bool
     */
    public function isSoldWithReservation(
        int $lotStatus,
        ?float $reservePrice,
        ?float $hammerPrice,
        string $auctionType,
        bool $isConditionalSales
    ): bool {
        return $isConditionalSales
            && AuctionStatusPureChecker::new()->isLiveOrHybrid($auctionType)
            && AuctionLotStatusPureChecker::new()->isSold($lotStatus)
            && LotSellInfoPureChecker::new()->isHammerPrice($hammerPrice)
            && Floating::gt($reservePrice, $hammerPrice);
    }

    /**
     * Checks, if timed lot item has ended.
     * Has sense for timed auctions only.
     * @param AuctionLotItem $auctionLot
     * @param DateTime|null $currentDateUtc
     * @return bool
     */
    public function isLotEnded(AuctionLotItem $auctionLot, ?DateTime $currentDateUtc = null): bool
    {
        $currentDateUtc = $currentDateUtc ?: $this->getCurrentDateUtc();
        [, $endDateUtc] = $this->createTimedLotDateDetector()->detect($auctionLot);
        $isEnded = $currentDateUtc->getTimestamp() >= $endDateUtc->getTimestamp();
        return $isEnded;
    }

    /**
     * Checks, if the lot item are open for bidding (absentee bid or online bid).
     * Have sense for any type of auction.
     * @param AuctionLotItem $auctionLot
     * @param DateTime|null $currentDateUtc
     * @return bool
     */
    public function isOpenForBidding(AuctionLotItem $auctionLot, ?DateTime $currentDateUtc = null): bool
    {
        $logData = ['li' => $auctionLot->LotItemId, 'a' => $auctionLot->AuctionId, 'ali' => $auctionLot->Id];
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
        if (!$auction) {
            log_error("Available auction not found, when checking if lot is open for bidding" . composeSuffix($logData));
            return false;
        }

        if (
            $auction->isTimed()
            && (!$this->createAuctionLotStatusChecker()->isBiddingStarted($auctionLot->Id)
                || $this->isLotEnded($auctionLot, $currentDateUtc))
        ) {
            return false;
        }

        if (!$auctionLot->isActive()) {
            return false;
        }

        if (
            $auction->isLiveOrHybrid()
            && !$this->createAuctionLotStatusChecker()->isPreBiddingActive($auctionLot->Id)
        ) {
            return false;
        }

        return true;
    }
}
