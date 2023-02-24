<?php
/**
 * SAM-9688: Ability to make Auction and Lot/Item fields required to have values: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\FieldConfig\Meta;

/**
 * Trait AuctionFieldConfigMetadataProviderAwareTrait
 * @package Sam\Auction\FieldConfig\Meta
 */
trait AuctionFieldConfigMetadataProviderAwareTrait
{
    protected ?AuctionFieldConfigMetadataProvider $auctionFieldConfigMetadataProvider = null;

    /**
     * @return AuctionFieldConfigMetadataProvider
     */
    protected function getAuctionFieldConfigMetadataProvider(): AuctionFieldConfigMetadataProvider
    {
        if ($this->auctionFieldConfigMetadataProvider === null) {
            $this->auctionFieldConfigMetadataProvider = AuctionFieldConfigMetadataProvider::new();
        }
        return $this->auctionFieldConfigMetadataProvider;
    }

    /**
     * @param AuctionFieldConfigMetadataProvider $auctionFieldConfigMetadataProvider
     * @return static
     * @internal
     */
    public function setAuctionFieldConfigMetadataProvider(AuctionFieldConfigMetadataProvider $auctionFieldConfigMetadataProvider): static
    {
        $this->auctionFieldConfigMetadataProvider = $auctionFieldConfigMetadataProvider;
        return $this;
    }
}
