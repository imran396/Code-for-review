<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\BidIncrement;

trait BidIncrementDeleteRepositoryCreateTrait
{
    protected ?BidIncrementDeleteRepository $bidIncrementDeleteRepository = null;

    protected function createBidIncrementDeleteRepository(): BidIncrementDeleteRepository
    {
        return $this->bidIncrementDeleteRepository ?: BidIncrementDeleteRepository::new();
    }

    /**
     * @param BidIncrementDeleteRepository $bidIncrementDeleteRepository
     * @return static
     * @internal
     */
    public function setBidIncrementDeleteRepository(BidIncrementDeleteRepository $bidIncrementDeleteRepository): static
    {
        $this->bidIncrementDeleteRepository = $bidIncrementDeleteRepository;
        return $this;
    }
}
