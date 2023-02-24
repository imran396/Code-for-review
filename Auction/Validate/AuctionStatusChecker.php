<?php
/**
 * SAM-3903: Auction status checker class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 19, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Validate;

use Auction;
use DateTime;
use Exception;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;

/**
 * Class AuctionStatusChecker
 * Check auction status
 * @package Sam\Auction
 */
class AuctionStatusChecker extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionReadRepositoryCreateTrait;
    use CurrentDateTrait;
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return static or customized class extending StatusChecker
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check whether auction.id is closed
     *
     * For live auctions checking for auction_status_id
     * For timed auctions also whether the end time is in the past
     *
     * @param int|null $auctionId auction.id
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isClosed(?int $auctionId, bool $isReadOnlyDb = false): bool
    {
        if (!$auctionId) {
            log_error('Undefined auction id passed in auction closed check, considering auction as closed');
            return true;
        }
        $isClosed = $this->createAuctionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($auctionId)
            ->filterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->filterClosed()
            ->exist();
        return $isClosed;
    }

    /**
     * Check if registration active
     * SAM-6006: Implement auction start register date
     *
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isRegistrationActive(int $auctionId, bool $isReadOnlyDb = false): bool
    {
        $auction = $this->getAuctionLoader()->load($auctionId, $isReadOnlyDb);
        return $auction && $this->detectIfRegistrationActiveByDatesRange($auction->StartRegisterDate, $auction->EndRegisterDate);
    }

    /**
     * @param DateTime|null $startRegisterDate
     * @param DateTime|null $endRegisterDate
     * @return bool
     */
    public function detectIfRegistrationActiveByDatesRange(?DateTime $startRegisterDate, ?DateTime $endRegisterDate): bool
    {
        $isRegistrationStarted = $startRegisterDate && $startRegisterDate <= $this->getCurrentDateUtc();
        $isRegistrationEnded = $endRegisterDate && $endRegisterDate <= $this->getCurrentDateUtc();
        return $isRegistrationStarted && !$isRegistrationEnded;
    }

    /**
     * @param string $startRegisterDateIso
     * @param string $endRegisterDateIso
     * @return bool
     * @throws Exception
     */
    public function detectIfRegistrationActiveByDatesRangeIso(string $startRegisterDateIso, string $endRegisterDateIso): bool
    {
        $startRegisterDate = $startRegisterDateIso ? new DateTime($startRegisterDateIso) : null;
        $endRegisterDate = $endRegisterDateIso ? new DateTime($endRegisterDateIso) : null;
        return $this->detectIfRegistrationActiveByDatesRange($startRegisterDate, $endRegisterDate);
    }

    /**
     * Show detailed bids info for certain modes only
     * @param Auction $auction
     * @return bool
     */
    public function isAccessOutbidWinningInfo(Auction $auction): bool
    {
        return $auction->NotifyAbsenteeBidders
            || $auction->isTimed();
    }

    /**
     * Check if bidding is paused in auction
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isBiddingPaused(int $auctionId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->createAuctionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterBiddingPaused(true)
            ->filterId($auctionId)
            ->exist();
        return $isFound;
    }

    /**
     * Check, if bulk groups are available in auction
     * @param Auction|null $auction null means grouping is un-available in absent auction
     * @return bool
     */
    public function isLotBulkGroupingAvailable(?Auction $auction): bool
    {
        $isAvailable = $auction
            && $auction->isTimed()
            && !$auction->ExtendAll;
        return $isAvailable;
    }
}
