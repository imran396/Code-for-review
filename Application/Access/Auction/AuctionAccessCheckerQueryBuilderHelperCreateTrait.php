<?php
/**
 * SAM-7764: Refactor \Auction_Access class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Access\Auction;

/**
 * Trait AuctionAccessCheckerQueryBuilderHelperCreateTrait
 * @package Sam\Application\Access\Auction
 */
trait AuctionAccessCheckerQueryBuilderHelperCreateTrait
{
    protected ?AuctionAccessCheckerQueryBuilderHelper $auctionAccessCheckerQueryBuilderHelper = null;

    /**
     * @return AuctionAccessCheckerQueryBuilderHelper
     */
    protected function createAuctionAccessCheckerQueryBuilderHelper(): AuctionAccessCheckerQueryBuilderHelper
    {
        return $this->auctionAccessCheckerQueryBuilderHelper ?: AuctionAccessCheckerQueryBuilderHelper::new();
    }

    /**
     * @param AuctionAccessCheckerQueryBuilderHelper $auctionAccessCheckerQueryBuilderHelper
     * @return static
     * @internal
     */
    public function setAuctionAccessCheckerQueryBuilderHelper(AuctionAccessCheckerQueryBuilderHelper $auctionAccessCheckerQueryBuilderHelper): static
    {
        $this->auctionAccessCheckerQueryBuilderHelper = $auctionAccessCheckerQueryBuilderHelper;
        return $this;
    }
}
