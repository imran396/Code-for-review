<?php

namespace Sam\Auction\Register;

/**
 * Trait AuctionRegistrationManagerAwareTrait
 * @package Sam\Auction\Register
 */
trait AuctionRegistrationManagerAwareTrait
{
    /**
     * @var AuctionRegistrationManager|null
     */
    protected ?AuctionRegistrationManager $auctionRegistrationManager = null;

    /**
     * @return AuctionRegistrationManager
     */
    protected function getAuctionRegistrationManager(): AuctionRegistrationManager
    {
        if ($this->auctionRegistrationManager === null) {
            $this->auctionRegistrationManager = AuctionRegistrationManager::new();
        }
        return $this->auctionRegistrationManager;
    }

    /**
     * @param AuctionRegistrationManager $auctionRegistrationManager
     * @return static
     * @internal
     */
    public function setAuctionRegistrationManager(AuctionRegistrationManager $auctionRegistrationManager): static
    {
        $this->auctionRegistrationManager = $auctionRegistrationManager;
        return $this;
    }
}
