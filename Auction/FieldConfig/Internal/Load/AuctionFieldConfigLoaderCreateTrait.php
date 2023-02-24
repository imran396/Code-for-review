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

namespace Sam\Auction\FieldConfig\Internal\Load;

/**
 * Trait AuctionFieldConfigLoaderCreateTrait
 * @package Sam\Auction\FieldConfig\Internal\Load
 * @internal
 */
trait AuctionFieldConfigLoaderCreateTrait
{
    protected ?AuctionFieldConfigLoader $auctionFieldConfigLoader = null;

    /**
     * @return AuctionFieldConfigLoader
     */
    protected function createAuctionFieldConfigLoader(): AuctionFieldConfigLoader
    {
        return $this->auctionFieldConfigLoader ?: AuctionFieldConfigLoader::new();
    }

    /**
     * @param AuctionFieldConfigLoader $auctionFieldConfigLoader
     * @return static
     * @internal
     */
    public function setAuctionFieldConfigLoader(AuctionFieldConfigLoader $auctionFieldConfigLoader): static
    {
        $this->auctionFieldConfigLoader = $auctionFieldConfigLoader;
        return $this;
    }
}
