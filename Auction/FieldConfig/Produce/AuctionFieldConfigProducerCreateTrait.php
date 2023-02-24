<?php
/**
 * SAM-9688: Ability to make Auction and Lot/Item fields required to have values: Implementation (Developer)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\FieldConfig\Produce;

/**
 * Trait AuctionFieldConfigProducerCreateTrait
 * @package Sam\Auction\FieldConfig\Produce
 */
trait AuctionFieldConfigProducerCreateTrait
{
    protected ?AuctionFieldConfigProducer $auctionFieldConfigProducer = null;

    /**
     * @return AuctionFieldConfigProducer
     */
    protected function createAuctionFieldConfigProducer(): AuctionFieldConfigProducer
    {
        return $this->auctionFieldConfigProducer ?: AuctionFieldConfigProducer::new();
    }

    /**
     * @param AuctionFieldConfigProducer $auctionFieldConfigProducer
     * @return static
     * @internal
     */
    public function setAuctionFieldConfigProducer(AuctionFieldConfigProducer $auctionFieldConfigProducer): static
    {
        $this->auctionFieldConfigProducer = $auctionFieldConfigProducer;
        return $this;
    }
}
