<?php

namespace Sam\Auction\Validate;

/**
 * Trait AuctionExistenceCheckerAwareTrait
 * @package Sam\Auction\Validate
 */
trait AuctionExistenceCheckerAwareTrait
{
    protected ?AuctionExistenceChecker $auctionExistenceChecker = null;

    /**
     * @param AuctionExistenceChecker $auctionExistenceChecker
     * @return static
     * @internal
     */
    public function setAuctionExistenceChecker(AuctionExistenceChecker $auctionExistenceChecker): static
    {
        $this->auctionExistenceChecker = $auctionExistenceChecker;
        return $this;
    }

    /**
     * @return AuctionExistenceChecker
     */
    protected function getAuctionExistenceChecker(): AuctionExistenceChecker
    {
        if ($this->auctionExistenceChecker === null) {
            $this->auctionExistenceChecker = AuctionExistenceChecker::new();
        }
        return $this->auctionExistenceChecker;
    }

}
