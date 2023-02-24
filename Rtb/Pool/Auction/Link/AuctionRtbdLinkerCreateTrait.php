<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/15/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Auction\Link;

/**
 * Trait AuctionRtbdLinkerCreateTrait
 * @package Sam\Rtb\Pool\Auction\Link
 */
trait AuctionRtbdLinkerCreateTrait
{
    /**
     * @var AuctionRtbdLinker|null
     */
    protected ?AuctionRtbdLinker $auctionRtbdLinker = null;

    /**
     * @return AuctionRtbdLinker
     */
    protected function createAuctionRtbdLinker(): AuctionRtbdLinker
    {
        $auctionRtbdLinker = $this->auctionRtbdLinker ?: AuctionRtbdLinker::new();
        return $auctionRtbdLinker;
    }

    /**
     * @param AuctionRtbdLinker $auctionRtbdLinker
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionRtbdLinker(AuctionRtbdLinker $auctionRtbdLinker): static
    {
        $this->auctionRtbdLinker = $auctionRtbdLinker;
        return $this;
    }
}
