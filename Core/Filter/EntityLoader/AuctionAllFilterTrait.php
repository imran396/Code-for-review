<?php
/**
 * Auction loader and existence checker all common filter trait
 *
 * SAM-4922: Entity Loader and Existence Checker approach integration
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 29, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\EntityLoader;

use Account;
use Auction;
use Sam\Core\Constants;
use Sam\Core\Filter\Availability\FilterAccountAvailabilityAwareTrait;
use Sam\Core\Filter\Availability\FilterAuctionAvailabilityAwareTrait;
use Sam\Core\Filter\Conformity\FilterDescriptor;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepository;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionCache\AuctionCacheReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionCache\AuctionCacheReadRepositoryCreateTrait;

/**
 * Trait AuctionAllFilterTrait
 * @package Sam\Core\Filter\EntityLoader
 */
trait AuctionAllFilterTrait
{
    use AuctionCacheReadRepositoryCreateTrait;
    use AuctionReadRepositoryCreateTrait;
    use FilterAccountAvailabilityAwareTrait;
    use FilterAuctionAvailabilityAwareTrait;

    /**
     * @return static
     */
    public function initFilter(): static
    {
        $this->filterAccountActive(true);
        $this->filterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses);
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->clearFilterAccount();
        $this->clearFilterAuction();
        return $this;
    }

    /**
     * @return FilterDescriptor[]
     */
    public function collectFilterDescriptors(): array
    {
        $descriptors = [];
        if ($this->hasFilterAccountActive()) {
            $descriptors[] = FilterDescriptor::new()->init(Account::class, 'Active', $this->getFilterAccountActive());
        }
        if ($this->hasFilterAuctionStatusId()) {
            $descriptors[] = FilterDescriptor::new()->init(Auction::class, 'AuctionStatusId', $this->getFilterAuctionStatusId());
        }
        return $descriptors;
    }

    /**
     * @param AuctionCacheReadRepository|AuctionReadRepository $repo
     * @param bool $isReadOnlyDb
     * @return AuctionCacheReadRepository|AuctionReadRepository
     */
    protected function applyCommonStatusFilters(
        AuctionCacheReadRepository|AuctionReadRepository $repo,
        bool $isReadOnlyDb
    ): AuctionCacheReadRepository|AuctionReadRepository {
        $repo->enableReadOnlyDb($isReadOnlyDb);
        if ($this->hasFilterAccountActive()) {
            $repo->joinAccountFilterActive($this->getFilterAccountActive());
        }
        return $repo;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return AuctionReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb): AuctionReadRepository
    {
        $repo = $this->applyCommonStatusFilters($this->createAuctionReadRepository(), $isReadOnlyDb);
        if ($this->hasFilterAuctionStatusId()) {
            $repo->filterAuctionStatusId($this->getFilterAuctionStatusId());
        }
        return $repo;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return AuctionCacheReadRepository
     */
    protected function prepareCacheRepository(bool $isReadOnlyDb): AuctionCacheReadRepository
    {
        $repo = $this->applyCommonStatusFilters($this->createAuctionCacheReadRepository(), $isReadOnlyDb);
        if ($this->hasFilterAuctionStatusId()) {
            $repo->joinAuctionFilterAuctionStatusId($this->getFilterAuctionStatusId());
        }
        return $repo;
    }
}
