<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionincStats;

trait AuctionincStatsDeleteRepositoryCreateTrait
{
    protected ?AuctionincStatsDeleteRepository $auctionincStatsDeleteRepository = null;

    protected function createAuctionincStatsDeleteRepository(): AuctionincStatsDeleteRepository
    {
        return $this->auctionincStatsDeleteRepository ?: AuctionincStatsDeleteRepository::new();
    }

    /**
     * @param AuctionincStatsDeleteRepository $auctionincStatsDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionincStatsDeleteRepository(AuctionincStatsDeleteRepository $auctionincStatsDeleteRepository): static
    {
        $this->auctionincStatsDeleteRepository = $auctionincStatsDeleteRepository;
        return $this;
    }
}
