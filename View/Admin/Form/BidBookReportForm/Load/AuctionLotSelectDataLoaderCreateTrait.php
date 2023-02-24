<?php
/**
 * SAM-5753: Refactor "Bid Book" report
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BidBookReportForm\Load;

/**
 * Trait AuctionLotSelectDataLoaderCreateTrait
 * @package Sam\View\Admin\Form\BidBookReportForm\Load
 */
trait AuctionLotSelectDataLoaderCreateTrait
{
    protected ?AuctionLotSelectDataLoader $auctionLotSelectDataLoader = null;

    /**
     * @return AuctionLotSelectDataLoader
     */
    protected function createAuctionLotSelectDataLoader(): AuctionLotSelectDataLoader
    {
        return $this->auctionLotSelectDataLoader ?: AuctionLotSelectDataLoader::new();
    }

    /**
     * @param AuctionLotSelectDataLoader $auctionLotSelectDataLoader
     * @return static
     * @internal
     */
    public function setAuctionLotSelectDataLoader(AuctionLotSelectDataLoader $auctionLotSelectDataLoader): static
    {
        $this->auctionLotSelectDataLoader = $auctionLotSelectDataLoader;
        return $this;
    }
}
