<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionincStats;

trait AuctionincStatsWriteRepositoryAwareTrait
{
    protected ?AuctionincStatsWriteRepository $auctionincStatsWriteRepository = null;

    protected function getAuctionincStatsWriteRepository(): AuctionincStatsWriteRepository
    {
        if ($this->auctionincStatsWriteRepository === null) {
            $this->auctionincStatsWriteRepository = AuctionincStatsWriteRepository::new();
        }
        return $this->auctionincStatsWriteRepository;
    }

    /**
     * @param AuctionincStatsWriteRepository $auctionincStatsWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionincStatsWriteRepository(AuctionincStatsWriteRepository $auctionincStatsWriteRepository): static
    {
        $this->auctionincStatsWriteRepository = $auctionincStatsWriteRepository;
        return $this;
    }
}
