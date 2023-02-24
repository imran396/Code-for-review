<?php
/**
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

namespace Sam\Bidder\AuctionBidder\Query;

/**
 * Trait AuctionBidderQueryBuilderHelperCreateTrait
 * @package Sam\Bidder\AuctionBidder\Query
 */
trait AuctionBidderQueryBuilderHelperCreateTrait
{
    protected ?AuctionBidderQueryBuilderHelper $auctionBidderQueryBuilderHelper = null;

    /**
     * @return AuctionBidderQueryBuilderHelper
     */
    protected function createAuctionBidderQueryBuilderHelper(): AuctionBidderQueryBuilderHelper
    {
        return $this->auctionBidderQueryBuilderHelper ?: AuctionBidderQueryBuilderHelper::new();
    }

    /**
     * @param AuctionBidderQueryBuilderHelper $auctionBidderQueryBuilderHelper
     * @return static
     * @internal
     */
    public function setAuctionBidderQueryBuilderHelper(AuctionBidderQueryBuilderHelper $auctionBidderQueryBuilderHelper): static
    {
        $this->auctionBidderQueryBuilderHelper = $auctionBidderQueryBuilderHelper;
        return $this;
    }
}
