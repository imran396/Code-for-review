<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\BidTransaction;

trait BidTransactionWriteRepositoryAwareTrait
{
    protected ?BidTransactionWriteRepository $bidTransactionWriteRepository = null;

    protected function getBidTransactionWriteRepository(): BidTransactionWriteRepository
    {
        if ($this->bidTransactionWriteRepository === null) {
            $this->bidTransactionWriteRepository = BidTransactionWriteRepository::new();
        }
        return $this->bidTransactionWriteRepository;
    }

    /**
     * @param BidTransactionWriteRepository $bidTransactionWriteRepository
     * @return static
     * @internal
     */
    public function setBidTransactionWriteRepository(BidTransactionWriteRepository $bidTransactionWriteRepository): static
    {
        $this->bidTransactionWriteRepository = $bidTransactionWriteRepository;
        return $this;
    }
}
