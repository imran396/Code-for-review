<?php
/**
 * Timezone loader and existence checker all common filter trait
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

use Sam\Core\Filter\Availability\FilterTimezoneAvailabilityAwareTrait;
use Sam\Core\Filter\Conformity\FilterDescriptor;
use Sam\Storage\ReadRepository\Entity\Timezone\TimezoneReadRepository;
use Sam\Storage\ReadRepository\Entity\Timezone\TimezoneReadRepositoryCreateTrait;

/**
 * Trait TimezoneAllFilterTrait
 * @package Sam\Core\Filter\EntityLoader
 */
trait TimezoneAllFilterTrait
{
    use FilterTimezoneAvailabilityAwareTrait;
    use TimezoneReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public function initFilter(): static
    {
        $this->filterTimezoneActive(true);
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->clearFilterTimezone();
        return $this;
    }

    /**
     * @return FilterDescriptor[]
     */
    public function collectFilterDescriptors(): array
    {
        $descriptors = [];
        if ($this->getFilterTimezoneActive()) {
            $descriptors[] = FilterDescriptor::new()->init(\Timezone::class, 'Active', $this->getFilterTimezoneActive());
        }
        return $descriptors;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return TimezoneReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb): TimezoneReadRepository
    {
        $repo = $this->createTimezoneReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        if ($this->hasFilterTimezoneActive()) {
            $repo->filterActive($this->getFilterTimezoneActive());
        }
        return $repo;
    }
}
