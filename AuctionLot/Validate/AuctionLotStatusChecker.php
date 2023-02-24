<?php
/**
 * SAM-6033: Implement lot start bidding date
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Validate;

use DateTime;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;

/**
 * Set of auction lot status checks
 *
 * Class AuctionLotStatusChecker
 * @package Sam\AuctionLot\Validate
 */
class AuctionLotStatusChecker extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use CurrentDateTrait;

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
     * Check if bidding started by auction lot id
     * SAM-6008: Implement auction start bidding date
     * SAM-6033: Implement lot start bidding date
     *
     * @param int $auctionLotId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isBiddingStarted(int $auctionLotId, bool $isReadOnlyDb = false): bool
    {
        $auctionLot = $this->getAuctionLotLoader()->loadById($auctionLotId, $isReadOnlyDb);
        if (!$auctionLot) {
            log_error("Available auctionLot not found, when check if bidding started" . composeSuffix(['ali' => $auctionLotId]));
            return false;
        }
        $startBiddingDate = $auctionLot->StartBiddingDate;
        if ($startBiddingDate === null) {
            $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
            if (!$auction) {
                log_error("Available auction not found, when check if bidding started" . composeSuffix(['a' => $auctionLot->AuctionId]));
                return false;
            }
            $startBiddingDate = $auction->StartBiddingDate;
        }

        return $this->detectIfBiddingStartedByStartBiddingDate($startBiddingDate);
    }

    /**
     * Check if bidding started based on lot start bidding date
     *
     * @param DateTime|null $startBiddingDate
     * @return bool
     */
    public function detectIfBiddingStartedByStartBiddingDate(?DateTime $startBiddingDate): bool
    {
        if (!$startBiddingDate) {
            return false;
        }
        return $startBiddingDate <= $this->getCurrentDateUtc();
    }

    /**
     * Check if bidding started based on lot start bidding date
     *
     * @param string $startBiddingDateIso
     * @return bool
     */
    public function detectIfBiddingStartedByStartBiddingDateIso(string $startBiddingDateIso): bool
    {
        if (!$startBiddingDateIso) {
            return false;
        }
        return $this->detectIfBiddingStartedByStartBiddingDate(new DateTime($startBiddingDateIso));
    }

    /**
     * Check if absentee bidding is available (Applicable only for lots in Live/Hybrid auctions)
     * SAM-6011: Implement Live/Hybrid end pre-bidding date
     * SAM-6034: Implement lot end pre-bidding date
     *
     * @param int $auctionLotId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isPreBiddingActive(int $auctionLotId, bool $isReadOnlyDb = false): bool
    {
        $isPreBiddingStarted = $this->isBiddingStarted($auctionLotId, $isReadOnlyDb);
        $isPreBiddingEnded = $this->isPreBiddingEnded($auctionLotId, $isReadOnlyDb);
        return $isPreBiddingStarted && !$isPreBiddingEnded;
    }

    /**
     * Check if absentee bidding ability ended (Applicable only for lots in Live/Hybrid auctions)
     * SAM-6011: Implement Live/Hybrid end pre-bidding date
     * SAM-6034: Implement lot end pre-bidding date
     *
     * @param int $auctionLotId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isPreBiddingEnded(int $auctionLotId, bool $isReadOnlyDb = false): bool
    {
        $auctionLot = $this->getAuctionLotLoader()->loadById($auctionLotId, $isReadOnlyDb);
        if (!$auctionLot) {
            log_error("Available auctionLot not found, when check if absentee bidding ability ended" . composeSuffix(['ali' => $auctionLotId]));
            return false;
        }
        $endPrebiddingDate = $auctionLot->EndPrebiddingDate;
        if ($endPrebiddingDate === null) {
            $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
            if (!$auction) {
                log_error("Available auction not found, when check if absentee bidding ability ended" . composeSuffix(['a' => $auctionLot->AuctionId]));
                return false;
            }
            if ($auction->isLiveOrHybrid()) {
                $endPrebiddingDate = $auction->EndPrebiddingDate;
            }
        }
        return $this->detectIfPreBiddingEndedByEndPreBiddingDate($endPrebiddingDate);
    }

    /**
     * @param DateTime|null $endPreBiddingDate
     * @return bool
     */
    public function detectIfPreBiddingEndedByEndPreBiddingDate(?DateTime $endPreBiddingDate): bool
    {
        if (!$endPreBiddingDate) {
            return false;
        }
        return $endPreBiddingDate < $this->getCurrentDateUtc();
    }

    /**
     * Check if absentee bidding ability ended (Applicable only for lots in Live/Hybrid auctions) by date
     *
     * @param string $endPreBiddingDateIso
     * @return bool
     */
    public function detectIfPreBiddingEndedByEndPreBiddingDateIso(string $endPreBiddingDateIso): bool
    {
        if (!$endPreBiddingDateIso) {
            return false;
        }
        return $this->detectIfPreBiddingEndedByEndPreBiddingDate(new DateTime($endPreBiddingDateIso));
    }
}
