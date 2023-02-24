<?php
/**
 * Re-opening/starting auction and resetting auction date and lot date.
 *
 * Related tickets:
 * SAM-3376 : Add "Re-open" button at Auction Lots page
 *
 * @author        Imran Rahman
 * @version       SAM 2.0
 * @since         May 06, 2016
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.api
 *
 */

namespace Sam\Auction\Open;

use Auction;
use DateInterval;
use DateTime;
use Exception;
use Sam\Auction\Date\AuctionEndDateDetectorCreateTrait;
use Sam\Auction\Load\AuctionDynamicLoaderAwareTrait;
use Sam\AuctionLot\Date\AuctionLotDateAssignorCreateTrait;
use Sam\AuctionLot\Date\Dto\TimedAuctionLotDates;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Load\TimedItemLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Rtb\WebClient\AuctionStateResyncerAwareTrait;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionDynamic\AuctionDynamicWriteRepositoryAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;

/**
 * Class AuctionOpener
 * @package Sam\Auction\Open
 */
class AuctionOpener extends CustomizableClass
{
    use AuctionDynamicLoaderAwareTrait;
    use AuctionDynamicWriteRepositoryAwareTrait;
    use AuctionEndDateDetectorCreateTrait;
    use AuctionLotDateAssignorCreateTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionReadRepositoryCreateTrait;
    use AuctionStateResyncerAwareTrait;
    use AuctionWriteRepositoryAwareTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use TimedItemLoaderAwareTrait;
    use TimezoneLoaderAwareTrait;

    /**
     * Class instantiation method
     *
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * marking started timed auctions
     *
     * @param int $editorUserId
     * @return void
     */
    public function startTimedAuctions(int $editorUserId): void
    {
        try {
            $currentDateUtc = $this->getCurrentDateUtc();

            $auctions = $this->createAuctionReadRepository()
                ->filterAuctionType(Constants\Auction::TIMED)
                ->filterAuctionStatusId(Constants\Auction::AS_ACTIVE)
                ->filterStartBiddingDateLessOrEqual($currentDateUtc->format(Constants\Date::ISO))
                ->filterEndDateGreaterOrEqual($currentDateUtc->format(Constants\Date::ISO))
                ->joinAccountFilterActive(true)
                ->loadEntities();

            foreach ($auctions as $auction) {
                try {
                    $auction->toStarted();
                    $this->getAuctionWriteRepository()->saveWithModifier($auction, $editorUserId);
                } catch (Exception $e) {
                    log_warning(
                        'Failed starting timed auction'
                        . composeSuffix(['a' => $auction->Id, 'error' => $e->getMessage()])
                    );
                }
            }
        } catch (Exception $e) {
            log_error('Failed starting timed auctions: ' . $e->getMessage());
        }
    }

    /**
     * Reopening auction by changing status.
     *
     * @param Auction $auction
     * @param int $editorUserId
     * @return void
     * @throws Exception
     */
    public function reopen(Auction $auction, int $editorUserId): void
    {
        if ($auction->isTimed()) {
            $this->resetLotsDateForTimed($auction->Id, $auction->StartBiddingDate, $editorUserId);
            $auction = $this->resetAuctionDateForTimed($auction, $editorUserId);
        } else {
            $auction = $this->resetAuctionDateForLive($auction);
        }
        $auction->toActive();
        $this->getAuctionWriteRepository()->saveWithModifier($auction, $editorUserId);

        $this->getAuctionStateResyncer()
            ->setAuction($auction)
            ->notifyRtbd();
    }

    /**
     * Resetting auction start date and end date for timed auction. It doesn't persist auction
     *
     * @param Auction $auction
     * @param int $editorUserId
     * @return Auction
     * @throws Exception
     */
    protected function resetAuctionDateForTimed(Auction $auction, int $editorUserId): Auction
    {
        $interval = $auction->StartDate->diff($auction->EndDate);
        $currentDateUtc = $this->getCurrentDateUtc();
        $auction->StartBiddingDate = $currentDateUtc->add(new DateInterval('P1D'));
        $auction->StartClosingDate = (clone $auction->StartDate)->add(new DateInterval('P' . $interval->d . 'D'));
        $auction->StartDate = clone $auction->StartBiddingDate;
        $auction->EndDate = $this->createAuctionEndDateDetector()->detect($auction);
        $auctionDynamic = $this->getAuctionDynamicLoader()->load($auction->Id);
        if ($auctionDynamic) {
            $this->getAuctionDynamicWriteRepository()->deleteWithModifier($auctionDynamic, $editorUserId);
        }
        return $auction;
    }

    /**
     * @param Auction $auction
     * @return Auction
     */
    protected function resetAuctionDateForLive(Auction $auction): Auction
    {
        $auction->StartDate = clone $auction->StartClosingDate;
        return $auction;
    }

    /**
     * Resetting auction lots date for timed auction.
     *
     * @param int $auctionId
     * @param DateTime $prevAuctionStartBiddingDate Auction start bidding date before change
     * @param int $editorUserId
     * @return void
     * @throws Exception
     */
    protected function resetLotsDateForTimed(int $auctionId, DateTime $prevAuctionStartBiddingDate, int $editorUserId): void
    {
        $startBiddingDate = $this->getCurrentDateUtc()->add(new DateInterval('P1D'));
        $generator = $this->getAuctionLotLoader()->yieldByAuctionId($auctionId, true);
        foreach ($generator as $auctionLot) {
            $timedAuctionLotDates = TimedAuctionLotDates::new();
            if ($auctionLot->StartBiddingDate) {
                $timedAuctionLotDates->setStartBiddingDate($startBiddingDate);
            }
            if ($auctionLot->StartClosingDate) {
                $prevStartBiddingDate = $auctionLot->StartBiddingDate ?: $prevAuctionStartBiddingDate;
                $interval = $prevStartBiddingDate->diff($auctionLot->StartClosingDate);
                $startClosingDate = clone $startBiddingDate;
                $startClosingDate = $startClosingDate->add(new DateInterval('P' . $interval->d . 'D'));
                $timedAuctionLotDates->setStartClosingDate($startClosingDate);
            }
            $this->createAuctionLotDateAssignor()->assignForTimed($auctionLot, $timedAuctionLotDates, $editorUserId);
        }
    }
}
