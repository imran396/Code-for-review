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

namespace Sam\Auction\FieldConfig\Order;

/**
 * Trait AuctionFieldConfigOrdererCreateTrait
 * @package Sam\Auction\FieldConfig\Order
 */
trait AuctionFieldConfigOrdererCreateTrait
{
    protected ?AuctionFieldConfigOrderer $auctionFieldConfigOrderer = null;

    /**
     * @return AuctionFieldConfigOrderer
     */
    protected function createAuctionFieldConfigOrderer(): AuctionFieldConfigOrderer
    {
        return $this->auctionFieldConfigOrderer ?: AuctionFieldConfigOrderer::new();
    }

    /**
     * @param AuctionFieldConfigOrderer $auctionFieldConfigOrderer
     * @return static
     * @internal
     */
    public function setAuctionFieldConfigOrderer(AuctionFieldConfigOrderer $auctionFieldConfigOrderer): static
    {
        $this->auctionFieldConfigOrderer = $auctionFieldConfigOrderer;
        return $this;
    }
}
