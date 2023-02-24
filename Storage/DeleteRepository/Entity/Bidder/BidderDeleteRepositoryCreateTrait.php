<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Bidder;

trait BidderDeleteRepositoryCreateTrait
{
    protected ?BidderDeleteRepository $bidderDeleteRepository = null;

    protected function createBidderDeleteRepository(): BidderDeleteRepository
    {
        return $this->bidderDeleteRepository ?: BidderDeleteRepository::new();
    }

    /**
     * @param BidderDeleteRepository $bidderDeleteRepository
     * @return static
     * @internal
     */
    public function setBidderDeleteRepository(BidderDeleteRepository $bidderDeleteRepository): static
    {
        $this->bidderDeleteRepository = $bidderDeleteRepository;
        return $this;
    }
}
