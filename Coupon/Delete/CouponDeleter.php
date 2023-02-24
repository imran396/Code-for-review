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

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\DeleteRepository\Entity\CouponAuction\CouponAuctionDeleteRepositoryCreateTrait;
use Sam\Storage\DeleteRepository\Entity\CouponLotCategory\CouponLotCategoryDeleteRepositoryCreateTrait;

/**
 * Class CouponDeleter
 * @package Sam\Coupon\Delete
 */
class CouponDeleter extends CustomizableClass
{
    use CouponAuctionDeleteRepositoryCreateTrait;
    use CouponLotCategoryDeleteRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $couponId
     */
    public function deleteAuctions(int $couponId): void
    {
        $this->createCouponAuctionDeleteRepository()
            ->filterCouponId($couponId)
            ->delete();
    }

    /**
     * @param int $couponId
     */
    public function deleteCategories(int $couponId): void
    {
        $this->createCouponLotCategoryDeleteRepository()
            ->filterCouponId($couponId)
            ->delete();
    }
}
