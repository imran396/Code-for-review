<?php
/**
 * SAM-5020: RtbCurrent record change outside of rtb daemon process
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/23/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */


namespace Sam\Rtb\WebClient;


/**
 * Trait AuctionStateResyncerAwareTrait
 * @package
 */
trait AuctionStateResyncerAwareTrait
{
    /**
     * @var AuctionStateResyncer|null
     */
    protected ?AuctionStateResyncer $auctionStateResyncer = null;

    /**
     * @return AuctionStateResyncer
     */
    protected function getAuctionStateResyncer(): AuctionStateResyncer
    {
        if ($this->auctionStateResyncer === null) {
            $this->auctionStateResyncer = AuctionStateResyncer::new();
        }
        return $this->auctionStateResyncer;
    }

    /**
     * @param AuctionStateResyncer $auctionStateResyncer
     * @return static
     * @internal
     */
    public function setAuctionStateResyncer(AuctionStateResyncer $auctionStateResyncer): static
    {
        $this->auctionStateResyncer = $auctionStateResyncer;
        return $this;
    }
}
