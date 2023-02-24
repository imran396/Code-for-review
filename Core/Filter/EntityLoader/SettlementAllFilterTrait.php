<?php
/**
 * Settlement loader and existence checker all common filter trait
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

use Sam\Core\Constants;
use Sam\Core\Filter\Availability\FilterAccountAvailabilityAwareTrait;
use Sam\Core\Filter\Availability\FilterSettlementAvailabilityAwareTrait;
use Sam\Core\Filter\Conformity\FilterDescriptor;
use Sam\Storage\ReadRepository\Entity\Settlement\SettlementReadRepository;
use Sam\Storage\ReadRepository\Entity\Settlement\SettlementReadRepositoryCreateTrait;

/**
 * Trait SettlementAllFilterTrait
 * @package Sam\Core\Filter\EntityLoader
 */
trait SettlementAllFilterTrait
{
    use SettlementReadRepositoryCreateTrait;
    use FilterAccountAvailabilityAwareTrait;
    use FilterSettlementAvailabilityAwareTrait;

    /**
     * @return static
     */
    public function initFilter(): static
    {
        $this->filterAccountActive(true);
        $this->filterSettlementStatusId(Constants\Settlement::$availableSettlementStatuses);
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->clearFilterAccount();
        $this->clearFilterSettlement();
        return $this;
    }

    /**
     * @return FilterDescriptor[]
     */
    public function collectFilterDescriptors(): array
    {
        $descriptors = [];
        if ($this->getFilterAccountActive()) {
            $descriptors[] = FilterDescriptor::new()->init(\Account::class, 'Active', $this->getFilterAccountActive());
        }
        if ($this->getFilterSettlementStatusId()) {
            $descriptors[] = FilterDescriptor::new()->init(\Settlement::class, 'SettlementStatusId', $this->getFilterSettlementStatusId());
        }
        return $descriptors;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return SettlementReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb): SettlementReadRepository
    {
        $repo = $this->createSettlementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        if ($this->hasFilterAccountActive()) {
            $repo->joinAccountFilterActive($this->getFilterAccountActive());
        }
        if ($this->hasFilterSettlementStatusId()) {
            $repo->filterSettlementStatusId($this->getFilterSettlementStatusId());
        }
        return $repo;
    }
}
