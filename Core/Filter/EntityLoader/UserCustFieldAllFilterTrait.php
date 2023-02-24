<?php
/**
 * User custom field filter trait
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

use Sam\Core\Filter\Availability\FilterUserCustomFieldAvailabilityAwareTrait;
use Sam\Core\Filter\Conformity\FilterDescriptor;
use Sam\Storage\ReadRepository\Entity\UserCustField\UserCustFieldReadRepository;
use Sam\Storage\ReadRepository\Entity\UserCustField\UserCustFieldReadRepositoryCreateTrait;

/**
 * Trait UserCustFieldAllFilterTrait
 * @package Sam\Core\Filter\EntityLoader
 */
trait UserCustFieldAllFilterTrait
{
    use UserCustFieldReadRepositoryCreateTrait;
    use FilterUserCustomFieldAvailabilityAwareTrait;

    /**
     * @return static
     */
    public function initFilter(): static
    {
        $this->filterUserCustomFieldActive(true);
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->clearFilterUserCustomField();
        return $this;
    }

    /**
     * @return FilterDescriptor[]
     */
    public function collectFilterDescriptors(): array
    {
        $descriptors = [];
        if ($this->hasFilterUserCustomFieldActive()) {
            $descriptors[] = FilterDescriptor::new()->init(\UserCustField::class, 'Active', $this->getFilterUserCustomFieldActive());
        }
        return $descriptors;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return UserCustFieldReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb): UserCustFieldReadRepository
    {
        $repo = $this->createUserCustFieldReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        if ($this->hasFilterUserCustomFieldActive()) {
            $repo->filterActive($this->getFilterUserCustomFieldActive());
        }
        return $repo;
    }
}
