<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionImage;

trait AuctionImageReadRepositoryCreateTrait
{
    protected ?AuctionImageReadRepository $auctionImageReadRepository = null;

    protected function createAuctionImageReadRepository(): AuctionImageReadRepository
    {
        return $this->auctionImageReadRepository ?: AuctionImageReadRepository::new();
    }

    /**
     * @param AuctionImageReadRepository $auctionImageReadRepository
     * @return static
     * @internal
     */
    public function setAuctionImageReadRepository(AuctionImageReadRepository $auctionImageReadRepository): static
    {
        $this->auctionImageReadRepository = $auctionImageReadRepository;
        return $this;
    }
}
