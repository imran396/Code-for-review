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

namespace Sam\Auction\FieldConfig\Provider;

/**
 * Trait AuctionFieldConfigProviderAwareTrait
 * @package Sam\Auction\FieldConfig\Provider
 */
trait AuctionFieldConfigProviderAwareTrait
{
    protected ?AuctionFieldConfigProvider $auctionFieldConfigProvider = null;

    /**
     * @return AuctionFieldConfigProvider
     */
    protected function getAuctionFieldConfigProvider(): AuctionFieldConfigProvider
    {
        if ($this->auctionFieldConfigProvider === null) {
            $this->auctionFieldConfigProvider = AuctionFieldConfigProvider::new();
        }
        return $this->auctionFieldConfigProvider;
    }

    /**
     * @param AuctionFieldConfigProvider $auctionFieldConfigProvider
     * @return static
     * @internal
     */
    public function setAuctionFieldConfigProvider(AuctionFieldConfigProvider $auctionFieldConfigProvider): static
    {
        $this->auctionFieldConfigProvider = $auctionFieldConfigProvider;
        return $this;
    }
}
