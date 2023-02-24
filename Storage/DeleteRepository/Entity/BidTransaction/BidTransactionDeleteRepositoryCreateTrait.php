<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\BidTransaction;

trait BidTransactionDeleteRepositoryCreateTrait
{
    protected ?BidTransactionDeleteRepository $bidTransactionDeleteRepository = null;

    protected function createBidTransactionDeleteRepository(): BidTransactionDeleteRepository
    {
        return $this->bidTransactionDeleteRepository ?: BidTransactionDeleteRepository::new();
    }

    /**
     * @param BidTransactionDeleteRepository $bidTransactionDeleteRepository
     * @return static
     * @internal
     */
    public function setBidTransactionDeleteRepository(BidTransactionDeleteRepository $bidTransactionDeleteRepository): static
    {
        $this->bidTransactionDeleteRepository = $bidTransactionDeleteRepository;
        return $this;
    }
}
