<?php
/**
 *
 * SAM-4681: Coupon management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-06
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Coupon\Validate;

/**
 * Trait CouponExistenceCheckerAwareTrait
 * @package Sam\Coupon\Validate
 */
trait CouponExistenceCheckerAwareTrait
{
    /**
     * @var CouponExistenceChecker|null
     */
    protected ?CouponExistenceChecker $couponExistenceChecker = null;

    /**
     * @return CouponExistenceChecker
     */
    protected function getCouponExistenceChecker(): CouponExistenceChecker
    {
        if ($this->couponExistenceChecker === null) {
            $this->couponExistenceChecker = CouponExistenceChecker::new();
        }
        return $this->couponExistenceChecker;
    }

    /**
     * @param CouponExistenceChecker $couponExistenceChecker
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setCouponExistenceChecker(CouponExistenceChecker $couponExistenceChecker): static
    {
        $this->couponExistenceChecker = $couponExistenceChecker;
        return $this;
    }
}
