<?php
/**
 * SAM-7842: Refactor \User_Access
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

namespace Sam\Bidder\AuctionBidder\Validate;

/**
 * Trait BidderExistenceCheckerCreateTrait
 * @package Sam\Bidder\AuctionBidder\Validate
 */
trait AuctionBidderExistenceCheckerCreateTrait
{
    protected ?AuctionBidderExistenceChecker $auctionBidderExistenceChecker = null;

    /**
     * @return AuctionBidderExistenceChecker
     */
    protected function createAuctionBidderExistenceChecker(): AuctionBidderExistenceChecker
    {
        return $this->auctionBidderExistenceChecker ?: AuctionBidderExistenceChecker::new();
    }

    /**
     * @param AuctionBidderExistenceChecker $existenceChecker
     * @return static
     * @internal
     */
    public function setAuctionBidderExistenceChecker(AuctionBidderExistenceChecker $existenceChecker): static
    {
        $this->auctionBidderExistenceChecker = $existenceChecker;
        return $this;
    }
}
