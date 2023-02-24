<?php
/**
 * SAM-5466: Advanced search panel auction auto-complete configuration
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Common\Autocomplete\Concrete\Auction\Internal\Build;

/**
 * Trait AuctionAutocompleteDataBuilderCreateTrait
 * @package Sam\Application\Controller\Responsive\Common\Autocomplete\Concrete\Auction\Internal\Build
 */
trait AuctionAutocompleteDataBuilderCreateTrait
{
    protected ?AuctionAutocompleteDataBuilder $auctionAutocompleteDataBuilder = null;

    /**
     * @return AuctionAutocompleteDataBuilder
     */
    protected function createAuctionAutocompleteDataBuilder(): AuctionAutocompleteDataBuilder
    {
        return $this->auctionAutocompleteDataBuilder ?: AuctionAutocompleteDataBuilder::new();
    }

    /**
     * @param AuctionAutocompleteDataBuilder $auctionAutocompleteDataBuilder
     * @return $this
     * @internal
     */
    public function setAuctionAutocompleteDataBuilder(AuctionAutocompleteDataBuilder $auctionAutocompleteDataBuilder): static
    {
        $this->auctionAutocompleteDataBuilder = $auctionAutocompleteDataBuilder;
        return $this;
    }
}
