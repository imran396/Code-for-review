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

namespace Sam\Coupon\Delete;

/**
 * Trait CouponDeleterAwareTrait
 * @package Sam\Coupon\Delete
 */
trait CouponDeleterAwareTrait
{
    /**
     * @var CouponDeleter|null
     */
    protected ?CouponDeleter $couponDeleter = null;

    /**
     * @return CouponDeleter
     */
    protected function getCouponDeleter(): CouponDeleter
    {
        if ($this->couponDeleter === null) {
            $this->couponDeleter = CouponDeleter::new();
        }
        return $this->couponDeleter;
    }

    /**
     * @param CouponDeleter $couponDeleter
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setCouponDeleter(CouponDeleter $couponDeleter): static
    {
        $this->couponDeleter = $couponDeleter;
        return $this;
    }
}
