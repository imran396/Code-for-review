<?php
/**
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @since           Oct 17, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Qform\Component;

/**
 * Trait AuctionCustomFieldComponentBuilderAwareTrait
 * @package Sam\CustomField\Auction\Qform\Component
 */
trait AuctionCustomFieldComponentBuilderAwareTrait
{
    protected ?AuctionCustomFieldComponentBuilder $auctionCustomFieldComponentBuilder = null;

    /**
     * @return AuctionCustomFieldComponentBuilder
     */
    protected function getAuctionCustomFieldComponentBuilder(): AuctionCustomFieldComponentBuilder
    {
        if ($this->auctionCustomFieldComponentBuilder === null) {
            $this->auctionCustomFieldComponentBuilder = AuctionCustomFieldComponentBuilder::new();
        }
        return $this->auctionCustomFieldComponentBuilder;
    }

    /**
     * @param AuctionCustomFieldComponentBuilder $auctionCustomFieldComponentBuilder
     * @return static
     * @internal
     */
    public function setAuctionCustomFieldComponentBuilder(AuctionCustomFieldComponentBuilder $auctionCustomFieldComponentBuilder): static
    {
        $this->auctionCustomFieldComponentBuilder = $auctionCustomFieldComponentBuilder;
        return $this;
    }
}
