<?php
/**
 * Public facade API for user registration in auction.
 * Its methods describe business needs.
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Register\General;

/**
 * Trait AuctionBidderRegistrationFacadeCreateTrait
 * @package
 */
trait AuctionBidderRegistratorFactoryCreateTrait
{
    protected ?AuctionBidderRegistratorFactory $auctionBidderRegistratorFactory = null;

    /**
     * @return AuctionBidderRegistratorFactory
     */
    protected function createAuctionBidderRegistratorFactory(): AuctionBidderRegistratorFactory
    {
        return $this->auctionBidderRegistratorFactory ?: AuctionBidderRegistratorFactory::new();
    }

    /**
     * @param AuctionBidderRegistratorFactory|null $auctionBidderRegistratorFactory
     * @return $this
     * @internal
     */
    public function setAuctionBidderRegistratorFactory(?AuctionBidderRegistratorFactory $auctionBidderRegistratorFactory): static
    {
        $this->auctionBidderRegistratorFactory = $auctionBidderRegistratorFactory;
        return $this;
    }
}
