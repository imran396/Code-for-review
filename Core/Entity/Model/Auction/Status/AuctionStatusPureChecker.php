<?php
/**
 * Pure checker for different auction statuses.
 * It is stateless service, so it doesn't need construct().
 *
 * SAM-6904: Enrich Auction entity
 * SAM-6822: Enrich entities
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\Auction\Status;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionStatusChecker
 * @package Sam\Core\Entity\Model\Auction\Status
 */
class AuctionStatusPureChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Auction type related checks ---

    /**
     * @param string|null $auctionType
     * @return bool
     * #[Pure]
     */
    public function isTimed(?string $auctionType): bool
    {
        return $auctionType === Constants\Auction::TIMED;
    }

    /**
     * @param string|null $auctionType
     * @param int|null $eventType
     * @return bool
     * #[Pure]
     */
    public function isTimedScheduled(?string $auctionType, ?int $eventType): bool
    {
        return $this->isTimed($auctionType)
            && $eventType === Constants\Auction::ET_SCHEDULED;
    }

    /**
     * @param string|null $auctionType
     * @param int|null $eventType
     * @return bool
     * #[Pure]
     */
    public function isTimedOngoing(?string $auctionType, ?int $eventType): bool
    {
        return $this->isTimed($auctionType)
            && $eventType === Constants\Auction::ET_ONGOING;
    }

    /**
     * @param string|null $auctionType
     * @return bool
     * #[Pure]
     */
    public function isLive(?string $auctionType): bool
    {
        return $auctionType === Constants\Auction::LIVE;
    }

    /**
     * @param string|null $auctionType
     * @return bool
     */
    public function isHybrid(?string $auctionType): bool
    {
        return $auctionType === Constants\Auction::HYBRID;
    }

    /**
     * @param string|null $auctionType
     * @return bool
     */
    public function isLiveOrHybrid(?string $auctionType): bool
    {
        return $this->isLive($auctionType) || $this->isHybrid($auctionType);
    }

    // --- Auction status related checks ---

    /**
     * @param int|null $auctionStatus
     * @return bool
     */
    public function isActive(?int $auctionStatus): bool
    {
        return $auctionStatus === Constants\Auction::AS_ACTIVE;
    }

    /**
     * @param int|null $auctionStatus
     * @return bool
     */
    public function isStarted(?int $auctionStatus): bool
    {
        return $auctionStatus === Constants\Auction::AS_STARTED;
    }

    /**
     * @param int|null $auctionStatus
     * @return bool
     */
    public function isPaused(?int $auctionStatus): bool
    {
        return $auctionStatus === Constants\Auction::AS_PAUSED;
    }

    /**
     * @param int|null $auctionStatus
     * @return bool
     */
    public function isDeleted(?int $auctionStatus): bool
    {
        return $auctionStatus === Constants\Auction::AS_DELETED;
    }

    /**
     * @param int|null $auctionStatus
     * @return bool
     */
    public function isArchived(?int $auctionStatus): bool
    {
        return $auctionStatus === Constants\Auction::AS_ARCHIVED;
    }

    /**
     * @param int|null $auctionStatus
     * @return bool
     */
    public function isClosed(?int $auctionStatus): bool
    {
        return $auctionStatus === Constants\Auction::AS_CLOSED;
    }

    /**
     * @param int|null $auctionStatus
     * @return bool
     */
    public function isStartedOrPaused(?int $auctionStatus): bool
    {
        return $this->isStarted($auctionStatus) || $this->isPaused($auctionStatus);
    }

    /**
     * @param int|null $auctionStatus
     * @return bool
     */
    public function isDeletedOrArchived(?int $auctionStatus): bool
    {
        return $this->isDeleted($auctionStatus) || $this->isArchived($auctionStatus);
    }

    /**
     * @param int|null $auctionStatus
     * @return bool
     */
    public function isAvailableAuctionStatus(?int $auctionStatus): bool
    {
        return in_array($auctionStatus, Constants\Auction::$availableAuctionStatuses, true);
    }

    /**
     * @param int|null $auctionStatus
     * @return bool
     */
    public function amongNotDeletedAuctionStatuses(?int $auctionStatus): bool
    {
        return in_array($auctionStatus, Constants\Auction::$notDeletedAuctionStatuses, true);
    }

    // --- Auction Date related checks ---

    /**
     * Is enabled "Auction to items" date assigment strategy
     * @param int $strategy
     * @return bool
     */
    public function isAuctionToItemsDateAssignment(int $strategy): bool
    {
        return $strategy === Constants\Auction::DAS_AUCTION_TO_ITEMS;
    }

    /**
     * Is enabled "Items to auction" date assigment strategy
     * @param int $strategy
     * @return bool
     */
    public function isItemsToAuctionDateAssignment(int $strategy): bool
    {
        return $strategy === Constants\Auction::DAS_ITEMS_TO_AUCTION;
    }

    public function isLotBulkGroupingAvailable(?string $auctionType, ?bool $extendAll): bool
    {
        return $this->isTimed($auctionType) && !$extendAll;
    }

    // --- Live sale clerking ---

    /**
     * Check if Clerking console has the "Simple" style.
     * @param string|null $clerkingStyle
     * @return bool
     */
    public function isSimpleClerking(?string $clerkingStyle): bool
    {
        return $clerkingStyle === Constants\Auction::CS_SIMPLE;
    }

    /**
     * Check if Clerking console has the "Advanced" style.
     * @param string|null $clerkingStyle
     * @return bool
     */
    public function isAdvancedClerking(?string $clerkingStyle): bool
    {
        return $clerkingStyle === Constants\Auction::CS_ADVANCED;
    }

    // --- Auction settings - absentee bidding ---

    public function isAbsenteeBidsDisplaySetAsNumberOfAbsentee(?string $absenteeBidsDisplay): bool
    {
        return $absenteeBidsDisplay === Constants\SettingAuction::ABD_NUMBER_OF_ABSENTEE;
    }

    public function isAbsenteeBidsDisplaySetAsNumberOfAbsenteeHigh(?string $absenteeBidsDisplay): bool
    {
        return $absenteeBidsDisplay === Constants\SettingAuction::ABD_NUMBER_OF_ABSENTEE_HIGH;
    }

    public function isAbsenteeBidsDisplaySetAsNumberOfAbsenteeLink(?string $absenteeBidsDisplay): bool
    {
        return $absenteeBidsDisplay === Constants\SettingAuction::ABD_NUMBER_OF_ABSENTEE_LINK;
    }

    public function isAbsenteeBidsDisplaySetAsDoNotDisplay(?string $absenteeBidsDisplay): bool
    {
        return $absenteeBidsDisplay === Constants\SettingAuction::ABD_DO_NOT_DISPLAY;
    }
}
