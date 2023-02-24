<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\CouponAuction;

trait CouponAuctionDeleteRepositoryCreateTrait
{
    protected ?CouponAuctionDeleteRepository $couponAuctionDeleteRepository = null;

    protected function createCouponAuctionDeleteRepository(): CouponAuctionDeleteRepository
    {
        return $this->couponAuctionDeleteRepository ?: CouponAuctionDeleteRepository::new();
    }

    /**
     * @param CouponAuctionDeleteRepository $couponAuctionDeleteRepository
     * @return static
     * @internal
     */
    public function setCouponAuctionDeleteRepository(CouponAuctionDeleteRepository $couponAuctionDeleteRepository): static
    {
        $this->couponAuctionDeleteRepository = $couponAuctionDeleteRepository;
        return $this;
    }
}
