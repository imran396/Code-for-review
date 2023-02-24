<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           30.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Storage\Entity\AwareTrait;

use Coupon;
use Sam\Storage\Entity\Aggregate\CouponAggregate;

/**
 * Trait CouponAwareTrait
 * @package Sam\Storage\Entity\AwareTrait
 */
trait CouponAwareTrait
{
    protected ?CouponAggregate $couponAggregate = null;

    /**
     * @return int|null
     */
    public function getCouponId(): ?int
    {
        return $this->getCouponAggregate()->getCouponId();
    }

    /**
     * @param int|null $couponId
     * @return static
     */
    public function setCouponId(int|null $couponId): static
    {
        $this->getCouponAggregate()->setCouponId($couponId);
        return $this;
    }

    /**
     * Return Coupon|null object
     * @param bool $isReadOnlyDb
     * @return Coupon|null
     */
    public function getCoupon(bool $isReadOnlyDb = false): ?Coupon
    {
        return $this->getCouponAggregate()->getCoupon($isReadOnlyDb);
    }

    /**
     * @param Coupon|null $coupon
     * @return static
     */
    public function setCoupon(?Coupon $coupon): static
    {
        $this->getCouponAggregate()->setCoupon($coupon);
        return $this;
    }

    // --- CouponAggregate ---

    /**
     * @return CouponAggregate
     */
    protected function getCouponAggregate(): CouponAggregate
    {
        if ($this->couponAggregate === null) {
            $this->couponAggregate = CouponAggregate::new();
        }
        return $this->couponAggregate;
    }
}
