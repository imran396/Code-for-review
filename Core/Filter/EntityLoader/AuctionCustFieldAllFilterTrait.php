<?php
/**
 * Auction custom field filter trait
 *
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\EntityLoader;

use AuctionCustField;
use Sam\Core\Filter\Availability\FilterAuctionCustomFieldAvailabilityAwareTrait;
use Sam\Core\Filter\Conformity\FilterDescriptor;
use Sam\Storage\ReadRepository\Entity\AuctionCustField\AuctionCustFieldReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionCustField\AuctionCustFieldReadRepositoryCreateTrait;

/**
 * Trait AuctionCustFieldAllFilterTrait
 * @package Sam\Core\Filter\EntityLoader
 */
trait AuctionCustFieldAllFilterTrait
{
    use AuctionCustFieldReadRepositoryCreateTrait;
    use FilterAuctionCustomFieldAvailabilityAwareTrait;

    /**
     * @return static
     */
    public function initFilter(): static
    {
        $this->filterAuctionCustomFieldActive(true);
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->clearFilterAuctionCustomField();
        return $this;
    }

    /**
     * @return FilterDescriptor[]
     */
    public function collectFilterDescriptors(): array
    {
        $descriptors = [];
        if ($this->hasFilterAuctionCustomFieldActive()) {
            $descriptors[] = FilterDescriptor::new()->init(AuctionCustField::class, 'Active', $this->getFilterAuctionCustomFieldActive());
        }
        return $descriptors;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return AuctionCustFieldReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb): AuctionCustFieldReadRepository
    {
        $repo = $this->createAuctionCustFieldReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        if ($this->hasFilterAuctionCustomFieldActive()) {
            $repo->filterActive($this->getFilterAuctionCustomFieldActive());
        }
        return $repo;
    }
}
