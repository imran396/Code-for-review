<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionRtbd;

trait AuctionRtbdReadRepositoryCreateTrait
{
    protected ?AuctionRtbdReadRepository $auctionRtbdReadRepository = null;

    protected function createAuctionRtbdReadRepository(): AuctionRtbdReadRepository
    {
        return $this->auctionRtbdReadRepository ?: AuctionRtbdReadRepository::new();
    }

    /**
     * @param AuctionRtbdReadRepository $auctionRtbdReadRepository
     * @return static
     * @internal
     */
    public function setAuctionRtbdReadRepository(AuctionRtbdReadRepository $auctionRtbdReadRepository): static
    {
        $this->auctionRtbdReadRepository = $auctionRtbdReadRepository;
        return $this;
    }
}
