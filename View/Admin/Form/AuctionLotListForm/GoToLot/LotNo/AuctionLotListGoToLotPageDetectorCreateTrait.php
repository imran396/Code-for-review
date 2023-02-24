<?php
/**
 * SAM-5665: Extract page detection logic for "Go to lot#" function at Auction Lot List page of admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 02, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\GoToLot\LotNo;

/**
 * Trait AuctionLotListGoToLotPageDetectorCreateTrait
 * @package Sam\View\Admin\Form\AuctionLotListForm\GoToLot\LotNo
 */
trait AuctionLotListGoToLotPageDetectorCreateTrait
{
    protected ?AuctionLotListGoToLotPageDetector $auctionLotListGoToLotPageDetector = null;

    /**
     * @return AuctionLotListGoToLotPageDetector
     */
    protected function createAuctionLotListGoToLotPageDetector(): AuctionLotListGoToLotPageDetector
    {
        return $this->auctionLotListGoToLotPageDetector ?: AuctionLotListGoToLotPageDetector::new();
    }

    /**
     * @param AuctionLotListGoToLotPageDetector $auctionLotListGoToLotPageDetector
     * @return static
     * @internal
     */
    public function setAuctionLotListGoToLotPageDetector(AuctionLotListGoToLotPageDetector $auctionLotListGoToLotPageDetector): static
    {
        $this->auctionLotListGoToLotPageDetector = $auctionLotListGoToLotPageDetector;
        return $this;
    }
}
