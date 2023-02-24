<?php
/**
 * Account loader and existence checker all common filter trait
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

use Account;
use Sam\Core\Filter\Availability\FilterAccountAvailabilityAwareTrait;
use Sam\Core\Filter\Conformity\FilterDescriptor;
use Sam\Storage\ReadRepository\Entity\Account\AccountReadRepository;
use Sam\Storage\ReadRepository\Entity\Account\AccountReadRepositoryCreateTrait;

/**
 * Trait AccountAllFilterTrait
 * @package Sam\Core\Filter\EntityLoader
 */
trait AccountAllFilterTrait
{
    use AccountReadRepositoryCreateTrait;
    use FilterAccountAvailabilityAwareTrait;

    /**
     * @return static
     */
    public function initFilter(): static
    {
        $this->filterAccountActive(true);
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->clearFilterAccount();
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
        return $descriptors;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return AccountReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb): AccountReadRepository
    {
        $repo = $this->createAccountReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        if ($this->hasFilterAccountActive()) {
            $repo->filterActive($this->getFilterAccountActive());
        }
        return $repo;
    }
}
