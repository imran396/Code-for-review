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

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\AuctionBidder\Internal\Build;

/**
 * Trait AuctionBidderAutocompleteDataBuilderCreateTrait
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\AuctionBidder\Internal\Build
 */
trait AuctionBidderAutocompleteDataBuilderCreateTrait
{
    protected ?AuctionBidderAutocompleteDataBuilder $auctionBidderAutocompleteDataBuilder = null;

    /**
     * @return AuctionBidderAutocompleteDataBuilder
     */
    protected function createAuctionBidderAutocompleteDataBuilder(): AuctionBidderAutocompleteDataBuilder
    {
        return $this->auctionBidderAutocompleteDataBuilder ?: AuctionBidderAutocompleteDataBuilder::new();
    }

    /**
     * @param AuctionBidderAutocompleteDataBuilder $auctionBidderAutocompleteDataBuilder
     * @return $this
     * @internal
     */
    public function setAuctionBidderAutocompleteDataBuilder(AuctionBidderAutocompleteDataBuilder $auctionBidderAutocompleteDataBuilder): static
    {
        $this->auctionBidderAutocompleteDataBuilder = $auctionBidderAutocompleteDataBuilder;
        return $this;
    }
}
