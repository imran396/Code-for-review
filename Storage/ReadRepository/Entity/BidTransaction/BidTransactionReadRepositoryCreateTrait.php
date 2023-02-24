<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\BidTransaction;

trait BidTransactionReadRepositoryCreateTrait
{
    protected ?BidTransactionReadRepository $bidTransactionReadRepository = null;

    protected function createBidTransactionReadRepository(): BidTransactionReadRepository
    {
        return $this->bidTransactionReadRepository ?: BidTransactionReadRepository::new();
    }

    /**
     * @param BidTransactionReadRepository $bidTransactionReadRepository
     * @return static
     * @internal
     */
    public function setBidTransactionReadRepository(BidTransactionReadRepository $bidTransactionReadRepository): static
    {
        $this->bidTransactionReadRepository = $bidTransactionReadRepository;
        return $this;
    }
}
