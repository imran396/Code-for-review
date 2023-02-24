<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Coupon;

trait CouponDeleteRepositoryCreateTrait
{
    protected ?CouponDeleteRepository $couponDeleteRepository = null;

    protected function createCouponDeleteRepository(): CouponDeleteRepository
    {
        return $this->couponDeleteRepository ?: CouponDeleteRepository::new();
    }

    /**
     * @param CouponDeleteRepository $couponDeleteRepository
     * @return static
     * @internal
     */
    public function setCouponDeleteRepository(CouponDeleteRepository $couponDeleteRepository): static
    {
        $this->couponDeleteRepository = $couponDeleteRepository;
        return $this;
    }
}
