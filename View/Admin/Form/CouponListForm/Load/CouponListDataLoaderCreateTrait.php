<?php
/**
 * Coupon List Data Loader Create Trait
 *
 * SAM-6441: Refactor coupon list page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\CouponListForm\Load;


/**
 * Trait CouponListDataLoaderCreateTrait
 */
trait CouponListDataLoaderCreateTrait
{
    protected ?CouponListDataLoader $couponListDataLoader = null;

    /**
     * @return CouponListDataLoader
     */
    protected function createCouponListDataLoader(): CouponListDataLoader
    {
        return $this->couponListDataLoader ?: CouponListDataLoader::new();
    }

    /**
     * @param CouponListDataLoader $couponListDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setCouponListDataLoader(CouponListDataLoader $couponListDataLoader): static
    {
        $this->couponListDataLoader = $couponListDataLoader;
        return $this;
    }
}
