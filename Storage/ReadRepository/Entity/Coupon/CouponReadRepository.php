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
 * // Sample1. Check, count and load array of Coupon filtered by criteria
 * $couponRepository = \Sam\Storage\ReadRepository\Entity\Coupon\CouponReadRepository::new()
 *     ->filterId($ids);   // array passed as argument
 *
 * $isFound = $couponRepository->exist();
 * $count = $couponRepository->count();
 * $coupons = $couponRepository->loadEntities();
 *
 * // Sample2. Load single CouponAuction
 * $couponRepository = \Sam\Storage\ReadRepository\Entity\Coupon\CouponReadRepository::new()
 *     ->filterId(1);
 * $coupon = $couponRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\Coupon;

use Sam\Core\Constants;

/**
 * Class CouponReadRepository
 * @package Sam\Storage\ReadRepository\Entity\Coupon
 */
class CouponReadRepository extends AbstractCouponReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'account' => 'JOIN account acc ON acc.id = co.account_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join 'account' table
     * @return static
     */
    public function joinAccount(): static
    {
        $this->join('account');
        return $this;
    }

    /**
     * Left join account table
     * Define filtering by account.active
     * @param bool|bool[] $active
     * @return static
     */
    public function joinAccountFilterActive(bool|array|null $active): static
    {
        $this->joinAccount();
        $this->filterArray('acc.active', $active);
        return $this;
    }

    /**
     * @param bool $ascending
     * @return static
     */
    public function orderByCouponTypeName(bool $ascending = true): static
    {
        $typeNames = Constants\Coupon::$typeNames;
        sort($typeNames);
        $caseExpr = $this->makeCase("co.coupon_type", $typeNames);
        $this->order($caseExpr, $ascending);
        return $this;
    }
}
