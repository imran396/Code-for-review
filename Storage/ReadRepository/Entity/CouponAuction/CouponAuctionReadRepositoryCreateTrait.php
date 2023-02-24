<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\CouponAuction;

trait CouponAuctionReadRepositoryCreateTrait
{
    protected ?CouponAuctionReadRepository $couponAuctionReadRepository = null;

    protected function createCouponAuctionReadRepository(): CouponAuctionReadRepository
    {
        return $this->couponAuctionReadRepository ?: CouponAuctionReadRepository::new();
    }

    /**
     * @param CouponAuctionReadRepository $couponAuctionReadRepository
     * @return static
     * @internal
     */
    public function setCouponAuctionReadRepository(CouponAuctionReadRepository $couponAuctionReadRepository): static
    {
        $this->couponAuctionReadRepository = $couponAuctionReadRepository;
        return $this;
    }
}
