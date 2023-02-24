<?php

namespace Sam\Auction\Auctioneer\Validate;

/**
 * Trait AuctionAuctioneerExistenceCheckerAwareTrait
 * @package Sam\Auction\Auctioneer\Validate
 */
trait AuctionAuctioneerExistenceCheckerAwareTrait
{
    protected ?AuctionAuctioneerExistenceChecker $auctionAuctioneerExistenceChecker = null;

    /**
     * @param AuctionAuctioneerExistenceChecker $auctionAuctioneerExistenceChecker
     * @return static
     * @internal
     */
    public function setAuctionAuctioneerExistenceChecker(AuctionAuctioneerExistenceChecker $auctionAuctioneerExistenceChecker): static
    {
        $this->auctionAuctioneerExistenceChecker = $auctionAuctioneerExistenceChecker;
        return $this;
    }

    /**
     * @return AuctionAuctioneerExistenceChecker
     */
    protected function getAuctionAuctioneerExistenceChecker(): AuctionAuctioneerExistenceChecker
    {
        if ($this->auctionAuctioneerExistenceChecker === null) {
            $this->auctionAuctioneerExistenceChecker = AuctionAuctioneerExistenceChecker::new();
        }
        return $this->auctionAuctioneerExistenceChecker;
    }

}
