<?php
/**
 * Auction lot loader and existence checker all common filter trait
 *
 * SAM-4922: Entity Loader and Existence Checker approach integration
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 28, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\EntityLoader;

use Account;
use Auction;
use AuctionLotItem;
use LotItem;
use Sam\Core\Constants;
use Sam\Core\Filter\Availability\FilterAccountAvailabilityAwareTrait;
use Sam\Core\Filter\Availability\FilterAuctionAvailabilityAwareTrait;
use Sam\Core\Filter\Availability\FilterAuctionLotAvailabilityAwareTrait;
use Sam\Core\Filter\Availability\FilterLotItemAvailabilityAwareTrait;
use Sam\Core\Filter\Conformity\FilterDescriptor;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItemCache\AuctionLotItemCacheReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionLotItemCache\AuctionLotItemCacheReadRepositoryCreateTrait;

/**
 * trait AuctionLotAllFilterTrait
 * @package Sam\Core\Filter\EntityLoader
 */
trait AuctionLotAllFilterTrait
{
    use AuctionLotItemCacheReadRepositoryCreateTrait;
    use AuctionLotItemReadRepositoryCreateTrait;
    use FilterAccountAvailabilityAwareTrait;
    use FilterAuctionAvailabilityAwareTrait;
    use FilterAuctionLotAvailabilityAwareTrait;
    use FilterLotItemAvailabilityAwareTrait;

    /**
     * @return static
     */
    public function initFilter(): static
    {
        $this->filterAccountActive(true);
        $this->filterLotStatusId(Constants\Lot::$availableLotStatuses);
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
        $this->clearFilterAuctionLot();
        $this->clearFilterLotItem();
        return $this;
    }

    /**
     * @return FilterDescriptor[]
     */
    public function collectFilterDescriptors(): array
    {
        $descriptors = [];
        if ($this->getFilterAccountActive()) {
            $descriptors[] = FilterDescriptor::new()->init(Account::class, 'Active', $this->getFilterAccountActive());
        }
        if ($this->getFilterAuctionStatusId()) {
            $descriptors[] = FilterDescriptor::new()->init(Auction::class, 'AuctionStatusId', $this->getFilterAuctionStatusId());
        }
        if ($this->getFilterLotItemActive()) {
            $descriptors[] = FilterDescriptor::new()->init(LotItem::class, 'Active', $this->getFilterLotItemActive());
        }
        if ($this->getFilterLotStatusId()) {
            $descriptors[] = FilterDescriptor::new()->init(AuctionLotItem::class, 'LotStatusId', $this->getFilterLotStatusId());
        }
        return $descriptors;
    }

    /**
     * @param AuctionLotItemReadRepository|AuctionLotItemCacheReadRepository $repo
     * @param bool $isReadOnlyDb
     * @return AuctionLotItemReadRepository|AuctionLotItemCacheReadRepository
     */
    protected function applyCommonStatusFilters(
        AuctionLotItemReadRepository|AuctionLotItemCacheReadRepository $repo,
        bool $isReadOnlyDb
    ): AuctionLotItemReadRepository|AuctionLotItemCacheReadRepository {
        $repo->enableReadOnlyDb($isReadOnlyDb);
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

    /**
     * @param bool $isReadOnlyDb
     * @return AuctionLotItemReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb): AuctionLotItemReadRepository
    {
        $repo = $this->applyCommonStatusFilters($this->createAuctionLotItemReadRepository(), $isReadOnlyDb);
        if ($this->hasFilterLotStatusId()) {
            $repo->filterLotStatusId($this->getFilterLotStatusId());
        }
        return $repo;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return AuctionLotItemCacheReadRepository
     */
    protected function prepareCacheRepository(bool $isReadOnlyDb): AuctionLotItemCacheReadRepository
    {
        $repo = $this->applyCommonStatusFilters($this->createAuctionLotItemCacheReadRepository(), $isReadOnlyDb);
        if ($this->hasFilterLotStatusId()) {
            $repo->joinAuctionLotItemFilterLotStatusId($this->getFilterLotStatusId());
        }
        return $repo;
    }
}
