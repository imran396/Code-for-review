<?php
/**
 * SAM-9688: Ability to make Auction and Lot/Item fields required to have values: Implementation (Developer)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 09, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\FieldConfig\Title;

/**
 * Trait AuctionFieldConfigTitleProviderCreateTrait
 * @package Sam\Auction\FieldConfig\Title
 */
trait AuctionFieldConfigTitleProviderCreateTrait
{
    protected ?AuctionFieldConfigTitleProvider $auctionFieldConfigTitleProvider = null;

    /**
     * @return AuctionFieldConfigTitleProvider
     */
    protected function createAuctionFieldConfigTitleProvider(): AuctionFieldConfigTitleProvider
    {
        return $this->auctionFieldConfigTitleProvider ?: AuctionFieldConfigTitleProvider::new();
    }

    /**
     * @param AuctionFieldConfigTitleProvider $auctionFieldConfigTitleProvider
     * @return static
     * @internal
     */
    public function setAuctionFieldConfigTitleProvider(AuctionFieldConfigTitleProvider $auctionFieldConfigTitleProvider): static
    {
        $this->auctionFieldConfigTitleProvider = $auctionFieldConfigTitleProvider;
        return $this;
    }
}
