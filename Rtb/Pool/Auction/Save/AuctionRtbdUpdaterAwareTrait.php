<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/30/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Auction\Save;

/**
 * Trait AuctionRtbdUpdaterAwareTrait
 * @package
 */
trait AuctionRtbdUpdaterAwareTrait
{
    /**
     * @var AuctionRtbdUpdater|null
     */
    protected ?AuctionRtbdUpdater $auctionRtbdUpdater = null;

    /**
     * @return AuctionRtbdUpdater
     */
    protected function getAuctionRtbdUpdater(): AuctionRtbdUpdater
    {
        if ($this->auctionRtbdUpdater === null) {
            $this->auctionRtbdUpdater = AuctionRtbdUpdater::new();
        }
        return $this->auctionRtbdUpdater;
    }

    /**
     * @param AuctionRtbdUpdater $auctionRtbdUpdater
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionRtbdUpdater(AuctionRtbdUpdater $auctionRtbdUpdater): static
    {
        $this->auctionRtbdUpdater = $auctionRtbdUpdater;
        return $this;
    }
}
