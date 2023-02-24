<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\Internal\UpdatingEntity\DetectByAuctionLot;

/**
 * Trait AuctionContextLotItemIdDetectorCreateTrait
 * @package Sam\Import\Csv\Lot
 */
trait AuctionContextLotItemIdDetectorCreateTrait
{
    protected ?AuctionContextLotItemIdDetector $auctionContextLotItemIdDetector = null;

    /**
     * @return AuctionContextLotItemIdDetector
     */
    protected function createAuctionContextLotItemIdDetector(): AuctionContextLotItemIdDetector
    {
        return $this->auctionContextLotItemIdDetector ?: AuctionContextLotItemIdDetector::new();
    }

    /**
     * @param AuctionContextLotItemIdDetector $detector
     * @return static
     * @internal
     */
    public function setAuctionContextLotItemIdDetector(AuctionContextLotItemIdDetector $detector): static
    {
        $this->auctionContextLotItemIdDetector = $detector;
        return $this;
    }
}
