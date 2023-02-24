<?php
/**
 * SAM-3376: Add "Re-open" button at Auction Lots page
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

namespace Sam\Auction\Open;

/**
 * Trait AuctionOpenerAwareTrait
 * @package Sam\Auction\Open
 */
trait AuctionOpenerAwareTrait
{
    /**
     * @var AuctionOpener|null
     */
    protected ?AuctionOpener $auctionOpener = null;

    /**
     * @return AuctionOpener
     */
    protected function getAuctionOpener(): AuctionOpener
    {
        if ($this->auctionOpener === null) {
            $this->auctionOpener = AuctionOpener::new();
        }
        return $this->auctionOpener;
    }

    /**
     * @param AuctionOpener $auctionOpener
     * @return static
     * @internal
     */
    public function setAuctionOpener(AuctionOpener $auctionOpener): static
    {
        $this->auctionOpener = $auctionOpener;
        return $this;
    }
}
