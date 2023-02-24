<?php
/**
 * SAM-3224: Refactoring of auction_closer.php
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

namespace Sam\Auction\Close;

/**
 * Trait AuctionCloserAwareTrait
 * @package Sam\Auction\Close
 */
trait AuctionCloserAwareTrait
{
    protected ?AuctionCloser $auctionCloser = null;

    /**
     * @return AuctionCloser
     */
    protected function getAuctionCloser(): AuctionCloser
    {
        if ($this->auctionCloser === null) {
            $this->auctionCloser = AuctionCloser::new();
        }
        return $this->auctionCloser;
    }

    /**
     * @param AuctionCloser $auctionCloser
     * @return static
     * @internal
     */
    public function setAuctionCloser(AuctionCloser $auctionCloser): static
    {
        $this->auctionCloser = $auctionCloser;
        return $this;
    }
}
