<?php
/**
 * SAM-5873: Date/ Time/ Timezone management overhaul
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           June 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Date\Calculate;

use DateTime;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Contains methods for determining auction dates based on auction lot dates
 *
 * Class AuctionDateFromLotsDetector
 * @package Sam\Auction\Date\Calculate
 */
class AuctionDateFromLotsDetector extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;

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
     * Get first start bidding date from an auction lots
     *
     * @param int $auctionId auction id
     * @return DateTime|null
     */
    public function detectStartBiddingDate(int $auctionId): ?DateTime
    {
        $row = $this->createAuctionLotItemReadRepository()
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->orderByStartBiddingDate()
            ->select(['ali.start_bidding_date'])
            ->skipStartBiddingDate(null)
            ->loadRow();
        $startBiddingDateIso = $row['start_bidding_date'] ?? null;
        if ($startBiddingDateIso) {
            return new DateTime($startBiddingDateIso);
        }
        return null;
    }

    /**
     * Get last start closing date from an auction lots
     *
     * @param int $auctionId auction id
     * @return DateTime|null
     */
    public function detectStartClosingDate(int $auctionId): ?DateTime
    {
        $row = $this->createAuctionLotItemReadRepository()
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->orderByStartClosingDate(false)
            ->select(['ali.start_closing_date'])
            ->loadRow();
        $startClosingDateIso = $row['start_closing_date'] ?? null;
        if ($startClosingDateIso) {
            return new DateTime($startClosingDateIso);
        }
        return null;
    }

    /**
     * Get last end date from an auction lots
     *
     * @param int $auctionId auction id
     * @return DateTime|null
     */
    public function detectEndDate(int $auctionId): ?DateTime
    {
        $row = $this->createAuctionLotItemReadRepository()
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->orderByEndDate(false)
            ->select(['ali.end_date'])
            ->loadRow();
        $endDateIso = $row['end_date'] ?? null;
        if ($endDateIso) {
            return new DateTime($endDateIso);
        }
        return null;
    }
}
