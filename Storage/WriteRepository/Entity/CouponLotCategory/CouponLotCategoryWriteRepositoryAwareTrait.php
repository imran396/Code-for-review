<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\CouponLotCategory;

trait CouponLotCategoryWriteRepositoryAwareTrait
{
    protected ?CouponLotCategoryWriteRepository $couponLotCategoryWriteRepository = null;

    protected function getCouponLotCategoryWriteRepository(): CouponLotCategoryWriteRepository
    {
        if ($this->couponLotCategoryWriteRepository === null) {
            $this->couponLotCategoryWriteRepository = CouponLotCategoryWriteRepository::new();
        }
        return $this->couponLotCategoryWriteRepository;
    }

    /**
     * @param CouponLotCategoryWriteRepository $couponLotCategoryWriteRepository
     * @return static
     * @internal
     */
    public function setCouponLotCategoryWriteRepository(CouponLotCategoryWriteRepository $couponLotCategoryWriteRepository): static
    {
        $this->couponLotCategoryWriteRepository = $couponLotCategoryWriteRepository;
        return $this;
    }
}
