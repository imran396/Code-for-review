<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/27/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Discovery\Search;

/**
 * Trait AuctionRtbdAdviserCreateTrait
 * @package
 */
trait AuctionRtbdAdviserCreateTrait
{
    /**
     * @var AuctionRtbdAdviser|null
     */
    protected ?AuctionRtbdAdviser $auctionRtbdAdviser = null;

    /**
     * @return AuctionRtbdAdviser
     */
    protected function createAuctionRtbdAdviser(): AuctionRtbdAdviser
    {
        $auctionRtbdAdviser = $this->auctionRtbdAdviser ?: AuctionRtbdAdviser::new();
        return $auctionRtbdAdviser;
    }

    /**
     * @param AuctionRtbdAdviser $auctionRtbdAdviser
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionRtbdAdviser(AuctionRtbdAdviser $auctionRtbdAdviser): static
    {
        $this->auctionRtbdAdviser = $auctionRtbdAdviser;
        return $this;
    }
}
