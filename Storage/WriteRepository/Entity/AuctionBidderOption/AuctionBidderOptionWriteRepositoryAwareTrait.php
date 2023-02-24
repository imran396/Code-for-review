<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionBidderOption;

trait AuctionBidderOptionWriteRepositoryAwareTrait
{
    protected ?AuctionBidderOptionWriteRepository $auctionBidderOptionWriteRepository = null;

    protected function getAuctionBidderOptionWriteRepository(): AuctionBidderOptionWriteRepository
    {
        if ($this->auctionBidderOptionWriteRepository === null) {
            $this->auctionBidderOptionWriteRepository = AuctionBidderOptionWriteRepository::new();
        }
        return $this->auctionBidderOptionWriteRepository;
    }

    /**
     * @param AuctionBidderOptionWriteRepository $auctionBidderOptionWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionBidderOptionWriteRepository(AuctionBidderOptionWriteRepository $auctionBidderOptionWriteRepository): static
    {
        $this->auctionBidderOptionWriteRepository = $auctionBidderOptionWriteRepository;
        return $this;
    }
}
