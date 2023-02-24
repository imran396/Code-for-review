<?php
/**
 * Lot item loader and existence checker all common filter trait
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

use Sam\Core\Filter\Availability\FilterAccountAvailabilityAwareTrait;
use Sam\Core\Filter\Availability\FilterLotItemAvailabilityAwareTrait;
use Sam\Core\Filter\Conformity\FilterDescriptor;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepositoryCreateTrait;

/**
 * Trait LotItemAllFilterTrait
 * @package Sam\Core\Filter\EntityLoader
 */
trait LotItemAllFilterTrait
{
    use FilterAccountAvailabilityAwareTrait;
    use FilterLotItemAvailabilityAwareTrait;
    use LotItemReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public function initFilter(): static
    {
        $this->filterAccountActive(true);
        $this->filterLotItemActive(true);
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->clearFilterAccount();
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
            $descriptors[] = FilterDescriptor::new()->init(\Account::class, 'Active', $this->getFilterAccountActive());
        }
        if ($this->getFilterLotItemActive()) {
            $descriptors[] = FilterDescriptor::new()->init(\LotItem::class, 'Active', $this->getFilterLotItemActive());
        }
        return $descriptors;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return LotItemReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb): LotItemReadRepository
    {
        $repo = $this->createLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        if ($this->hasFilterLotItemActive()) {
            $repo->filterActive($this->getFilterLotItemActive());
        }
        if ($this->hasFilterAccountActive()) {
            $repo->joinAccountFilterActive($this->getFilterAccountActive());
        }
        return $repo;
    }
}
