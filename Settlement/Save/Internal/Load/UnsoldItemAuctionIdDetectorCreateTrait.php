<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Save\Internal\Load;

/**
 * Trait UnsoldItemAuctionIdDetectorCreateTrait
 * @package Sam\Settlement\Save
 * @internal
 */
trait UnsoldItemAuctionIdDetectorCreateTrait
{
    protected ?UnsoldItemAuctionIdDetector $unsoldItemAuctionIdDetector = null;

    /**
     * @return UnsoldItemAuctionIdDetector
     */
    protected function createUnsoldItemAuctionIdDetector(): UnsoldItemAuctionIdDetector
    {
        return $this->unsoldItemAuctionIdDetector ?: UnsoldItemAuctionIdDetector::new();
    }

    /**
     * @param UnsoldItemAuctionIdDetector $unsoldItemAuctionIdDetector
     * @return static
     * @internal
     */
    public function setUnsoldItemAuctionIdDetector(UnsoldItemAuctionIdDetector $unsoldItemAuctionIdDetector): static
    {
        $this->unsoldItemAuctionIdDetector = $unsoldItemAuctionIdDetector;
        return $this;
    }
}
