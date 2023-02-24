<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\CouponLotCategory;

trait CouponLotCategoryReadRepositoryCreateTrait
{
    protected ?CouponLotCategoryReadRepository $couponLotCategoryReadRepository = null;

    protected function createCouponLotCategoryReadRepository(): CouponLotCategoryReadRepository
    {
        return $this->couponLotCategoryReadRepository ?: CouponLotCategoryReadRepository::new();
    }

    /**
     * @param CouponLotCategoryReadRepository $couponLotCategoryReadRepository
     * @return static
     * @internal
     */
    public function setCouponLotCategoryReadRepository(CouponLotCategoryReadRepository $couponLotCategoryReadRepository): static
    {
        $this->couponLotCategoryReadRepository = $couponLotCategoryReadRepository;
        return $this;
    }
}
