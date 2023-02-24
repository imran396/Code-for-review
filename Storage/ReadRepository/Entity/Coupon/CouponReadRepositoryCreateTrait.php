<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Coupon;

trait CouponReadRepositoryCreateTrait
{
    protected ?CouponReadRepository $couponReadRepository = null;

    protected function createCouponReadRepository(): CouponReadRepository
    {
        return $this->couponReadRepository ?: CouponReadRepository::new();
    }

    /**
     * @param CouponReadRepository $couponReadRepository
     * @return static
     * @internal
     */
    public function setCouponReadRepository(CouponReadRepository $couponReadRepository): static
    {
        $this->couponReadRepository = $couponReadRepository;
        return $this;
    }
}
