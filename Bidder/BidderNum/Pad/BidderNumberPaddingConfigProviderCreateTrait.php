<?php
/**
 * SAM-8662: Adjustments for Bidder Number Padding and Adviser services and apply unit tests
 * SAM-9648: Drop "approved" flag from "auction_bidder" table
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\BidderNum\Pad;

/**
 * Trait BidderNumberPaddingConfigProviderCreateTrait
 * @package Sam\Bidder\BidderNum\Pad
 */
trait BidderNumberPaddingConfigProviderCreateTrait
{
    protected ?BidderNumberPaddingConfigProvider $bidderNumberPaddingConfigProvider = null;

    /**
     * @return BidderNumberPaddingConfigProvider
     */
    protected function createBidderNumberPaddingConfigProvider(): BidderNumberPaddingConfigProvider
    {
        return $this->bidderNumberPaddingConfigProvider ?: BidderNumberPaddingConfigProvider::new();
    }

    /**
     * @param BidderNumberPaddingConfigProvider $bidderNumberPaddingConfigProvider
     * @return static
     * @internal
     */
    public function setBidderNumberPaddingConfigProvider(BidderNumberPaddingConfigProvider $bidderNumberPaddingConfigProvider): static
    {
        $this->bidderNumberPaddingConfigProvider = $bidderNumberPaddingConfigProvider;
        return $this;
    }
}
