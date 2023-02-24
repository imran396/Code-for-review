<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/31/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Reset;

/**
 * Trait AuctionResetterAwareTrait
 * @package Sam\Auction\Reset
 */
trait AuctionResetterAwareTrait
{
    /**
     * @var AuctionResetter|null
     */
    protected ?AuctionResetter $auctionResetter = null;

    /**
     * @return AuctionResetter
     */
    protected function getAuctionResetter(): AuctionResetter
    {
        if ($this->auctionResetter === null) {
            $this->auctionResetter = AuctionResetter::new();
        }
        return $this->auctionResetter;
    }

    /**
     * @param AuctionResetter $auctionResetter
     * @return static
     * @internal
     */
    public function setAuctionResetter(AuctionResetter $auctionResetter): static
    {
        $this->auctionResetter = $auctionResetter;
        return $this;
    }
}
