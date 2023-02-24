<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionRtbd;

trait AuctionRtbdWriteRepositoryAwareTrait
{
    protected ?AuctionRtbdWriteRepository $auctionRtbdWriteRepository = null;

    protected function getAuctionRtbdWriteRepository(): AuctionRtbdWriteRepository
    {
        if ($this->auctionRtbdWriteRepository === null) {
            $this->auctionRtbdWriteRepository = AuctionRtbdWriteRepository::new();
        }
        return $this->auctionRtbdWriteRepository;
    }

    /**
     * @param AuctionRtbdWriteRepository $auctionRtbdWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionRtbdWriteRepository(AuctionRtbdWriteRepository $auctionRtbdWriteRepository): static
    {
        $this->auctionRtbdWriteRepository = $auctionRtbdWriteRepository;
        return $this;
    }
}
