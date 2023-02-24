<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\CouponAuction;

trait CouponAuctionWriteRepositoryAwareTrait
{
    protected ?CouponAuctionWriteRepository $couponAuctionWriteRepository = null;

    protected function getCouponAuctionWriteRepository(): CouponAuctionWriteRepository
    {
        if ($this->couponAuctionWriteRepository === null) {
            $this->couponAuctionWriteRepository = CouponAuctionWriteRepository::new();
        }
        return $this->couponAuctionWriteRepository;
    }

    /**
     * @param CouponAuctionWriteRepository $couponAuctionWriteRepository
     * @return static
     * @internal
     */
    public function setCouponAuctionWriteRepository(CouponAuctionWriteRepository $couponAuctionWriteRepository): static
    {
        $this->couponAuctionWriteRepository = $couponAuctionWriteRepository;
        return $this;
    }
}
