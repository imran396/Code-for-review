<?php
/**
 * SAM-4440 Refactor feed management logic to \Sam\Feed namespace
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 17, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\EntityLoader;

use Sam\Core\Filter\Availability\FilterFeedAvailabilityAwareTrait;
use Sam\Core\Filter\Conformity\FilterDescriptor;
use Sam\Storage\ReadRepository\Entity\Feed\FeedReadRepository;
use Sam\Storage\ReadRepository\Entity\Feed\FeedReadRepositoryCreateTrait;

/**
 * Trait FeedAllFilterTrait
 */
trait FeedAllFilterTrait
{
    use FeedReadRepositoryCreateTrait;
    use FilterFeedAvailabilityAwareTrait;

    /**
     * @return static
     */
    public function initFilter(): static
    {
        $this->filterFeedActive(true);
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->clearFilterFeed();
        return $this;
    }

    /**
     * @return FilterDescriptor[]
     */
    public function collectFilterDescriptors(): array
    {
        $descriptors = [];
        if ($this->getFilterFeedActive()) {
            $descriptors[] = FilterDescriptor::new()->init(\Feed::class, 'Active', $this->getFilterFeedActive());
        }
        return $descriptors;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return FeedReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb): FeedReadRepository
    {
        $repo = $this->createFeedReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        if ($this->hasFilterFeedActive()) {
            $repo->filterActive($this->getFilterFeedActive());
        }
        return $repo;
    }
}
