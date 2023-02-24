<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\CouponLotCategory;

trait CouponLotCategoryDeleteRepositoryCreateTrait
{
    protected ?CouponLotCategoryDeleteRepository $couponLotCategoryDeleteRepository = null;

    protected function createCouponLotCategoryDeleteRepository(): CouponLotCategoryDeleteRepository
    {
        return $this->couponLotCategoryDeleteRepository ?: CouponLotCategoryDeleteRepository::new();
    }

    /**
     * @param CouponLotCategoryDeleteRepository $couponLotCategoryDeleteRepository
     * @return static
     * @internal
     */
    public function setCouponLotCategoryDeleteRepository(CouponLotCategoryDeleteRepository $couponLotCategoryDeleteRepository): static
    {
        $this->couponLotCategoryDeleteRepository = $couponLotCategoryDeleteRepository;
        return $this;
    }
}
