<?php
/**
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 12, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\OrderNo;

/**
 * Trait AuctionCustomFieldOrderNoAdviserAwareTrait
 * @package Sam\CustomField\Auction\OrderNo
 */
trait AuctionCustomFieldOrderNoAdviserAwareTrait
{
    protected ?AuctionCustomFieldOrderNoAdviser $auctionCustomFieldOrderNoAdviser = null;

    /**
     * @return AuctionCustomFieldOrderNoAdviser
     */
    protected function getAuctionCustomFieldOrderNoAdviser(): AuctionCustomFieldOrderNoAdviser
    {
        if ($this->auctionCustomFieldOrderNoAdviser === null) {
            $this->auctionCustomFieldOrderNoAdviser = AuctionCustomFieldOrderNoAdviser::new();
        }
        return $this->auctionCustomFieldOrderNoAdviser;
    }

    /**
     * @param AuctionCustomFieldOrderNoAdviser $auctionCustomFieldOrderNoAdviser
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionCustomFieldOrderNoAdviser(AuctionCustomFieldOrderNoAdviser $auctionCustomFieldOrderNoAdviser): static
    {
        $this->auctionCustomFieldOrderNoAdviser = $auctionCustomFieldOrderNoAdviser;
        return $this;
    }
}
