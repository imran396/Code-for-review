<?php
/**
 * Location loader and existence checker all common filter trait
 *
 * SAM-4922: Entity Loader and Existence Checker approach integration
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 1, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\EntityLoader;

use Sam\Core\Filter\Availability\FilterLocationAvailabilityAwareTrait;
use Sam\Core\Filter\Conformity\FilterDescriptor;
use Sam\Storage\ReadRepository\Entity\Location\LocationReadRepository;
use Sam\Storage\ReadRepository\Entity\Location\LocationReadRepositoryCreateTrait;

/**
 * Trait LocationAllFilterTrait
 * @package Sam\Core\Filter\EntityLoader
 */
trait LocationAllFilterTrait
{
    use LocationReadRepositoryCreateTrait;
    use FilterLocationAvailabilityAwareTrait;

    /**
     * @return static
     */
    public function initFilter(): static
    {
        $this->filterLocationActive(true);
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->clearFilterLocation();
        return $this;
    }

    /**
     * @return FilterDescriptor[]
     */
    public function collectFilterDescriptors(): array
    {
        $descriptors = [];
        $withFilterLocationActive = $this->getFilterLocationActive();
        if ($withFilterLocationActive) {
            $descriptors[] = FilterDescriptor::new()->init(\Location::class, 'Active', $withFilterLocationActive);
        }
        return $descriptors;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return LocationReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb): LocationReadRepository
    {
        $repo = $this->createLocationReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        if ($this->hasFilterLocationActive()) {
            $repo->filterActive($this->getFilterLocationActive());
        }
        return $repo;
    }
}
