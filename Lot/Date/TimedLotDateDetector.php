<?php
/**
 * SAM-5349: Lot start/end date detector
 * SAM-4877: Incorrect null date in case of Timed+Ongoing auction lot at Timed Lot Details page
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

namespace Sam\Lot\Date;

use AuctionLotItem;
use DateTime;
use InvalidArgumentException;
use Sam\Core\Service\CustomizableClass;
use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;
use Sam\Auction\Load\AuctionDynamicLoaderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\TimedItemLoaderAwareTrait;
use Sam\AuctionLot\StaggerClosing\StaggerClosingHelperCreateTrait;
use Sam\Date\DateHelperAwareTrait;

/**
 * Class LotDateDetector
 * @package Sam\Lot\Date
 */
class TimedLotDateDetector extends CustomizableClass
{
    use AuctionCacheLoaderAwareTrait;
    use AuctionDynamicLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use DateHelperAwareTrait;
    use StaggerClosingHelperCreateTrait;
    use TimedItemLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return start and end dates in UTC of timed online item.
     * We consider, if auction's option "Extend All" is On and staggered closing settings.
     * @param AuctionLotItem $auctionLot
     * @return DateTime[] [\DateTime $startDateUtc, \DateTime $endDateUtc]
     */
    public function detect(AuctionLotItem $auctionLot): array
    {
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
        if (!$auction) {
            $message = "Available auction not found for detecting lot date range"
                . composeSuffix(['a' => $auctionLot->AuctionId]);
            log_error($message);
            throw new InvalidArgumentException($message);
        }

        if (
            $auction->ExtendAll
            && $auctionLot->isActive()
        ) {
            $auctionDynamic = $this->getAuctionDynamicLoader()->loadOrCreate($auction->Id);
            $lotStartDateUtc = $auction->StartBiddingDate;
            $lotEndDateUtc = $auctionDynamic->ExtendAllStartClosingDate;
            if ($auction->StaggerClosing) {
                $lotEndDateUtc = $this->createStaggerClosingHelper()
                    ->calcEndDate(
                        $auctionDynamic->ExtendAllStartClosingDate,
                        $auction->LotsPerInterval,
                        $auction->StaggerClosing,
                        $auctionLot->Order
                    );
            }
        } else {
            $lotStartDateUtc = $auctionLot->StartBiddingDate;
            $lotEndDateUtc = $auctionLot->EndDate ?? $auctionLot->StartClosingDate;
            // Timed+Scheduled+ExtendAll may not have date(?), then we auction's date
            if (!$lotStartDateUtc) {
                $lotStartDateUtc = $auction->StartBiddingDate;
            }
            if (
                !$lotEndDateUtc
                && $auction->isTimedScheduled()
            ) {
                $lotEndDateUtc = $auction->StartClosingDate;
            }
        }
        return [$lotStartDateUtc, $lotEndDateUtc];
    }
}
