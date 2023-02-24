<?php

namespace Sam\Reseller\AuctionBidderCert;

/**
 * Trait AuctionBidderCertUploaderAwareTrait
 * @package Sam\Reseller\AuctionBidderCert
 */
trait AuctionBidderCertUploaderAwareTrait
{
    protected ?AuctionBidderCertUploader $auctionBidderCertUploader = null;

    /**
     * @return AuctionBidderCertUploader
     */
    protected function getResellerAuctionBidderCertUploader(): AuctionBidderCertUploader
    {
        if ($this->auctionBidderCertUploader === null) {
            $this->auctionBidderCertUploader = AuctionBidderCertUploader::new();
        }
        return $this->auctionBidderCertUploader;
    }

    /**
     * @param AuctionBidderCertUploader $auctionBidderCertUploader
     * @return static
     * @internal
     */
    public function setResellerAuctionBidderCertUploader(AuctionBidderCertUploader $auctionBidderCertUploader): static
    {
        $this->auctionBidderCertUploader = $auctionBidderCertUploader;
        return $this;
    }
}
