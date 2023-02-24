<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\BidIncrement;

trait BidIncrementWriteRepositoryAwareTrait
{
    protected ?BidIncrementWriteRepository $bidIncrementWriteRepository = null;

    protected function getBidIncrementWriteRepository(): BidIncrementWriteRepository
    {
        if ($this->bidIncrementWriteRepository === null) {
            $this->bidIncrementWriteRepository = BidIncrementWriteRepository::new();
        }
        return $this->bidIncrementWriteRepository;
    }

    /**
     * @param BidIncrementWriteRepository $bidIncrementWriteRepository
     * @return static
     * @internal
     */
    public function setBidIncrementWriteRepository(BidIncrementWriteRepository $bidIncrementWriteRepository): static
    {
        $this->bidIncrementWriteRepository = $bidIncrementWriteRepository;
        return $this;
    }
}
