<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Bidder;

trait BidderWriteRepositoryAwareTrait
{
    protected ?BidderWriteRepository $bidderWriteRepository = null;

    protected function getBidderWriteRepository(): BidderWriteRepository
    {
        if ($this->bidderWriteRepository === null) {
            $this->bidderWriteRepository = BidderWriteRepository::new();
        }
        return $this->bidderWriteRepository;
    }

    /**
     * @param BidderWriteRepository $bidderWriteRepository
     * @return static
     * @internal
     */
    public function setBidderWriteRepository(BidderWriteRepository $bidderWriteRepository): static
    {
        $this->bidderWriteRepository = $bidderWriteRepository;
        return $this;
    }
}
