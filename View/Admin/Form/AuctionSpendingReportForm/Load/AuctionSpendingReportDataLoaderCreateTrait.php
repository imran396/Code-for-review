<?php
/**
 * Auction Spending Report Data Loader Create Trait
 *
 * SAM-5841: Refactor data loader for Auction Spending Report page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 21, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionSpendingReportForm\Load;

/**
 * Trait AuctionSpendingReportDataLoaderCreateTrait
 */
trait AuctionSpendingReportDataLoaderCreateTrait
{
    protected ?AuctionSpendingReportDataLoader $auctionSpendingReportDataLoader = null;

    /**
     * @return AuctionSpendingReportDataLoader
     */
    protected function createAuctionSpendingReportDataLoader(): AuctionSpendingReportDataLoader
    {
        $auctionSpendingReportDataLoader = $this->auctionSpendingReportDataLoader
            ?: AuctionSpendingReportDataLoader::new();
        return $auctionSpendingReportDataLoader;
    }

    /**
     * @param AuctionSpendingReportDataLoader $auctionSpendingReportDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionSpendingReportDataLoader(
        AuctionSpendingReportDataLoader $auctionSpendingReportDataLoader
    ): static {
        $this->auctionSpendingReportDataLoader = $auctionSpendingReportDataLoader;
        return $this;
    }
}
