<?php
/**
 * SAM-5753: Refactor "Bid Book" report
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\BidBook\Html\Internal\Load;

/**
 * Trait AuctionBidBookReportDataLoaderCreateTrait
 * @package Sam\Report\Auction\BidBook\Html\Internal\Load
 */
trait AuctionBidBookReportDataLoaderCreateTrait
{
    protected ?AuctionBidBookReportDataLoader $auctionBidBookReportDataLoader = null;

    /**
     * @return AuctionBidBookReportDataLoader
     */
    protected function createAuctionBidBookReportDataLoader(): AuctionBidBookReportDataLoader
    {
        return $this->auctionBidBookReportDataLoader ?: AuctionBidBookReportDataLoader::new();
    }

    /**
     * @param AuctionBidBookReportDataLoader $auctionBidBookReportDataLoader
     * @return static
     * @internal
     */
    public function setAuctionBidBookReportDataLoader(AuctionBidBookReportDataLoader $auctionBidBookReportDataLoader): static
    {
        $this->auctionBidBookReportDataLoader = $auctionBidBookReportDataLoader;
        return $this;
    }
}
