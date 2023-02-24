<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Bidder;

trait BidderReadRepositoryCreateTrait
{
    protected ?BidderReadRepository $bidderReadRepository = null;

    protected function createBidderReadRepository(): BidderReadRepository
    {
        return $this->bidderReadRepository ?: BidderReadRepository::new();
    }

    /**
     * @param BidderReadRepository $bidderReadRepository
     * @return static
     * @internal
     */
    public function setBidderReadRepository(BidderReadRepository $bidderReadRepository): static
    {
        $this->bidderReadRepository = $bidderReadRepository;
        return $this;
    }
}
