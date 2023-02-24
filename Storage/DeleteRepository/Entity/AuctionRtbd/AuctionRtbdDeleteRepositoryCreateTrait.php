<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionRtbd;

trait AuctionRtbdDeleteRepositoryCreateTrait
{
    protected ?AuctionRtbdDeleteRepository $auctionRtbdDeleteRepository = null;

    protected function createAuctionRtbdDeleteRepository(): AuctionRtbdDeleteRepository
    {
        return $this->auctionRtbdDeleteRepository ?: AuctionRtbdDeleteRepository::new();
    }

    /**
     * @param AuctionRtbdDeleteRepository $auctionRtbdDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionRtbdDeleteRepository(AuctionRtbdDeleteRepository $auctionRtbdDeleteRepository): static
    {
        $this->auctionRtbdDeleteRepository = $auctionRtbdDeleteRepository;
        return $this;
    }
}
