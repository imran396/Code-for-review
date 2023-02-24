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
 * Trait AuctionAccessCheckerCreateTrait
 * @package Sam\Application\Access\Auction
 */
trait AuctionAccessCheckerCreateTrait
{
    protected ?AuctionAccessChecker $auctionAccessChecker = null;

    /**
     * @return AuctionAccessChecker
     */
    protected function createAuctionAccessChecker(): AuctionAccessChecker
    {
        return $this->auctionAccessChecker ?: AuctionAccessChecker::new();
    }

    /**
     * @param AuctionAccessChecker $auctionAccessChecker
     * @return static
     * @internal
     */
    public function setAuctionAccessChecker(AuctionAccessChecker $auctionAccessChecker): static
    {
        $this->auctionAccessChecker = $auctionAccessChecker;
        return $this;
    }
}
