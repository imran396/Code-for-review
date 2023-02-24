<?php
/**
 * SAM-3696 : Coupon related repositories  https://bidpath.atlassian.net/browse/SAM-3696
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           17 May, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of CouponLotCategory filtered by criteria
 * $couponLotCategoryRepository = \Sam\Storage\ReadRepository\Entity\CouponLotCategory\CouponLotCategoryReadRepository::new()
 *     ->filterId($ids);   // array passed as argument
 *
 * $isFound = $couponLotCategoryRepository->exist();
 * $count = $couponLotCategoryRepository->count();
 * $couponLotCategory = $couponLotCategoryRepository->loadEntities();
 *
 * // Sample2. Load single CouponLotCategory
 * $couponLotCategoryRepository = \Sam\Storage\ReadRepository\Entity\CouponLotCategory\CouponLotCategoryReadRepository::new()
 *     ->filterId(1);
 * $couponLotCategory = $couponLotCategoryRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\CouponLotCategory;

/**
 * Class CouponLotCategoryReadRepository
 * @package Sam\Storage\ReadRepository\Entity\CouponLotCategory
 */
class CouponLotCategoryReadRepository extends AbstractCouponLotCategoryReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'lot_category' => 'JOIN lot_category lc ON lc.id = clc.lot_category_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * join `lot_category` table
     * @return static
     */
    public function joinLotCategory(): static
    {
        $this->join('lot_category');
        return $this;
    }

    /**
     * Define filtering by lc.active
     * @param bool|bool[] $active
     * @return static
     */
    public function joinLotCategoryFilterActive(bool|array|null $active): static
    {
        $this->joinLotCategory();
        $this->filterArray('lc.active', $active);
        return $this;
    }
}
