<?php
/**
 * Coupon loader and existence checker all common filter trait
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
use Sam\Core\Filter\Availability\FilterCouponAvailabilityAwareTrait;
use Sam\Core\Filter\Conformity\FilterDescriptor;
use Sam\Storage\ReadRepository\Entity\Coupon\CouponReadRepository;
use Sam\Storage\ReadRepository\Entity\Coupon\CouponReadRepositoryCreateTrait;

/**
 * Trait CouponAllFilterTrait
 * @package Sam\Core\Filter\EntityLoader
 */
trait CouponAllFilterTrait
{
    use CouponReadRepositoryCreateTrait;
    use FilterAccountAvailabilityAwareTrait;
    use FilterCouponAvailabilityAwareTrait;

    /**
     * @return static
     */
    public function initFilter(): static
    {
        $this->filterAccountActive(true);
        $this->filterCouponStatusId(Constants\Coupon::$availableCouponStatuses);
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->clearFilterAccount();
        $this->clearFilterCoupon();
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
        if ($this->getFilterCouponStatusId()) {
            $descriptors[] = FilterDescriptor::new()->init(\Coupon::class, 'CouponStatusId', $this->getFilterCouponStatusId());
        }
        return $descriptors;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return CouponReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb): CouponReadRepository
    {
        $repo = $this->createCouponReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        if ($this->hasFilterAccountActive()) {
            $repo->joinAccountFilterActive($this->getFilterAccountActive());
        }
        if ($this->hasFilterCouponStatusId()) {
            $repo->filterCouponStatusId($this->getFilterCouponStatusId());
        }
        return $repo;
    }
}
