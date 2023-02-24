<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Coupon;

trait CouponWriteRepositoryAwareTrait
{
    protected ?CouponWriteRepository $couponWriteRepository = null;

    protected function getCouponWriteRepository(): CouponWriteRepository
    {
        if ($this->couponWriteRepository === null) {
            $this->couponWriteRepository = CouponWriteRepository::new();
        }
        return $this->couponWriteRepository;
    }

    /**
     * @param CouponWriteRepository $couponWriteRepository
     * @return static
     * @internal
     */
    public function setCouponWriteRepository(CouponWriteRepository $couponWriteRepository): static
    {
        $this->couponWriteRepository = $couponWriteRepository;
        return $this;
    }
}
