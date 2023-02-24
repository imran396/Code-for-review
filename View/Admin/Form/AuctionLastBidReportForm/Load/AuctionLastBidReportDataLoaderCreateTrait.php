<?php

/**
 * Auction Last Bid Report Data Loader Create Trait
 *
 * SAM-5598: Refactor data loader for Auction Last Bid Report at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 25, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLastBidReportForm\Load;

/**
 * Trait AuctionLastBidReportDataLoaderCreateTrait
 */
trait AuctionLastBidReportDataLoaderCreateTrait
{
    protected ?AuctionLastBidReportDataLoader $auctionLastBidReportDataLoader = null;

    /**
     * @return AuctionLastBidReportDataLoader
     */
    protected function createAuctionLastBidReportDataLoader(): AuctionLastBidReportDataLoader
    {
        $auctionLastBidReportDataLoader = $this->auctionLastBidReportDataLoader
            ?: AuctionLastBidReportDataLoader::new();
        return $auctionLastBidReportDataLoader;
    }

    /**
     * @param AuctionLastBidReportDataLoader $auctionLastBidReportDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionLastBidReportDataLoader(AuctionLastBidReportDataLoader $auctionLastBidReportDataLoader): static
    {
        $this->auctionLastBidReportDataLoader = $auctionLastBidReportDataLoader;
        return $this;
    }
}
