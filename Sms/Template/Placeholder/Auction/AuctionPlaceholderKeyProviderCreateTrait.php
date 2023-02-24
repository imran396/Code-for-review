<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\Auction;

/**
 * Trait AuctionPlaceholderKeyProviderCreateTrait
 * @package Sam\Sms\Template\Placeholder\Auction
 */
trait AuctionPlaceholderKeyProviderCreateTrait
{
    protected ?AuctionPlaceholderKeyProvider $auctionPlaceholderKeyProvider = null;

    /**
     * @return AuctionPlaceholderKeyProvider
     */
    protected function createAuctionPlaceholderKeyProvider(): AuctionPlaceholderKeyProvider
    {
        return $this->auctionPlaceholderKeyProvider ?: AuctionPlaceholderKeyProvider::new();
    }

    /**
     * @param AuctionPlaceholderKeyProvider $auctionPlaceholderKeyProvider
     * @return static
     * @internal
     */
    public function setAuctionPlaceholderKeyProvider(AuctionPlaceholderKeyProvider $auctionPlaceholderKeyProvider): static
    {
        $this->auctionPlaceholderKeyProvider = $auctionPlaceholderKeyProvider;
        return $this;
    }
}
