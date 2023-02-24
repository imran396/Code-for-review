<?php
/**
 * Invoice loader and existence checker all common filter trait
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
use Sam\Core\Filter\Availability\FilterInvoiceAvailabilityAwareTrait;
use Sam\Core\Filter\Conformity\FilterDescriptor;
use Sam\Storage\ReadRepository\Entity\Invoice\InvoiceReadRepository;
use Sam\Storage\ReadRepository\Entity\Invoice\InvoiceReadRepositoryCreateTrait;

/**
 * Trait InvoiceAllFilterTrait
 * @package Sam\Core\Filter\EntityLoader
 */
trait InvoiceAllFilterTrait
{
    use InvoiceReadRepositoryCreateTrait;
    use FilterAccountAvailabilityAwareTrait;
    use FilterInvoiceAvailabilityAwareTrait;

    /**
     * @return static
     */
    public function initFilter(): static
    {
        $this->filterAccountActive(true);
        $this->filterInvoiceStatusId(Constants\Invoice::$availableInvoiceStatuses);
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->clearFilterAccount();
        $this->clearFilterInvoice();
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
        if ($this->getFilterInvoiceStatusId()) {
            $descriptors[] = FilterDescriptor::new()->init(\Invoice::class, 'InvoiceStatusId', $this->getFilterInvoiceStatusId());
        }
        return $descriptors;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return InvoiceReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb): InvoiceReadRepository
    {
        $repo = $this->createInvoiceReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        if ($this->hasFilterAccountActive()) {
            $repo->joinAccountFilterActive($this->getFilterAccountActive());
        }
        if ($this->hasFilterInvoiceStatusId()) {
            $repo->filterInvoiceStatusId($this->getFilterInvoiceStatusId());
        }
        return $repo;
    }
}
