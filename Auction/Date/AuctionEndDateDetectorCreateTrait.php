<?php
/**
 * SAM-6018: Implement auction start closing date
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 06, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Date;

/**
 * Trait EndDateDetectorCreateTrait
 * @package Sam\Auction\Date
 */
trait AuctionEndDateDetectorCreateTrait
{
    protected ?AuctionEndDateDetector $auctionEndDateDetector = null;

    /**
     * @return AuctionEndDateDetector
     */
    protected function createAuctionEndDateDetector(): AuctionEndDateDetector
    {
        return $this->auctionEndDateDetector ?: AuctionEndDateDetector::new();
    }

    /**
     * @param AuctionEndDateDetector $auctionEndDateDetector
     * @return static
     * @internal
     */
    public function setAuctionEndDateDetector(AuctionEndDateDetector $auctionEndDateDetector): static
    {
        $this->auctionEndDateDetector = $auctionEndDateDetector;
        return $this;
    }
}
