<?php
/**
 * Timed lot item existence checker
 *
 * SAM-4978: Timed Item existence checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/24/2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Validate;

use Sam\Core\Constants;
use Sam\Core\Filter\Availability\FilterAccountAvailabilityAwareTrait;
use Sam\Core\Filter\Availability\FilterAuctionAvailabilityAwareTrait;
use Sam\Core\Filter\Availability\FilterLotItemAvailabilityAwareTrait;
use Sam\Core\Validate\EntityExistenceCheckerBase;
use Sam\Storage\ReadRepository\Entity\TimedOnlineItem\TimedOnlineItemReadRepository;
use Sam\Storage\ReadRepository\Entity\TimedOnlineItem\TimedOnlineItemReadRepositoryCreateTrait;

/**
 * Class TimedItemExistenceChecker
 * @package Sam\AuctionLot\Validate
 */
class TimedItemExistenceChecker extends EntityExistenceCheckerBase
{
    use FilterAccountAvailabilityAwareTrait;
    use FilterAuctionAvailabilityAwareTrait;
    use FilterLotItemAvailabilityAwareTrait;
    use TimedOnlineItemReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->filterAccountActive(true);
        $this->filterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses);
        $this->filterLotItemActive(true);
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->clearFilterAccount();
        $this->clearFilterAuction();
        $this->clearFilterLotItem();
        return $this;
    }

    /**
     * Check whether an TimedOnlineItem exists for an auction.id and lot_item.id among available statuses
     *
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function exist(int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): bool
    {
        $isFound = false;
        if ($lotItemId) {
            $isFound = $this->prepareRepository($isReadOnlyDb)
                ->filterAuctionId($auctionId)
                ->filterLotItemId($lotItemId)
                ->exist();
        }
        return $isFound;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return TimedOnlineItemReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb): TimedOnlineItemReadRepository
    {
        $repo = $this->createTimedOnlineItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        if ($this->hasFilterAccountActive()) {
            $repo->joinAccountFilterActive($this->getFilterAccountActive());
        }
        if ($this->hasFilterAuctionStatusId()) {
            $repo->joinAuctionFilterAuctionStatusId($this->getFilterAuctionStatusId());
        }
        if ($this->hasFilterLotItemActive()) {
            $repo->joinLotItemFilterActive($this->getFilterLotItemActive());
        }
        return $repo;
    }
}
