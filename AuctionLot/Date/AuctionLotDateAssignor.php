<?php
/**
 * SAM-6079: Implement lot start closing date
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 27, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Date;

use AuctionLotItem;
use Sam\AuctionLot\Date\Dto\LiveHybridAuctionLotDates;
use Sam\AuctionLot\Date\Dto\TimedAuctionLotDates;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;

/**
 * Contains methods for changing auction lot dates
 *
 * Class AuctionLotDateAssignor
 * @package Sam\AuctionLot\Date
 */
class AuctionLotDateAssignor extends CustomizableClass
{
    use AuctionLotItemWriteRepositoryAwareTrait;

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
     * Populates and persists timed auction lot dates
     *
     * @param AuctionLotItem $auctionLot
     * @param TimedAuctionLotDates $dates
     * @param int $editorUserId
     * @return AuctionLotItem
     */
    public function assignForTimed(AuctionLotItem $auctionLot, TimedAuctionLotDates $dates, int $editorUserId): AuctionLotItem
    {
        if ($dates->getStartClosingDate() !== null) {
            $auctionLot->StartClosingDate = $dates->getStartClosingDate();
            $auctionLot->EndDate = $dates->getStartClosingDate();
        }
        if ($dates->getStartBiddingDate() !== null) {
            $auctionLot->StartBiddingDate = $dates->getStartBiddingDate();
            $auctionLot->StartDate = $dates->getStartBiddingDate();
        }
        if ($dates->getEndDate() !== null) {
            $auctionLot->EndDate = $dates->getEndDate();
        }
        $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
        return $auctionLot;
    }

    /**
     * Populates and persists live or hybrid auction lot dates
     *
     * @param AuctionLotItem $auctionLot
     * @param LiveHybridAuctionLotDates $dates
     * @param int $editorUserId
     * @return AuctionLotItem
     */
    public function assignForLiveOrHybrid(AuctionLotItem $auctionLot, LiveHybridAuctionLotDates $dates, int $editorUserId): AuctionLotItem
    {
        if ($dates->getEndDate() !== null) {
            $auctionLot->EndDate = $dates->getEndDate();
        }
        if ($dates->getStartDate() !== null) {
            $auctionLot->StartDate = $dates->getStartDate();
        }
        $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
        return $auctionLot;
    }
}
