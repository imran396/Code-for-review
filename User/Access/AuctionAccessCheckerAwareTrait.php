<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           8/29/2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Access;

/**
 * Trait AuctionAccessCheckerAwareTrait
 * @package Sam\User\Access
 */
trait AuctionAccessCheckerAwareTrait
{
    protected ?AuctionAccessChecker $auctionAccessChecker = null;

    /**
     * @return AuctionAccessChecker
     */
    protected function getAuctionAccessChecker(): AuctionAccessChecker
    {
        if ($this->auctionAccessChecker === null) {
            $this->auctionAccessChecker = AuctionAccessChecker::new();
        }
        return $this->auctionAccessChecker;
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
