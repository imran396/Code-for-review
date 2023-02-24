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

namespace Sam\Coupon\Load;

/**
 * Trait CouponLoaderAwareTrait
 * @package Sam\Coupon\Load
 */
trait CouponLoaderAwareTrait
{
    /**
     * @var CouponLoader|null
     */
    protected ?CouponLoader $couponLoader = null;

    /**
     * @return CouponLoader
     */
    protected function getCouponLoader(): CouponLoader
    {
        if ($this->couponLoader === null) {
            $this->couponLoader = CouponLoader::new();
        }
        return $this->couponLoader;
    }

    /**
     * @param CouponLoader $couponLoader
     * @return static
     * @internal
     */
    public function setCouponLoader(CouponLoader $couponLoader): static
    {
        $this->couponLoader = $couponLoader;
        return $this;
    }
}
