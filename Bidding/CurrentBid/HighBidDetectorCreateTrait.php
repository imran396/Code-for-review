<?php
/**
 * SAM-5394: High bid detector
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/18/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\CurrentBid;

/**
 * Trait CurrentBidDetectorCreateTrait
 * @package Sam\Bidding\CurrentBid
 */
trait HighBidDetectorCreateTrait
{
    /**
     * @var HighBidDetector|null
     */
    protected ?HighBidDetector $currentBidDetector = null;

    /**
     * @return HighBidDetector
     */
    protected function createHighBidDetector(): HighBidDetector
    {
        $currentBidDetector = $this->currentBidDetector ?: HighBidDetector::new();
        return $currentBidDetector;
    }

    /**
     * @param HighBidDetector $currentBidDetector
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setHighBidDetector(HighBidDetector $currentBidDetector): static
    {
        $this->currentBidDetector = $currentBidDetector;
        return $this;
    }
}
