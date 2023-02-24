<?php
/**
 * SAM-5873: Date/ Time/ Timezone management overhaul
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           June 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Date\Calculate;

/**
 * Trait AuctionDateFromLotsDetectorCreateTrait
 * @package Sam\Auction\Date\Calculate
 */
trait AuctionDateFromLotsDetectorCreateTrait
{
    protected ?AuctionDateFromLotsDetector $auctionDateFromLotsDetector = null;

    /**
     * @return AuctionDateFromLotsDetector
     */
    protected function createAuctionDateFromLotsDetector(): AuctionDateFromLotsDetector
    {
        return $this->auctionDateFromLotsDetector ?: AuctionDateFromLotsDetector::new();
    }

    /**
     * @param AuctionDateFromLotsDetector $auctionDateFromLotsDetector
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionDateFromLotsDetector(AuctionDateFromLotsDetector $auctionDateFromLotsDetector): static
    {
        $this->auctionDateFromLotsDetector = $auctionDateFromLotsDetector;
        return $this;
    }
}
