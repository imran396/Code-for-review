<?php
/**
 * SAM-3903: Auction status checker class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/1/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Validate;

/**
 * Trait AuctionStatusCheckerAwareTrait
 * @package Sam\Auction\Validate
 */
trait AuctionStatusCheckerAwareTrait
{
    /**
     * @var AuctionStatusChecker|null
     */
    protected ?AuctionStatusChecker $auctionStatusChecker = null;

    /**
     * @return AuctionStatusChecker
     */
    protected function getAuctionStatusChecker(): AuctionStatusChecker
    {
        if ($this->auctionStatusChecker === null) {
            $this->auctionStatusChecker = AuctionStatusChecker::new();
        }
        return $this->auctionStatusChecker;
    }

    /**
     * @param AuctionStatusChecker $auctionStatusChecker
     * @return static
     * @internal
     */
    public function setAuctionStatusChecker(AuctionStatusChecker $auctionStatusChecker): static
    {
        $this->auctionStatusChecker = $auctionStatusChecker;
        return $this;
    }
}
