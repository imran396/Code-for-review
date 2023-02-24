<?php
/**
 * SAM-6018: Implement auction start closing date
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 06, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Date;

use Auction;
use DateInterval;
use DateTime;
use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;
use Sam\Auction\Load\AuctionDynamicLoaderAwareTrait;
use Sam\AuctionLot\StaggerClosing\StaggerClosingHelperCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * Responsible for the detection of auction end date based on its configuration.
 * The main purpose is to determine the actual auction end date for further caching
 *
 * Class EndDateDetector
 * @package Sam\Auction\Date
 */
class AuctionEndDateDetector extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionCacheLoaderAwareTrait;
    use AuctionDynamicLoaderAwareTrait;
    use StaggerClosingHelperCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * Detect end date by formula
     * Live: [live end date]
     * Hybrid: [start closing date] + [lots qty] * ([gap time] + [extend time])
     * Timed Scheduled:
     *                    - Calc for stagger closing
     *                    - StartClosingDate by default
     * Timed Ongoing: NULL
     *
     * @param Auction $auction
     * @return DateTime|null Actual auction's end date. NULL if can't determine
     */
    public function detect(Auction $auction): ?DateTime
    {
        if ($auction->isLive()) {
            return $auction->EndDate;
        }
        if ($auction->isHybrid()) {
            return $this->detectForHybridAuction($auction);
        }
        if ($auction->isTimed()) {
            return $this->detectForTimedAuction($auction);
        }
        return null;
    }

    /**
     * @param Auction $auction
     * @return DateTime
     */
    private function detectForHybridAuction(Auction $auction): DateTime
    {
        $endDateUtc = clone $auction->StartClosingDate;
        $lotsQty = $auction->Id ? $this->getAuctionLotsQty($auction->Id) : 0;
        $auctionDuration = $lotsQty * ($auction->LotStartGapTime + $auction->ExtendTime);
        return $endDateUtc->add(new DateInterval(sprintf('PT%uS', $auctionDuration)));
    }

    /**
     * @param Auction $auction
     * @return DateTime|null
     */
    private function detectForTimedAuction(Auction $auction): ?DateTime
    {
        if ($auction->isTimedOngoing()) {
            return null;
        }

        $startClosingDate = $this->detectTimedAuctionStartClosingDate($auction);
        if (!$startClosingDate) {
            return null;
        }

        $endDate = $startClosingDate;
        $isStaggerClosing = $auction->StaggerClosing > 0 && $auction->LotsPerInterval > 0;

        if ($isStaggerClosing) {
            $auctionLotsQty = $this->getAuctionLotsQty($auction->Id);
            $lastLotOrderNumber = $auctionLotsQty > 0 ? $auctionLotsQty : 1;
            $endDate = $this->createStaggerClosingHelper()->calcEndDate(
                $startClosingDate,
                $auction->LotsPerInterval,
                $auction->StaggerClosing,
                $lastLotOrderNumber
            );
        }
        return $endDate;
    }

    protected function detectTimedAuctionStartClosingDate(Auction $auction): ?DateTime
    {
        $startClosingDate = $auction->StartClosingDate;
        if (
            $auction->ExtendAll
            && $auction->Id
        ) {
            $startClosingDate = $this->getAuctionDynamicLoader()
                ->loadOrCreate($auction->Id)
                ->ExtendAllStartClosingDate;
        }
        return $startClosingDate;
    }

    /**
     * @param int|null $auctionId
     * @return int
     */
    private function getAuctionLotsQty(?int $auctionId): int
    {
        $auctionCache = $this->getAuctionCacheLoader()->load($auctionId);
        $lotsQty = $auctionCache->TotalLots ?? 0;
        return $lotsQty;
    }
}
