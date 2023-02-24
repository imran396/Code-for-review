<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionincStats;

trait AuctionincStatsReadRepositoryCreateTrait
{
    protected ?AuctionincStatsReadRepository $auctionincStatsReadRepository = null;

    protected function createAuctionincStatsReadRepository(): AuctionincStatsReadRepository
    {
        return $this->auctionincStatsReadRepository ?: AuctionincStatsReadRepository::new();
    }

    /**
     * @param AuctionincStatsReadRepository $auctionincStatsReadRepository
     * @return static
     * @internal
     */
    public function setAuctionincStatsReadRepository(AuctionincStatsReadRepository $auctionincStatsReadRepository): static
    {
        $this->auctionincStatsReadRepository = $auctionincStatsReadRepository;
        return $this;
    }
}
