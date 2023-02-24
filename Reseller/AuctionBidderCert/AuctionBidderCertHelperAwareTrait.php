<?php

namespace Sam\Reseller\AuctionBidderCert;

/**
 * Trait AuctionBidderCertHelperAwareTrait
 * @package Sam\Reseller\AuctionBidderCert
 */
trait AuctionBidderCertHelperAwareTrait
{
    protected ?AuctionBidderCertHelper $auctionBidderCertHelper = null;

    /**
     * @return AuctionBidderCertHelper
     */
    protected function getAuctionBidderCertHelper(): AuctionBidderCertHelper
    {
        if ($this->auctionBidderCertHelper === null) {
            $this->auctionBidderCertHelper = AuctionBidderCertHelper::new();
        }
        return $this->auctionBidderCertHelper;
    }

    /**
     * @param AuctionBidderCertHelper $resellerAuctionBidderCertHelper
     * @return static
     * @internal
     */
    public function setAuctionBidderCertHelper(AuctionBidderCertHelper $resellerAuctionBidderCertHelper): static
    {
        $this->auctionBidderCertHelper = $resellerAuctionBidderCertHelper;
        return $this;
    }
}
