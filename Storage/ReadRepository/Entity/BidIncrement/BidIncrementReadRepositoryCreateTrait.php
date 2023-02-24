<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\BidIncrement;

trait BidIncrementReadRepositoryCreateTrait
{
    protected ?BidIncrementReadRepository $bidIncrementReadRepository = null;

    protected function createBidIncrementReadRepository(): BidIncrementReadRepository
    {
        return $this->bidIncrementReadRepository ?: BidIncrementReadRepository::new();
    }

    /**
     * @param BidIncrementReadRepository $bidIncrementReadRepository
     * @return static
     * @internal
     */
    public function setBidIncrementReadRepository(BidIncrementReadRepository $bidIncrementReadRepository): static
    {
        $this->bidIncrementReadRepository = $bidIncrementReadRepository;
        return $this;
    }
}
