<?php
/**
 * Trait for Auction Helper
 *
 * SAM-5065: Auction helper
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 6, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction;

/**
 * Trait AuctionHelperAwareTrait
 * @package Sam\Auction
 */
trait AuctionHelperAwareTrait
{
    protected ?AuctionHelper $auctionHelper = null;

    /**
     * @return AuctionHelper
     */
    protected function getAuctionHelper(): AuctionHelper
    {
        if ($this->auctionHelper === null) {
            $this->auctionHelper = AuctionHelper::new();
        }
        return $this->auctionHelper;
    }

    /**
     * @param AuctionHelper
     * @return static
     * @internal
     */
    public function setAuctionHelper(AuctionHelper $helper): static
    {
        $this->auctionHelper = $helper;
        return $this;
    }
}
