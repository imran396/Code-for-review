<?php
/**
 * SAM-10097: Distinguish auction bidder autocomplete data loading end-points for different pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\AuctionBidder;

/**
 * Trait AuctionBidderAutocompleteResponseProducerCreateTrait
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\AuctionBidder
 */
trait AuctionBidderAutocompleteResponseProducerCreateTrait
{
    protected ?AuctionBidderAutocompleteResponseProducer $auctionBidderAutocompleteResponseProducer = null;

    /**
     * @return AuctionBidderAutocompleteResponseProducer
     */
    protected function createAuctionBidderAutocompleteResponseProducer(): AuctionBidderAutocompleteResponseProducer
    {
        return $this->auctionBidderAutocompleteResponseProducer ?: AuctionBidderAutocompleteResponseProducer::new();
    }

    /**
     * @param AuctionBidderAutocompleteResponseProducer $auctionBidderAutocompleteResponseProducer
     * @return $this
     * @internal
     */
    public function setAuctionBidderAutocompleteResponseProducer(AuctionBidderAutocompleteResponseProducer $auctionBidderAutocompleteResponseProducer): static
    {
        $this->auctionBidderAutocompleteResponseProducer = $auctionBidderAutocompleteResponseProducer;
        return $this;
    }
}
